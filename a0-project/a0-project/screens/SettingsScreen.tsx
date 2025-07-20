import { View, Text, StyleSheet, TouchableOpacity, Image, ScrollView, Linking } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { MaterialCommunityIcons, Ionicons, AntDesign } from '@expo/vector-icons';
import { LinearGradient } from 'expo-linear-gradient';

const SettingItem = ({ icon, title, hasSection, onPress }) => (
  <TouchableOpacity 
    style={[
      styles.settingItem,
      hasSection && styles.sectionHeader
    ]}
    onPress={onPress}
  >
    {icon}
    <Text style={styles.settingText}>{title}</Text>
    <Ionicons name="chevron-forward" size={24} color="#666" />
  </TouchableOpacity>
);

export default function SettingsScreen({ navigation }) {
  return (
    <LinearGradient
      colors={['#000000', '#1a1a1a']}
      style={styles.container}
    >
      <SafeAreaView style={styles.safeArea}>  <TouchableOpacity 
    style={styles.backButton}
    onPress={() => navigation.navigate('Menu')}
  >
    <Ionicons name="menu" size={24} color="white" />
  </TouchableOpacity>

        <ScrollView style={styles.content}>
          <Image
            source={{ uri: 'https://api.a0.dev/assets/image?text=gymni%20logo%20white&aspect=4:1' }}
            style={styles.logo}
          />

          <Text style={styles.title}>Settings</Text>

          {/* Account Section */}
          <SettingItem
            icon={<AntDesign name="user" size={24} color="white" />}
            title="Account"
            hasSection
          />          <SettingItem
            icon={<Ionicons name="person-circle-outline" size={24} color="white" />}
            title="Edit profile"
            onPress={() => navigation.navigate('EditProfile')}
          />
          
          <SettingItem
            icon={<Ionicons name="key-outline" size={24} color="white" />}
            title="Change password"            onPress={() => navigation.navigate('ChangePassword')}
          />          <SettingItem
            icon={<Ionicons name="notifications-outline" size={24} color="white" />}
            title="Notifications"
            onPress={() => navigation.navigate('Notifications')}
          />

          {/* More Section */}
          <SettingItem
            icon={<Ionicons name="add-circle-outline" size={24} color="white" />}
            title="More"
            hasSection
          />
          
          <SettingItem
            icon={<MaterialCommunityIcons name="flag-triangle" size={24} color="white" />}
            title="Challenge"
            onPress={() => navigation.navigate('Challenges')}
          />
          
          <SettingItem
            icon={<MaterialCommunityIcons name="crown-outline" size={24} color="white" />}            title="Subscriptions"
            onPress={() => navigation.navigate('SubscriptionManagement')}
          />          <SettingItem
            icon={<Ionicons name="help-circle-outline" size={24} color="white" />}
            title="Support and FAQS"
            onPress={() => navigation.navigate('SupportFAQ')}
          />
          
          <SettingItem
            icon={<Ionicons name="document-text-outline" size={24} color="white" />}
            title="Terms and Conditions"
            onPress={() => Linking.openURL('https://www.gymnifitness.com/terms-and-conditions')}
          />
          <SettingItem
            icon={<Ionicons name="shield-outline" size={24} color="white" />}
            title="Privacy Policy"
            onPress={() => Linking.openURL('https://www.gymnifitness.com/privacy-policy')}
          />

          <TouchableOpacity 
            style={styles.deleteButton}
            onPress={() => {/* Handle account deletion */}}
          >
            <Ionicons name="trash-outline" size={24} color="#FF3B30" />
            <Text style={styles.deleteText}>Delete Account</Text>
            <Ionicons name="chevron-forward" size={24} color="#666" />
          </TouchableOpacity>
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
  backButton: {
    padding: 16,
  },
  content: {
    flex: 1,
    paddingHorizontal: 16,
  },
  logo: {
    width: '100%',
    height: 60,
    resizeMode: 'contain',
    marginBottom: 24,
  },
  title: {
    fontSize: 32,
    fontWeight: 'bold',
    color: '#FF9500',
    marginBottom: 32,
  },
  settingItem: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 16,
    borderBottomWidth: 1,
    borderBottomColor: '#333',
  },
  sectionHeader: {
    marginTop: 24,
    borderBottomWidth: 2,
    borderBottomColor: '#444',
  },
  settingText: {
    flex: 1,
    color: 'white',
    fontSize: 18,
    marginLeft: 16,
  },
  deleteButton: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 16,
    marginTop: 24,
    marginBottom: 40,
  },
  deleteText: {
    flex: 1,
    color: '#FF3B30',
    fontSize: 18,
    marginLeft: 16,
  },
});