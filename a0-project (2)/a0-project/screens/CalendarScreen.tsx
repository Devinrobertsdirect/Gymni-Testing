import React, { useState } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, ScrollView, TextInput, Modal } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';
import { toast } from 'sonner-native';
import type { StackNavigationProp } from '@react-navigation/stack';
import type { RouteProp } from '@react-navigation/native';
import HamburgerMenuButton from './HamburgerMenuButton';

type RootStackParamList = {
  Calendar: undefined;
  VideoMode: { workout: any };
};

type CalendarScreenNavigationProp = StackNavigationProp<RootStackParamList, 'Calendar'>;

interface CalendarScreenProps {
  navigation: CalendarScreenNavigationProp;
}

const mockCompletedWorkouts = [
  {
    id: 1,
    title: "Full Body Strength",
    date: "2025-01-15",
    weights: "Bench Press: 135lbs, Squats: 185lbs, Deadlift: 225lbs"
  },
  {
    id: 2,
    title: "Upper Body Focus",
    date: "2025-02-01",
    weights: "Shoulder Press: 95lbs, Rows: 135lbs, Pull-ups: BW+25lbs"
  },
  {
    id: 3,
    title: "Leg Day",
    date: "2025-02-03",
    weights: "Front Squat: 155lbs, RDL: 185lbs, Lunges: 35lbs DBs"
  }
];

const mockScheduledWorkouts = [
  {
    id: 1,
    title: "HIIT Cardio",
    date: "2025-02-05",
    time: "07:00",
    type: "cardio",
    duration: "45 min"
  },
  {
    id: 2,
    title: "Full Body Strength",
    date: "2025-02-07",
    time: "18:30",
    type: "strength",
    duration: "60 min"
  }
];

export default function CalendarScreen({ navigation }: CalendarScreenProps) {
  const [selectedDate, setSelectedDate] = useState(new Date());
  const [view, setView] = useState('calendar');
  const [showScheduleModal, setShowScheduleModal] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const [filteredWorkouts, setFilteredWorkouts] = useState(mockCompletedWorkouts);
  const [newWorkout, setNewWorkout] = useState({
    title: '',
    date: new Date().toISOString().split('T')[0],
    time: '09:00',
    type: 'strength',
    duration: '60'
  });

  const monthNames = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
  ];

  const workoutTypes = [
    { id: 'strength', name: 'Strength', icon: 'barbell' },
    { id: 'cardio', name: 'Cardio', icon: 'bicycle-outline' },
    { id: 'hiit', name: 'HIIT', icon: 'flash' },
    { id: 'mobility', name: 'Mobility', icon: 'body-outline' }
  ];

  const getDaysInMonth = (date: Date): (Date | null)[] => {
    const year = date.getFullYear();
    const month = date.getMonth();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const firstDay = new Date(year, month, 1).getDay();
    
    const days = [];
    for (let i = 0; i < firstDay; i++) {
      days.push(null);
    }
    
    for (let i = 1; i <= daysInMonth; i++) {
      days.push(new Date(year, month, i));
    }
    
    return days;
  };

  const hasWorkout = (date: Date | null): boolean => {
    if (!date) return false;
    const dateStr = date.toISOString().split('T')[0];
    return (
      mockCompletedWorkouts.some(workout => workout.date === dateStr) ||
      mockScheduledWorkouts.some(workout => workout.date === dateStr)
    );
  };

  const isScheduledWorkout = (date: Date | null): boolean => {
    if (!date) return false;
    const dateStr = date.toISOString().split('T')[0];
    return mockScheduledWorkouts.some(workout => workout.date === dateStr);
  };

  const isToday = (date: Date | null): boolean => {
    if (!date) return false;
    const today = new Date();
    return (
      date.getDate() === today.getDate() &&
      date.getMonth() === today.getMonth() &&
      date.getFullYear() === today.getFullYear()
    );
  };

  const handleSearch = (text: string) => {
    setSearchQuery(text);
    const filtered = mockCompletedWorkouts.filter(workout => {
      const workoutDate = new Date(workout.date);
      const monthName = monthNames[workoutDate.getMonth()].toLowerCase();
      return workout.title.toLowerCase().includes(text.toLowerCase()) || 
             monthName.includes(text.toLowerCase());
    });
    setFilteredWorkouts(filtered);
  };

  const handleScheduleWorkout = () => {
    if (!newWorkout.title) {
      toast.error('Please enter a workout title');
      return;
    }

    // Here you would typically make an API call to save the workout
    mockScheduledWorkouts.push({
      id: Date.now(),
      ...newWorkout
    });

    toast.success('Workout scheduled successfully!');
    setShowScheduleModal(false);
    setNewWorkout({
      title: '',
      date: new Date().toISOString().split('T')[0],
      time: '09:00',
      type: 'strength',
      duration: '60'
    });
  };

  const getWorkoutsForDate = (date: Date | null): any[] => {
    if (!date) return [];
    const dateStr = date.toISOString().split('T')[0];
    return mockScheduledWorkouts.filter(workout => workout.date === dateStr);
  };

  return (
    <View style={styles.container}>
      <SafeAreaView style={styles.safeArea}>
        {/* Header */}
        <View style={styles.header}>
          <HamburgerMenuButton navigation={navigation} />
          <Text style={styles.headerTitle}>Calendar</Text>
          <TouchableOpacity onPress={() => setShowScheduleModal(true)}>
            <Ionicons name="add" size={24} color="#FF9500" />
          </TouchableOpacity>
        </View>

        {/* View Toggle */}
        <View style={styles.viewToggle}>
          <TouchableOpacity 
            style={[styles.toggleButton, view === 'calendar' && styles.activeToggleButton]}
            onPress={() => setView('calendar')}
          >
            <Ionicons 
              name="calendar" 
              size={20} 
              color={view === 'calendar' ? '#FF9500' : '#8e8e8e'} 
            />
            <Text style={[styles.toggleText, view === 'calendar' && styles.activeToggleText]}>
              Calendar
            </Text>
          </TouchableOpacity>
          <TouchableOpacity 
            style={[styles.toggleButton, view === 'list' && styles.activeToggleButton]}
            onPress={() => setView('list')}
          >
            <Ionicons 
              name="list" 
              size={20} 
              color={view === 'list' ? '#FF9500' : '#8e8e8e'} 
            />
            <Text style={[styles.toggleText, view === 'list' && styles.activeToggleText]}>
              List
            </Text>
          </TouchableOpacity>
        </View>

        {view === 'calendar' ? (
          <ScrollView style={styles.content}>
            {/* Month Navigation */}
            <View style={styles.monthNavigation}>
              <TouchableOpacity
                onPress={() => {
                  const newDate = new Date(selectedDate);
                  newDate.setMonth(newDate.getMonth() - 1);
                  setSelectedDate(newDate);
                }}
              >
                <Ionicons name="chevron-back" size={24} color="white" />
              </TouchableOpacity>
              <Text style={styles.monthYear}>
                {monthNames[selectedDate.getMonth()]} {selectedDate.getFullYear()}
              </Text>
              <TouchableOpacity
                onPress={() => {
                  const newDate = new Date(selectedDate);
                  newDate.setMonth(newDate.getMonth() + 1);
                  setSelectedDate(newDate);
                }}
              >
                <Ionicons name="chevron-forward" size={24} color="white" />
              </TouchableOpacity>
            </View>

            {/* Calendar Grid */}
            <View style={styles.calendarGrid}>
              {/* Weekday Headers */}
              {['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].map((day) => (
                <Text key={day} style={styles.weekdayHeader}>{day}</Text>
              ))}
              
              {/* Calendar Days */}
              {getDaysInMonth(selectedDate).map((date, index) => {
                const workouts = date ? getWorkoutsForDate(date) : [];
                return (
                  <TouchableOpacity
                    key={index}
                    style={[
                      styles.dayCell,
                      isToday(date) && styles.todayCell,
                      hasWorkout(date) && styles.workoutCell,
                      isScheduledWorkout(date) && styles.scheduledWorkoutCell
                    ]}
                    disabled={!date}
                    onPress={() => {
                      if (date) {
                        setSelectedDate(date);
                        if (workouts.length > 0) {
                          toast.success(
                            `Scheduled Workouts for ${date.toLocaleDateString()}:`,
                            {
                              description: workouts.map(w => 
                                `${w.title} at ${w.time}`
                              ).join('\n')
                            }
                          );
                        }
                      }
                    }}
                  >
                    {date && (
                      <>
                        <Text style={[
                          styles.dayNumber,
                          isToday(date) && styles.todayNumber,
                          hasWorkout(date) && styles.workoutNumber
                        ]}>
                          {date.getDate()}
                        </Text>
                        {workouts.length > 0 && (
                          <View style={styles.workoutIndicatorsContainer}>
                            {workouts.map((_, i) => (
                              <View 
                                key={i} 
                                style={[
                                  styles.workoutIndicator,
                                  { backgroundColor: '#FF9500' }
                                ]} 
                              />
                            ))}
                          </View>
                        )}
                      </>
                    )}
                  </TouchableOpacity>
                );
              })}
            </View>

            {/* Schedule Summary */}
            {getWorkoutsForDate(selectedDate).length > 0 && (
              <View style={styles.scheduleSummary}>
                <Text style={styles.summaryTitle}>
                  Scheduled for {selectedDate.toLocaleDateString()}:
                </Text>
                {getWorkoutsForDate(selectedDate).map((workout) => (
                  <TouchableOpacity
                    key={workout.id}
                    style={styles.summaryWorkout}
                    onPress={() => navigation.navigate('VideoMode', { workout })}
                  >
                    <View style={styles.summaryWorkoutHeader}>
                      <Text style={styles.summaryWorkoutTitle}>{workout.title}</Text>
                      <TouchableOpacity 
                        onPress={() => {
                          // Handle delete workout
                          toast.success('Workout deleted');
                          const index = mockScheduledWorkouts.findIndex(w => w.id === workout.id);
                          if (index !== -1) {
                            mockScheduledWorkouts.splice(index, 1);
                          }
                        }}
                      >
                        <Ionicons name="trash-outline" size={20} color="#FF3B30" />
                      </TouchableOpacity>
                    </View>
                    <Text style={styles.summaryWorkoutTime}>{workout.time}</Text>
                    <Text style={styles.summaryWorkoutDuration}>{workout.duration} minutes</Text>
                  </TouchableOpacity>
                ))}
              </View>
            )}
          </ScrollView>
        ) : (
          <ScrollView style={styles.listView}>
            {/* Search Bar */}
            <View style={styles.searchContainer}>
              <View style={styles.searchBar}>
                <Ionicons name="search" size={20} color="#8E8E93" />
                <TextInput
                  style={styles.searchInput}
                  placeholder="Search by title or month"
                  placeholderTextColor="#8e8e8e"
                  value={searchQuery}
                  onChangeText={handleSearch}
                />
              </View>
            </View>

            {/* Completed Workouts List */}
            <View style={styles.completedWorkouts}>
              <Text style={styles.sectionTitle}>Completed Workouts</Text>
              {filteredWorkouts.map(workout => (
                <View key={workout.id} style={styles.workoutCard}>
                  <Text style={styles.workoutDate}>
                    {new Date(workout.date).toLocaleDateString()}
                  </Text>
                  <Text style={styles.workoutTitle}>{workout.title}</Text>
                  <Text style={styles.workoutWeights}>{workout.weights}</Text>
                </View>
              ))}
            </View>
          </ScrollView>
        )}

        {/* Schedule Workout Modal */}
        <Modal
          visible={showScheduleModal}
          transparent={true}
          animationType="slide"
        >
          <View style={styles.modalOverlay}>
            <View style={styles.modalContent}>
              <View style={styles.modalHeader}>
                <Text style={styles.modalTitle}>Schedule Workout</Text>
                <TouchableOpacity 
                  style={styles.modalCloseButton}
                  onPress={() => setShowScheduleModal(false)}
                >
                  <Ionicons name="close" size={24} color="white" />
                </TouchableOpacity>
              </View>

              <ScrollView style={styles.modalScrollView}>
                <View style={styles.inputGroup}>
                  <Text style={styles.inputLabel}>Workout Title</Text>
                  <TextInput
                    style={styles.input}
                    placeholder="Enter workout title"
                    placeholderTextColor="#8e8e8e"
                    value={newWorkout.title}
                    onChangeText={(text) => setNewWorkout({...newWorkout, title: text})}
                  />
                </View>

                <View style={styles.inputGroup}>
                  <Text style={styles.inputLabel}>Workout Type</Text>
                  <View style={styles.typeButtons}>
                    {workoutTypes.map((type) => (
                      <TouchableOpacity
                        key={type.id}
                        style={[
                          styles.typeButton,
                          newWorkout.type === type.id && styles.activeTypeButton
                        ]}
                        onPress={() => setNewWorkout({...newWorkout, type: type.id})}
                      >
                        <Ionicons 
                          name={type.icon} 
                          size={24} 
                          color={newWorkout.type === type.id ? '#FF9500' : '#8e8e8e'} 
                        />
                        <Text style={[
                          styles.typeButtonText,
                          newWorkout.type === type.id && styles.activeTypeButtonText
                        ]}>
                          {type.name}
                        </Text>
                      </TouchableOpacity>
                    ))}
                  </View>
                </View>

                <View style={styles.inputGroup}>
                  <Text style={styles.inputLabel}>Date</Text>
                  <TextInput
                    style={styles.input}
                    placeholder="YYYY-MM-DD"
                    placeholderTextColor="#8e8e8e"
                    value={newWorkout.date}
                    onChangeText={(text) => setNewWorkout({...newWorkout, date: text})}
                  />
                </View>

                <View style={styles.inputGroup}>
                  <Text style={styles.inputLabel}>Time</Text>
                  <TextInput
                    style={styles.input}
                    placeholder="HH:MM"
                    placeholderTextColor="#8e8e8e"
                    value={newWorkout.time}
                    onChangeText={(text) => setNewWorkout({...newWorkout, time: text})}
                  />
                </View>

                <View style={styles.inputGroup}>
                  <Text style={styles.inputLabel}>Duration (minutes)</Text>
                  <TextInput
                    style={styles.input}
                    placeholder="Enter duration"
                    placeholderTextColor="#8e8e8e"
                    keyboardType="numeric"
                    value={newWorkout.duration}
                    onChangeText={(text) => setNewWorkout({...newWorkout, duration: text})}
                  />
                </View>
              </ScrollView>

              <TouchableOpacity
                style={styles.scheduleButton}
                onPress={handleScheduleWorkout}
              >
                <Text style={styles.scheduleButtonText}>Schedule Workout</Text>
              </TouchableOpacity>
            </View>
          </View>
        </Modal>
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
  viewToggle: {
    flexDirection: 'row',
    backgroundColor: '#1C1C1E',
    margin: 16,
    borderRadius: 12,
    padding: 4,
  },
  toggleButton: {
    flex: 1,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    paddingVertical: 8,
    gap: 8,
    borderRadius: 8,
  },
  activeToggleButton: {
    backgroundColor: 'rgba(255, 149, 0, 0.1)',
  },
  toggleText: {
    color: '#8e8e8e',
    fontSize: 16,
  },
  activeToggleText: {
    color: '#FF9500',
  },
  content: {
    flex: 1,
  },
  monthNavigation: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 16,
    paddingVertical: 12,
  },
  monthYear: {
    color: 'white',
    fontSize: 18,
    fontWeight: '600',
  },
  calendarGrid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    padding: 16,
  },
  weekdayHeader: {
    width: '14.28%',
    textAlign: 'center',
    color: '#8e8e8e',
    fontSize: 14,
    marginBottom: 8,
  },
  dayCell: {
    width: '14.28%',
    aspectRatio: 1,
    alignItems: 'center',
    justifyContent: 'center',
    position: 'relative',
  },
  todayCell: {
    backgroundColor: 'rgba(255, 149, 0, 0.1)',
    borderRadius: 8,
  },
  workoutCell: {
    backgroundColor: 'rgba(52, 199, 89, 0.1)',
    borderRadius: 8,
  },
  scheduledWorkoutCell: {
    backgroundColor: 'rgba(255, 149, 0, 0.1)',
    borderRadius: 8,
  },
  dayNumber: {
    color: 'white',
    fontSize: 16,
  },
  todayNumber: {
    color: '#FF9500',
    fontWeight: 'bold',
  },
  workoutNumber: {
    color: '#34C759',
  },
  workoutIndicatorsContainer: {
    position: 'absolute',
    bottom: 4,
    flexDirection: 'row',
    gap: 2,
  },
  workoutIndicator: {
    width: 4,
    height: 4,
    borderRadius: 2,
  },
  scheduleSummary: {
    margin: 16,
    backgroundColor: '#1C1C1E',
    borderRadius: 12,
    padding: 16,
  },
  summaryTitle: {
    color: '#FF9500',
    fontSize: 16,
    fontWeight: '600',
    marginBottom: 12,
  },
  summaryWorkout: {
    backgroundColor: 'rgba(255, 149, 0, 0.1)',
    borderRadius: 8,
    padding: 12,
    marginBottom: 8,
  },
  summaryWorkoutHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 4,
  },
  summaryWorkoutTitle: {
    color: 'white',
    fontSize: 16,
    fontWeight: '600',
  },
  summaryWorkoutTime: {
    color: '#FF9500',
    fontSize: 14,
  },
  summaryWorkoutDuration: {
    color: '#8e8e8e',
    fontSize: 14,
  },
  listView: {
    flex: 1,
    padding: 16,
  },
  searchContainer: {
    marginBottom: 16,
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
  completedWorkouts: {
    gap: 12,
  },
  sectionTitle: {
    color: '#FF9500',
    fontSize: 18,
    fontWeight: '600',
    marginBottom: 16,
  },
  workoutCard: {
    backgroundColor: '#1C1C1E',
    borderRadius: 12,
    padding: 16,
    marginBottom: 12,
  },
  workoutDate: {
    color: '#8e8e8e',
    fontSize: 14,
    marginBottom: 8,
  },
  workoutTitle: {
    color: 'white',
    fontSize: 18,
    fontWeight: '600',
    marginBottom: 8,
  },
  workoutWeights: {
    color: '#8e8e8e',
    fontSize: 14,
    lineHeight: 20,
  },
  modalOverlay: {
    flex: 1,
    backgroundColor: 'rgba(0, 0, 0, 0.7)',
    justifyContent: 'flex-end',
  },
  modalContent: {
    backgroundColor: '#1C1C1E',
    borderTopLeftRadius: 20,
    borderTopRightRadius: 20,
    maxHeight: '80%',
  },
  modalHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    padding: 16,
    borderBottomWidth: 1,
    borderBottomColor: '#333',
  },
  modalTitle: {
    color: 'white',
    fontSize: 20,
    fontWeight: 'bold',
  },
  modalCloseButton: {
    padding: 4,
  },
  modalScrollView: {
    padding: 16,
  },
  inputGroup: {
    marginBottom: 20,
  },
  inputLabel: {
    color: '#FF9500',
    fontSize: 14,
    marginBottom: 8,
  },
  input: {
    backgroundColor: '#2C2C2E',
    borderRadius: 8,
    padding: 12,
    color: 'white',
    fontSize: 16,
  },
  typeButtons: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    gap: 8,
  },
  typeButton: {
    flex: 1,
    minWidth: '45%',
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
    backgroundColor: '#2C2C2E',
    padding: 12,
    borderRadius: 8,
  },
  activeTypeButton: {
    backgroundColor: 'rgba(255, 149, 0, 0.1)',
    borderWidth: 1,
    borderColor: '#FF9500',
  },
  typeButtonText: {
    color: '#8e8e8e',
    fontSize: 14,
  },
  activeTypeButtonText: {
    color: '#FF9500',
  },
  scheduleButton: {
    backgroundColor: '#FF9500',
    margin: 16,
    padding: 16,
    borderRadius: 12,
    alignItems: 'center',
  },
  scheduleButtonText: {
    color: 'white',
    fontSize: 16,
    fontWeight: '600',
  },
});