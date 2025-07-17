import React, { useState, useRef } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, Image, ScrollView, TextInput, ActivityIndicator, Dimensions } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons, MaterialCommunityIcons, FontAwesome5 } from '@expo/vector-icons';
import { Video } from 'expo-av';

export default function VideoModeScreen({ navigation, route }) {
  const [videoRef, setVideoRef] = useState(null);
  // Initial mock comments
  const initialComments = [
    {
      id: 1,
      user: {
        name: 'Sarah Johnson',
        avatar: 'https://api.a0.dev/assets/image?text=sarah%20profile&aspect=1:1&seed=1'
      },
      text: 'This workout was exactly what I needed! The intensity was perfect and I loved how the instructor explained each movement.',
      timestamp: new Date(Date.now() - 3600000 * 2), // 2 hours ago
      likes: 12,
      isLiked: false
    },
    {
      id: 2,
      user: {
        name: 'Mike Williams',
        avatar: 'https://api.a0.dev/assets/image?text=mike%20profile&aspect=1:1&seed=2'
      },
      text: 'Great for beginners! I appreciate the modifications shown for different fitness levels.',
      timestamp: new Date(Date.now() - 3600000 * 5), // 5 hours ago
      likes: 8,
      isLiked: false
    },
    {
      id: 3,
      user: {
        name: 'Emma Davis',
        avatar: 'https://api.a0.dev/assets/image?text=emma%20profile&aspect=1:1&seed=3'
      },
      text: 'The pacing was a bit fast for me, but I managed to keep up. Will definitely try again!',
      timestamp: new Date(Date.now() - 86400000), // 1 day ago
      likes: 5,
      isLiked: false
    }
  ];
  const { workout } = route.params;  const [activeTab, setActiveTab] = useState('video');
  const [completedExercises, setCompletedExercises] = useState(new Set());
  const [expandedSections, setExpandedSections] = useState(new Set(['warmup', 'circuit']));
  const [activeTimer, setActiveTimer] = useState(null);
  const [remainingTime, setRemainingTime] = useState(0);
  const timerRef = useRef(null);

  // Mock workout data structure
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
  };  const startTimer = () => {
    // Clear any existing timer
    if (timerRef.current) {
      clearInterval(timerRef.current);
    }

    setRemainingTime(0);
    setActiveTimer(true);

    // Start the timer
    const startTime = Date.now();
    timerRef.current = setInterval(() => {
      const elapsed = Date.now() - startTime;
      setRemainingTime(elapsed);
    }, 100);
  };

  const formatTime = (ms) => {
    const seconds = Math.ceil(ms / 1000);
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = seconds % 60;
    return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
  };

  // Cleanup timer on unmount
  React.useEffect(() => {
    return () => {
      if (timerRef.current) {
        clearInterval(timerRef.current);
      }
    };
  }, []);

  const [likes, setLikes] = useState(0);  const [comments, setComments] = useState(initialComments);
  const [commentText, setCommentText] = useState('');
  const [isPlaying, setIsPlaying] = useState(false);  const [isBuffering, setIsBuffering] = useState(false);
  const [progress, setProgress] = useState(0);
  const [duration, setDuration] = useState(0);
  const [isFullscreen, setIsFullscreen] = useState(false);

  const renderStars = (rating) => {
    return [...Array(5)].map((_, index) => (
      <Ionicons
        key={index}
        name={index < rating ? "star" : "star-outline"}
        size={24}
        color="#FF9500"
      />
    ));
  };

  // Assume isAuthenticated is a boolean indicating if the user is logged in
  const isAuthenticated = false; // TODO: Replace with real auth logic

  return (
    <View style={styles.container}>
      <SafeAreaView style={styles.safeArea}>
        {/* Header */}
        <View style={styles.header}>
          <TouchableOpacity 
            style={styles.backButton}
            onPress={() => navigation.goBack()}
          >
            <Ionicons name="chevron-back" size={24} color="white" />
          </TouchableOpacity>
          <Text style={styles.headerTitle}>{workout.title}</Text>
          <View style={{ width: 24 }} />
        </View>

        {/* Mode Toggle */}
        <View style={styles.modeToggle}>
          <TouchableOpacity 
            style={[
              styles.modeButton,
              activeTab === 'video' && styles.activeModeButton
            ]}
            onPress={() => setActiveTab('video')}
          >
            <Text style={[
              styles.modeButtonText,
              activeTab === 'video' && styles.activeModeButtonText
            ]}>Video Mode</Text>
          </TouchableOpacity>
          <TouchableOpacity 
            style={[
              styles.modeButton,
              activeTab === 'description' && styles.activeModeButton
            ]}
            onPress={() => setActiveTab('description')}
          >
            <Text style={[
              styles.modeButtonText,
              activeTab === 'description' && styles.activeModeButtonText
            ]}>Description Mode</Text>
          </TouchableOpacity>
        </View>

        <ScrollView style={styles.content}>          {/* Main Video/Image */}
          {activeTab === 'video' ? (
            <View style={styles.videoContainer}>              <Video
                source={{ uri: 'https://storage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4' }}
                style={styles.mainImage}
                resizeMode="cover"
                isLooping
                shouldPlay={false}
                isMuted={false}
                ref={video => setVideoRef(video)}
                onPlaybackStatusUpdate={status => {
                  if (status.isLoaded) {
                    setIsBuffering(status.isBuffering);
                    setProgress(status.positionMillis / status.durationMillis);
                    setDuration(status.durationMillis);
                    if (status.didJustFinish) {
                      setIsPlaying(false);
                    }
                  }
                }}
              />              <View style={[styles.videoOverlay, isFullscreen && styles.fullscreenOverlay]}>
                {isBuffering ? (
                  <ActivityIndicator size="large" color="#FF9500" />
                ) : (
                  <TouchableOpacity 
                    style={styles.playButton}
                    onPress={async () => {
                      if (videoRef) {
                        if (isPlaying) {
                          await videoRef.pauseAsync();
                        } else {
                          await videoRef.playAsync();
                        }
                        setIsPlaying(!isPlaying);
                      }
                    }}
                  >
                    <FontAwesome5 
                      name={isPlaying ? "pause" : "play"} 
                      size={24} 
                      color="white" 
                    />
                  </TouchableOpacity>
                )}                <TouchableOpacity 
                  style={styles.fullscreenButton}
                  onPress={() => setIsFullscreen(!isFullscreen)}
                >
                  <Ionicons 
                    name={isFullscreen ? "contract" : "expand"} 
                    size={24} 
                    color="white" 
                  />
                </TouchableOpacity>

                <View style={styles.progressContainer}>
                  <View style={styles.progressBar}>
                    <View 
                      style={[
                        styles.progressFill, 
                        { width: `${progress * 100}%` }
                      ]} 
                    />
                  </View>
                  <Text style={styles.durationText}>
                    {formatTime(progress * duration)} / {formatTime(duration)}
                  </Text>
                </View>
              </View>
            </View>
          ) : (
            <Image 
              source={{ uri: workout.image }}
              style={styles.mainImage}
            />
          )}

          {/* Workout Title and Add to Calendar */}
          <View style={styles.titleSection}>
            <MaterialCommunityIcons name="dumbbell" size={24} color="#FF9500" />
            <Text style={styles.workoutTitle}>{workout.title}</Text>            <TouchableOpacity 
              style={styles.calendarButton}
              onPress={() => navigation.navigate('Calendar', { 
                scheduleWorkout: {
                  ...workout,
                  id: workout.id,
                  title: workout.title,
                  type: workout.type,
                  duration: workout.totalTime,
                  equipment: workout.equipment,
                  image: workout.image
                }
              })}
            >
              <Text style={styles.calendarButtonText}>ADD TO CALENDAR</Text>
            </TouchableOpacity>



          </View>

          {/* Description */}          {activeTab === 'description' ? (
            <View style={styles.descriptionMode}>
              {/* Warmup Section */}
              <TouchableOpacity 
                style={styles.sectionHeader}
                onPress={() => toggleSection('warmup')}
              >
                <View style={styles.sectionTitleContainer}>
                  <MaterialCommunityIcons name="fire" size={24} color="#FF9500" />                  <Text style={styles.sectionTitle}>Warmup</Text>
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
                  ))}                  <View style={styles.restContainer}>
                    <Text style={styles.restText}>Rest: {workoutStructure.circuit.rest}</Text>
                    <TouchableOpacity
                      style={styles.timerButton}
                      onPress={() => startTimer()}
                    >
                      <Ionicons 
                        name="timer-outline" 
                        size={20} 
                        color="#FF9500" 
                      />
                    </TouchableOpacity>
                  </View>
                </View>
              )}

              {/* Exercise Demos Button */}
              <TouchableOpacity 
                style={styles.demosButton}                onPress={() => navigation.navigate('ExerciseDemos')}
              >
                <Text style={styles.demosButtonText}>Exercise Demos</Text>
              </TouchableOpacity>

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
            </View>
          ) : (
            <Text style={styles.description}>
              Part of our {workout.type.toLowerCase()} series! Get ready for an intense {workout.totalTime} workout targeting your {workout.muscleGroup} muscles.
            </Text>
          )}

          {/* Rating Stars */}
          <View style={styles.ratingContainer}>
            {renderStars(workout.rating || 4)}
          </View>

          {/* Workout Details */}
          <View style={styles.detailsSection}>
            <Text style={styles.detailsTitle}>Strength</Text>
            <View style={styles.detailsGrid}>
              <View style={styles.detailItem}>
                <Text style={styles.detailLabel}>Total Time</Text>
                <Text style={styles.detailValue}>{workout.totalTime}</Text>
              </View>
              <View style={styles.detailItem}>
                <Text style={styles.detailLabel}>Intensity</Text>
                <Text style={styles.detailValue}>{workout.intensity}/10</Text>
              </View>
              <View style={styles.detailItem}>
                <Text style={styles.detailLabel}>Equipment</Text>
                <Text style={styles.detailValue}>{workout.equipment}</Text>
              </View>
              <View style={styles.detailItem}>
                <Text style={styles.detailLabel}>Muscle Group</Text>
                <Text style={styles.detailValue}>{workout.muscleGroup}</Text>
              </View>
            </View>
          </View>

          {/* Challenge Friend Button */}          <TouchableOpacity 
            style={styles.challengeButton}
            onPress={() => navigation.navigate('CreateChallenge', { 
              workoutChallenge: {
                workout: workout,
                type: workout.type.toLowerCase(),
                defaultDescription: `I completed ${workout.title} 7 times! How many rounds can you complete?`,
                image: workout.image
              }
            })}
          >
            <Text style={styles.challengeButtonText}>CHALLENGE A FRIEND</Text>
          </TouchableOpacity>

          {/* Social Section */}
          <View style={styles.socialSection}>
            <TouchableOpacity 
              style={styles.socialButton}
              onPress={() => setLikes(prev => prev + 1)}
            >
              <Ionicons name="heart-outline" size={24} color="white" />
              <Text style={styles.socialText}>{likes}</Text>
            </TouchableOpacity>
            <TouchableOpacity style={styles.socialButton}>
              <Ionicons name="share-outline" size={24} color="white" />
              <Text style={styles.socialText}>Share</Text>
            </TouchableOpacity>
            <TouchableOpacity style={styles.socialButton}>
              <Ionicons name="download-outline" size={24} color="white" />
              <Text style={styles.socialText}>Save</Text>
            </TouchableOpacity>
          </View>          {/* Comments Section */}
          {isAuthenticated && (
            <View style={styles.commentsSection}>
              <Text style={styles.commentsSectionTitle}>Comments ({comments.length})</Text>
              
              {/* Comment Input */}
              <View style={styles.commentInputContainer}>
                <TextInput
                  style={styles.commentInput}
                  placeholder="Write a comment..."
                  placeholderTextColor="#8e8e8e"
                  value={commentText}
                  onChangeText={setCommentText}
                  multiline
                />
                <TouchableOpacity 
                  style={[
                    styles.sendButton,
                    !commentText.trim() && styles.sendButtonDisabled
                  ]}
                  onPress={() => {
                    if (commentText.trim()) {
                      const newComment = {
                        id: Date.now(),
                        user: {
                          name: 'You',
                          avatar: 'https://api.a0.dev/assets/image?text=user%20avatar&aspect=1:1'
                        },
                        text: commentText,
                        timestamp: new Date(),
                        likes: 0,
                        isLiked: false
                      };
                      setComments([newComment, ...comments]);
                      setCommentText('');
                    }
                  }}
                  disabled={!commentText.trim()}
                >
                  <Ionicons 
                    name="send" 
                    size={24} 
                    color={commentText.trim() ? "#FF9500" : "#666"} 
                  />
                </TouchableOpacity>
              </View>

              {/* Comments List */}
              <View style={styles.commentsList}>
                {comments.length === 0 ? (
                  <Text style={styles.noCommentsText}>Be the first to comment!</Text>
                ) : (
                  comments.map((comment) => (
                    <View key={comment.id} style={styles.commentItem}>
                      <Image 
                        source={{ uri: comment.user.avatar }}
                        style={styles.commentAvatar}
                      />
                      <View style={styles.commentContent}>
                        <View style={styles.commentHeader}>
                          <Text style={styles.commentUser}>{comment.user.name}</Text>
                          <Text style={styles.commentTime}>
                            {formatCommentTime(comment.timestamp)}
                          </Text>
                        </View>
                        <Text style={styles.commentText}>{comment.text}</Text>
                        <View style={styles.commentActions}>
                          <TouchableOpacity 
                            style={styles.commentAction}
                            onPress={() => {
                              setComments(comments.map(c => 
                                c.id === comment.id 
                                  ? { 
                                      ...c, 
                                      likes: c.isLiked ? c.likes - 1 : c.likes + 1,
                                      isLiked: !c.isLiked 
                                    }
                                  : c
                              ));
                            }}
                          >
                            <Ionicons 
                              name={comment.isLiked ? "heart" : "heart-outline"}
                              size={16} 
                              color={comment.isLiked ? "#FF9500" : "#8e8e8e"}
                            />
                            <Text style={[
                              styles.commentActionText,
                              comment.isLiked && styles.commentActionTextActive
                            ]}>
                              {comment.likes}
                            </Text>
                          </TouchableOpacity>
                          <TouchableOpacity style={styles.commentAction}>
                            <Ionicons name="chatbubble-outline" size={16} color="#8e8e8e" />
                            <Text style={styles.commentActionText}>Reply</Text>
                          </TouchableOpacity>
                        </View>
                      </View>
                    </View>
                  ))
                )}
              </View>
            </View>
          )}
        </ScrollView>
      </SafeAreaView>
    </View>
  );
}

const formatTime = (millis) => {
  if (!millis) return '00:00';
  const totalSeconds = Math.floor(millis / 1000);
  const minutes = Math.floor(totalSeconds / 60);
  const seconds = totalSeconds % 60;
  return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
};

// Helper function to format comment timestamps
const formatCommentTime = (date) => {
  const now = new Date();
  const diff = now.getTime() - date.getTime();
  
  // Convert milliseconds to minutes/hours/days
  const minutes = Math.floor(diff / 60000);
  const hours = Math.floor(minutes / 60);
  const days = Math.floor(hours / 24);
  
  if (days > 0) return `${days}d ago`;
  if (hours > 0) return `${hours}h ago`;
  if (minutes > 0) return `${minutes}m ago`;
  return 'Just now';
};

const styles = StyleSheet.create({
  descriptionMode: {
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
  },  cancelTimerText: {
    color: '#FF3B30',
    fontSize: 16,
  },
  restContainer: {
    marginTop: 12,
    backgroundColor: 'rgba(255, 149, 0, 0.1)',
    borderRadius: 8,
    padding: 12,
  },
  restTimerButtons: {
    flexDirection: 'row',
    justifyContent: 'center',
    gap: 12,
    marginTop: 8,
  },
  restTimerButton: {
    backgroundColor: '#FF9500',
    paddingVertical: 8,
    paddingHorizontal: 16,
    borderRadius: 8,
  },
  restTimerButtonText: {
    color: 'white',
    fontSize: 14,
    fontWeight: '600',
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
    fontSize: 20,
    fontWeight: 'bold',
    color: '#FF9500',
  },
  modeToggle: {
    flexDirection: 'row',
    margin: 16,
    backgroundColor: '#1C1C1E',
    borderRadius: 25,
    padding: 4,
  },
  modeButton: {
    flex: 1,
    paddingVertical: 12,
    alignItems: 'center',
    borderRadius: 21,
  },
  activeModeButton: {
    backgroundColor: '#FF9500',
  },
  modeButtonText: {
    color: '#8e8e8e',
    fontSize: 16,
    fontWeight: '600',
  },
  activeModeButtonText: {
    color: 'white',
  },
  content: {
    flex: 1,
  },  mainImage: {
    width: '100%',
    height: 300,
  },
  fullscreenVideo: {
    width: Dimensions.get('window').width,
    height: Dimensions.get('window').height,
  },  videoContainer: {
    width: '100%',
    height: 300,
    backgroundColor: '#000',
    position: 'relative',
  },
  fullscreenOverlay: {
    position: 'absolute',
    top: 0,
    left: 0,
    right: 0,
    bottom: 0,
    zIndex: 1000,
    height: Dimensions.get('window').height,
  },
  videoOverlay: {
    ...StyleSheet.absoluteFillObject,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: 'rgba(0, 0, 0, 0.3)',
  },  playButton: {
    width: 60,
    height: 60,
    borderRadius: 30,
    backgroundColor: 'rgba(255, 149, 0, 0.8)',
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 20,
  },  progressContainer: {
    position: 'absolute',
    bottom: 20,
    left: 20,
    right: 20,
  },
  fullscreenButton: {
    position: 'absolute',
    top: 20,
    right: 20,
    backgroundColor: 'rgba(0, 0, 0, 0.5)',
    padding: 8,
    borderRadius: 20,
  },
  progressBar: {
    height: 4,
    backgroundColor: 'rgba(255, 255, 255, 0.3)',
    borderRadius: 2,
    overflow: 'hidden',
    marginBottom: 8,
  },
  progressFill: {
    height: '100%',
    backgroundColor: '#FF9500',
    borderRadius: 2,
  },
  durationText: {
    color: 'white',
    fontSize: 12,
    textAlign: 'right',
  },
  titleSection: {
    flexDirection: 'row',
    alignItems: 'center',
    padding: 16,
    gap: 12,
  },
  workoutTitle: {
    flex: 1,
    fontSize: 20,
    fontWeight: 'bold',
    color: 'white',
  },
  calendarButton: {
    backgroundColor: '#FF9500',
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 20,
  },
  calendarButtonText: {
    color: 'white',
    fontSize: 12,
    fontWeight: '600',
  },
  description: {
    color: '#8e8e8e',
    fontSize: 16,
    lineHeight: 24,
    padding: 16,
  },
  ratingContainer: {
    flexDirection: 'row',
    padding: 16,
    gap: 4,
  },
  detailsSection: {
    padding: 16,
  },
  detailsTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    color: 'white',
    marginBottom: 16,
  },
  detailsGrid: {
    backgroundColor: '#1C1C1E',
    borderRadius: 12,
    padding: 16,
  },
  detailItem: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    paddingVertical: 8,
  },
  detailLabel: {
    color: '#8e8e8e',
    fontSize: 16,
  },
  detailValue: {
    color: 'white',
    fontSize: 16,
    fontWeight: '500',
  },
  challengeButton: {
    backgroundColor: '#FF9500',
    margin: 16,
    padding: 16,
    borderRadius: 12,
    alignItems: 'center',
  },
  challengeButtonText: {
    color: 'white',
    fontSize: 16,
    fontWeight: '600',
  },
  socialSection: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    padding: 16,
    borderTopWidth: 1,
    borderBottomWidth: 1,
    borderColor: '#333',
  },
  socialButton: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
  },
  socialText: {
    color: 'white',
    fontSize: 16,
  },  commentsSection: {
    padding: 16,
  },
  commentsSectionTitle: {
    color: 'white',
    fontSize: 18,
    fontWeight: '600',
    marginBottom: 16,
  },
  commentInputContainer: {
    flexDirection: 'row',
    alignItems: 'flex-end',
    gap: 12,
    backgroundColor: '#1C1C1E',
    borderRadius: 16,
    paddingHorizontal: 16,
    paddingVertical: 8,
    marginBottom: 20,
  },
  commentInput: {
    flex: 1,
    color: 'white',
    fontSize: 16,
    maxHeight: 100,
    minHeight: 40,
  },
  sendButton: {
    padding: 8,
  },
  sendButtonDisabled: {
    opacity: 0.5,
  },
  commentsList: {
    gap: 16,
  },
  noCommentsText: {
    color: '#8e8e8e',
    fontSize: 16,
    textAlign: 'center',
    fontStyle: 'italic',
    marginTop: 20,
  },
  commentItem: {
    flexDirection: 'row',
    gap: 12,
    marginBottom: 16,
  },
  commentAvatar: {
    width: 40,
    height: 40,
    borderRadius: 20,
  },
  commentContent: {
    flex: 1,
  },
  commentHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 4,
  },
  commentUser: {
    color: 'white',
    fontSize: 16,
    fontWeight: '600',
  },
  commentTime: {
    color: '#8e8e8e',
    fontSize: 12,
  },
  commentText: {
    color: 'white',
    fontSize: 14,
    lineHeight: 20,
  },
  commentActions: {
    flexDirection: 'row',
    gap: 16,
    marginTop: 8,
  },
  commentAction: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 4,
  },
  commentActionText: {
    color: '#8e8e8e',
    fontSize: 12,
  },
  commentActionTextActive: {
    color: '#FF9500',
  },
});