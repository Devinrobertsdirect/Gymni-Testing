import React, { useState } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, Image, ScrollView, TextInput } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';

const mockFriends = [
  {
    id: 1,
    name: 'Sarah Johnson',
    avatar: 'https://api.a0.dev/assets/image?text=sarah%20profile&aspect=1:1&seed=1',
    workouts: 125,
    followers: 1.2,
    following: true,
    lastWorkout: '2h ago'
  },
  {
    id: 2,
    name: 'Mike Williams',
    avatar: 'https://api.a0.dev/assets/image?text=mike%20profile&aspect=1:1&seed=2',
    workouts: 89,
    followers: 0.8,
    following: false,
    lastWorkout: '5h ago'
  },
  {
    id: 3,
    name: 'Emma Davis',
    avatar: 'https://api.a0.dev/assets/image?text=emma%20profile&aspect=1:1&seed=3',
    workouts: 234,
    followers: 2.5,
    following: true,
    lastWorkout: '1d ago'
  },
  {
    id: 4,
    name: 'David Chen',
    avatar: 'https://api.a0.dev/assets/image?text=david%20profile&aspect=1:1&seed=4',
    workouts: 156,
    followers: 1.5,
    following: false,
    lastWorkout: '2d ago'
  }
];

export default function FriendsSocialScreen() {
  const [searchQuery, setSearchQuery] = useState('');
  const [followingFilter, setFollowingFilter] = useState(false);

  const filteredFriends = mockFriends.filter(friend => {
    if (followingFilter && !friend.following) return false;
    return friend.name.toLowerCase().includes(searchQuery.toLowerCase());
  });

  return (
    <View style={styles.container}>
      <ScrollView style={styles.content}>
        {/* Search and Filter */}
        <View style={styles.searchContainer}>
          <View style={styles.searchBar}>
            <Ionicons name="search" size={20} color="#8E8E93" />
            <TextInput
              style={styles.searchInput}
              placeholder="Search friends"
              placeholderTextColor="#8E8E93"
              value={searchQuery}
              onChangeText={setSearchQuery}
            />
          </View>
          <TouchableOpacity 
            style={[styles.filterButton, followingFilter && styles.activeFilter]}
            onPress={() => setFollowingFilter(!followingFilter)}
          >
            <Text style={[styles.filterText, followingFilter && styles.activeFilterText]}>
              Following
            </Text>
          </TouchableOpacity>
        </View>

        {/* Friends List */}
        <View style={styles.friendsList}>
          {filteredFriends.map(friend => (
            <View key={friend.id} style={styles.friendCard}>
              <View style={styles.friendHeader}>
                <Image source={{ uri: friend.avatar }} style={styles.avatar} />
                <View style={styles.friendInfo}>
                  <Text style={styles.friendName}>{friend.name}</Text>
                  <Text style={styles.lastWorkout}>Last workout: {friend.lastWorkout}</Text>
                </View>
                <TouchableOpacity 
                  style={[styles.followButton, friend.following && styles.followingButton]}
                >
                  <Text style={[styles.followButtonText, friend.following && styles.followingButtonText]}>
                    {friend.following ? 'Following' : 'Follow'}
                  </Text>
                </TouchableOpacity>
              </View>
              
              <View style={styles.statsContainer}>
                <View style={styles.statItem}>
                  <Text style={styles.statNumber}>{friend.workouts}</Text>
                  <Text style={styles.statLabel}>Workouts</Text>
                </View>
                <View style={styles.statItem}>
                  <Text style={styles.statNumber}>{friend.followers}K</Text>
                  <Text style={styles.statLabel}>Followers</Text>
                </View>
                <TouchableOpacity style={styles.messageButton}>
                  <Ionicons name="chatbubble-outline" size={24} color="#FF9500" />
                </TouchableOpacity>
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
  content: {
    flex: 1,
  },
  searchContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    padding: 16,
    gap: 12,
  },
  searchBar: {
    flex: 1,
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#1C1C1E',
    borderRadius: 12,
    paddingHorizontal: 12,
    height: 40,
  },
  searchInput: {
    flex: 1,
    color: 'white',
    fontSize: 16,
    marginLeft: 8,
  },
  filterButton: {
    backgroundColor: '#1C1C1E',
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 20,
  },
  activeFilter: {
    backgroundColor: 'rgba(255, 149, 0, 0.2)',
  },
  filterText: {
    color: '#8E8E93',
    fontSize: 14,
    fontWeight: '600',
  },
  activeFilterText: {
    color: '#FF9500',
  },
  friendsList: {
    padding: 16,
    gap: 16,
  },
  friendCard: {
    backgroundColor: '#1C1C1E',
    borderRadius: 16,
    padding: 16,
  },
  friendHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 16,
  },
  avatar: {
    width: 50,
    height: 50,
    borderRadius: 25,
  },
  friendInfo: {
    flex: 1,
    marginLeft: 12,
  },
  friendName: {
    color: 'white',
    fontSize: 16,
    fontWeight: '600',
    marginBottom: 4,
  },
  lastWorkout: {
    color: '#8E8E93',
    fontSize: 12,
  },
  followButton: {
    backgroundColor: '#FF9500',
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 20,
  },
  followingButton: {
    backgroundColor: 'rgba(255, 149, 0, 0.2)',
  },
  followButtonText: {
    color: 'white',
    fontSize: 14,
    fontWeight: '600',
  },
  followingButtonText: {
    color: '#FF9500',
  },
  statsContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: 'rgba(255, 149, 0, 0.1)',
    borderRadius: 12,
    padding: 12,
  },
  statItem: {
    flex: 1,
    alignItems: 'center',
  },
  statNumber: {
    color: '#FF9500',
    fontSize: 18,
    fontWeight: 'bold',
  },
  statLabel: {
    color: '#8E8E93',
    fontSize: 12,
    marginTop: 4,
  },
  messageButton: {
    backgroundColor: 'rgba(255, 149, 0, 0.2)',
    width: 40,
    height: 40,
    borderRadius: 20,
    justifyContent: 'center',
    alignItems: 'center',
  },
});