import { View, Text, StyleSheet, Image, TouchableOpacity, ScrollView, Dimensions, Animated, Pressable } from 'react-native';
import { useState, useRef } from 'react';
import { SafeAreaView } from 'react-native-safe-area-context';
import { LinearGradient } from 'expo-linear-gradient';
import { Ionicons, MaterialCommunityIcons, FontAwesome5 } from '@expo/vector-icons';
import HamburgerMenuButton from './HamburgerMenuButton';
import type { StackNavigationProp } from '@react-navigation/stack';

type RootStackParamList = {
  MainHome: undefined;
  // Add other routes as needed
};

type MainHomeScreenNavigationProp = StackNavigationProp<RootStackParamList, 'MainHome'>;

interface MainHomeScreenProps {
  navigation: MainHomeScreenNavigationProp;
}

const SCREEN_WIDTH = Dimensions.get('window').width;
const IMAGE_SIZE = (SCREEN_WIDTH - 48) / 3;

export default function MainHomeScreen({ navigation }: MainHomeScreenProps) {
  const workoutImages = Array(12).fill(null).map((_, i) => (
    `https://api.a0.dev/assets/image?text=fitness%20workout%20${i + 1}&aspect=1:1&seed=${i}`
  ));  const [selectedWorkout, setSelectedWorkout] = useState(null);
  const scaleAnim = useRef(new Animated.Value(1)).current;

  const handlePressIn = () => {
    Animated.spring(scaleAnim, {
      toValue: 0.95,
      useNativeDriver: true,
    }).start();
  };

  const handlePressOut = () => {
    Animated.spring(scaleAnim, {
      toValue: 1,
      useNativeDriver: true,
    }).start();
  };

  return (
    <Animated.View style={[styles.container, { transform: [{ scale: scaleAnim }] }]}>
      <SafeAreaView style={styles.safeArea}>
        {/* Top Bar */}
        <View style={styles.topBar}>
          <HamburgerMenuButton navigation={navigation} />
          <Image
            source={{ uri: 'https://api.a0.dev/assets/image?text=gymni%20logo%20white&aspect=4:1' }}
            style={styles.logo}
          />
        </View>

        <ScrollView style={styles.scrollView} showsVerticalScrollIndicator={false}>
          {/* Profile Card */}
          <View style={styles.profileCard}>
            <View style={styles.profileHeader}>
              <View style={styles.profileImageContainer}>
                <Image
                  source={{ uri: 'https://api.a0.dev/assets/image?text=fitness%20profile&aspect=1:1' }}
                  style={styles.profileImage}
                />
                <TouchableOpacity style={styles.editButton}>
                  <Ionicons name="add" size={24} color="white" />
                </TouchableOpacity>
              </View>
              <Text style={styles.username}>ally furbay</Text>
            </View>

            <View style={styles.statsContainer}>
              <View style={styles.statItem}>
                <Text style={styles.statNumber}>0</Text>
                <Text style={styles.statLabel}>Saved Workouts</Text>
              </View>              <TouchableOpacity 
                style={styles.statItem}
                onPress={() => navigation.navigate('SocialFeed', { initialTab: 'groups' })}
              >
                <Text style={styles.statNumber}>2</Text>
                <Text style={styles.statLabel}>My Groups</Text>
              </TouchableOpacity>              <TouchableOpacity 
                style={styles.statItem}
                onPress={() => navigation.navigate('Calendar')}
              >
                <Ionicons name="calendar-outline" size={24} color="white" />
                <Text style={styles.statLabel}>Calendar</Text>
              </TouchableOpacity>
            </View>
          </View>          {/* Navigation Tabs */}
          <View style={styles.navigationTabs}>
            <TouchableOpacity 
              style={[styles.navTab, styles.activeNavTab]}
            >
              <Ionicons name="star" size={24} color="#FF9500" />
              <Text style={[styles.navTabText, styles.activeNavTabText]}>What's New</Text>
            </TouchableOpacity>
            <TouchableOpacity 
              style={[styles.navTab]}
              onPress={() => navigation.navigate('Challenges')}
            >
              <MaterialCommunityIcons name="flag-triangle" size={24} color="#8e8e8e" />
              <Text style={styles.navTabText}>Challenges</Text>
            </TouchableOpacity>
          </View>

          {/* Workout Grid */}
          <View style={styles.workoutGrid}>
            {workoutImages.map((uri, index) => (
              <Pressable 
                key={index} 
                style={[
                  styles.workoutItem,
                  selectedWorkout === index && styles.selectedWorkout
                ]}
                onPress={() => {
                  setSelectedWorkout(index);
                  // Alternate between Cardio and Strength for demonstration
                  if (index % 2 === 0) {
                    navigation.navigate('Cardio', { workoutIndex: index });
                  } else {
                    navigation.navigate('Strength', { workoutIndex: index });
                  }
                }}
                onPressIn={handlePressIn}
                onPressOut={handlePressOut}
              >
                <Image source={{ uri }} style={styles.workoutImage} />
                <View style={styles.workoutOverlay}>
                  <Text style={styles.workoutText}>Workout {index + 1}</Text>
                </View>
              </Pressable>
            ))}
          </View>
        </ScrollView>

        {/* Bottom Navigation */}
        <View style={styles.bottomNav}>
          <TouchableOpacity style={styles.navItem}>
            <Ionicons name="home" size={24} color="#FF9500" />
          </TouchableOpacity>          <TouchableOpacity 
            style={styles.navItem}
            onPress={() => navigation.navigate('Fitness')}
          >
            <Ionicons name="barbell-outline" size={24} color="white" />
          </TouchableOpacity>          <TouchableOpacity 
            style={styles.navItem}
            onPress={() => navigation.navigate('SocialFeed')}
          >
            <Ionicons name="people-outline" size={24} color="white" />
          </TouchableOpacity>          <TouchableOpacity 
            style={styles.navItem}
            onPress={() => navigation.navigate('Calendar')}
          >
            <Ionicons name="calendar-outline" size={24} color="white" />
          </TouchableOpacity>
          <TouchableOpacity style={styles.navItem} onPress={() => navigation.navigate('Search')}>
            <Ionicons name="search-outline" size={24} color="white" />
          </TouchableOpacity>
        </View>
      </SafeAreaView>    </Animated.View>
  );
}

const styles = StyleSheet.create({
  selectedWorkout: {
    borderWidth: 2,
    borderColor: '#FF9500',
    borderRadius: 12,
  },
  workoutOverlay: {
    position: 'absolute',
    bottom: 0,
    left: 0,
    right: 0,
    backgroundColor: 'rgba(0,0,0,0.5)',
    padding: 8,
    borderBottomLeftRadius: 8,
    borderBottomRightRadius: 8,
  },
  workoutText: {
    color: 'white',
    fontSize: 12,
    fontWeight: '600',
  },
  navigationTabs: {
    flexDirection: 'row',
    backgroundColor: '#1a1a1a',
    margin: 16,
    borderRadius: 12,
    padding: 8,
  },
  navTab: {
    flex: 1,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    paddingVertical: 12,
    gap: 8,
    borderRadius: 8,
  },
  activeNavTab: {
    backgroundColor: 'rgba(255, 149, 0, 0.1)',
  },
  navTabText: {
    color: '#8e8e8e',
    fontSize: 16,
    fontWeight: '600',
  },
  activeNavTabText: {
    color: '#FF9500',
  },
  container: {
    flex: 1,
    backgroundColor: '#000000',
  },
  safeArea: {
    flex: 1,
  },
  scrollView: {
    flex: 1,
  },
  topBar: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 16,
    paddingVertical: 12,
  },
  logo: {
    width: 100,
    height: 30,
    resizeMode: 'contain',
  },  profileCard: {
    borderRadius: 16,
    margin: 16,
    padding: 16,
    backgroundColor: '#1a1a1a',
    shadowColor: '#FF9500',
    shadowOffset: { width: 0, height: 0 },
    shadowOpacity: 0.2,
    shadowRadius: 15,
    elevation: 5,
  },
  profileHeader: {
    alignItems: 'center',
    marginBottom: 16,
  },  profileImageContainer: {
    position: 'relative',
  },
  profileImage: {
    width: 80,
    height: 80,
    borderRadius: 40,
  },
  editButton: {
    position: 'absolute',
    right: -8,
    bottom: -8,
    backgroundColor: '#FF9500',
    borderRadius: 16,
    width: 32,
    height: 32,
    justifyContent: 'center',
    alignItems: 'center',
  },
  username: {
    color: '#FF9500',
    fontSize: 20,
    fontWeight: '600',
    marginTop: 8,
  },
  statsContainer: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    marginTop: 16,
  },
  statItem: {
    alignItems: 'center',
  },
  statNumber: {
    color: 'white',
    fontSize: 24,
    fontWeight: 'bold',
  },
  statLabel: {
    color: '#8e8e8e',
    fontSize: 14,
    marginTop: 4,
  },
  sectionHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    paddingHorizontal: 16,
    marginBottom: 16,
  },
  sectionTitle: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
  },
  sectionTitleText: {
    color: 'white',
    fontSize: 18,
    fontWeight: '600',
  },
  workoutGrid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    padding: 8,
    gap: 8,
  },
  workoutItem: {
    width: IMAGE_SIZE,
    height: IMAGE_SIZE,
  },
  workoutImage: {
    width: '100%',
    height: '100%',
    borderRadius: 8,
  },
  bottomNav: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    paddingVertical: 12,
    backgroundColor: '#1a1a1a',
    borderTopWidth: 1,
    borderTopColor: '#333333',
  },
  navItem: {
    padding: 8,
  },
});