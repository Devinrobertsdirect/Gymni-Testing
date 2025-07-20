import React, { useState } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, Switch, ScrollView } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { LinearGradient } from 'expo-linear-gradient';
import { Ionicons } from '@expo/vector-icons';
import { toast } from 'sonner-native';

export default function NotificationsScreen({ navigation }) {
  const [permissions, setPermissions] = useState({
    workout: {
      reminders: true,
      achievements: true,
      summaries: true
    },
    social: {
      challenges: true,
      mentions: true,
      friendActivity: false,
      groupUpdates: true
    },
    messages: {
      directMessages: true,
      groupMessages: true,
      trainerMessages: true
    },
    system: {
      updates: true,
      subscription: true,
      maintenance: false
    }
  });

  const handleToggle = (category: string, setting: string) => {
    setPermissions(prev => ({
      ...prev,
      [category]: {
        ...prev[category],
        [setting]: !prev[category][setting]
      }
    }));

    // Show success message
    toast.success('Notification settings updated');
  };

  const NotificationGroup = ({ title, category, settings }) => (
    <View style={styles.section}>
      <Text style={styles.sectionTitle}>{title}</Text>
      {Object.entries(settings).map(([key, value]) => (
        <View key={key} style={styles.settingRow}>
          <View style={styles.settingInfo}>
            <Text style={styles.settingTitle}>
              {key.split(/(?=[A-Z])/).join(' ')}
            </Text>
            <Text style={styles.settingDescription}>
              {getSettingDescription(category, key)}
            </Text>
          </View>
          <Switch
            value={value as boolean}
            onValueChange={() => handleToggle(category, key)}
            trackColor={{ false: '#333', true: 'rgba(255, 149, 0, 0.3)' }}
            thumbColor={value ? '#FF9500' : '#666'}
          />
        </View>
      ))}
    </View>
  );

  const getSettingDescription = (category: string, setting: string) => {
    const descriptions = {
      workout: {
        reminders: 'Get reminded about your scheduled workouts',
        achievements: 'Celebrate when you hit new fitness milestones',
        summaries: 'Weekly progress and activity reports'
      },
      social: {
        challenges: 'Updates about challenges and invites',
        mentions: 'When someone mentions you in comments',
        friendActivity: 'Stay updated with friends\' achievements',
        groupUpdates: 'Important updates from your groups'
      },
      messages: {
        directMessages: 'Receive private message notifications',
        groupMessages: 'Get notified about group conversations',
        trainerMessages: 'Priority notifications from trainers'
      },
      system: {
        updates: 'App updates and new features',
        subscription: 'Billing and subscription updates',
        maintenance: 'Scheduled maintenance notifications'
      }
    };
    return descriptions[category][setting];
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
          <Text style={styles.headerTitle}>Notifications</Text>
          <View style={{ width: 70 }} />
        </View>

        <ScrollView style={styles.content}>
          <Text style={styles.description}>
            Choose which notifications you'd like to receive and how you'd like to be notified.
          </Text>

          <NotificationGroup 
            title="Workout Notifications" 
            category="workout"
            settings={permissions.workout}
          />

          <NotificationGroup 
            title="Social Notifications" 
            category="social"
            settings={permissions.social}
          />

          <NotificationGroup 
            title="Messages" 
            category="messages"
            settings={permissions.messages}
          />

          <NotificationGroup 
            title="System Notifications" 
            category="system"
            settings={permissions.system}
          />

          <TouchableOpacity 
            style={styles.enableAllButton}
            onPress={() => {
              const allEnabled = Object.values(permissions).every(category => 
                Object.values(category).every(value => value === true)
              );

              const newPermissions = Object.keys(permissions).reduce((acc, category) => ({
                ...acc,
                [category]: Object.keys(permissions[category]).reduce((catAcc, setting) => ({
                  ...catAcc,
                  [setting]: !allEnabled
                }), {})
              }), {});

              setPermissions(newPermissions);
              toast.success(allEnabled ? 'All notifications disabled' : 'All notifications enabled');
            }}
          >
            <Text style={styles.enableAllText}>
              {Object.values(permissions).every(category => 
                Object.values(category).every(value => value === true)
              ) ? 'Disable All Notifications' : 'Enable All Notifications'}
            </Text>
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
  content: {
    flex: 1,
    padding: 24,
  },
  description: {
    color: '#8e8e8e',
    fontSize: 16,
    lineHeight: 24,
    marginBottom: 32,
  },
  section: {
    marginBottom: 32,
  },
  sectionTitle: {
    color: '#FF9500',
    fontSize: 18,
    fontWeight: '600',
    marginBottom: 16,
  },
  settingRow: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    backgroundColor: '#1C1C1E',
    padding: 16,
    borderRadius: 12,
    marginBottom: 12,
  },
  settingInfo: {
    flex: 1,
    marginRight: 16,
  },
  settingTitle: {
    color: 'white',
    fontSize: 16,
    fontWeight: '600',
    textTransform: 'capitalize',
    marginBottom: 4,
  },
  settingDescription: {
    color: '#8e8e8e',
    fontSize: 14,
    lineHeight: 20,
  },
  enableAllButton: {
    backgroundColor: '#1C1C1E',
    padding: 16,
    borderRadius: 12,
    alignItems: 'center',
    marginTop: 8,
    marginBottom: 32,
  },
  enableAllText: {
    color: '#FF9500',
    fontSize: 16,
    fontWeight: '600',
  },
});