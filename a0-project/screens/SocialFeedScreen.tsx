import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, Image, ScrollView, TextInput, Modal } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';
import FriendsSocialScreen from './FriendsSocialScreen';
import GroupsSocialScreen from './GroupsSocialScreen';
import TrendingSocialScreen from './TrendingSocialScreen';
import { toast } from 'sonner-native';

// Firebase imports
import { db, auth } from '../config/firebase';
import { 
  collection, 
  query, 
  where, 
  getDocs, 
  addDoc, 
  deleteDoc, 
  doc,
  updateDoc,
  onSnapshot,
  orderBy 
} from 'firebase/firestore';

interface Post {
  id: string;
  userId: string;
  user: {
    name: string;
    avatar: string;
  };
  content: string;
  images?: string[];
  workoutType?: string;
  timestamp: Date;
  reactions: {
    likes: number;
    fire: number;
  };
  createdAt: any;
}

// Mock data for fallback/initial state
const initialPosts: Post[] = [
  {
    id: 1,
    user: {
      name: 'Sarah Johnson',
      avatar: 'https://api.a0.dev/assets/image?text=sarah%20profile&aspect=1:1&seed=1'
    },
    content: "Just crushed a 5x5 squat PR at 225lbs! 💪 Been working towards this for months. Remember, progress isn't always linear but keep pushing!",
    timestamp: new Date(Date.now() - 3600000 * 2), // 2 hours ago
    reactions: {
      likes: 24,
      fire: 12
    }
  },
  {
    id: 2,
    user: {
      name: 'Emma Davis',
      avatar: 'https://api.a0.dev/assets/image?text=emma%20profile&aspect=1:1&seed=3'
    },
    content: "Low energy day but still made it to the gym. Sometimes showing up is the hardest part. Did a light workout but proud I didn't skip! 🏋️‍♀️",
    timestamp: new Date(Date.now() - 3600000 * 5), // 5 hours ago
    reactions: {
      likes: 18,
      fire: 5
    }
  },
  {
    id: 3,
    user: {
      name: 'Mike Williams',
      avatar: 'https://api.a0.dev/assets/image?text=mike%20profile&aspect=1:1&seed=2'
    },
    content: "Hit a new deadlift PR today! 405lbs x 2 🔥 Thanks to everyone who's been helping me with form checks!",
    timestamp: new Date(Date.now() - 86400000), // 1 day ago
    reactions: {
      likes: 45,
      fire: 20
    }
  }
];

const formatTimestamp = (date: Date) => {
  const now = new Date();
  const diff = now.getTime() - date.getTime();
  
  const minutes = Math.floor(diff / 60000);
  const hours = Math.floor(minutes / 60);
  const days = Math.floor(hours / 24);
  
  if (days > 0) {
    return `${days}d ago`;
  } else if (hours > 0) {
    return `${hours}h ago`;
  } else if (minutes > 0) {
    return `${minutes}m ago`;
  } else {
    return 'Just now';
  }
};

export default function SocialFeedScreen({ navigation, route }) {
  const [activeTab, setActiveTab] = useState(route.params?.initialTab || 'feed');
  const [posts, setPosts] = useState<Post[]>([]);
  const [newPost, setNewPost] = useState('');
  const [showShareModal, setShowShareModal] = useState(false);
  const [loading, setLoading] = useState(false);

  // Fetch posts from Firestore
  useEffect(() => {
    const postsQuery = query(
      collection(db, 'posts'),
      orderBy('createdAt', 'desc')
    );

    const unsubscribe = onSnapshot(postsQuery, (snapshot) => {
      const postsData = snapshot.docs.map(doc => ({
        id: doc.id,
        ...doc.data()
      })) as Post[];
      setPosts(postsData);
    }, (error) => {
      console.error('Error fetching posts:', error);
      // Fallback to mock data if Firebase fails
      setPosts(initialPosts);
    });

    // Cleanup listener on unmount
    return () => unsubscribe();
  }, []);

  // Firebase functions
  const handleCreatePost = async () => {
    if (!newPost.trim() || !auth.currentUser?.uid) {
      toast.error('Please enter some content');
      return;
    }

    try {
      setLoading(true);
      await addDoc(collection(db, 'posts'), {
        userId: auth.currentUser.uid,
        content: newPost.trim(),
        reactions: {
          likes: 0,
          fire: 0
        },
        createdAt: new Date(),
        timestamp: new Date()
      });

      setNewPost('');
      setShowShareModal(false);
      toast.success('Post shared successfully!');
    } catch (error) {
      console.error('Error creating post:', error);
      toast.error('Failed to share post');
    } finally {
      setLoading(false);
    }
  };

  const handleLikePost = async (postId: string) => {
    try {
      const post = posts.find(p => p.id === postId);
      if (!post) return;

      await updateDoc(doc(db, 'posts', postId), {
        'reactions.likes': post.reactions.likes + 1
      });
    } catch (error) {
      console.error('Error liking post:', error);
      toast.error('Failed to like post');
    }
  };

  const handleFirePost = async (postId: string) => {
    try {
      const post = posts.find(p => p.id === postId);
      if (!post) return;

      await updateDoc(doc(db, 'posts', postId), {
        'reactions.fire': post.reactions.fire + 1
      });
    } catch (error) {
      console.error('Error firing post:', error);
      toast.error('Failed to fire post');
    }
  };

  // Mock user groups
  const userGroups = [
    { id: 1, name: 'Morning Warriors', avatar: 'https://api.a0.dev/assets/image?text=morning%20warriors&aspect=1:1&seed=1' },
    { id: 2, name: 'HIIT Heroes', avatar: 'https://api.a0.dev/assets/image?text=hiit%20heroes&aspect=1:1&seed=2' }
  ];

  const handlePost = () => {
    if (newPost.trim()) {
      setShowShareModal(true);
    }
  };

  const handleShare = (type: 'all' | 'friends' | number) => {
    // Use the existing Firebase function
    handleCreatePost();
  };

  const renderTabContent = () => {
    switch (activeTab) {
      case 'friends':
        return <FriendsSocialScreen />;
      case 'groups':
        return <GroupsSocialScreen />;
      case 'trending':
        return <TrendingSocialScreen />;
      default:
        return (
          <ScrollView style={styles.feedContent}>
            {/* New Post Input */}
            <View style={styles.newPostContainer}>
              <Image
                source={{ uri: 'https://api.a0.dev/assets/image?text=user%20profile&aspect=1:1' }}
                style={styles.avatar}
              />
              <View style={styles.inputContainer}>
                <TextInput
                  style={styles.input}
                  placeholder="Share your fitness journey..."
                  placeholderTextColor="#8e8e8e"
                  multiline
                  value={newPost}
                  onChangeText={setNewPost}
                />
                <TouchableOpacity 
                  style={[styles.postButton, !newPost.trim() && styles.postButtonDisabled]}
                  onPress={handlePost}
                  disabled={!newPost.trim()}
                >
                  <Text style={styles.postButtonText}>Post</Text>
                </TouchableOpacity>
              </View>
            </View>

            {/* Posts List */}
            {posts.map(post => (
              <View key={post.id} style={styles.post}>
                <Image source={{ uri: post.user.avatar }} style={styles.avatar} />
                <View style={styles.postContent}>
                  <View style={styles.postHeader}>
                    <Text style={styles.userName}>{post.user.name}</Text>
                    <Text style={styles.timestamp}>{formatTimestamp(post.timestamp)}</Text>
                  </View>
                  <Text style={styles.postText}>{post.content}</Text>
                  <View style={styles.reactions}>
                    <TouchableOpacity 
                      style={styles.reactionButton}
                      onPress={() => handleLikePost(post.id)}
                    >
                      <Ionicons name="heart-outline" size={24} color="#FF9500" />
                      <Text style={styles.reactionCount}>{post.reactions.likes}</Text>
                    </TouchableOpacity>
                    <TouchableOpacity 
                      style={styles.reactionButton}
                      onPress={() => handleFirePost(post.id)}
                    >
                      <MaterialCommunityIcons name="fire" size={24} color="#FF9500" />
                      <Text style={styles.reactionCount}>{post.reactions.fire}</Text>
                    </TouchableOpacity>
                    <TouchableOpacity style={styles.reactionButton} onPress={() => navigation.navigate('PostDetail', { post })}>
                      <Ionicons name="chatbubble-outline" size={24} color="#8e8e8e" />
                    </TouchableOpacity>
                  </View>
                </View>
              </View>
            ))}
          </ScrollView>
        );
    }
  };

  return (
    <View style={styles.container}>
      <SafeAreaView style={styles.safeArea}>
        {/* Header */}
        <View style={styles.header}>
          <TouchableOpacity onPress={() => navigation.navigate('Menu')}>
            <Ionicons name="menu" size={28} color="white" />
          </TouchableOpacity>
          <Text style={styles.headerTitle}>Gymn'i Feed</Text>
          <View style={styles.notificationContainer}>
            <Ionicons name="notifications-outline" size={28} color="white" />
            <View style={styles.notificationBadge}>
              <Text style={styles.notificationText}>6</Text>
            </View>
          </View>
        </View>

        <View style={styles.tabContainer}>
          <ScrollView 
            horizontal 
            showsHorizontalScrollIndicator={false}
            contentContainerStyle={styles.tabScrollContent}
          >
            <TouchableOpacity 
              style={[styles.tab, activeTab === 'feed' && styles.activeTab]}
              onPress={() => setActiveTab('feed')}
            >
              <Text style={[styles.tabText, activeTab === 'feed' && styles.activeTabText]}>Feed</Text>
            </TouchableOpacity>
            <TouchableOpacity 
              style={[styles.tab, activeTab === 'friends' && styles.activeTab]}
              onPress={() => setActiveTab('friends')}
            >
              <Text style={[styles.tabText, activeTab === 'friends' && styles.activeTabText]}>Friends</Text>
            </TouchableOpacity>
            <TouchableOpacity 
              style={[styles.tab, activeTab === 'groups' && styles.activeTab]}
              onPress={() => setActiveTab('groups')}
            >
              <Text style={[styles.tabText, activeTab === 'groups' && styles.activeTabText]}>Groups</Text>
            </TouchableOpacity>
            <TouchableOpacity 
              style={[styles.tab, activeTab === 'trending' && styles.activeTab]}
              onPress={() => setActiveTab('trending')}
            >
              <Text style={[styles.tabText, activeTab === 'trending' && styles.activeTabText]}>Trending</Text>
            </TouchableOpacity>
          </ScrollView>
        </View>

        {renderTabContent()}

        {/* Share Modal */}
        <Modal
          visible={showShareModal}
          transparent={true}
          animationType="slide"
        >
          <TouchableOpacity 
            style={styles.modalOverlay}
            activeOpacity={1}
            onPress={() => setShowShareModal(false)}
          >
            <View style={styles.modalContent}>
              <View style={styles.modalHeader}>
                <Text style={styles.modalTitle}>Share to</Text>
                <TouchableOpacity 
                  style={styles.closeButton}
                  onPress={() => setShowShareModal(false)}
                >
                  <Ionicons name="close" size={24} color="white" />
                </TouchableOpacity>
              </View>

              <TouchableOpacity 
                style={styles.shareOption}
                onPress={() => handleShare('all')}
              >
                <View style={styles.shareOptionIcon}>
                  <Ionicons name="globe-outline" size={24} color="#FF9500" />
                </View>
                <View style={styles.shareOptionContent}>
                  <Text style={styles.shareOptionTitle}>Everyone</Text>
                  <Text style={styles.shareOptionSubtitle}>Share with all Gymn'i users</Text>
                </View>
              </TouchableOpacity>

              <TouchableOpacity 
                style={styles.shareOption}
                onPress={() => handleShare('friends')}
              >
                <View style={styles.shareOptionIcon}>
                  <Ionicons name="people-outline" size={24} color="#FF9500" />
                </View>
                <View style={styles.shareOptionContent}>
                  <Text style={styles.shareOptionTitle}>Friends Only</Text>
                  <Text style={styles.shareOptionSubtitle}>Share with your friends</Text>
                </View>
              </TouchableOpacity>

              {userGroups.length > 0 && (
                <View style={styles.groupsSection}>
                  <Text style={styles.groupsTitle}>Your Groups</Text>
                  {userGroups.map(group => (
                    <TouchableOpacity 
                      key={group.id}
                      style={styles.shareOption}
                      onPress={() => handleShare(group.id)}
                    >
                      <Image source={{ uri: group.avatar }} style={styles.groupAvatar} />
                      <View style={styles.shareOptionContent}>
                        <Text style={styles.shareOptionTitle}>{group.name}</Text>
                        <Text style={styles.shareOptionSubtitle}>Share with group members</Text>
                      </View>
                    </TouchableOpacity>
                  ))}
                </View>
              )}
            </View>
          </TouchableOpacity>
        </Modal>

        {/* Bottom Navigation */}
        <View style={styles.bottomNav}>
          <TouchableOpacity 
            style={styles.navItem}
            onPress={() => navigation.navigate('MainHome')}
          >
            <Ionicons name="home-outline" size={24} color="white" />
          </TouchableOpacity>
          <TouchableOpacity 
            style={styles.navItem}
            onPress={() => navigation.navigate('Fitness')}
          >
            <Ionicons name="barbell-outline" size={24} color="white" />
          </TouchableOpacity>
          <TouchableOpacity style={[styles.navItem, styles.activeNavItem]}>
            <Ionicons name="people" size={24} color="#FF9500" />
          </TouchableOpacity>
          <TouchableOpacity style={styles.navItem}>
            <Ionicons name="calendar-outline" size={24} color="white" />
          </TouchableOpacity>
          <TouchableOpacity style={styles.navItem}>
            <Ionicons name="search-outline" size={24} color="white" />
          </TouchableOpacity>
        </View>
      </SafeAreaView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#000000',
  },
  safeArea: {
    flex: 1,
  },
  header: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingHorizontal: 16,
    paddingVertical: 12,
  },
  headerTitle: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#FF9500',
  },
  notificationContainer: {
    position: 'relative',
  },
  notificationBadge: {
    position: 'absolute',
    top: -5,
    right: -5,
    backgroundColor: '#FF9500',
    borderRadius: 10,
    width: 20,
    height: 20,
    justifyContent: 'center',
    alignItems: 'center',
  },
  notificationText: {
    color: 'white',
    fontSize: 12,
    fontWeight: 'bold',
  },
  tabContainer: {
    backgroundColor: '#1C1C1E',
    marginHorizontal: 16,
    marginVertical: 8,
    borderRadius: 20,
    padding: 4,
  },
  tabScrollContent: {
    paddingHorizontal: 4,
  },
  tab: {
    paddingVertical: 8,
    paddingHorizontal: 16,
    borderRadius: 16,
    marginHorizontal: 4,
  },
  activeTab: {
    backgroundColor: 'rgba(255, 149, 0, 0.2)',
  },
  tabText: {
    color: '#8e8e8e',
    fontSize: 14,
    fontWeight: '600',
  },
  activeTabText: {
    color: '#FF9500',
  },
  feedContent: {
    flex: 1,
  },
  newPostContainer: {
    flexDirection: 'row',
    padding: 16,
    gap: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#1C1C1E',
  },
  avatar: {
    width: 40,
    height: 40,
    borderRadius: 20,
  },
  inputContainer: {
    flex: 1,
  },
  input: {
    backgroundColor: '#1C1C1E',
    borderRadius: 20,
    padding: 12,
    color: 'white',
    fontSize: 16,
    minHeight: 40,
  },
  postButton: {
    alignSelf: 'flex-end',
    backgroundColor: '#FF9500',
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 16,
    marginTop: 8,
  },
  postButtonDisabled: {
    opacity: 0.5,
  },
  postButtonText: {
    color: 'white',
    fontWeight: '600',
  },
  post: {
    flexDirection: 'row',
    padding: 16,
    gap: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#1C1C1E',
  },
  postContent: {
    flex: 1,
  },
  postHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 8,
  },
  userName: {
    color: 'white',
    fontSize: 16,
    fontWeight: '600',
  },
  timestamp: {
    color: '#8e8e8e',
    fontSize: 14,
  },
  postText: {
    color: 'white',
    fontSize: 16,
    lineHeight: 22,
    marginBottom: 12,
  },
  reactions: {
    flexDirection: 'row',
    gap: 16,
  },
  reactionButton: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 4,
  },
  reactionCount: {
    color: '#8e8e8e',
    fontSize: 14,
  },
  bottomNav: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    paddingVertical: 12,
    backgroundColor: '#1C1C1E',
    borderTopWidth: 1,
    borderTopColor: '#2C2C2E',
  },
  navItem: {
    padding: 8,
  },
  activeNavItem: {
    backgroundColor: 'rgba(255, 149, 0, 0.1)',
    borderRadius: 20,
  },
  modalOverlay: {
    flex: 1,
    backgroundColor: 'rgba(0, 0, 0, 0.7)',
    justifyContent: 'flex-end',
  },
  modalContent: {
    backgroundColor: '#1C1C1E',
    borderTopLeftRadius: 20,
    borderTopRightRadius: 20,
    paddingTop: 20,
    maxHeight: '80%',
  },
  modalHeader: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 20,
    paddingHorizontal: 16,
  },
  modalTitle: {
    color: 'white',
    fontSize: 18,
    fontWeight: '600',
  },
  closeButton: {
    position: 'absolute',
    right: 16,
  },
  shareOption: {
    flexDirection: 'row',
    alignItems: 'center',
    padding: 16,
    borderBottomWidth: 1,
    borderBottomColor: '#2C2C2E',
  },
  shareOptionIcon: {
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: 'rgba(255, 149, 0, 0.1)',
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 12,
  },
  shareOptionContent: {
    flex: 1,
  },
  shareOptionTitle: {
    color: 'white',
    fontSize: 16,
    fontWeight: '600',
    marginBottom: 4,
  },
  shareOptionSubtitle: {
    color: '#8E8E93',
    fontSize: 14,
  },
  groupsSection: {
    marginTop: 16,
  },
  groupsTitle: {
    color: '#FF9500',
    fontSize: 16,
    fontWeight: '600',
    marginLeft: 16,
    marginBottom: 8,
  },
  groupAvatar: {
    width: 40,
    height: 40,
    borderRadius: 20,
    marginRight: 12,
  },
});