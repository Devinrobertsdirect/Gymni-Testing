import { View, Text, StyleSheet, TouchableOpacity, Image, Alert } from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { toast } from 'sonner-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';

export default function MenuScreen({ navigation }) {
  return (
    <View style={styles.container}>
      <SafeAreaView style={styles.safeArea}>
        <TouchableOpacity 
          style={styles.closeButton}
          onPress={() => navigation.goBack()}
        >
          <Ionicons name="close" size={28} color="white" />
        </TouchableOpacity>

        <View style={styles.profileSection}>
          <View style={styles.profileImageContainer}>
            <Image
              source={{ uri: 'https://api.a0.dev/assets/image?text=fitness%20profile&aspect=1:1' }}
              style={styles.profileImage}
            />
          </View>
          <Text style={styles.username}>Morgan Furbay</Text>
          
          <View style={styles.statsRow}>
            <View style={styles.stat}>
              <Text style={styles.statNumber}>2</Text>
              <Text style={styles.statLabel}>Saved Workouts</Text>
            </View>
            <View style={styles.stat}>
              <Text style={styles.statNumber}>1</Text>
              <Text style={styles.statLabel}>My Groups</Text>
            </View>
            <View style={styles.stat}>
              <Ionicons name="calendar-outline" size={24} color="white" />
              <Text style={styles.statLabel}>Calendar</Text>
            </View>
          </View>
        </View>

        <View style={styles.menuItems}>          <TouchableOpacity 
            style={styles.menuItem}
            onPress={() => navigation.navigate('MainHome')}
          >
            <Ionicons name="home" size={24} color="#FF9500" />
            <Text style={styles.menuText}>Home</Text>
          </TouchableOpacity>          <TouchableOpacity 
            style={styles.menuItem}
            onPress={() => navigation.navigate('AboutUs')}
          >
            <Ionicons name="people-outline" size={24} color="#FF9500" />
            <Text style={styles.menuText}>About Us</Text>
          </TouchableOpacity>          <TouchableOpacity 
            style={styles.menuItem}
            onPress={() => navigation.navigate('Fitness')}
          >
            <Ionicons name="barbell-outline" size={24} color="#FF9500" />
            <Text style={styles.menuText}>Fitness</Text>
          </TouchableOpacity>          <TouchableOpacity 
            style={styles.menuItem}
            onPress={() => navigation.navigate('SocialFeed')}
          >
            <MaterialCommunityIcons name="message-text-outline" size={24} color="#FF9500" />
            <Text style={styles.menuText}>Gymni feed</Text>
          </TouchableOpacity>

          <TouchableOpacity style={styles.menuItem} onPress={handleLogout}>
            <Ionicons name="musical-notes-outline" size={24} color="#FF9500" />
            <Text style={styles.menuText}>Spotify</Text>
          </TouchableOpacity>

          <TouchableOpacity 
            style={styles.menuItem}
            onPress={() => navigation.navigate('Settings')}
          >
            <Ionicons name="settings-outline" size={24} color="#FF9500" />
            <Text style={styles.menuText}>Settings</Text>
          </TouchableOpacity>

          <TouchableOpacity style={styles.menuItem} onPress={handleLogout}>
            <Ionicons name="log-out-outline" size={24} color="#FF9500" />
            <Text style={styles.menuText}>Logout</Text>
          </TouchableOpacity>
        </View>
      </SafeAreaView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#1a1a1a',
  },
  safeArea: {
    flex: 1,
  },
  closeButton: {
    padding: 16,
    alignSelf: 'flex-end',
  },
  profileSection: {
    alignItems: 'center',
    marginBottom: 32,
  },  profileImageContainer: {
    width: 80,
    height: 80,
    borderRadius: 40,
    marginBottom: 12,
  },
  profileImage: {
    width: '100%',
    height: '100%',
  },
  username: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#FF9500',
    marginBottom: 16,
  },
  statsRow: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    width: '100%',
    paddingHorizontal: 24,
  },
  stat: {
    alignItems: 'center',
  },
  statNumber: {
    fontSize: 20,
    fontWeight: 'bold',
    color: 'white',
  },
  statLabel: {
    color: '#8e8e8e',
    marginTop: 4,
  },
  menuItems: {
    paddingHorizontal: 24,
  },
  menuItem: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 16,
    gap: 16,
  },
  menuText: {
    color: 'white',
    fontSize: 18,
    fontWeight: '500',
  },
});
