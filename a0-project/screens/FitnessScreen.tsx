import React from 'react';
import { View, Text, StyleSheet, TouchableOpacity, ScrollView, Image } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { LinearGradient } from 'expo-linear-gradient';
import { MaterialCommunityIcons, Ionicons, FontAwesome5 } from '@expo/vector-icons';
import { WORKOUT_TYPES } from '../config/workoutAssets';

const FitnessCard = ({ title, icon, onPress, description }) => (
  <TouchableOpacity style={styles.card} onPress={onPress}>
    <View style={styles.cardContent}>
      {icon}
      <View style={styles.cardTextContainer}>
        <Text style={styles.cardTitle}>{title}</Text>
        <Text style={styles.cardSubtitle}>{description}</Text>
      </View>
    </View>
  </TouchableOpacity>
);

export default function FitnessScreen({ navigation }) {
  return (
    <View style={styles.container}>
      <SafeAreaView style={styles.safeArea}>
        <View style={styles.header}>
          <TouchableOpacity onPress={() => navigation.goBack()}>
            <Ionicons name="menu" size={28} color="white" />
          </TouchableOpacity>
          <Text style={styles.headerTitle}>Fitness</Text>
          <View style={{ width: 28 }} />
        </View>

        <ScrollView style={styles.content} showsVerticalScrollIndicator={false}>
          {WORKOUT_TYPES.map((workout) => (
            <FitnessCard
              key={workout.id}
              title={workout.name}
              description={workout.description}
              icon={
                <Image 
                  source={workout.asset} 
                  style={styles.workoutIcon}
                  resizeMode="contain"
                />
              }
              onPress={() => navigation.navigate(workout.name)}
            />
          ))}
        </ScrollView>

        <View style={styles.bottomNav}>          <TouchableOpacity 
            style={styles.navItem}
            onPress={() => navigation.navigate('MainHome')}
          >
            <Ionicons name="home-outline" size={24} color="white" />
          </TouchableOpacity>
          <TouchableOpacity style={styles.navItem}>
            <Ionicons name="barbell" size={24} color="#FF9500" />
          </TouchableOpacity>          <TouchableOpacity 
            style={styles.navItem}
            onPress={() => navigation.navigate('SocialFeed')}
          >
            <Ionicons name="people-outline" size={24} color="white" />
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
  content: {
    flex: 1,
    paddingHorizontal: 16,
  },
  card: {
    backgroundColor: '#1C1C1E',
    borderRadius: 12,
    marginBottom: 16,
    overflow: 'hidden',
  },
  cardContent: {
    padding: 20,
    flexDirection: 'row',
    alignItems: 'center',
  },
  cardTextContainer: {
    marginLeft: 16,
  },
  cardTitle: {
    fontSize: 24,
    fontWeight: 'bold',
    color: 'white',
    marginBottom: 4,
  },
  cardSubtitle: {
    fontSize: 16,
    color: '#8E8E93',
  },
  workoutIcon: {
    width: 32,
    height: 32,
    tintColor: '#FF9500',
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
});