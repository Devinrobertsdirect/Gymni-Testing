import React, { useState } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, Image, ScrollView, TextInput } from 'react-native';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';

const mockGroups = [
  {
    id: 1,
    name: "Morning Warriors 💪",
    lastMessage: "Morgan: Great session everyone! See you tomorrow at 6am!",
    time: "4:56 PM",
    avatar: "https://api.a0.dev/assets/image?text=morning%20warriors&aspect=1:1&seed=1",
    unread: true
  },
  {
    id: 2,
    name: "HIIT Heroes 🔥",
    lastMessage: "Emma: Just posted my workout stats for today's challenge",
    time: "2:30 PM",
    avatar: "https://api.a0.dev/assets/image?text=hiit%20heroes&aspect=1:1&seed=2",
    unread: false
  },
  {
    id: 3,
    name: "Yoga Flow 🧘‍♀️",
    lastMessage: "Sarah: New flow sequence uploaded for tomorrow's session",
    time: "Yesterday",
    avatar: "https://api.a0.dev/assets/image?text=yoga%20flow&aspect=1:1&seed=3",
    unread: false
  },
  {
    id: 4,
    name: "Strength Squad 🏋️‍♂️",
    lastMessage: "David: Who's joining the deadlift challenge?",
    time: "2/20/25",
    avatar: "https://api.a0.dev/assets/image?text=strength%20squad&aspect=1:1&seed=4",
    archived: true
  }
];

export default function GroupsSocialScreen() {
  const [filter, setFilter] = useState('all');
  const [searchQuery, setSearchQuery] = useState('');

  const getFilteredGroups = () => {
    let filtered = mockGroups;
    if (filter === 'unread') {
      filtered = mockGroups.filter(group => group.unread);
    } else if (filter === 'archived') {
      filtered = mockGroups.filter(group => group.archived);
    } else if (filter === 'favorites') {
      filtered = mockGroups.filter(group => group.favorite);
    }
    
    if (searchQuery) {
      filtered = filtered.filter(group => 
        group.name.toLowerCase().includes(searchQuery.toLowerCase())
      );
    }
    
    return filtered;
  };

  const FilterChip = ({ label, value }) => (
    <TouchableOpacity 
      style={[
        styles.filterChip,
        filter === value && styles.activeFilterChip
      ]}
      onPress={() => setFilter(value)}
    >
      <Text style={[
        styles.filterChipText,
        filter === value && styles.activeFilterChipText
      ]}>
        {label}
      </Text>
    </TouchableOpacity>
  );

  return (
    <View style={styles.container}>
      {/* Search Bar */}
      <View style={styles.searchContainer}>
        <View style={styles.searchBar}>
          <Ionicons name="search" size={20} color="#8E8E93" />
          <TextInput
            style={styles.searchInput}
            placeholder="Search groups..."
            placeholderTextColor="#8E8E93"
            value={searchQuery}
            onChangeText={setSearchQuery}
          />
        </View>
      </View>

      {/* Filter Chips */}
      <ScrollView 
        horizontal 
        showsHorizontalScrollIndicator={false}
        style={styles.filterContainer}
        contentContainerStyle={styles.filterContent}
      >          <FilterChip label="All" value="all" />
          <FilterChip label="Unread" value="unread" />
          <FilterChip label="Favorites" value="favorites" />
          <TouchableOpacity style={styles.addFilterChip}>
          <Ionicons name="add" size={24} color="#8E8E93" />
        </TouchableOpacity>
      </ScrollView>

      {/* Archived Section */}
      {filter === 'all' && (
        <TouchableOpacity style={styles.archivedSection}>
          <Ionicons name="archive-outline" size={24} color="#8E8E93" />
          <Text style={styles.archivedText}>Archived</Text>
          <Text style={styles.archivedCount}>1</Text>
        </TouchableOpacity>
      )}

      {/* Groups List */}
      <ScrollView style={styles.groupsList}>
        {getFilteredGroups().map(group => (          <TouchableOpacity            key={group.id} 
            style={styles.groupItem}
            onPress={() => navigation.navigate('GroupDetail', { 
              group: {
                id: group.id,
                name: group.name,
                avatar: group.avatar,
                lastMessage: group.lastMessage,
                time: group.time,
                unread: group.unread
              }
            })}
          >
            <Image source={{ uri: group.avatar }} style={styles.groupAvatar} />
            <View style={styles.groupContent}>
              <View style={styles.groupHeader}>
                <Text style={styles.groupName}>{group.name}</Text>
                <Text style={styles.groupTime}>{group.time}</Text>
              </View>
              <Text 
                style={[
                  styles.groupMessage,
                  group.unread && styles.unreadMessage
                ]}
                numberOfLines={1}
              >
                {group.lastMessage}
              </Text>
            </View>
          </TouchableOpacity>
        ))}
      </ScrollView>

      {/* Create Group Button */}
      <TouchableOpacity 
        style={styles.createButton}
        onPress={() => {
          toast.message('Coming Soon!', {
            description: 'Group creation will be available in the next update'
          });
        }}
      >
        <Ionicons name="add" size={24} color="white" />
      </TouchableOpacity>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#000000',
  },
  searchContainer: {
    padding: 16,
  },
  searchBar: {
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
  filterContainer: {
    maxHeight: 44,
  },
  filterContent: {
    paddingHorizontal: 16,
    gap: 8,
  },
  filterChip: {
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 16,
    backgroundColor: '#1C1C1E',
  },
  activeFilterChip: {
    backgroundColor: '#FF9500',
  },
  filterChipText: {
    color: '#8E8E93',
    fontSize: 14,
    fontWeight: '600',
  },
  activeFilterChipText: {
    color: 'white',
  },
  addFilterChip: {
    width: 44,
    height: 44,
    borderRadius: 22,
    backgroundColor: '#1C1C1E',
    justifyContent: 'center',
    alignItems: 'center',
  },
  archivedSection: {
    flexDirection: 'row',
    alignItems: 'center',
    padding: 16,
    gap: 12,
  },
  archivedText: {
    flex: 1,
    color: '#8E8E93',
    fontSize: 16,
  },
  archivedCount: {
    color: '#8E8E93',
    fontSize: 16,
  },
  groupsList: {
    flex: 1,
  },
  groupItem: {
    flexDirection: 'row',
    padding: 16,
    gap: 12,
  },
  groupAvatar: {
    width: 50,
    height: 50,
    borderRadius: 25,
  },
  groupContent: {
    flex: 1,
    borderBottomWidth: 0.5,
    borderBottomColor: '#2C2C2E',
    paddingBottom: 16,
  },
  groupHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 4,
  },
  groupName: {
    color: 'white',
    fontSize: 16,
    fontWeight: '600',
  },
  groupTime: {
    color: '#8E8E93',
    fontSize: 14,
  },
  groupMessage: {
    color: '#8E8E93',
    fontSize: 14,
  },
  unreadMessage: {
    color: 'white',
    fontWeight: '500',
  },
  createButton: {
    position: 'absolute',
    right: 16,
    bottom: 16,
    width: 56,
    height: 56,
    borderRadius: 28,
    backgroundColor: '#FF9500',
    justifyContent: 'center',
    alignItems: 'center',
    shadowColor: '#FF9500',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.3,
    shadowRadius: 8,
    elevation: 5,
  },
});