import React, { useState } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, Image, ScrollView, TextInput } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';

const mockTrendingPosts = [
  {
    id: 1,
    user: {
      name: 'Mike Williams',
      avatar: 'https://api.a0.dev/assets/image?text=mike%20profile&aspect=1:1&seed=2'
    },
    content: "Just hit a 405lb deadlift PR! 🔥 6 months of consistent training paying off. #GymProgress #Strength",
    timestamp: new Date(Date.now() - 3600000 * 2),
    reactions: {
      likes: 245,
      fire: 89
    },
    comments: 42,
    shares: 15,
    isHot: true
  },
  {
    id: 2,
    user: {
      name: 'Sarah Johnson',
      avatar: 'https://api.a0.dev/assets/image?text=sarah%20profile&aspect=1:1&seed=1'
    },
    content: "30-day transformation complete! Down 15lbs and feeling stronger than ever. Consistency is key! 💪 #FitnessJourney",
    image: 'https://api.a0.dev/assets/image?text=fitness%20transformation&aspect=16:9&seed=1',
    timestamp: new Date(Date.now() - 3600000 * 5),
    reactions: {
      likes: 892,
      fire: 234
    },
    comments: 156,
    shares: 78,
    isHot: true
  },
  {
    id: 3,
    user: {
      name: 'Emma Davis',
      avatar: 'https://api.a0.dev/assets/image?text=emma%20profile&aspect=1:1&seed=3'
    },
    content: "New 5K personal best: 22:15! Started running 6 months ago at 35:00. Never give up on your goals! 🏃‍♀️ #Running",
    timestamp: new Date(Date.now() - 86400000),
    reactions: {
      likes: 567,
      fire: 123
    },
    comments: 89,
    shares: 34,
    isHot: true
  }
];

const trendingTopics = [
  { id: 1, tag: '#GymProgress', posts: 12453 },
  { id: 2, tag: '#FitnessJourney', posts: 8976 },
  { id: 3, tag: '#Running', posts: 6543 },
  { id: 4, tag: '#Strength', posts: 5432 },
  { id: 5, tag: '#WeightLoss', posts: 4321 }
];

export default function TrendingSocialScreen() {
  const [activeFilter, setActiveFilter] = useState('popular');

  const formatNumber = (num) => {
    if (num >= 1000) {
      return (num / 1000).toFixed(1) + 'K';
    }
    return num;
  };

  const formatTimestamp = (date) => {
    const now = new Date();
    const diff = now.getTime() - date.getTime();
    const hours = Math.floor(diff / (1000 * 60 * 60));
    const days = Math.floor(hours / 24);
    
    if (days > 0) return `${days}d ago`;
    if (hours > 0) return `${hours}h ago`;
    return 'Just now';
  };

  return (
    <View style={styles.container}>
      {/* Filter Tabs */}
      <View style={styles.filterTabs}>
        <TouchableOpacity 
          style={[styles.filterTab, activeFilter === 'popular' && styles.activeFilterTab]}
          onPress={() => setActiveFilter('popular')}
        >
          <MaterialCommunityIcons 
            name="fire" 
            size={20} 
            color={activeFilter === 'popular' ? '#FF9500' : '#8e8e8e'} 
          />
          <Text style={[styles.filterTabText, activeFilter === 'popular' && styles.activeFilterTabText]}>
            Popular
          </Text>
        </TouchableOpacity>
        <TouchableOpacity 
          style={[styles.filterTab, activeFilter === 'recent' && styles.activeFilterTab]}
          onPress={() => setActiveFilter('recent')}
        >
          <Ionicons 
            name="time-outline" 
            size={20} 
            color={activeFilter === 'recent' ? '#FF9500' : '#8e8e8e'} 
          />
          <Text style={[styles.filterTabText, activeFilter === 'recent' && styles.activeFilterTabText]}>
            Recent
          </Text>
        </TouchableOpacity>
      </View>

      <ScrollView style={styles.content}>
        {/* Trending Topics */}
        <View style={styles.trendingTopicsContainer}>
          <Text style={styles.sectionTitle}>Trending Topics</Text>
          <ScrollView 
            horizontal 
            showsHorizontalScrollIndicator={false}
            style={styles.topicsScroll}
          >
            {trendingTopics.map((topic, index) => (
              <TouchableOpacity key={topic.id} style={styles.topicCard}>
                <Text style={styles.topicTag}>{topic.tag}</Text>
                <Text style={styles.topicPosts}>{formatNumber(topic.posts)} posts</Text>
                {index < 3 && (
                  <View style={styles.trendingBadge}>
                    <MaterialCommunityIcons name="trending-up" size={16} color="#FF9500" />
                  </View>
                )}
              </TouchableOpacity>
            ))}
          </ScrollView>
        </View>

        {/* Trending Posts */}
        <View style={styles.postsContainer}>
          {mockTrendingPosts.map((post) => (
            <View key={post.id} style={styles.postCard}>
              <View style={styles.postHeader}>
                <Image source={{ uri: post.user.avatar }} style={styles.avatar} />
                <View style={styles.postHeaderInfo}>
                  <Text style={styles.userName}>{post.user.name}</Text>
                  <Text style={styles.timestamp}>{formatTimestamp(post.timestamp)}</Text>
                </View>
                {post.isHot && (
                  <View style={styles.hotBadge}>
                    <MaterialCommunityIcons name="fire" size={16} color="#FF3B30" />
                    <Text style={styles.hotText}>Hot</Text>
                  </View>
                )}
              </View>

              <Text style={styles.postContent}>{post.content}</Text>

              {post.image && (
                <Image source={{ uri: post.image }} style={styles.postImage} />
              )}

              <View style={styles.postStats}>
                <View style={styles.statItem}>
                  <Ionicons name="heart" size={20} color="#FF9500" />
                  <Text style={styles.statText}>{formatNumber(post.reactions.likes)}</Text>
                </View>
                <View style={styles.statItem}>
                  <MaterialCommunityIcons name="fire" size={20} color="#FF9500" />
                  <Text style={styles.statText}>{formatNumber(post.reactions.fire)}</Text>
                </View>
                <View style={styles.statItem}>
                  <Ionicons name="chatbubble-outline" size={20} color="#8e8e8e" />
                  <Text style={styles.statText}>{formatNumber(post.comments)}</Text>
                </View>
                <View style={styles.statItem}>
                  <Ionicons name="share-outline" size={20} color="#8e8e8e" />
                  <Text style={styles.statText}>{formatNumber(post.shares)}</Text>
                </View>
              </View>
            </View>
          ))}
        </View>
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#000000',
  },
  filterTabs: {
    flexDirection: 'row',
    padding: 16,
    gap: 12,
  },
  filterTab: {
    flex: 1,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    gap: 8,
    backgroundColor: '#1C1C1E',
    padding: 12,
    borderRadius: 20,
  },
  activeFilterTab: {
    backgroundColor: 'rgba(255, 149, 0, 0.1)',
  },
  filterTabText: {
    color: '#8e8e8e',
    fontSize: 16,
    fontWeight: '600',
  },
  activeFilterTabText: {
    color: '#FF9500',
  },
  content: {
    flex: 1,
  },
  trendingTopicsContainer: {
    padding: 16,
  },
  sectionTitle: {
    color: '#FF9500',
    fontSize: 18,
    fontWeight: '600',
    marginBottom: 16,
  },
  topicsScroll: {
    flexGrow: 0,
  },
  topicCard: {
    backgroundColor: '#1C1C1E',
    padding: 12,
    borderRadius: 16,
    marginRight: 12,
    minWidth: 120,
  },
  topicTag: {
    color: 'white',
    fontSize: 16,
    fontWeight: '600',
    marginBottom: 4,
  },
  topicPosts: {
    color: '#8e8e8e',
    fontSize: 14,
  },
  trendingBadge: {
    position: 'absolute',
    top: 8,
    right: 8,
    backgroundColor: 'rgba(255, 149, 0, 0.1)',
    padding: 4,
    borderRadius: 12,
  },
  postsContainer: {
    padding: 16,
    gap: 16,
  },
  postCard: {
    backgroundColor: '#1C1C1E',
    borderRadius: 16,
    padding: 16,
    marginBottom: 16,
  },
  postHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 12,
  },
  avatar: {
    width: 40,
    height: 40,
    borderRadius: 20,
  },
  postHeaderInfo: {
    flex: 1,
    marginLeft: 12,
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
  hotBadge: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: 'rgba(255, 59, 48, 0.1)',
    paddingVertical: 4,
    paddingHorizontal: 8,
    borderRadius: 12,
    gap: 4,
  },
  hotText: {
    color: '#FF3B30',
    fontSize: 12,
    fontWeight: '600',
  },
  postContent: {
    color: 'white',
    fontSize: 16,
    lineHeight: 24,
    marginBottom: 12,
  },
  postImage: {
    width: '100%',
    height: 200,
    borderRadius: 12,
    marginBottom: 12,
  },
  postStats: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    borderTopWidth: 1,
    borderTopColor: '#2C2C2E',
    paddingTop: 12,
  },
  statItem: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 4,
  },
  statText: {
    color: '#8e8e8e',
    fontSize: 14,
  },
});