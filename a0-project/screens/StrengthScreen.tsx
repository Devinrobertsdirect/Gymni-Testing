import React from 'react';
import { View, Text, StyleSheet, TouchableOpacity, Image, ScrollView, TextInput } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { useState } from 'react';
import type { StackNavigationProp } from '@react-navigation/stack';
import type { RouteProp } from '@react-navigation/native';
import HamburgerMenuButton from './HamburgerMenuButton';

type RootStackParamList = {
  Strength: undefined | { filters?: any };
  VideoMode: { workout: any };
  Filter: { sourceScreen?: string };
  Fitness: undefined;
};

type StrengthScreenNavigationProp = StackNavigationProp<RootStackParamList, 'Strength'>;
type StrengthScreenRouteProp = RouteProp<RootStackParamList, 'Strength'>;

interface StrengthScreenProps {
  navigation: StrengthScreenNavigationProp;
  route: StrengthScreenRouteProp;
}

export default function StrengthScreen({ navigation, route }: StrengthScreenProps) {
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
  };  // Mock strength workouts data

  const mockWorkouts = [
    {
      id: 1,
      title: "Bodyweight Power",
      type: "Strength",
      totalTime: "30 minutes",
      intensity: 7,
      equipment: "Bodyweight",
      muscleGroup: "full",
      image: "https://api.a0.dev/assets/image?text=bodyweight%20circuit%20training&aspect=16:9&seed=1"
    },
    {
      id: 2,
      title: "Dumbbell Upper Split",
      type: "Strength",
      totalTime: "45 minutes",
      intensity: 8,
      equipment: "Dumbbells",
      muscleGroup: "upper",
      image: "https://api.a0.dev/assets/image?text=dumbbell%20upper%20body&aspect=16:9&seed=2"
    },
    {
      id: 3,
      title: "Band Full Body",
      type: "Strength",
      totalTime: "40 minutes",
      intensity: 6,
      equipment: "Resistance Bands",
      muscleGroup: "full",
      image: "https://api.a0.dev/assets/image?text=resistance%20band%20workout&aspect=16:9&seed=3"
    },
    {
      id: 4,
      title: "Cable Upper Power",
      type: "Strength",
      totalTime: "50 minutes",
      intensity: 8,
      equipment: "Cable Machine",
      muscleGroup: "upper",
      image: "https://api.a0.dev/assets/image?text=cable%20machine%20workout&aspect=16:9&seed=4"
    },
    {
      id: 5,
      title: "Full Gym Split",
      type: "Strength",
      totalTime: "75 minutes",
      intensity: 9,
      equipment: "Full Gym",
      muscleGroup: "full",
      image: "https://api.a0.dev/assets/image?text=full%20gym%20workout&aspect=16:9&seed=5"
    },
    {
      id: 6,
      title: "Kettlebell Power",
      type: "Strength",
      totalTime: "40 minutes",
      intensity: 7,
      equipment: "Kettlebell",
      muscleGroup: "full",
      image: "https://api.a0.dev/assets/image?text=kettlebell%20workout&aspect=16:9&seed=6"
    },
    {
      id: 7,
      title: "Dumbbell Lower Body",
      type: "Strength",
      totalTime: "45 minutes",
      intensity: 8,
      equipment: "Dumbbells",
      muscleGroup: "lower",
      image: "https://api.a0.dev/assets/image?text=dumbbell%20leg%20workout&aspect=16:9&seed=7"
    },
    {
      id: 8,
      title: "Band Upper Focus",
      type: "Strength",
      totalTime: "35 minutes",
      intensity: 6,
      equipment: "Resistance Bands",
      muscleGroup: "upper",
      image: "https://api.a0.dev/assets/image?text=resistance%20band%20upper&aspect=16:9&seed=8"
    },
    {
      id: 9,
      title: "Cable Core Blast",
      type: "Strength",
      totalTime: "30 minutes",
      intensity: 7,
      equipment: "Cable Machine",
      muscleGroup: "core",
      image: "https://api.a0.dev/assets/image?text=cable%20core%20workout&aspect=16:9&seed=9"
    },
    {
      id: 10,
      title: "Bodyweight HIIT",
      type: "Strength",
      totalTime: "25 minutes",
      intensity: 9,
      equipment: "Bodyweight",
      muscleGroup: "full",
      image: "https://api.a0.dev/assets/image?text=bodyweight%20hiit&aspect=16:9&seed=10"
    },
    {
      id: 11,
      title: "Kettlebell Lower Body",
      type: "Strength",
      totalTime: "45 minutes",
      intensity: 8,
      equipment: "Kettlebell",
      muscleGroup: "lower",
      image: "https://api.a0.dev/assets/image?text=kettlebell%20legs&aspect=16:9&seed=11"
    },
    {
      id: 12,
      title: "Full Gym Upper Split",
      type: "Strength",
      totalTime: "60 minutes",
      intensity: 9,
      equipment: "Full Gym",
      muscleGroup: "upper",
      image: "https://api.a0.dev/assets/image?text=gym%20upper%20body&aspect=16:9&seed=12"
    }
  ];

  const filteredWorkouts = React.useMemo(() => {
    return mockWorkouts.filter(workout => {
      // Apply search filter
      if (!workout.title.toLowerCase().includes(searchQuery.toLowerCase())) {
        return false;
      }

      // Apply active filters if any
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
        {/* Active Filters Indicator */}        {activeFilterCount > 0 && (
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
          <Text style={styles.headerTitle}>Strength</Text>
          <View style={{ width: 24 }} />
        </View>

        {/* Search Bar */}
        <View style={styles.searchContainer}>          <View style={styles.searchBar}>
            <Ionicons name="search" size={20} color="#8E8E93" />
            <TextInput
              style={styles.searchInput}
              placeholder="Search"
              placeholderTextColor="#8E8E93"
              value={searchQuery}
              onChangeText={setSearchQuery}
            />
          </View>          <TouchableOpacity 
            style={styles.filterButton}
            onPress={() => navigation.navigate('Filter', { sourceScreen: 'Strength' })}
          >
            <Ionicons name="options-outline" size={24} color="white" />
          </TouchableOpacity>
        </View>

        {/* Workout List */}
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
                  <Text style={styles.infoLabel}>Intensity: </Text>
                  <Text style={styles.infoValue}>{workout.intensity}/10</Text>
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