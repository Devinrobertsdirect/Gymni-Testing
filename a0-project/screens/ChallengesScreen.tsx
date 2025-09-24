import React from 'react';
import { View, Text, StyleSheet, TouchableOpacity, Image, Animated, ScrollView } from 'react-native';
import { useRef, useState, useEffect } from 'react';
import { SafeAreaView } from 'react-native-safe-area-context';
import { LinearGradient } from 'expo-linear-gradient';
import { MaterialCommunityIcons, Ionicons, FontAwesome5 } from '@expo/vector-icons';
import { toast } from 'sonner-native';

// Firebase imports
import { db, auth } from '../config/firebase';
import { 
  collection, 
  query, 
  where, 
  getDocs, 
  addDoc, 
  deleteDoc, 
  doc,
  updateDoc,
  onSnapshot,
  orderBy 
} from 'firebase/firestore';

interface Challenge {
  id: string;
  type: string;
  name: string;
  status: 'pending' | 'in-progress' | 'completed' | 'expired';
  challenger: string;
  challenged?: string;
  date: string;
  description: string;
  timeRemaining?: string;
  currentProgress?: string;
  targetScore?: string;
  expiresAt: string;
  userId: string;
  createdAt: any;
  participants?: string[];
}

// Mock data for fallback/initial state
const mockChallenges: Challenge[] = [
  {
    id: 1,
    type: 'strength',
    name: 'Deadlift Challenge',
    status: 'in-progress',
    challenger: 'David Chen',
    challenged: 'You',
    date: '2025-04-04',
    description: 'Max weight deadlift challenge',
    timeRemaining: '23h 45m',
    currentProgress: '405 lbs',
    targetScore: '450 lbs',
    expiresAt: '2025-04-05T15:00:00',
  },
  {
    id: 2,
    type: 'cardio',
    name: '5K Time Trial',
    status: 'in-progress',
    challenger: 'Sarah Johnson',
    challenged: 'You',
    date: '2025-04-04',
    description: '5K run for best time',
    timeRemaining: '35h 15m',
    currentProgress: '24:30',
    targetScore: '23:00',
    expiresAt: '2025-04-06T09:00:00',
  },
  {
    id: 3,
    type: 'hiit',
    name: 'Tabata Showdown',
    status: 'pending',
    challenger: 'Lisa Wong',
    date: '2025-04-04',
    description: '20 minute HIIT workout challenge',
    expiresIn: '47h'
  },
  {
    id: 4,
    type: 'mobility',
    name: 'Flexibility Challenge',
    status: 'pending',
    challenger: 'Mike Williams',
    date: '2025-04-04',
    description: 'Full body flexibility assessment',
    expiresIn: '72h'
  },
  {
    id: 1,
    type: 'strength',
    name: 'Max Deadlift Challenge',
    outcome: 'win',
    date: '2025-04-03',
    challenger: 'John Smith',
    score: '405 lbs vs 385 lbs',
    comments: 'Great form! New personal record! 💪',
  },
  {
    id: 2,
    type: 'hiit',
    name: '500 Rep Challenge',
    outcome: 'loss',
    date: '2025-04-02',
    challenger: 'Sarah Johnson',
    score: '22:45 vs 20:15',
    comments: 'Tough one! Will get it next time! 🔥',
  },
  {
    id: 3,
    type: 'cardio',
    name: '5K Race',
    outcome: 'win',
    date: '2025-04-01',
    challenger: 'Mike Williams',
    score: '23:15 vs 24:30',
    comments: 'Perfect weather for a run! 🏃‍♂️',
  },
  {
    id: 4,
    type: 'mobility',
    name: 'Flexibility Test',
    outcome: 'loss',
    date: '2025-03-31',
    challenger: 'Emma Davis',
    score: '8/10 vs 9/10',
    comments: 'Need to work on hip mobility 🧘‍♀️',
  }
];

const getTypeIcon = (type) => {
  switch (type) {
    case 'strength':
      return <FontAwesome5 name="dumbbell" size={20} color="#FF9500" />;
    case 'hiit':
      return <MaterialCommunityIcons name="lightning-bolt" size={20} color="#FF9500" />;
    case 'cardio':
      return <MaterialCommunityIcons name="run" size={20} color="#FF9500" />;
    case 'mobility':
      return <MaterialCommunityIcons name="yoga" size={20} color="#FF9500" />;
    default:
      return null;
  }
};

export default function ChallengesScreen({ navigation, route }) {
  const [activeTab, setActiveTab] = useState('all');
  const [sortBy, setSortBy] = useState('date');
  const [expandedId, setExpandedId] = useState(null);
  const [challenges, setChallenges] = useState<Challenge[]>([]);
  const [loading, setLoading] = useState(false);

  // Fetch challenges from Firestore
  useEffect(() => {
    if (!auth.currentUser?.uid) return;

    const userId = auth.currentUser.uid;

    // Set up real-time listener for challenges
    const challengesQuery = query(
      collection(db, 'challenges'),
      where('participants', 'array-contains', userId),
      orderBy('createdAt', 'desc')
    );

    const unsubscribe = onSnapshot(challengesQuery, (snapshot) => {
      const challengesData = snapshot.docs.map(doc => ({
        id: doc.id,
        ...doc.data()
      })) as Challenge[];
      setChallenges(challengesData);
    }, (error) => {
      console.error('Error fetching challenges:', error);
      // Fallback to mock data if Firebase fails
      setChallenges(mockChallenges);
    });

    // Cleanup listener on unmount
    return () => unsubscribe();
  }, [auth.currentUser]);

  // Handle new challenges being added
  React.useEffect(() => {
    if (route.params?.newChallenge) {
      setChallenges(prevChallenges => [route.params.newChallenge, ...prevChallenges]);
      // Clear the params after using them
      navigation.setParams({ newChallenge: null });
    }
  }, [route.params?.newChallenge]);
  const [sortOrder, setSortOrder] = useState('desc');

  // Firebase challenge actions
  const handleAcceptChallenge = async (challengeId: string) => {
    try {
      setLoading(true);
      await updateDoc(doc(db, 'challenges', challengeId), {
        status: 'in-progress',
        participants: [...(challenges.find(c => c.id === challengeId)?.participants || []), auth.currentUser?.uid],
        acceptedAt: new Date()
      });
      toast.success('Challenge accepted!');
    } catch (error) {
      console.error('Error accepting challenge:', error);
      toast.error('Failed to accept challenge');
    } finally {
      setLoading(false);
    }
  };

  const handleDeclineChallenge = async (challengeId: string) => {
    try {
      setLoading(true);
      await updateDoc(doc(db, 'challenges', challengeId), {
        status: 'declined',
        declinedBy: auth.currentUser?.uid,
        declinedAt: new Date()
      });
      toast.success('Challenge declined');
    } catch (error) {
      console.error('Error declining challenge:', error);
      toast.error('Failed to decline challenge');
    } finally {
      setLoading(false);
    }
  };

  const handleCompleteChallenge = async (challengeId: string, finalScore: string) => {
    try {
      setLoading(true);
      await updateDoc(doc(db, 'challenges', challengeId), {
        status: 'completed',
        finalScore: finalScore,
        completedAt: new Date(),
        completedBy: auth.currentUser?.uid
      });
      toast.success('Challenge completed!');
    } catch (error) {
      console.error('Error completing challenge:', error);
      toast.error('Failed to complete challenge');
    } finally {
      setLoading(false);
    }
  };

  const handleForfeitChallenge = async (challengeId: string) => {
    try {
      setLoading(true);
      await updateDoc(doc(db, 'challenges', challengeId), {
        status: 'forfeited',
        forfeitedBy: auth.currentUser?.uid,
        forfeitedAt: new Date()
      });
      toast.success('Challenge forfeited');
    } catch (error) {
      console.error('Error forfeiting challenge:', error);
      toast.error('Failed to forfeit challenge');
    } finally {
      setLoading(false);
    }
  };

  const getSortedChallenges = () => {
    let filtered = [...challenges];
    
    // Filter based on active tab
    if (activeTab === 'active') {
      // Only show pending and in-progress challenges
      filtered = filtered.filter(c => c.status === 'pending' || c.status === 'in-progress');
    } else if (activeTab === 'completed') {
      // Only show challenges that have an outcome (win/loss)
      filtered = filtered.filter(c => c.outcome);
    } else if (activeTab !== 'active') {
      filtered = filtered.filter(c => c.type === activeTab);
    }

    // Sort based on selected criteria
    return filtered.sort((a, b) => {
      switch (sortBy) {
        case 'date':
          return sortOrder === 'desc' 
            ? new Date(b.date).getTime() - new Date(a.date).getTime()
            : new Date(a.date).getTime() - new Date(b.date).getTime();
        case 'outcome':
          return sortOrder === 'desc'
            ? b.outcome.localeCompare(a.outcome)
            : a.outcome.localeCompare(b.outcome);
        case 'challenger':
          return sortOrder === 'desc'
            ? b.challenger.localeCompare(a.challenger)
            : a.challenger.localeCompare(b.challenger);
        default:
          return 0;
      }
    });
  };

  return (    <View style={styles.container}>
      <SafeAreaView style={styles.safeArea}>
        {/* Top Bar */}
        <View style={styles.topBar}>
          <TouchableOpacity>
            <Ionicons name="menu" size={28} color="white" />
          </TouchableOpacity>
          <Image
            source={{ uri: 'https://api.a0.dev/assets/image?text=gymni%20logo%20white&aspect=4:1' }}
            style={styles.logo}
          />
        </View>

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
            </View>
            <View style={styles.statItem}>
              <Text style={styles.statNumber}>2</Text>
              <Text style={styles.statLabel}>My Groups</Text>
            </View>
            <View style={styles.statItem}>
              <Ionicons name="calendar-outline" size={24} color="white" />
              <Text style={styles.statLabel}>Calendar</Text>
            </View>
          </View>
        </View>        {/* Navigation Tabs */}  {/* Challenge Button */}  <TouchableOpacity 
    style={styles.createChallengeButton}
    onPress={() => navigation.navigate('CreateChallenge')}
  >
    <LinearGradient
      colors={['#FF9500', '#FF5733']}
      start={{ x: 0, y: 0 }}
      end={{ x: 1, y: 0 }}
      style={styles.gradientButton}
    >
      <Ionicons name="add-circle" size={24} color="white" />
      <Text style={styles.createChallengeText}>Challenge Someone</Text>
    </LinearGradient>
  </TouchableOpacity>

  <View style={styles.navigationTabs}>
          <TouchableOpacity 
            style={[styles.navTab]}
            onPress={() => navigation.navigate('MainHome')}
          >
            <Ionicons name="star" size={24} color="#8e8e8e" />
            <Text style={styles.navTabText}>What's New</Text>
          </TouchableOpacity>
          <TouchableOpacity 
            style={[styles.navTab, styles.activeNavTab]}
          >
            <MaterialCommunityIcons name="flag-triangle" size={24} color="#FF9500" />
            <Text style={[styles.navTabText, styles.activeNavTabText]}>Challenges</Text>
          </TouchableOpacity>
        </View>

        {/* Filter Tabs */}
        <View style={styles.filterTabs}>          <TouchableOpacity            style={[styles.filterTab, activeTab === 'active' && styles.activeFilterTab]}
            onPress={() => {
              setActiveTab('active');
              Animated.sequence([
                Animated.spring(scaleAnim, {
                  toValue: 0.95,
                  useNativeDriver: true,
                }),
                Animated.spring(scaleAnim, {
                  toValue: 1,
                  useNativeDriver: true,
                })
              ]).start();
            }}
          >
            <Text style={[styles.filterTabText, activeTab === 'all' && styles.activeFilterTabText]}>              Active
            </Text>
          </TouchableOpacity>
          <TouchableOpacity 
            style={[styles.filterTab, activeTab === 'completed' && styles.activeFilterTab]}
            onPress={() => setActiveTab('completed')}
          >
            <Text style={[styles.filterTabText, activeTab === 'completed' && styles.activeFilterTabText]}>
              Completed Challenges
            </Text>
          </TouchableOpacity>
        </View>        <ScrollView style={styles.challengeList}>          {getSortedChallenges().map((challenge) => (
            <TouchableOpacity 
              key={challenge.id}
              style={[
                styles.challengeItem,
                challenge.status === 'pending' && styles.pendingChallengeItem
              ]}
              onPress={() => setExpandedId(expandedId === challenge.id ? null : challenge.id)}
            >
              <View style={styles.challengeHeader}>
                <View style={styles.challengeLeft}>
                  {getTypeIcon(challenge.type)}
                  <Text style={styles.challengeName}>{challenge.name}</Text>
                </View>                {(() => {
                  if (challenge.status === 'pending') {
                    return (
                      <View style={styles.pendingContainer}>
                        <Text style={styles.pendingText}>Expires in {challenge.expiresIn}</Text>
                      </View>
                    );
                  } else if (challenge.status === 'in-progress') {
                    return (
                      <View style={styles.inProgressContainer}>
                        <Text style={styles.inProgressText}>In Progress</Text>
                        <Text style={styles.timeRemainingText}>{challenge.timeRemaining}</Text>
                      </View>
                    );
                  } else {
                    return (
                      <Text style={[
                        styles.outcomeText,
                        challenge.outcome === 'win' ? styles.winText : styles.lossText
                      ]}>
                        {challenge.outcome === 'win' ? 'Win' : 'Loss'}
                      </Text>
                    );
                  }
                })()}
              </View>              {expandedId === challenge.id && (
                <View style={styles.expandedContent}>
                  <Text style={styles.detailText}>Date: {new Date(challenge.date).toLocaleDateString()}</Text>
                  <Text style={styles.detailText}>{challenge.challenger} challenged {challenge.challenged}</Text>
                  
                  {challenge.status === 'in-progress' ? (
                    <>
                      <View style={styles.progressSection}>
                        <Text style={styles.progressTitle}>Progress</Text>
                        <View style={styles.progressBar}>
                          <View style={[styles.progressFill, { width: `${(parseInt(challenge.currentProgress) / parseInt(challenge.targetScore)) * 100}%` }]} />
                        </View>
                        <Text style={styles.progressText}>{challenge.currentProgress} / {challenge.targetScore}</Text>
                        <Text style={styles.timeRemaining}>Time Remaining: {challenge.timeRemaining}</Text>
                      </View>
                      <View style={styles.actionButtons}>
                        <TouchableOpacity 
                          style={[styles.actionButton, styles.updateButton]}
                          onPress={() => {
                            // Here you would show a modal/form to update progress
                            toast.message('Update Progress', {
                              description: 'Coming soon: Update your progress here'
                            });
                          }}
                        >
                          <Text style={styles.actionButtonText}>Update Progress</Text>
                        </TouchableOpacity>
                        <TouchableOpacity 
                          style={[styles.actionButton, styles.forfeitButton]}
                          onPress={() => handleForfeitChallenge(challenge.id)}
                        >
                          <Text style={styles.actionButtonText}>Forfeit</Text>
                        </TouchableOpacity>
                        <TouchableOpacity 
                          style={[styles.actionButton, styles.completeButton]}
                          onPress={() => {
                            // TODO: Replace with proper React Native input modal
                            const finalScore = '425 lbs'; // Placeholder - implement proper input modal
                            if (finalScore) {
                              handleCompleteChallenge(challenge.id, finalScore);
                            }
                          }}
                        >
                          <Text style={styles.actionButtonText}>Complete Challenge</Text>
                        </TouchableOpacity>
                      </View>
                    </>
                  ) : challenge.status === 'pending' ? (
                    <>                <View style={styles.challengeContent}>
                  <Text style={styles.detailText}>Description: {challenge.description}</Text>
                  {challenge.workout && (
                    <TouchableOpacity
                      style={styles.linkedWorkout}
                      onPress={() => navigation.navigate('VideoMode', { workout: challenge.workout })}
                    >
                      <Image 
                        source={{ uri: challenge.workout.image }} 
                        style={styles.workoutThumbnail} 
                      />
                      <View style={styles.workoutInfo}>
                        <Text style={styles.workoutTitle}>{challenge.workout.title}</Text>
                        <Text style={styles.workoutMeta}>
                          {challenge.workout.type} • {challenge.workout.totalTime}
                        </Text>
                      </View>
                      <Ionicons name="chevron-forward" size={20} color="#8e8e8e" />
                    </TouchableOpacity>
                  )}
                </View>
                      <View style={styles.actionButtons}>
                        <TouchableOpacity 
                          style={[styles.actionButton, styles.acceptButton]}
                          onPress={() => handleAcceptChallenge(challenge.id)}
                        >
                          <Text style={styles.actionButtonText}>Accept Challenge</Text>
                        </TouchableOpacity>
                        <TouchableOpacity 
                          style={[styles.actionButton, styles.denyButton]}
                          onPress={() => handleDeclineChallenge(challenge.id)}
                        >
                          <Text style={styles.actionButtonText}>Decline</Text>
                        </TouchableOpacity>
                      </View>
                    </>
                  ) : (
                    <>
                      <Text style={styles.detailText}>Score: {challenge.score}</Text>
                      <Text style={styles.detailText}>Comments: {challenge.comments}</Text>
                    </>
                  )}
                </View>
              )}
            </TouchableOpacity>
          ))}
        </ScrollView>
      </SafeAreaView>
    </View>
  );
}  const styles = StyleSheet.create({
  waitingText: {
    color: '#8e8e8e',
    fontSize: 14,
    fontStyle: 'italic',
    textAlign: 'center',
    marginVertical: 8,
  },
  expiryText: {
    color: '#FF9500',
    fontSize: 12,
    textAlign: 'center',
    marginTop: 8,
  },
  pendingDetails: {
    backgroundColor: 'rgba(255, 149, 0, 0.1)',
    padding: 12,
    borderRadius: 8,
    marginTop: 8,
  },
    createChallengeButton: {
      marginHorizontal: 16,
      marginBottom: 16,
      borderRadius: 12,
      overflow: 'hidden',
      elevation: 5,
      shadowColor: '#FF9500',
      shadowOffset: { width: 0, height: 4 },
      shadowOpacity: 0.3,
      shadowRadius: 8,
    },
    gradientButton: {
      flexDirection: 'row',
      alignItems: 'center',
      justifyContent: 'center',
      paddingVertical: 16,
      gap: 8,
    },
    createChallengeText: {
      color: 'white',
      fontSize: 16,
      fontWeight: '600',
    },
    container: {
      flex: 1,
      backgroundColor: '#000000',
    },
    safeArea: {
      flex: 1,
    },
    pendingChallengeItem: {
      borderWidth: 1,
      borderColor: '#64748B',
    },
  inProgressItem: {
    borderWidth: 1,
    borderColor: '#FDB022',
  },
  progressSection: {
    marginTop: 12,
    backgroundColor: 'rgba(253, 176, 34, 0.1)',
    padding: 12,
    borderRadius: 8,
  },
  progressTitle: {
    color: '#FDB022',
    fontSize: 16,
    fontWeight: '600',
    marginBottom: 8,
  },
  progressBar: {
    height: 6,
    backgroundColor: 'rgba(255, 255, 255, 0.1)',
    borderRadius: 3,
    overflow: 'hidden',
    marginBottom: 8,
  },
  progressFill: {
    height: '100%',
    backgroundColor: '#FDB022',
    borderRadius: 3,
  },
  progressText: {
    color: 'white',
    fontSize: 14,
    marginBottom: 4,
  },
  timeRemaining: {
    color: '#FDB022',
    fontSize: 12,
    fontWeight: '500',
  },
  updateButton: {
    backgroundColor: '#FDB022',
  },
  forfeitButton: {
    backgroundColor: '#FF3B30',
  },
  completeButton: {
    backgroundColor: '#34C759',
  },  inProgressContainer: {
    backgroundColor: 'rgba(253, 176, 34, 0.1)',
    paddingHorizontal: 12,
    paddingVertical: 4,
    borderRadius: 12,
  },
  inProgressText: {
    color: '#FDB022',
    fontSize: 14,
    fontWeight: '600',
    textAlign: 'center',
  },
  timeRemainingText: {
    color: '#FDB022',
    fontSize: 12,
    textAlign: 'center',
  },
  pendingContainer: {
    backgroundColor: 'rgba(100, 116, 139, 0.1)',
    paddingHorizontal: 12,
    paddingVertical: 4,
    borderRadius: 12,
  },
  pendingText: {
    color: '#64748B',
    fontSize: 14,
    fontWeight: '600',
  },
  actionButtons: {
    flexDirection: 'row',
    gap: 8,
    marginTop: 12,
  },
  actionButton: {
    flex: 1,
    paddingVertical: 8,
    paddingHorizontal: 16,
    borderRadius: 8,
    alignItems: 'center',
  },
  acceptButton: {
    backgroundColor: '#34C759',
  },
  denyButton: {
    backgroundColor: '#FF3B30',
  },
  actionButtonText: {
    color: 'white',
    fontSize: 14,
    fontWeight: '600',
  },
  challengeList: {
    flex: 1,
    paddingHorizontal: 16,
  },
  challengeItem: {
    backgroundColor: '#1a1a1a',
    borderRadius: 12,
    padding: 16,
    marginBottom: 8,
  },
  challengeHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  challengeLeft: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 12,
  },
  challengeName: {
    color: 'white',
    fontSize: 16,
    fontWeight: '600',
  },
  outcomeText: {
    fontSize: 14,
    fontWeight: '600',
    paddingHorizontal: 12,
    paddingVertical: 4,
    borderRadius: 12,
  },
  winText: {
    color: '#34C759',
    backgroundColor: 'rgba(52, 199, 89, 0.1)',
  },
  lossText: {
    color: '#FF3B30',
    backgroundColor: 'rgba(255, 59, 48, 0.1)',
  },
  expandedContent: {
    marginTop: 12,
    paddingTop: 12,
    borderTopWidth: 1,
    borderTopColor: '#333',
    gap: 8,
  },  detailText: {
    color: '#8e8e8e',
    fontSize: 14,
    marginBottom: 12,
  },
  challengeContent: {
    gap: 12,
  },
  linkedWorkout: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#2C2C2E',
    borderRadius: 12,
    padding: 12,
    marginTop: 8,
  },
  workoutThumbnail: {
    width: 50,
    height: 50,
    borderRadius: 8,
  },
  workoutInfo: {
    flex: 1,
    marginLeft: 12,
  },
  workoutTitle: {
    color: 'white',
    fontSize: 14,
    fontWeight: '600',
  },
  workoutMeta: {
    color: '#8e8e8e',
    fontSize: 12,
    marginTop: 4,
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
  filterTabs: {
    flexDirection: 'row',
    backgroundColor: '#1a1a1a',
    margin: 16,
    borderRadius: 25,
    padding: 4,
  },
  filterTab: {
    flex: 1,
    paddingVertical: 8,
    alignItems: 'center',
    borderRadius: 21,
  },
  activeFilterTab: {
    backgroundColor: '#FF9500',
  },
  filterTabText: {
    color: '#8e8e8e',
    fontSize: 14,
    fontWeight: '600',
  },
  activeFilterTabText: {
    color: 'white',
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
  container: {
    flex: 1,
    backgroundColor: '#000000',
  },
  safeArea: {
    flex: 1,
  },
  topBar: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 16,
    paddingVertical: 12,
  },
  title: {
    fontSize: 20,
    fontWeight: 'bold',
    color: 'white',
  },
  tabContainer: {
    flexDirection: 'row',
    backgroundColor: '#1a1a1a',
    margin: 16,
    borderRadius: 25,
    padding: 4,
  },
  tab: {
    flex: 1,
    paddingVertical: 8,
    alignItems: 'center',
    borderRadius: 21,
  },
  activeTab: {
    backgroundColor: '#FF9500',
  },
  tabText: {
    color: '#8e8e8e',
    fontSize: 14,
    fontWeight: '600',
  },
  activeTabText: {
    color: 'white',
  },
  emptyContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    paddingHorizontal: 32,
  },
  emptyTitle: {
    fontSize: 24,
    fontWeight: 'bold',
    color: 'white',
    marginTop: 16,
    marginBottom: 8,
  },
  emptyText: {
    fontSize: 16,
    color: '#8e8e8e',
    textAlign: 'center',
    marginBottom: 32,
  },
  challengeButton: {
    backgroundColor: '#FF9500',
    paddingVertical: 16,
    paddingHorizontal: 32,
    borderRadius: 25,
  },
  challengeButtonText: {
    color: 'white',
    fontSize: 16,
    fontWeight: '600',
  },
});