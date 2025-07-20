import React from 'react';
import { View, Text, StyleSheet, TouchableOpacity, Image, ScrollView } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';

const exerciseData = [
  {
    id: 1,
    name: "Bent arm plank",
    image: "https://api.a0.dev/assets/image?text=bent%20arm%20plank&aspect=16:9&seed=1",
    hasDemo: true,
    hasTutorial: true
  },
  {
    id: 2,
    name: "Bicep curl",
    image: "https://api.a0.dev/assets/image?text=bicep%20curl&aspect=16:9&seed=2",
    hasDemo: true,
    hasTutorial: true
  },
  {
    id: 3,
    name: "Pushup",
    image: "https://api.a0.dev/assets/image?text=pushup&aspect=16:9&seed=3",
    hasDemo: true,
    hasTutorial: true
  },
  {
    id: 4,
    name: "Tricep Kickback",
    image: "https://api.a0.dev/assets/image?text=tricep%20kickback&aspect=16:9&seed=4",
    hasDemo: true,
    hasTutorial: true
  },
  {
    id: 5,
    name: "Skull Crusher",
    image: "https://api.a0.dev/assets/image?text=skull%20crusher&aspect=16:9&seed=5",
    hasDemo: true,
    hasTutorial: true
  },
  {
    id: 6,
    name: "Plate Serves",
    image: "https://api.a0.dev/assets/image?text=plate%20serves&aspect=16:9&seed=6",
    hasDemo: false,
    hasTutorial: true
  }
];

export default function ExerciseDemosScreen({ navigation }) {
  return (
    <View style={styles.container}>
      <SafeAreaView style={styles.safeArea}>
        <View style={styles.header}>
          <TouchableOpacity 
            style={styles.backButton}
            onPress={() => navigation.goBack()}
          >
            <Ionicons name="chevron-back" size={24} color="white" />
          </TouchableOpacity>
          <Text style={styles.headerTitle}>Exercise Demos</Text>
          <View style={{ width: 24 }} />
        </View>

        <ScrollView style={styles.content}>
          {exerciseData.map((exercise) => (
            <View key={exercise.id} style={styles.exerciseCard}>
              <Image source={{ uri: exercise.image }} style={styles.exerciseImage} />
              <View style={styles.exerciseContent}>
                <Text style={styles.exerciseName}>{exercise.name}</Text>
                <View style={styles.buttonContainer}>
                  {exercise.hasDemo && (
                    <TouchableOpacity 
                      style={styles.actionButton}
                      onPress={() => {
                        toast.message('Coming Soon!', {
                          description: 'Demo videos will be available in the next update'
                        });
                      }}
                    >
                      <Text style={styles.actionButtonText}>DEMO</Text>
                    </TouchableOpacity>
                  )}
                  {exercise.hasTutorial && (
                    <TouchableOpacity 
                      style={styles.actionButton}
                      onPress={() => {
                        toast.message('Coming Soon!', {
                          description: 'Tutorial videos will be available in the next update'
                        });
                      }}
                    >
                      <Text style={styles.actionButtonText}>TUTORIAL</Text>
                    </TouchableOpacity>
                  )}
                </View>
              </View>
            </View>
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
    fontSize: 20,
    fontWeight: 'bold',
    color: '#FF9500',
  },
  content: {
    flex: 1,
    padding: 16,
  },
  exerciseCard: {
    backgroundColor: '#1C1C1E',
    borderRadius: 12,
    marginBottom: 16,
    overflow: 'hidden',
  },
  exerciseImage: {
    width: '100%',
    height: 120,
    resizeMode: 'cover',
  },
  exerciseContent: {
    padding: 16,
  },
  exerciseName: {
    color: 'white',
    fontSize: 16,
    fontWeight: '600',
    marginBottom: 12,
  },
  buttonContainer: {
    flexDirection: 'row',
    gap: 12,
  },
  actionButton: {
    backgroundColor: '#333333',
    paddingVertical: 8,
    paddingHorizontal: 16,
    borderRadius: 16,
  },
  actionButtonText: {
    color: 'white',
    fontSize: 14,
    fontWeight: '600',
  },
});