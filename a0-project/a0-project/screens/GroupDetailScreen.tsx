import React, { useState } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, Image, ScrollView, TextInput, Modal } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';
import { toast } from 'sonner-native';

export default function GroupDetailScreen({ navigation, route }) {
  const { group } = route.params;
  const [newPost, setNewPost] = useState('');
  const [selectedImage, setSelectedImage] = useState(null);
  const [showShareModal, setShowShareModal] = useState(false);
  const [isFavorite, setIsFavorite] = useState(false);

  const handlePost = () => {
    if (!newPost.trim() && !selectedImage) return;
    toast.success('Posted successfully!');
    setNewPost('');
    setSelectedImage(null);
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
          <Text style={styles.headerTitle}>{group.name}</Text>
          <TouchableOpacity onPress={() => setIsFavorite(!isFavorite)}>
            <Ionicons 
              name={isFavorite ? "star" : "star-outline"} 
              size={24} 
              color={isFavorite ? "#FF9500" : "white"} 
            />
          </TouchableOpacity>
        </View>

        <ScrollView style={styles.content}>
          {/* Group Info */}
          <View style={styles.groupInfo}>
            <Image source={{ uri: group.avatar }} style={styles.groupAvatar} />
            <View style={styles.groupStats}>
              <View style={styles.statItem}>
                <Text style={styles.statNumber}>24</Text>
                <Text style={styles.statLabel}>Members</Text>
              </View>
              <View style={styles.statItem}>
                <Text style={styles.statNumber}>156</Text>
                <Text style={styles.statLabel}>Posts</Text>
              </View>
              <View style={styles.statItem}>
                <Text style={styles.statNumber}>45</Text>
                <Text style={styles.statLabel}>Media</Text>
              </View>
            </View>
          </View>

          {/* New Post Input */}
          <View style={styles.newPostContainer}>
            <Image
              source={{ uri: 'https://api.a0.dev/assets/image?text=user%20profile&aspect=1:1' }}
              style={styles.userAvatar}
            />
            <View style={styles.inputContainer}>
              <TextInput
                style={styles.input}
                placeholder="Share something with the group..."
                placeholderTextColor="#8e8e8e"
                multiline
                value={newPost}
                onChangeText={setNewPost}
              />
              {selectedImage && (
                <View style={styles.selectedImageContainer}>
                  <Image source={{ uri: selectedImage }} style={styles.selectedImage} />
                  <TouchableOpacity 
                    style={styles.removeImageButton}
                    onPress={() => setSelectedImage(null)}
                  >
                    <Ionicons name="close-circle" size={24} color="white" />
                  </TouchableOpacity>
                </View>
              )}
              <View style={styles.postActions}>
                <View style={styles.postButtons}>
                  <TouchableOpacity 
                    style={styles.actionButton}
                    onPress={() => setShowShareModal(true)}
                  >
                    <Ionicons name="image-outline" size={24} color="#FF9500" />
                  </TouchableOpacity>
                  <TouchableOpacity 
                    style={styles.actionButton}
                    onPress={() => setShowShareModal(true)}
                  >
                    <MaterialCommunityIcons name="dumbbell" size={24} color="#FF9500" />
                  </TouchableOpacity>
                </View>
                <TouchableOpacity 
                  style={[
                    styles.postButton,
                    (!newPost.trim() && !selectedImage) && styles.postButtonDisabled
                  ]}
                  onPress={handlePost}
                  disabled={!newPost.trim() && !selectedImage}
                >
                  <Text style={styles.postButtonText}>Post</Text>
                </TouchableOpacity>
              </View>
            </View>
          </View>

          {/* Recent Activity */}
          <View style={styles.recentActivity}>
            <Text style={styles.sectionTitle}>Recent Activity</Text>
            <Text style={styles.lastMessage}>{group.lastMessage}</Text>
          </View>
        </ScrollView>

        {/* Share Modal */}
        <Modal
          visible={showShareModal}
          transparent={true}
          animationType="slide"
          onRequestClose={() => setShowShareModal(false)}
        >
          <TouchableOpacity 
            style={styles.modalOverlay}
            activeOpacity={1}
            onPress={() => setShowShareModal(false)}
          >
            <View style={styles.modalContent}>
              <Text style={styles.modalTitle}>Add to Your Post</Text>
              <TouchableOpacity 
                style={styles.modalOption}
                onPress={() => {
                  setSelectedImage('https://api.a0.dev/assets/image?text=mock%20upload&aspect=16:9');
                  setShowShareModal(false);
                }}
              >
                <Ionicons name="image-outline" size={24} color="#FF9500" />
                <Text style={styles.modalOptionText}>Photo</Text>
              </TouchableOpacity>
              <TouchableOpacity 
                style={styles.modalOption}
                onPress={() => {
                  setShowShareModal(false);
                }}
              >
                <MaterialCommunityIcons name="dumbbell" size={24} color="#FF9500" />
                <Text style={styles.modalOptionText}>Workout</Text>
              </TouchableOpacity>
              <TouchableOpacity 
                style={styles.modalCloseButton}
                onPress={() => setShowShareModal(false)}
              >
                <Text style={styles.modalCloseText}>Cancel</Text>
              </TouchableOpacity>
            </View>
          </TouchableOpacity>
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
  },
  groupInfo: {
    alignItems: 'center',
    padding: 16,
    borderBottomWidth: 1,
    borderBottomColor: '#333',
  },
  groupAvatar: {
    width: 80,
    height: 80,
    borderRadius: 40,
    marginBottom: 16,
  },
  groupStats: {
    flexDirection: 'row',
    gap: 32,
  },
  statItem: {
    alignItems: 'center',
  },
  statNumber: {
    color: 'white',
    fontSize: 20,
    fontWeight: 'bold',
  },
  statLabel: {
    color: '#8e8e8e',
    fontSize: 14,
  },
  newPostContainer: {
    flexDirection: 'row',
    padding: 16,
    gap: 12,
  },
  userAvatar: {
    width: 40,
    height: 40,
    borderRadius: 20,
  },
  inputContainer: {
    flex: 1,
  },
  input: {
    backgroundColor: '#1C1C1E',
    borderRadius: 20,
    padding: 12,
    color: 'white',
    fontSize: 16,
    minHeight: 40,
  },
  selectedImageContainer: {
    marginTop: 8,
    position: 'relative',
  },
  selectedImage: {
    width: '100%',
    height: 200,
    borderRadius: 12,
  },
  removeImageButton: {
    position: 'absolute',
    top: 8,
    right: 8,
    backgroundColor: 'rgba(0, 0, 0, 0.5)',
    borderRadius: 12,
  },
  postActions: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginTop: 8,
  },
  postButtons: {
    flexDirection: 'row',
    gap: 12,
  },
  actionButton: {
    padding: 8,
  },
  postButton: {
    backgroundColor: '#FF9500',
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 16,
  },
  postButtonDisabled: {
    opacity: 0.5,
  },
  postButtonText: {
    color: 'white',
    fontWeight: '600',
  },
  recentActivity: {
    padding: 16,
  },
  sectionTitle: {
    color: '#FF9500',
    fontSize: 18,
    fontWeight: '600',
    marginBottom: 12,
  },
  lastMessage: {
    color: 'white',
    fontSize: 16,
    lineHeight: 24,
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
    paddingTop: 20,
  },
  modalTitle: {
    color: 'white',
    fontSize: 18,
    fontWeight: '600',
    textAlign: 'center',
    marginBottom: 20,
  },
  modalOption: {
    flexDirection: 'row',
    alignItems: 'center',
    padding: 16,
    gap: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#333',
  },
  modalOptionText: {
    color: 'white',
    fontSize: 16,
  },
  modalCloseButton: {
    padding: 16,
    alignItems: 'center',
    borderTopWidth: 1,
    borderTopColor: '#333',
  },
  modalCloseText: {
    color: '#FF9500',
    fontSize: 16,
    fontWeight: '600',
  },
});