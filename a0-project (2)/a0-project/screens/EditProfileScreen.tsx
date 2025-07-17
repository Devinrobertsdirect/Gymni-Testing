import React, { useState } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, Image, TextInput, ScrollView } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { LinearGradient } from 'expo-linear-gradient';
import { Ionicons } from '@expo/vector-icons';
import { toast } from 'sonner-native';
import HamburgerMenuButton from './HamburgerMenuButton';
import type { StackNavigationProp } from '@react-navigation/stack';

type RootStackParamList = {
  EditProfile: undefined;
};

type EditProfileScreenNavigationProp = StackNavigationProp<RootStackParamList, 'EditProfile'>;

interface EditProfileScreenProps {
  navigation: EditProfileScreenNavigationProp;
}

export default function EditProfileScreen({ navigation }: EditProfileScreenProps) {
  const [photoUri, setPhotoUri] = useState('https://api.a0.dev/assets/image?text=user%20profile&aspect=1:1');
  const [formData, setFormData] = useState({
    fullName: 'Morgan Furbay',
    username: 'morgan_furbay',
    email: 'morgan@example.com',
    bio: 'Fitness enthusiast | Personal Trainer | Nutrition Coach',
    location: 'New York, NY',
    instagram: 'morgan.furbay',
    twitter: 'morganfurbay'
  });

  const handleSave = () => {
    // Here you would typically make an API call to update the profile
    toast.success('Profile updated successfully!');
    navigation.goBack();
  };

  return (
    <LinearGradient
      colors={['#1a1a1a', '#000000']}
      style={styles.container}
    >
      <SafeAreaView style={styles.safeArea}>
        {/* Header */}
        <View style={styles.header}>
          <HamburgerMenuButton navigation={navigation} />
          <Text style={styles.headerTitle}>Edit Profile</Text>
          <TouchableOpacity 
            style={styles.saveButton}
            onPress={handleSave}
          >
            <Text style={styles.saveButtonText}>Save</Text>
          </TouchableOpacity>
        </View>

        <ScrollView style={styles.content}>
          {/* Profile Photo */}
          <View style={styles.photoSection}>
            <Image 
              source={{ uri: photoUri }}
              style={styles.profilePhoto}
            />
            <TouchableOpacity 
              style={styles.changePhotoButton}
              onPress={() => {
                toast.message('Coming soon!', {
                  description: 'Photo upload will be available in the next update'
                });
              }}
            >
              <Text style={styles.changePhotoText}>Change Profile Photo</Text>
            </TouchableOpacity>
          </View>

          {/* Form Fields */}
          <View style={styles.form}>
            <View style={styles.inputGroup}>
              <Text style={styles.label}>Full Name</Text>
              <TextInput
                style={styles.input}
                value={formData.fullName}
                onChangeText={(text) => setFormData({ ...formData, fullName: text })}
                placeholder="Enter your full name"
                placeholderTextColor="#8e8e8e"
              />
            </View>

            <View style={styles.inputGroup}>
              <Text style={styles.label}>Username</Text>
              <TextInput
                style={styles.input}
                value={formData.username}
                onChangeText={(text) => setFormData({ ...formData, username: text })}
                placeholder="Enter your username"
                placeholderTextColor="#8e8e8e"
                autoCapitalize="none"
              />
            </View>

            <View style={styles.inputGroup}>
              <Text style={styles.label}>Email</Text>
              <TextInput
                style={styles.input}
                value={formData.email}
                onChangeText={(text) => setFormData({ ...formData, email: text })}
                placeholder="Enter your email"
                placeholderTextColor="#8e8e8e"
                keyboardType="email-address"
                autoCapitalize="none"
              />
            </View>

            <View style={styles.inputGroup}>
              <Text style={styles.label}>Bio</Text>
              <TextInput
                style={[styles.input, styles.bioInput]}
                value={formData.bio}
                onChangeText={(text) => setFormData({ ...formData, bio: text })}
                placeholder="Write something about yourself"
                placeholderTextColor="#8e8e8e"
                multiline
                numberOfLines={4}
              />
            </View>

            <View style={styles.inputGroup}>
              <Text style={styles.label}>Location</Text>
              <TextInput
                style={styles.input}
                value={formData.location}
                onChangeText={(text) => setFormData({ ...formData, location: text })}
                placeholder="Enter your location"
                placeholderTextColor="#8e8e8e"
              />
            </View>

            <View style={styles.divider} />

            <Text style={styles.sectionTitle}>Social Media Links</Text>

            <View style={styles.inputGroup}>
              <View style={styles.socialInput}>
                <Ionicons name="logo-instagram" size={24} color="#FF9500" />
                <TextInput
                  style={styles.socialInputField}
                  value={formData.instagram}
                  onChangeText={(text) => setFormData({ ...formData, instagram: text })}
                  placeholder="Instagram username"
                  placeholderTextColor="#8e8e8e"
                  autoCapitalize="none"
                />
              </View>
            </View>

            <View style={styles.inputGroup}>
              <View style={styles.socialInput}>
                <Ionicons name="logo-twitter" size={24} color="#FF9500" />
                <TextInput
                  style={styles.socialInputField}
                  value={formData.twitter}
                  onChangeText={(text) => setFormData({ ...formData, twitter: text })}
                  placeholder="Twitter username"
                  placeholderTextColor="#8e8e8e"
                  autoCapitalize="none"
                />
              </View>
            </View>
          </View>
        </ScrollView>
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
    justifyContent: 'space-between',
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
  headerTitle: {
    color: 'white',
    fontSize: 20,
    fontWeight: 'bold',
  },
  saveButton: {
    backgroundColor: '#FF9500',
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 16,
  },
  saveButtonText: {
    color: 'white',
    fontSize: 16,
    fontWeight: '600',
  },
  content: {
    flex: 1,
  },
  photoSection: {
    alignItems: 'center',
    padding: 24,
  },
  profilePhoto: {
    width: 120,
    height: 120,
    borderRadius: 60,
    marginBottom: 16,
  },
  changePhotoButton: {
    backgroundColor: '#333',
    paddingHorizontal: 20,
    paddingVertical: 10,
    borderRadius: 20,
  },
  changePhotoText: {
    color: '#FF9500',
    fontSize: 16,
    fontWeight: '600',
  },
  form: {
    padding: 16,
  },
  inputGroup: {
    marginBottom: 20,
  },
  label: {
    color: '#FF9500',
    fontSize: 14,
    marginBottom: 8,
    fontWeight: '600',
  },
  input: {
    backgroundColor: '#1C1C1E',
    borderRadius: 12,
    padding: 16,
    color: 'white',
    fontSize: 16,
  },
  bioInput: {
    height: 120,
    textAlignVertical: 'top',
  },
  divider: {
    height: 1,
    backgroundColor: '#333',
    marginVertical: 24,
  },
  sectionTitle: {
    color: 'white',
    fontSize: 20,
    fontWeight: 'bold',
    marginBottom: 16,
  },
  socialInput: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#1C1C1E',
    borderRadius: 12,
    padding: 16,
    gap: 12,
  },
  socialInputField: {
    flex: 1,
    color: 'white',
    fontSize: 16,
  },
});