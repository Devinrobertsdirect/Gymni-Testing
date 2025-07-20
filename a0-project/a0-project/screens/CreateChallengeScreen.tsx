import React from 'react';
import { View, Text, StyleSheet, TouchableOpacity, TextInput, ScrollView, Image } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { LinearGradient } from 'expo-linear-gradient';
import { useState } from 'react';
import { Ionicons, MaterialCommunityIcons, FontAwesome5 } from '@expo/vector-icons';
import { toast } from 'sonner-native';

// Mock friends data
const mockFriends = [
  { id: 1, name: 'Sarah Johnson', avatar: 'https://api.a0.dev/assets/image?text=sarah%20profile&aspect=1:1&seed=1' },
  { id: 2, name: 'Mike Williams', avatar: 'https://api.a0.dev/assets/image?text=mike%20profile&aspect=1:1&seed=2' },
  { id: 3, name: 'Emma Davis', avatar: 'https://api.a0.dev/assets/image?text=emma%20profile&aspect=1:1&seed=3' },
  { id: 4, name: 'David Chen', avatar: 'https://api.a0.dev/assets/image?text=david%20profile&aspect=1:1&seed=4' },
];

const challengeTypes = [
  { id: 'hiit', name: 'HIIT', icon: (color) => <MaterialCommunityIcons name="lightning-bolt" size={24} color={color} /> },
  { id: 'strength', name: 'Strength', icon: (color) => <FontAwesome5 name="dumbbell" size={24} color={color} /> },
  { id: 'cardio', name: 'Cardio', icon: (color) => <MaterialCommunityIcons name="run" size={24} color={color} /> },
  { id: 'mobility', name: 'Mobility', icon: (color) => <MaterialCommunityIcons name="yoga" size={24} color={color} /> },
];

export default function CreateChallengeScreen({ navigation, route }) {
  const [selectedType, setSelectedType] = useState(null);  const [customType, setCustomType] = useState('');
  const workoutChallenge = route.params?.workoutChallenge;

  // Set initial values if workout challenge
  React.useEffect(() => {
    if (workoutChallenge) {
      setSelectedType(workoutChallenge.type);
      setCustomType('');
      setName(workoutChallenge.workout.title + ' Challenge');
      setDescription(workoutChallenge.defaultDescription);
    }
  }, [workoutChallenge]);
  const [selectedFriend, setSelectedFriend] = useState(null);
  const [searchQuery, setSearchQuery] = useState('');  const [name, setName] = useState('');  const [description, setDescription] = useState('');
  const [duration, setDuration] = useState(24); // Default 24 hours


  const filteredFriends = mockFriends.filter(friend => 
    friend.name.toLowerCase().includes(searchQuery.toLowerCase())
  );

  const handleCreateChallenge = () => {    if (!name) {
      toast.error('Please enter a challenge title');
      return;
    }

    if (!selectedType && !customType) {
      toast.error('Please select or enter a challenge type');
      return;
    }

    if (!selectedFriend) {
      toast.error('Please select a friend to challenge');
      return;
    }    if (!description) {
      toast.error('Please enter a challenge description');
      return;
    }

    if (duration < 24) {
      toast.error('Challenge duration must be at least 24 hours');
      return;
    }

    if (duration > 744) {
      toast.error('Challenge duration cannot exceed 31 days');
      return;
    }
    // Create the challenge with unique ID
    const newChallenge = {
      id: Date.now(), // Create unique ID using timestamp
      type: customType || selectedType,      name: name,
      status: 'pending',
      challenger: selectedFriend.name,
      challenged: 'You',
      date: new Date().toISOString().split('T')[0],
      description: description,      expiresIn: `${duration}h`

    };

    // Update challenges in the previous screen
    navigation.navigate('Challenges', { newChallenge });
    
    // Show success message
    toast.success('Challenge sent! 🎯', {
      description: `${selectedFriend.name} will be notified`
    });
  };

  return (
    <LinearGradient
      colors={['#1a1a1a', '#000000']}
      style={styles.container}
    >
      <SafeAreaView style={styles.safeArea}>
        <View style={styles.header}>
          <TouchableOpacity 
            style={styles.backButton}
            onPress={() => navigation.goBack()}
          >
            <Ionicons name="chevron-back" size={24} color="white" />
            <Text style={styles.backText}>Back</Text>
          </TouchableOpacity>
          <Text style={styles.title}>Create Challenge</Text>
        </View>

        <ScrollView style={styles.content} showsVerticalScrollIndicator={false}>
          {/* Challenge Type Selection */}
          <View style={styles.section}>
            <Text style={styles.sectionTitle}>Challenge Type</Text>
            <View style={styles.typeGrid}>
              {challengeTypes.map(type => (
                <TouchableOpacity
                  key={type.id}
                  style={[
                    styles.typeButton,
                    selectedType === type.id && styles.selectedTypeButton
                  ]}
                  onPress={() => {
                    setSelectedType(type.id);
                    setCustomType('');
                  }}
                >
                  {type.icon(selectedType === type.id ? '#FF9500' : '#8e8e8e')}
                  <Text style={[
                    styles.typeText,
                    selectedType === type.id && styles.selectedTypeText
                  ]}>
                    {type.name}
                  </Text>
                </TouchableOpacity>
              ))}
            </View>

            <Text style={styles.orText}>- OR -</Text>

            <TextInput
              style={styles.customTypeInput}
              placeholder="Enter custom challenge type"
              placeholderTextColor="#8e8e8e"
              value={customType}
              onChangeText={(text) => {
                setCustomType(text);
                setSelectedType(null);
              }}
            />          </View>          {/* Challenge Title */}
          <View style={styles.section}>
            <Text style={styles.sectionTitle}>Challenge Title</Text>
            <View>
              <TextInput
                style={[
                  styles.customTypeInput,
                  name.length >= 50 && styles.inputWarning
                ]}
                placeholder="e.g. '30 Day Plank Challenge'"
                placeholderTextColor="#8e8e8e"
                value={name}
                onChangeText={(text) => {
                  if (text.length <= 50) {
                    setName(text);
                  }
                }}
                maxLength={50}
              />
              <Text style={styles.characterCount}>
                {name.length}/50 characters
              </Text>
              <Text style={styles.placeholderSuggestions}>
                Suggestions: "100 Push-up Challenge", "5K Run Challenge", "Flexibility Journey"
              </Text>
            </View>
          </View>

          {/* Challenge Duration */}
          <View style={styles.section}>
            <Text style={styles.sectionTitle}>Challenge Duration</Text>
            <View style={styles.durationContainer}>
              <View style={styles.presetButtons}>
                <TouchableOpacity 
                  style={[styles.presetButton, duration === 24 && styles.selectedPreset]}
                  onPress={() => setDuration(24)}
                >
                  <Text style={[styles.presetText, duration === 24 && styles.selectedPresetText]}>24h</Text>
                </TouchableOpacity>
                <TouchableOpacity 
                  style={[styles.presetButton, duration === 48 && styles.selectedPreset]}
                  onPress={() => setDuration(48)}
                >
                  <Text style={[styles.presetText, duration === 48 && styles.selectedPresetText]}>48h</Text>
                </TouchableOpacity>
                <TouchableOpacity 
                  style={[styles.presetButton, duration === 168 && styles.selectedPreset]}
                  onPress={() => setDuration(168)}
                >
                  <Text style={[styles.presetText, duration === 168 && styles.selectedPresetText]}>1 Week</Text>
                </TouchableOpacity>
                <TouchableOpacity 
                  style={[styles.presetButton, duration === 744 && styles.selectedPreset]}
                  onPress={() => setDuration(744)}
                >
                  <Text style={[styles.presetText, duration === 744 && styles.selectedPresetText]}>31 Days</Text>
                </TouchableOpacity>
              </View>
              
              <View style={styles.customDurationContainer}>
                <TextInput
                  style={styles.durationInput}
                  keyboardType="numeric"
                  value={String(duration)}
                  onChangeText={(text) => {
                    const hours = parseInt(text) || 24;
                    if (hours >= 24 && hours <= 744) {
                      setDuration(hours);
                    }
                  }}
                  maxLength={3}
                />
                <Text style={styles.durationLabel}>hours</Text>
              </View>
              
              <Text style={styles.durationHelp}>
                {duration < 24 ? 'Minimum duration is 24 hours' :
                 duration > 744 ? 'Maximum duration is 31 days (744 hours)' :
                 `Challenge will expire in ${duration >= 72 ? 
                    `${Math.floor(duration/24)} days` : 
                    `${duration} hours`}`}
              </Text>
            </View>          </View>

          {/* Friend Selection */}
          <View style={styles.section}>
            <Text style={styles.sectionTitle}>Select Friend</Text>
            <TextInput
              style={styles.searchInput}
              placeholder="Search friends..."
              placeholderTextColor="#8e8e8e"
              value={searchQuery}
              onChangeText={setSearchQuery}
            />

            <ScrollView 
              horizontal 
              showsHorizontalScrollIndicator={false}
              style={styles.friendsScroll}
            >
              {filteredFriends.map(friend => (
                <TouchableOpacity
                  key={friend.id}
                  style={[
                    styles.friendCard,
                    selectedFriend?.id === friend.id && styles.selectedFriendCard
                  ]}
                  onPress={() => setSelectedFriend(friend)}
                >
                  <Image 
                    source={{ uri: friend.avatar }}
                    style={styles.friendAvatar}
                  />
                  <Text style={styles.friendName}>{friend.name}</Text>
                </TouchableOpacity>
              ))}
            </ScrollView>
          </View>

          {/* Challenge Description */}
          <View style={styles.section}>
            <Text style={styles.sectionTitle}>Challenge Description</Text>
            <TextInput
              style={styles.descriptionInput}
              placeholder="Describe your challenge..."
              placeholderTextColor="#8e8e8e"
              multiline
              numberOfLines={4}
              value={description}
              onChangeText={setDescription}
            />
          </View>
        </ScrollView>

        {/* Create Button */}
        <TouchableOpacity 
          style={[
            styles.createButton,
            (!selectedType && !customType) || !selectedFriend || !description 
              ? styles.createButtonDisabled 
              : null
          ]}
          onPress={handleCreateChallenge}
          disabled={(!selectedType && !customType) || !selectedFriend || !description}
        >
          <Text style={styles.createButtonText}>Send Challenge</Text>
        </TouchableOpacity>
      </SafeAreaView>
    </LinearGradient>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
  safeArea: {
    flex: 1,
  },
  header: {
    flexDirection: 'row',
    alignItems: 'center',
    padding: 16,
    borderBottomWidth: 1,
    borderBottomColor: '#333',
  },
  backButton: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  backText: {
    color: 'white',
    fontSize: 16,
    marginLeft: 8,
  },
  title: {
    color: 'white',
    fontSize: 20,
    fontWeight: 'bold',
    marginLeft: 16,
  },
  content: {
    flex: 1,
    padding: 16,
  },
  section: {
    marginBottom: 24,
  },
  sectionTitle: {
    color: '#FF9500',
    fontSize: 18,
    fontWeight: '600',
    marginBottom: 16,
  },
  typeGrid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    gap: 12,
    marginBottom: 16,
  },
  typeButton: {
    flex: 1,
    minWidth: '45%',
    backgroundColor: '#333',
    padding: 16,
    borderRadius: 12,
    alignItems: 'center',
    gap: 8,
  },
  selectedTypeButton: {
    backgroundColor: 'rgba(255, 149, 0, 0.2)',
    borderWidth: 1,
    borderColor: '#FF9500',
  },
  typeText: {
    color: '#8e8e8e',
    fontSize: 14,
    fontWeight: '600',
  },
  selectedTypeText: {
    color: '#FF9500',
  },
  orText: {
    color: '#8e8e8e',
    textAlign: 'center',
    marginVertical: 16,
  },
  customTypeInput: {
    backgroundColor: '#333',
    padding: 16,
    borderRadius: 12,
    color: 'white',
    fontSize: 16,
  },
  searchInput: {
    backgroundColor: '#333',
    padding: 16,
    borderRadius: 12,
    color: 'white',
    fontSize: 16,
    marginBottom: 16,
  },
  friendsScroll: {
    flexGrow: 0,
  },
  friendCard: {
    backgroundColor: '#333',
    padding: 16,
    borderRadius: 12,
    marginRight: 12,
    alignItems: 'center',
    width: 100,
  },
  selectedFriendCard: {
    backgroundColor: 'rgba(255, 149, 0, 0.2)',
    borderWidth: 1,
    borderColor: '#FF9500',
  },
  friendAvatar: {
    width: 60,
    height: 60,
    borderRadius: 30,
    marginBottom: 8,
  },
  friendName: {
    color: 'white',
    fontSize: 14,
    textAlign: 'center',
  },
  descriptionInput: {
    backgroundColor: '#333',
    padding: 16,
    borderRadius: 12,
    color: 'white',
    fontSize: 16,
    height: 120,
    textAlignVertical: 'top',
  },
  createButton: {
    backgroundColor: '#FF9500',
    margin: 16,
    padding: 16,
    borderRadius: 12,
    alignItems: 'center',
  },
  createButtonDisabled: {
    opacity: 0.5,
  },  createButtonText: {
    color: 'white',
    fontSize: 16,
    fontWeight: '600',
  },
  characterCount: {
    color: '#8e8e8e',
    fontSize: 12,
    textAlign: 'right',
    marginTop: 4,
  },
  placeholderSuggestions: {
    color: '#8e8e8e',
    fontSize: 12,
    marginTop: 8,
    fontStyle: 'italic',  },
  durationContainer: {
    backgroundColor: '#333',
    padding: 16,
    borderRadius: 12,
  },
  presetButtons: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 16,
  },
  presetButton: {
    paddingVertical: 8,
    paddingHorizontal: 16,
    borderRadius: 8,
    backgroundColor: '#1a1a1a',
  },
  selectedPreset: {
    backgroundColor: 'rgba(255, 149, 0, 0.2)',
    borderWidth: 1,
    borderColor: '#FF9500',
  },
  presetText: {
    color: '#8e8e8e',
    fontSize: 14,
    fontWeight: '600',
  },
  selectedPresetText: {
    color: '#FF9500',
  },
  customDurationContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 8,
  },
  durationInput: {
    backgroundColor: '#1a1a1a',
    padding: 12,
    borderRadius: 8,
    color: 'white',
    fontSize: 16,
    width: 80,
    textAlign: 'center',
    marginRight: 8,
  },
  durationLabel: {
    color: '#8e8e8e',
    fontSize: 16,
  },
  durationHelp: {
    color: '#8e8e8e',
    fontSize: 12,
    fontStyle: 'italic',
  },
  inputWarning: {
    borderWidth: 1,
    borderColor: '#FF9500',
  },


});