import React from 'react';
import { View, Text, StyleSheet, TouchableOpacity, Image, ScrollView, TextInput } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { useState } from 'react';  // Mock cardio workouts data
  const mockWorkouts = [
    {
      id: 1,
      title: "Endurance Run",
      type: "Cardio",
      totalTime: "45 minutes",
      intensity: 7,
      equipment: "Treadmill",
      muscleGroup: "lower",
      image: "https://api.a0.dev/assets/image?text=endurance%20run%20cardio&aspect=16:9&seed=1"
    },
    {
      id: 5,
      title: "HIIT Rowing",
      type: "Cardio",
      totalTime: "30 minutes",
      intensity: 9,
      equipment: "Rowing Machine",
      muscleGroup: "full",
      image: "https://api.a0.dev/assets/image?text=rowing%20cardio&aspect=16:9&seed=5"
    },
    {
      id: 6,
      title: "Bodyweight Circuit",
      type: "Cardio",
      totalTime: "20 minutes",
      intensity: 8,
      equipment: "None",
      muscleGroup: "full",
      image: "https://api.a0.dev/assets/image?text=bodyweight%20cardio&aspect=16:9&seed=6"
    },
  {
    id: 2,
    title: "High-Intensity Cycling",
    type: "Cardio",
    totalTime: "30 minutes",
    intensity: 9,
    equipment: "Stationary Bike",
    muscleGroup: "lower",
    image: "https://api.a0.dev/assets/image?text=cycling%20cardio&aspect=16:9&seed=2"
  },
  {
    id: 3,
    title: "Swimming Laps",
    type: "Cardio",
    totalTime: "40 minutes",
    intensity: 6,
    equipment: "Pool",
    muscleGroup: "full",
    image: "https://api.a0.dev/assets/image?text=swimming%20cardio&aspect=16:9&seed=3"
  },
  {
    id: 4,
    title: "Jump Rope Circuit",
    type: "Cardio",
    totalTime: "20 minutes",
    intensity: 8,
    equipment: "Jump Rope",
    muscleGroup: "full",
    image: "https://api.a0.dev/assets/image?text=jump%20rope%20cardio&aspect=16:9&seed=4"
  }
];

import type { StackNavigationProp } from '@react-navigation/stack';
import type { RouteProp } from '@react-navigation/native';
import HamburgerMenuButton from './HamburgerMenuButton';

type RootStackParamList = {
  Cardio: undefined | { filters?: any };
  VideoMode: { workout: any };
  Filter: { sourceScreen?: string };
  Fitness: undefined;
};

type CardioScreenNavigationProp = StackNavigationProp<RootStackParamList, 'Cardio'>;
type CardioScreenRouteProp = RouteProp<RootStackParamList, 'Cardio'>;

interface CardioScreenProps {
  navigation: CardioScreenNavigationProp;
  route: CardioScreenRouteProp;
}

export default function CardioScreen({ navigation, route }: CardioScreenProps) {
  const [searchQuery, setSearchQuery] = useState('');
  const [activeFilters, setActiveFilters] = useState(null);
  const [activeFilterCount, setActiveFilterCount] = useState(0);
  const [favorites, setFavorites] = useState(new Set());

  // Handle incoming filters
  React.useEffect(() => {
    if (route.params?.filters === null) {
      setActiveFilters(null);
      setActiveFilterCount(0);
    } else if (route.params?.filters) {
      setActiveFilters(route.params.filters);
    }
  }, [route.params?.filters]);

  const toggleFavorite = (workoutId) => {
    setFavorites(prev => {
      const newFavorites = new Set(prev);
      if (newFavorites.has(workoutId)) {
        newFavorites.delete(workoutId);
      } else {
        newFavorites.add(workoutId);
      }
      return newFavorites;
    });
  };

  const filteredWorkouts = React.useMemo(() => {
    return mockWorkouts.filter(workout => {
      // Apply search filter
      if (!workout.title.toLowerCase().includes(searchQuery.toLowerCase())) {
        return false;
      }      // Apply active filters if any
      if (activeFilters) {
        // Duration filter
        if (activeFilters.duration && !workout.totalTime.includes(activeFilters.duration)) {
          return false;
        }

        // Equipment filter
        if (activeFilters.equipment && workout.equipment !== activeFilters.equipment) {
          return false;
        }

        // Muscle group filter
        if (activeFilters.muscleGroup && workout.muscleGroup !== activeFilters.muscleGroup) {
          return false;
        }

        // Intensity filter
        if (activeFilters.intensities && activeFilters.intensities.length > 0) {
          if (!activeFilters.intensities.includes(workout.intensity)) {
            return false;
          }
        }

        // Saved filter
        if (activeFilters.savedOnly && !favorites.has(workout.id)) {
          return false;
        }
      }

      return true;
    });
  }, [searchQuery, activeFilters, favorites]);

  // Update active filter count when filters change
  React.useEffect(() => {
    if (activeFilters) {
      let count = 0;
      if (activeFilters.duration) count++;
      if (activeFilters.muscleGroup) count++;
      if (activeFilters.intensities?.length > 0) count++;
      if (activeFilters.savedOnly) count++;
      setActiveFilterCount(count);
    } else {
      setActiveFilterCount(0);
    }
  }, [activeFilters]);

  return (
    <View style={styles.container}>
      <SafeAreaView style={styles.safeArea}>
        {/* Active Filters Indicator */}
        {activeFilterCount > 0 && (
          <View style={styles.activeFiltersContainer}>
            <Text style={styles.activeFiltersText}>
              {activeFilterCount} {activeFilterCount === 1 ? 'filter' : 'filters'} active
            </Text>
            <TouchableOpacity 
              onPress={() => {
                navigation.navigate('Filter');
              }}
            >
              <Text style={styles.editFiltersText}>Edit Filters</Text>
            </TouchableOpacity>
          </View>
        )}

        {/* Header */}
        <View style={styles.header}>
          <TouchableOpacity onPress={() => navigation.goBack()} style={{padding: 8}}>
            <Ionicons name="chevron-back" size={24} color="white" />
          </TouchableOpacity>
          <HamburgerMenuButton navigation={navigation} />
          <Text style={styles.headerTitle}>Cardio</Text>
          <View style={{ width: 24 }} />
        </View>

        {/* Search Bar */}
        <View style={styles.searchContainer}>
          <View style={styles.searchBar}>
            <Ionicons name="search" size={20} color="#8E8E93" />
            <TextInput
              style={styles.searchInput}
              placeholder="Search"
              placeholderTextColor="#8E8E93"
              value={searchQuery}
              onChangeText={setSearchQuery}
            />
          </View>
          <TouchableOpacity 
            style={styles.filterButton}            onPress={() => navigation.navigate('Filter', { sourceScreen: 'Cardio' })}
          >
            <Ionicons name="options-outline" size={24} color="white" />
          </TouchableOpacity>
        </View>

        {/* Workout List */}
        <ScrollView style={styles.workoutList}>
          {filteredWorkouts.map((workout) => (
            <TouchableOpacity
              key={workout.id}
              style={styles.workoutCard}
              onPress={() => navigation.navigate('VideoMode', { workout })}
            >
              <Image source={{ uri: workout.image }} style={styles.workoutImage} />
              <View style={styles.workoutInfo}>
                <Text style={styles.workoutTitle}>{workout.title}</Text>
                <Text style={styles.workoutDetails}>{workout.totalTime} | {workout.equipment}</Text>
              </View>
            </TouchableOpacity>
          ))}
        </ScrollView>
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
  activeFiltersContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: 'rgba(255, 149, 0, 0.1)',
    marginHorizontal: 16,
    marginTop: 8,
    padding: 8,
    borderRadius: 8,
  },
  activeFiltersText: {
    color: '#FF9500',
    fontSize: 14,
  },
  editFiltersText: {
    color: '#FF9500',
    fontSize: 14,
    textDecorationLine: 'underline',
  },
  header: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingHorizontal: 16,
    paddingVertical: 12,
  },
  backButton: {
    padding: 4,
  },
  headerTitle: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#FF9500',
  },
  searchContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingHorizontal: 16,
    paddingVertical: 8,
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
    width: 40,
    height: 40,
    borderRadius: 12,
    alignItems: 'center',
    justifyContent: 'center',
  },
  workoutList: {
    flex: 1,
    padding: 16,
  },
  workoutCard: {
    backgroundColor: '#1C1C1E',
    borderRadius: 12,
    marginBottom: 16,
    overflow: 'hidden',
  },
  workoutImage: {
    width: '100%',
    height: 200,
    resizeMode: 'cover',
  },
  workoutDetails: {
    padding: 16,
  },
  workoutHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 8,
  },
  workoutTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    color: 'white',
  },
  workoutType: {
    color: '#FF9500',
    fontSize: 16,
    marginBottom: 8,
  },
  workoutInfo: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 4,
  },
  infoLabel: {
    color: '#8E8E93',
    fontSize: 14,
    width: 100,
  },
  infoValue: {
    color: 'white',
    fontSize: 14,
  },
});