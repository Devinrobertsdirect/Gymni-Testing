import React from 'react';
import { View, Text, StyleSheet, TouchableOpacity, Image, ScrollView, TextInput } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { useState } from 'react';
import HamburgerMenuButton from './HamburgerMenuButton';
import { getWorkoutAsset } from '../config/workoutAssets';

// Mock HIIT workouts data
const mockWorkouts = [
  {
    id: 1,
    title: "Bodyweight Burner",
    type: "HIIT",
    totalTime: "30 minutes",
    rating: 5,
    intensity: 7,
    equipment: "Bodyweight",
    muscleGroup: "full",
    image: getWorkoutAsset('hiit')
  },
  {
    id: 2,
    title: "Dumbbell Power",
    type: "HIIT",
    totalTime: "45 minutes",
    rating: 4,
    intensity: 8,
    equipment: "Dumbbells",
    muscleGroup: "upper",
    image: getWorkoutAsset('hiit')
  },
  {
    id: 3,
    title: "Resistance Band Blast",
    type: "HIIT",
    totalTime: "20 minutes",
    rating: 5,
    intensity: 6,
    equipment: "Resistance Bands",
    muscleGroup: "full",
    image: getWorkoutAsset('hiit')
  },
  {
    id: 4,
    title: "Cable Machine Circuit",
    type: "HIIT",
    totalTime: "40 minutes",
    rating: 5,
    intensity: 9,
    equipment: "Cable Machine",
    muscleGroup: "full",
    image: getWorkoutAsset('hiit')
  },
  {
    id: 5,
    title: "Full Gym Fury",
    type: "HIIT",
    totalTime: "50 minutes",
    rating: 4,
    intensity: 9,
    equipment: "Full Gym",
    muscleGroup: "full",
    image: "getWorkoutAsset('hiit')?text=gym%20hiit&aspect=16:9&seed=102"
  },
  {
    id: 6,
    title: "Kettlebell King",
    type: "HIIT",
    totalTime: "35 minutes",
    rating: 5,
    intensity: 8,
    equipment: "Kettlebell",
    muscleGroup: "full",
    image: "getWorkoutAsset('hiit')?text=kettlebell%20hiit&aspect=16:9&seed=103"
  },
  {
    id: 7,
    title: "Bodyweight Beast",
    type: "HIIT",
    totalTime: "25 minutes",
    rating: 4,
    intensity: 7,
    equipment: "Bodyweight",
    muscleGroup: "lower",
    image: "getWorkoutAsset('hiit')?text=bodyweight%20lower%20hiit&aspect=16:9&seed=104"
  },
  {
    id: 8,
    title: "Dumbbell Destroyer",
    type: "HIIT",
    totalTime: "40 minutes",
    rating: 5,
    intensity: 8,
    equipment: "Dumbbells",
    muscleGroup: "full",
    image: "getWorkoutAsset('hiit')?text=dumbbell%20full%20hiit&aspect=16:9&seed=105"
  },
  {
    id: 9,
    title: "Resistance Band Burnout",
    type: "HIIT",
    totalTime: "30 minutes",
    rating: 4,
    intensity: 7,
    equipment: "Resistance Bands",
    muscleGroup: "upper",
    image: "getWorkoutAsset('hiit')?text=band%20upper%20hiit&aspect=16:9&seed=106"
  },
  {
    id: 10,
    title: "Cable Machine Carnage",
    type: "HIIT",
    totalTime: "45 minutes",
    rating: 5,
    intensity: 9,
    equipment: "Cable Machine",
    muscleGroup: "upper",
    image: "getWorkoutAsset('hiit')?text=cable%20upper%20hiit&aspect=16:9&seed=107"
  },
  {
    id: 11,
    title: "Full Gym Frenzy",
    type: "HIIT",
    totalTime: "55 minutes",
    rating: 5,
    intensity: 10,
    equipment: "Full Gym",
    muscleGroup: "full",
    image: "getWorkoutAsset('hiit')?text=full%20gym%20hiit&aspect=16:9&seed=108"
  },
  {
    id: 12,
    title: "Kettlebell Killer",
    type: "HIIT",
    totalTime: "35 minutes",
    rating: 4,
    intensity: 8,
    equipment: "Kettlebell",
    muscleGroup: "lower",
    image: "getWorkoutAsset('hiit')?text=kettlebell%20lower%20hiit&aspect=16:9&seed=109"
  }
];

export default function HIITScreen({ navigation, route }) {  const [searchQuery, setSearchQuery] = useState('');
  const [activeFilters, setActiveFilters] = useState(null);
  const [activeFilterCount, setActiveFilterCount] = useState(0);

  // Handle incoming filters
  React.useEffect(() => {
    // If filters are explicitly null, clear them
    if (route.params?.filters === null) {
      setActiveFilters(null);
      setActiveFilterCount(0);
    } 
    // If new filters are provided, set them
    else if (route.params?.filters) {
      setActiveFilters(route.params.filters);
    }
  }, [route.params?.filters]);



  const [favorites, setFavorites] = useState(new Set());

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

  const renderStars = (rating) => {
    return [...Array(5)].map((_, index) => (
      <Ionicons
        key={index}
        name={index < rating ? "star" : "star-outline"}
        size={20}
        color="#FF9500"
      />
    ));
  };  const filteredWorkouts = React.useMemo(() => {
    return mockWorkouts.filter(workout => {
      // Apply search filter
      if (!workout.title.toLowerCase().includes(searchQuery.toLowerCase())) {
        return false;
      }

      // Apply active filters if any
      if (activeFilters) {
        // Equipment filter
        if (activeFilters.equipment && workout.equipment !== activeFilters.equipment) {
          return false;
        }

        // Duration filter
        if (activeFilters.duration && !workout.totalTime.includes(activeFilters.duration)) {
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

  // Add active filters display  // Update active filter count when filters change
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
  }, [activeFilters]);  return (
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
          <Text style={styles.headerTitle}>HIIT</Text>
          <View style={{ width: 24 }} />
        </View>

        {/* Search Bar */}        <View style={styles.searchContainer}>
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
            style={styles.filterButton}
            onPress={() => navigation.navigate('Filter', { sourceScreen: 'HIIT' })}
          >
            <Ionicons name="options-outline" size={24} color="white" />
          </TouchableOpacity>
        </View>        {/* Workout List */}
        <ScrollView style={styles.workoutList}>
          {filteredWorkouts.map(workout => (
            <TouchableOpacity 
              key={workout.id} 
              style={styles.workoutCard}
              onPress={() => navigation.navigate('VideoMode', { workout })}
            >
              <Image source={{ uri: workout.image }} style={styles.workoutImage} />
              <View style={styles.workoutDetails}>
                <View style={styles.workoutHeader}>
                  <Text style={styles.workoutTitle}>{workout.title}</Text>
                  <TouchableOpacity
                    onPress={() => toggleFavorite(workout.id)}
                  >
                    <Ionicons
                      name={favorites.has(workout.id) ? "heart" : "heart-outline"}
                      size={24}
                      color="white"
                    />
                  </TouchableOpacity>
                </View>
                <Text style={styles.workoutType}>{workout.type}</Text>
                <View style={styles.workoutInfo}>
                  <Text style={styles.infoLabel}>Total time: </Text>
                  <Text style={styles.infoValue}>{workout.totalTime}</Text>
                </View>
                <View style={styles.workoutInfo}>
                  <Text style={styles.infoLabel}>Rating: </Text>
                  <View style={styles.ratingContainer}>
                    {renderStars(workout.rating)}
                  </View>
                </View>
                <View style={styles.workoutInfo}>
                  <Text style={styles.infoLabel}>Intensity: </Text>
                  <Text style={styles.infoValue}>{workout.intensity}</Text>
                </View>
                <View style={styles.workoutInfo}>
                  <Text style={styles.infoLabel}>Equipment: </Text>
                  <Text style={styles.infoValue}>{workout.equipment}</Text>
                </View>
                <View style={styles.workoutInfo}>
                  <Text style={styles.infoLabel}>Muscle Group: </Text>
                  <Text style={styles.infoValue}>{workout.muscleGroup}</Text>
                </View>
              </View>
            </TouchableOpacity>
          ))}
        </ScrollView>
      </SafeAreaView>
    </View>
  );
}

const styles = StyleSheet.create({
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
  ratingContainer: {
    flexDirection: 'row',
    gap: 2,
  },
});