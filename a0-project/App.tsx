import 'react-native-gesture-handler';
import { NavigationContainer, DefaultTheme } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';
import { StyleSheet } from 'react-native';
import { SafeAreaProvider } from "react-native-safe-area-context";
import { Toaster } from 'sonner-native';

// Screen Imports
import AboutUsScreen from "./screens/AboutUsScreen";
import SupportFAQScreen from "./screens/SupportFAQScreen";
import ChangePasswordScreen from "./screens/ChangePasswordScreen";
import EditProfileScreen from "./screens/EditProfileScreen";
import NotificationsScreen from "./screens/NotificationsScreen";
import HomeScreen from "./screens/HomeScreen";
import CalendarScreen from "./screens/CalendarScreen";
import PasswordScreen from "./screens/PasswordScreen";
import CreateAccountScreen from "./screens/CreateAccountScreen";
import CreatePasswordScreen from "./screens/CreatePasswordScreen";
import CreateProfileScreen from "./screens/CreateProfileScreen";
import SubscriptionScreen from "./screens/SubscriptionScreen";
import SubscriptionSuccessScreen from "./screens/SubscriptionSuccessScreen";
import SubscriptionManagementScreen from "./screens/SubscriptionManagementScreen";
import MainHomeScreen from "./screens/MainHomeScreen";
import ChallengesScreen from "./screens/ChallengesScreen";
import CreateChallengeScreen from "./screens/CreateChallengeScreen";
import MenuScreen from "./screens/MenuScreen";
import SettingsScreen from "./screens/SettingsScreen";
import FitnessScreen from './screens/FitnessScreen';
import HIITScreen from './screens/HIITScreen';
import FilterScreen from './screens/FilterScreen';
import StrengthScreen from './screens/StrengthScreen';
import CardioScreen from './screens/CardioScreen';
import MobilityScreen from './screens/MobilityScreen';
import VideoModeScreen from './screens/VideoModeScreen';
import ExerciseDemosScreen from './screens/ExerciseDemosScreen';
import SocialFeedScreen from './screens/SocialFeedScreen';
import GroupDetailScreen from './screens/GroupDetailScreen';
import SearchScreen from './screens/SearchScreen';
import PostDetailScreen from './screens/PostDetailScreen';
import SportsScreen from './screens/SportsScreen';

export type RootStackParamList = {  
  ChangePassword: undefined;
  EditProfile: undefined;
  Notifications: undefined;
  Home: undefined;
  SupportFAQ: undefined;
  Password: { email: string };
  CreateAccount: undefined;
  CreatePassword: { fullName: string; email: string };
  CreateProfile: undefined;
  Subscription: undefined;
  SubscriptionSuccess: undefined;
  SubscriptionManagement: undefined;
  MainHome: undefined;
  Menu: undefined;
  Settings: undefined;
  Challenges: undefined;
  CreateChallenge: undefined;
  Fitness: undefined;
  HIIT: { filters?: any };
  Strength: { filters?: any };
  Cardio: { filters?: any };
  Mobility: { filters?: any };
  Filter: { sourceScreen?: string };  
  VideoMode: { workout: any };  
  ExerciseDemos: undefined;
  SocialFeed: undefined;
  GroupDetail: { group: any };
  AboutUs: undefined;
  Search: undefined;
  PostDetail: { post: any };
  Sports: undefined;
};

const Stack = createNativeStackNavigator<RootStackParamList>();

function RootStack() {  return (    <Stack.Navigator screenOptions={{ headerShown: false }}>
      <Stack.Screen name="Home" component={HomeScreen} />
      <Stack.Screen name="Password" component={PasswordScreen} />
      <Stack.Screen name="CreateAccount" component={CreateAccountScreen} />
      <Stack.Screen name="CreatePassword" component={CreatePasswordScreen} />
      <Stack.Screen name="CreateProfile" component={CreateProfileScreen} />
      <Stack.Screen name="Subscription" component={SubscriptionScreen} />
      <Stack.Screen name="SubscriptionSuccess" component={SubscriptionSuccessScreen} />
      <Stack.Screen name="SubscriptionManagement" component={SubscriptionManagementScreen} />
      <Stack.Screen name="MainHome" component={MainHomeScreen} />
      <Stack.Screen name="Calendar" component={CalendarScreen} />
      <Stack.Screen name="Menu" component={MenuScreen} />
      <Stack.Screen name="Settings" component={SettingsScreen} />
      <Stack.Screen name="Challenges" component={ChallengesScreen} />
      <Stack.Screen name="CreateChallenge" component={CreateChallengeScreen} />
      <Stack.Screen name="Fitness" component={FitnessScreen} />
      <Stack.Screen name="HIIT" component={HIITScreen} />
      <Stack.Screen name="Strength" component={StrengthScreen} initialParams={{ filters: null }} />
      <Stack.Screen name="Cardio" component={CardioScreen} initialParams={{ filters: null }} />
      <Stack.Screen name="Mobility" component={MobilityScreen} />
      <Stack.Screen name="Filter" component={FilterScreen} />
      <Stack.Screen name="VideoMode" component={VideoModeScreen} />
      <Stack.Screen name="ExerciseDemos" component={ExerciseDemosScreen} />
      <Stack.Screen name="SocialFeed" component={SocialFeedScreen} />
      <Stack.Screen name="GroupDetail" component={GroupDetailScreen} />
      <Stack.Screen name="AboutUs" component={AboutUsScreen} />
      <Stack.Screen name="EditProfile" component={EditProfileScreen} />
      <Stack.Screen name="ChangePassword" component={ChangePasswordScreen} />
      <Stack.Screen name="Notifications" component={NotificationsScreen} />
      <Stack.Screen name="SupportFAQ" component={SupportFAQScreen} />
      <Stack.Screen name="Search" component={SearchScreen} />
      <Stack.Screen name="PostDetail" component={PostDetailScreen} />
      <Stack.Screen name="Sports" component={SportsScreen} />
    </Stack.Navigator>
  );
}

export default function App() {
  return (
    <SafeAreaProvider style={styles.container}>
      <NavigationContainer>
        <Toaster />
        <RootStack />
      </NavigationContainer>
    </SafeAreaProvider>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
  }
});
