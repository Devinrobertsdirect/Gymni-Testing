import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, Image, ScrollView, TextInput } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';

// Placeholder for fetching real trending posts from backend/API
async function fetchTrendingPosts() {
  // TODO: Replace with real API call
  return [];
}

const starterPosts = [
  { id: 1, user: { name: 'Gymn’i Team', avatar: '' }, content: "Welcome to the Gymn’i community! Post your first workout and inspire others.", timestamp: new Date(), reactions: { likes: 0, fire: 0 }, comments: 0, shares: 0 },
  { id: 2, user: { name: 'Gymn’i Team', avatar: '' }, content: "Tip: Track your progress and celebrate every milestone!", timestamp: new Date(), reactions: { likes: 0, fire: 0 }, comments: 0, shares: 0 },
  { id: 3, user: { name: 'Gymn’i Team', avatar: '' }, content: "Share your favorite workout routine with the community.", timestamp: new Date(), reactions: { likes: 0, fire: 0 }, comments: 0, shares: 0 },
  { id: 4, user: { name: 'Gymn’i Team', avatar: '' }, content: "Stay motivated! Check out trending topics and join the conversation.", timestamp: new Date(), reactions: { likes: 0, fire: 0 }, comments: 0, shares: 0 },
  { id: 5, user: { name: 'Gymn’i Team', avatar: '' }, content: "Invite your friends and grow your fitness network!", timestamp: new Date(), reactions: { likes: 0, fire: 0 }, comments: 0, shares: 0 },
];

const trendingTopics = [
  { id: 1, tag: '#GymProgress', posts: 12453 },
  { id: 2, tag: '#FitnessJourney', posts: 8976 },
  { id: 3, tag: '#Running', posts: 6543 },
  { id: 4, tag: '#Strength', posts: 5432 },
  { id: 5, tag: '#WeightLoss', posts: 4321 }
];

type Post = {
  id: number;
  user: { name: string; avatar: string };
  content: string;
  timestamp: Date;
  reactions: { likes: number; fire: number };
  comments: number;
  shares: number;
  isHot?: boolean;
  image?: string;
};

export default function TrendingSocialScreen() {
  const [activeFilter, setActiveFilter] = useState('popular');
  const [posts, setPosts] = useState<Post[]>([]);

  useEffect(() => {
    fetchTrendingPosts().then(fetched => {
      setPosts(fetched && fetched.length > 0 ? fetched : starterPosts);
    });
  }, []);

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

  // Assume isAuthenticated is a boolean indicating if the user is logged in
  const isAuthenticated = false; // TODO: Replace with real auth logic

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
          {posts.map((post) => (
            <View key={post.id} style={styles.postCard}>
              <View style={styles.postHeader}>
                <Image source={post.user.avatar ? { uri: post.user.avatar } : require('../assets/icon.png')} style={styles.avatar} />
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