import React, { useState, useRef } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, ScrollView } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';

export default function DescriptionModeScreen({ navigation, route }) {
  const [completedExercises, setCompletedExercises] = useState(new Set());
  const [expandedSections, setExpandedSections] = useState(new Set(['warmup', 'circuit']));
  const [activeTimer, setActiveTimer] = useState(null);
  const [remainingTime, setRemainingTime] = useState(0);
  const timerRef = useRef(null);

  const workoutStructure = {
    warmup: {
      title: "Warmup",
      exercises: [
        { id: 'w1', name: "Jumping Jacks", sets: 1, reps: "20" },
        { id: 'w2', name: "Quad to hamstring stretch", sets: 1, reps: "3e" },
        { id: 'w3', name: "Hamstring trunk twist", sets: 1, reps: "3" },
        { id: 'w4', name: "Jumping Jacks", sets: 1, reps: "20s" }
      ]
    },
    circuit: {
      title: "Circuit",
      exercises: [
        { id: 'c1', name: "RDL", sets: 3, reps: "10" },
        { id: 'c2', name: "Physioball hamstring curls", sets: 3, reps: "8" },
        { id: 'c3', name: "Kettlebell swing", sets: 3, reps: "10" }
      ],
      rest: "1-2 minutes"
    }
  };

  const toggleSection = (sectionId) => {
    setExpandedSections(prev => {
      const newSet = new Set(prev);
      if (newSet.has(sectionId)) {
        newSet.delete(sectionId);
      } else {
        newSet.add(sectionId);
      }
      return newSet;
    });
  };

  const toggleExerciseCompletion = (exerciseId) => {
    setCompletedExercises(prev => {
      const newSet = new Set(prev);
      if (newSet.has(exerciseId)) {
        newSet.delete(exerciseId);
      } else {
        newSet.add(exerciseId);
      }
      return newSet;
    });
  };

  const startTimer = (duration) => {
    if (timerRef.current) {
      clearInterval(timerRef.current);
    }
    
    const durationMs = duration * 1000;
    setRemainingTime(durationMs);
    setActiveTimer(duration);

    const startTime = Date.now();
    timerRef.current = setInterval(() => {
      const elapsed = Date.now() - startTime;
      const remaining = durationMs - elapsed;
      
      if (remaining <= 0) {
        clearInterval(timerRef.current);
        setRemainingTime(0);
        setActiveTimer(null);
      } else {
        setRemainingTime(remaining);
      }
    }, 100);
  };

  const formatTime = (ms) => {
    const seconds = Math.ceil(ms / 1000);
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = seconds % 60;
    return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
  };

  React.useEffect(() => {
    return () => {
      if (timerRef.current) {
        clearInterval(timerRef.current);
      }
    };
  }, []);

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
          <Text style={styles.headerTitle}>Quick Hits: Hamstrings</Text>
          <View style={{ width: 24 }} />
        </View>

        <ScrollView style={styles.content}>
          {/* Warmup Section */}
          <TouchableOpacity 
            style={styles.sectionHeader}
            onPress={() => toggleSection('warmup')}
          >
            <View style={styles.sectionTitleContainer}>
              <MaterialCommunityIcons name="fire" size={24} color="#FF9500" />
              <Text style={styles.sectionTitle}>Warmup</Text>
            </View>
            <Ionicons 
              name={expandedSections.has('warmup') ? "chevron-up" : "chevron-down"} 
              size={24} 
              color="#FF9500" 
            />
          </TouchableOpacity>

          {expandedSections.has('warmup') && (
            <View style={styles.exercisesContainer}>
              <View style={styles.exerciseHeaderRow}>
                <Text style={styles.exerciseHeaderTitle}>Warmup</Text>
                <View style={styles.exerciseHeaderMetrics}>
                  <Text style={styles.headerMetricText}>Sets</Text>
                  <Text style={styles.headerMetricText}>Reps</Text>
                </View>
              </View>
              {workoutStructure.warmup.exercises.map((exercise) => (
                <TouchableOpacity
                  key={exercise.id}
                  style={[
                    styles.exerciseRow,
                    completedExercises.has(exercise.id) && styles.completedExercise
                  ]}
                  onPress={() => toggleExerciseCompletion(exercise.id)}
                >
                  <View style={styles.exerciseInfo}>
                    <Text style={styles.exerciseName}>{exercise.name}</Text>
                  </View>
                  <View style={styles.exerciseMetrics}>
                    <Text style={styles.metricText}>{exercise.sets}</Text>
                    <View style={styles.repsContainer}>
                      <Text style={styles.metricText}>{exercise.reps}</Text>
                      {exercise.reps.endsWith('s') && (
                        <TouchableOpacity
                          style={styles.timerButton}
                          onPress={() => startTimer(parseInt(exercise.reps))}
                        >
                          <Ionicons 
                            name="timer-outline" 
                            size={20} 
                            color="#FF9500" 
                          />
                        </TouchableOpacity>
                      )}
                    </View>
                  </View>
                </TouchableOpacity>
              ))}
            </View>
          )}

          {/* Circuit Section */}
          <TouchableOpacity 
            style={styles.sectionHeader}
            onPress={() => toggleSection('circuit')}
          >
            <View style={styles.sectionTitleContainer}>
              <MaterialCommunityIcons name="repeat" size={24} color="#FF9500" />
              <Text style={styles.sectionTitle}>Circuit</Text>
            </View>
            <Ionicons 
              name={expandedSections.has('circuit') ? "chevron-up" : "chevron-down"} 
              size={24} 
              color="#FF9500" 
            />
          </TouchableOpacity>

          {expandedSections.has('circuit') && (
            <View style={styles.exercisesContainer}>
              {workoutStructure.circuit.exercises.map((exercise) => (
                <TouchableOpacity
                  key={exercise.id}
                  style={[
                    styles.exerciseRow,
                    completedExercises.has(exercise.id) && styles.completedExercise
                  ]}
                  onPress={() => toggleExerciseCompletion(exercise.id)}
                >
                  <View style={styles.exerciseInfo}>
                    <Text style={styles.exerciseName}>{exercise.name}</Text>
                  </View>
                  <View style={styles.exerciseMetrics}>
                    <Text style={styles.metricText}>{exercise.sets}</Text>
                    <Text style={styles.metricText}>{exercise.reps}</Text>
                  </View>
                </TouchableOpacity>
              ))}
              <Text style={styles.restText}>Rest: {workoutStructure.circuit.rest}</Text>
            </View>
          )}

          {/* Exercise Demos Button */}
          <TouchableOpacity 
            style={styles.demosButton}
            onPress={() => {
              toast.message('Coming Soon!', {
                description: 'Exercise demos will be available in the next update'
              });
            }}
          >
            <Text style={styles.demosButtonText}>Exercise Demos</Text>
          </TouchableOpacity>
        </ScrollView>

        {/* Active Timer Display */}
        {activeTimer && (
          <View style={styles.timerOverlay}>
            <Text style={styles.timerText}>{formatTime(remainingTime)}</Text>
            <TouchableOpacity
              style={styles.cancelTimerButton}
              onPress={() => {
                if (timerRef.current) {
                  clearInterval(timerRef.current);
                  setActiveTimer(null);
                }
              }}
            >
              <Text style={styles.cancelTimerText}>Cancel Timer</Text>
            </TouchableOpacity>
          </View>
        )}
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
  sectionHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: '#1C1C1E',
    padding: 16,
    borderRadius: 12,
    marginBottom: 16,
  },
  sectionTitleContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 12,
  },
  sectionTitle: {
    color: '#FF9500',
    fontSize: 18,
    fontWeight: '600',
  },
  exercisesContainer: {
    backgroundColor: '#1C1C1E',
    borderRadius: 12,
    padding: 16,
    marginBottom: 24,
  },
  exerciseHeaderRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 16,
  },
  exerciseHeaderTitle: {
    color: '#FF9500',
    fontSize: 16,
    fontWeight: '600',
  },
  exerciseHeaderMetrics: {
    flexDirection: 'row',
    gap: 24,
  },
  headerMetricText: {
    color: '#FF9500',
    fontSize: 14,
    width: 40,
    textAlign: 'center',
  },
  exerciseRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingVertical: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#333',
  },
  completedExercise: {
    opacity: 0.5,
  },
  exerciseInfo: {
    flex: 1,
  },
  exerciseName: {
    color: 'white',
    fontSize: 16,
  },
  exerciseMetrics: {
    flexDirection: 'row',
    gap: 24,
    alignItems: 'center',
  },
  metricText: {
    color: 'white',
    fontSize: 16,
    width: 40,
    textAlign: 'center',
  },
  repsContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
  },
  timerButton: {
    padding: 4,
  },
  restText: {
    color: '#8e8e8e',
    fontSize: 14,
    marginTop: 12,
    fontStyle: 'italic',
  },
  demosButton: {
    backgroundColor: '#FF9500',
    padding: 16,
    borderRadius: 12,
    alignItems: 'center',
    marginTop: 24,
  },
  demosButtonText: {
    color: 'white',
    fontSize: 16,
    fontWeight: '600',
  },
  timerOverlay: {
    position: 'absolute',
    bottom: 24,
    left: 24,
    right: 24,
    backgroundColor: 'rgba(28, 28, 30, 0.95)',
    borderRadius: 16,
    padding: 16,
    alignItems: 'center',
    gap: 12,
  },
  timerText: {
    color: '#FF9500',
    fontSize: 32,
    fontWeight: 'bold',
  },
  cancelTimerButton: {
    padding: 8,
  },
  cancelTimerText: {
    color: '#FF3B30',
    fontSize: 16,
  },
});