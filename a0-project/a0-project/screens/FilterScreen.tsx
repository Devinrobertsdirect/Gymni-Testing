import React from 'react';
import { View, Text, StyleSheet, TouchableOpacity, ScrollView, Modal } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { MaterialCommunityIcons, Ionicons, FontAwesome } from '@expo/vector-icons';
import { useState } from 'react';

const durations = ['5 minutes', '10 minutes', '20 minutes', '30 minutes', '45 minutes', '1 hour'];
const muscleGroups = ['upper', 'lower', 'full', 'glutes', 'chest', 'shoulders', 'back', 'core'];
const intensityLevels = Array.from({length: 10}, (_, i) => i + 1);
const getEquipmentOptions = (sourceScreen) => {
  switch (sourceScreen) {
    case 'Strength':
      return [
        'Bodyweight',
        'Dumbbells',
        'Resistance Bands',
        'Cable Machine',
        'Full Gym',
        'Kettlebell'
      ];
    case 'HIIT':
      return [
        'Bodyweight',
        'Dumbbells',
        'Resistance Bands',
        'Cable Machine',
        'Full Gym',
        'Kettlebell'
      ];
    case 'Cardio':
      return [
        'Treadmill',
        'Rowing Machine',
        'Stationary Bike',
        'Elliptical',
        'Stair Master'
      ];
    case 'Mobility':
      return [
        'Bodyweight',
        'Yoga Blocks',
        'Foam Roller',
        'Resistance Bands',
        'Ball'
      ];
    default:
      return ['Bodyweight'];
  }
};

export default function FilterScreen({ navigation, route }) {
  const [selectedDuration, setSelectedDuration] = useState('');  const [selectedMuscleGroup, setSelectedMuscleGroup] = useState('');
  const [selectedEquipment, setSelectedEquipment] = useState('');
  const [selectedIntensities, setSelectedIntensities] = useState(new Set());
  const [isSavedOnly, setIsSavedOnly] = useState(false);  const [showDurationModal, setShowDurationModal] = useState(false);  const [showMuscleGroupModal, setShowMuscleGroupModal] = useState(false);
  const [showEquipmentModal, setShowEquipmentModal] = useState(false);  const handleApplyFilter = () => {
    const filters = {
      duration: selectedDuration,
      muscleGroup: selectedMuscleGroup,
      equipment: selectedEquipment,
      intensities: Array.from(selectedIntensities),
      savedOnly: isSavedOnly
    };
    // Navigate back to the source screen with filters
    navigation.navigate(route.params?.sourceScreen || 'HIIT', { filters });
  };  const clearFilters = () => {
    setSelectedDuration('');
    setSelectedMuscleGroup('');
    setSelectedEquipment('');
    setSelectedIntensities(new Set());
    setIsSavedOnly(false);
    // Navigate back to source screen with null filters to show all workouts
    navigation.navigate(route.params?.sourceScreen || 'HIIT', { filters: null });
  };

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
          <Text style={styles.headerTitle}>Filter</Text>
          <View style={{ width: 24 }} />
        </View>        <ScrollView style={styles.content}>
          {/* Equipment Section */}
          <View style={styles.section}>
            <View style={styles.sectionHeader}>
              <MaterialCommunityIcons name="dumbbell" size={24} color="#FF9500" />
              <Text style={styles.sectionTitle}>Equipment</Text>
            </View>
            <TouchableOpacity 
              style={styles.dropdown}
              onPress={() => setShowEquipmentModal(true)}
            >
              <Text style={[
                styles.dropdownText,
                !selectedEquipment && styles.placeholderText
              ]}>
                {selectedEquipment || 'Select Equipment'}
              </Text>
              <Ionicons name="chevron-down" size={24} color="#8E8E93" />
            </TouchableOpacity>
          </View>

          {/* Duration Section */}
          <View style={styles.section}>
            <View style={styles.sectionHeader}>
              <MaterialCommunityIcons name="clock-outline" size={24} color="#FF9500" />
              <Text style={styles.sectionTitle}>Duration</Text>
            </View>
            <TouchableOpacity 
              style={styles.dropdown}
              onPress={() => setShowDurationModal(true)}
            >
              <Text style={[
                styles.dropdownText,
                !selectedDuration && styles.placeholderText
              ]}>
                {selectedDuration || 'Select Duration'}
              </Text>
              <Ionicons name="chevron-down" size={24} color="#8E8E93" />
            </TouchableOpacity>
          </View>          {/* Muscle Group Section */}
          <View style={styles.section}>
            <View style={styles.sectionHeader}>
              <MaterialCommunityIcons name="muscle" size={24} color="#FF9500" />
              <Text style={styles.sectionTitle}>Muscle Group</Text>
            </View>
            <TouchableOpacity 
              style={styles.dropdown}
              onPress={() => setShowMuscleGroupModal(true)}
            >
              <Text style={[
                styles.dropdownText,
                !selectedMuscleGroup && styles.placeholderText
              ]}>
                {selectedMuscleGroup || 'Select Muscle Group'}
              </Text>
              <Ionicons name="chevron-down" size={24} color="#8E8E93" />
            </TouchableOpacity>
          </View>          {/* Intensity Section */}          <View style={styles.section}>
            <View style={styles.sectionHeader}>
              <MaterialCommunityIcons name="lightning-bolt" size={24} color="#FF9500" />
              <Text style={styles.sectionTitle}>Intensity</Text>
            </View>
            <View style={styles.intensityContainer}>
              {intensityLevels.map((level) => (
                <TouchableOpacity
                  key={level}
                  style={[
                    styles.intensityButton,
                    selectedIntensities.has(level) && styles.selectedIntensityButton
                  ]}
                  onPress={() => {
                    setSelectedIntensities(prev => {
                      const newSet = new Set(prev);
                      if (newSet.has(level)) {
                        newSet.delete(level);
                      } else {
                        newSet.add(level);
                      }
                      return newSet;
                    });
                  }}
                >
                  <Text style={[
                    styles.intensityButtonText,
                    selectedIntensities.has(level) && styles.selectedIntensityButtonText
                  ]}>
                    {level}
                  </Text>
                </TouchableOpacity>
              ))}
            </View>
          </View>

          {/* Saved Toggle */}
          <TouchableOpacity 
            style={styles.savedToggle}
            onPress={() => setIsSavedOnly(!isSavedOnly)}
          >
            <View style={styles.savedHeader}>
              <MaterialCommunityIcons name="bookmark-outline" size={24} color="#FF9500" />
              <Text style={styles.sectionTitle}>Saved</Text>
            </View>
            <View style={[
              styles.toggleBox,
              isSavedOnly && styles.toggleBoxActive
            ]}>
              <View style={[
                styles.toggleCircle,
                isSavedOnly && styles.toggleCircleActive
              ]} />
            </View>
          </TouchableOpacity>
        </ScrollView>        {/* Muscle Group Modal */}
        <Modal
          visible={showMuscleGroupModal}
          transparent={true}
          animationType="fade"
        >
          <View style={styles.modalOverlay}>
            <View style={styles.modalContent}>
              <Text style={styles.modalTitle}>Select Muscle Group</Text>
              {muscleGroups.map((muscleGroup) => (
                <TouchableOpacity
                  key={muscleGroup}
                  style={[
                    styles.modalOption,
                    selectedMuscleGroup === muscleGroup && styles.selectedModalOption
                  ]}
                  onPress={() => {
                    setSelectedMuscleGroup(muscleGroup);
                    setShowMuscleGroupModal(false);
                  }}
                >
                  <Text style={[
                    styles.modalOptionText,
                    selectedMuscleGroup === muscleGroup && styles.selectedModalOptionText
                  ]}>
                    {muscleGroup.charAt(0).toUpperCase() + muscleGroup.slice(1)}
                  </Text>
                </TouchableOpacity>
              ))}
              <TouchableOpacity
                style={styles.modalCancelButton}
                onPress={() => setShowMuscleGroupModal(false)}
              >
                <Text style={styles.modalCancelText}>Cancel</Text>
              </TouchableOpacity>
            </View>
          </View>
        </Modal>

        {/* Duration Modal */}
        <Modal
          visible={showDurationModal}
          transparent={true}
          animationType="fade"
        >
          <View style={styles.modalOverlay}>
            <View style={styles.modalContent}>
              <Text style={styles.modalTitle}>Select Duration</Text>
              {durations.map((duration) => (
                <TouchableOpacity
                  key={duration}
                  style={[
                    styles.modalOption,
                    selectedDuration === duration && styles.selectedModalOption
                  ]}
                  onPress={() => {
                    setSelectedDuration(duration);
                    setShowDurationModal(false);
                  }}
                >
                  <Text style={[
                    styles.modalOptionText,
                    selectedDuration === duration && styles.selectedModalOptionText
                  ]}>
                    {duration}
                  </Text>
                </TouchableOpacity>
              ))}
              <TouchableOpacity
                style={styles.modalCancelButton}
                onPress={() => setShowDurationModal(false)}
              >
                <Text style={styles.modalCancelText}>Cancel</Text>
              </TouchableOpacity>
            </View>
          </View>        </Modal>

        {/* Equipment Modal */}
        <Modal
          visible={showEquipmentModal}
          transparent={true}
          animationType="fade"
        >
          <View style={styles.modalOverlay}>
            <View style={styles.modalContent}>
              <Text style={styles.modalTitle}>Select Equipment</Text>              {getEquipmentOptions(route.params?.sourceScreen).map((equipment) => (
                <TouchableOpacity
                  key={equipment}
                  style={[
                    styles.modalOption,
                    selectedEquipment === equipment && styles.selectedModalOption
                  ]}
                  onPress={() => {
                    setSelectedEquipment(equipment);
                    setShowEquipmentModal(false);
                  }}
                >
                  <Text style={[
                    styles.modalOptionText,
                    selectedEquipment === equipment && styles.selectedModalOptionText
                  ]}>
                    {equipment}
                  </Text>
                </TouchableOpacity>
              ))}
              <TouchableOpacity
                style={styles.modalCancelButton}
                onPress={() => setShowEquipmentModal(false)}
              >
                <Text style={styles.modalCancelText}>Cancel</Text>
              </TouchableOpacity>
            </View>
          </View>
        </Modal>

        {/* Bottom Buttons */}
        <View style={styles.buttonContainer}>
          <TouchableOpacity 
            style={styles.clearButton}
            onPress={clearFilters}
          >
            <Text style={styles.clearButtonText}>Clear Filter</Text>
          </TouchableOpacity>
          <TouchableOpacity 
            style={styles.applyButton}
            onPress={handleApplyFilter}
          >
            <Text style={styles.applyButtonText}>Apply Filter</Text>
          </TouchableOpacity>
        </View>
      </SafeAreaView>
    </View>
  );
}

const styles = StyleSheet.create({
  intensityContainer: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    gap: 8,
    padding: 8,
    backgroundColor: '#1C1C1E',
    borderRadius: 12,
  },
  intensityButton: {
    width: '18%',
    aspectRatio: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#2C2C2E',
    borderRadius: 8,
  },
  selectedIntensityButton: {
    backgroundColor: 'rgba(255, 149, 0, 0.2)',
    borderWidth: 1,
    borderColor: '#FF9500',
  },
  intensityButtonText: {
    color: '#8E8E93',
    fontSize: 16,
    fontWeight: '600',
  },
  selectedIntensityButtonText: {
    color: '#FF9500',
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
  content: {
    flex: 1,
    padding: 16,
  },
  section: {
    marginBottom: 24,
  },
  sectionHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 12,
    gap: 8,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: '600',
    color: 'white',
  },
  dropdown: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: '#1C1C1E',
    borderRadius: 12,
    padding: 16,
  },
  dropdownText: {
    fontSize: 16,
    color: 'white',
  },
  placeholderText: {
    color: '#8E8E93',
  },
  savedToggle: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 24,
  },
  savedHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
  },
  toggleBox: {
    width: 51,
    height: 31,
    backgroundColor: '#1C1C1E',
    borderRadius: 16,
    padding: 2,
  },
  toggleBoxActive: {
    backgroundColor: '#FF9500',
  },
  toggleCircle: {
    width: 27,
    height: 27,
    backgroundColor: 'white',
    borderRadius: 14,
  },
  toggleCircleActive: {
    transform: [{ translateX: 20 }],
  },
  buttonContainer: {
    flexDirection: 'row',
    gap: 12,
    padding: 16,
  },
  clearButton: {
    flex: 1,
    backgroundColor: '#1C1C1E',
    borderRadius: 12,
    padding: 16,
    alignItems: 'center',
  },
  clearButtonText: {
    color: 'white',
    fontSize: 16,
    fontWeight: '600',
  },
  applyButton: {
    flex: 1,
    backgroundColor: '#FF9500',
    borderRadius: 12,
    padding: 16,
    alignItems: 'center',
  },
  applyButtonText: {
    color: 'white',
    fontSize: 16,
    fontWeight: '600',
  },
  modalOverlay: {
    flex: 1,
    backgroundColor: 'rgba(0, 0, 0, 0.7)',
    justifyContent: 'center',
    alignItems: 'center',
    padding: 24,
  },
  modalContent: {
    backgroundColor: '#1C1C1E',
    borderRadius: 16,
    padding: 16,
    width: '100%',
    maxWidth: 340,
  },
  modalTitle: {
    color: '#FF9500',
    fontSize: 20,
    fontWeight: 'bold',
    marginBottom: 16,
    textAlign: 'center',
  },
  modalOption: {
    padding: 16,
    borderRadius: 12,
    marginBottom: 8,
  },
  selectedModalOption: {
    backgroundColor: 'rgba(255, 149, 0, 0.2)',
  },
  modalOptionText: {
    color: 'white',
    fontSize: 16,
    textAlign: 'center',
  },
  selectedModalOptionText: {
    color: '#FF9500',
    fontWeight: '600',
  },
  modalCancelButton: {
    marginTop: 8,
    padding: 16,
    borderRadius: 12,
    backgroundColor: '#2C2C2E',
  },
  modalCancelText: {
    color: '#FF9500',
    fontSize: 16,
    fontWeight: '600',
    textAlign: 'center',
  },
});