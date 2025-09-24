import { View, Text, StyleSheet, TextInput, TouchableOpacity, Image } from 'react-native';
import { useState, useEffect } from 'react';
import { AntDesign } from '@expo/vector-icons';
import { LinearGradient } from 'expo-linear-gradient';
import { SafeAreaView } from 'react-native-safe-area-context';
import { toast } from 'sonner-native';
import HamburgerMenuButton from './HamburgerMenuButton';
import * as WebBrowser from 'expo-web-browser';
import * as Google from 'expo-auth-session/providers/google';
// Apple authentication import (add if installed):
// import * as AppleAuthentication from 'expo-apple-authentication';
import API_CLIENT from '../utils/apiClient';
import AsyncStorage from '@react-native-async-storage/async-storage';

WebBrowser.maybeCompleteAuthSession();

export default function HomeScreen({ navigation }: any) {
  const [loading, setLoading] = useState(false);
  const [request, response, promptAsync] = Google.useAuthRequest({
    clientId: '796288733107-dre8gjp35ust079chrume180ucneuotj.apps.googleusercontent.com', // Replace with your Expo/Google client ID
    iosClientId: '796288733107-0mlqtiklpgqk5v1cb416terp273lls5m.apps.googleusercontent.com',   // Replace with your iOS client ID
    androidClientId: '796288733107-n21o6hupfudps9m7rdkfleo8kuqc221o.apps.googleusercontent.com', // Replace with your Android client ID
    webClientId: '796288733107-dre8gjp35ust079chrume180ucneuotj.apps.googleusercontent.com',   // Replace with your web client ID
  });

  // Handle Google OAuth response
  useEffect(() => {
    if (response?.type === 'success') {
      const { authentication } = response;
      if (authentication?.accessToken) {
        fetchGoogleUserInfo(authentication.accessToken);
      }
    }
  }, [response]);

  const fetchGoogleUserInfo = async (accessToken: string) => {
    try {
      setLoading(true);
      const userInfoResponse = await fetch('https://www.googleapis.com/userinfo/v2/me', {
        headers: { Authorization: `Bearer ${accessToken}` },
      });
      const userInfo = await userInfoResponse.json();
      // userInfo.id (Google ID), userInfo.email
      const backendResponse: any = await API_CLIENT.post('social_login', {
        social_id: userInfo.id,
        email: userInfo.email,
        token: '', // Optionally pass FCM/device token if available
      });
      await AsyncStorage.setItem('userToken', backendResponse.token);
      toast.success('Successfully signed in!');
      navigation.navigate('MainHome');
    } catch (error: any) {
      toast.error(error.message || 'Google login failed.');
    } finally {
      setLoading(false);
    }
  };

  const handleGoogleSignIn = async () => {
    try {
      setLoading(true);
      await promptAsync();
    } catch (error) {
      toast.error('Failed to connect to Google');
      console.error('Google Sign In Error:', error);
    } finally {
      setLoading(false);
    }
  };

  // Apple login handler (requires expo-apple-authentication)
  const handleAppleSignIn = async () => {
    try {
      setLoading(true);
      // Uncomment and implement if expo-apple-authentication is installed
      // const credential = await AppleAuthentication.signInAsync({
      //   requestedScopes: [
      //     AppleAuthentication.AppleAuthenticationScope.FULL_NAME,
      //     AppleAuthentication.AppleAuthenticationScope.EMAIL,
      //   ],
      // });
      // const backendResponse: any = await API_CLIENT.post('social_login', {
      //   social_id: credential.user,
      //   email: credential.email,
      //   token: '',
      // });
      // await AsyncStorage.setItem('userToken', backendResponse.token);
      // toast.success('Successfully signed in!');
      // navigation.navigate('MainHome');
      toast.error('Apple login is not yet set up. Please install expo-apple-authentication and configure.');
    } catch (error) {
      toast.error('Failed to connect to Apple');
      console.error('Apple Sign In Error:', error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <LinearGradient
      colors={['#1a1a1a', '#000000']}
      style={styles.container}
    >
      <SafeAreaView style={styles.content}>
        <View style={styles.header}>
          <HamburgerMenuButton navigation={navigation} />
          <Text style={styles.headerTitle}>Home</Text>
          <View style={{ width: 24 }} />
        </View>
        {/* Logo */}
        <View style={styles.logoContainer}>
          <Image
            source={{ uri: 'https://api.a0.dev/assets/image?text=modern%20minimal%20fitness%20logo%20white%20on%20transparent&aspect=1:1' }}
            style={styles.logo}
          />
        </View>

        {/* Welcome Text */}
        <Text style={styles.welcomeText}>Welcome to Gymn'i</Text>
        <Text style={styles.subText}>Sign in to continue</Text>

        {/* Social Sign In Buttons */}
        <View style={styles.socialButtonsContainer}>
          <TouchableOpacity 
            style={styles.socialButton}
            onPress={handleGoogleSignIn}
            disabled={loading}
          >
            <AntDesign name="google" size={24} color="white" />
            <Text style={styles.socialButtonText}>Continue with Google</Text>
          </TouchableOpacity>

          <TouchableOpacity 
            style={styles.socialButton}
            onPress={handleAppleSignIn}
            disabled={loading}
          >
            <AntDesign name="apple1" size={24} color="white" />
            <Text style={styles.socialButtonText}>Continue with Apple</Text>
          </TouchableOpacity>
        </View>

        {/* Divider */}
        <View style={styles.dividerContainer}>
          <View style={styles.divider} />
          <Text style={styles.dividerText}>OR</Text>
          <View style={styles.divider} />
        </View>

        {/* Input Fields */}
        <View style={styles.inputContainer}>          <TextInput
            style={styles.input}
            placeholder="Email"
            placeholderTextColor="#8e8e8e"
            keyboardType="email-address"
            autoCapitalize="none"
            autoComplete="email"
            onSubmitEditing={(event) => {
              if (event.nativeEvent.text) {
                navigation.navigate('Password', { email: event.nativeEvent.text });
              }
            }}
          />
        </View>

        {/* Sign In Button */}        <TouchableOpacity 
          style={styles.signInButton}
          onPress={() => {
            // We'll use the TextInput's onSubmitEditing instead
            // This button is now just a visual element
            navigation.navigate('Password', { email: '' });
          }}
        >
          <Text style={styles.signInButtonText}>Sign In</Text>
        </TouchableOpacity>

        {/* Create Account Link */}        <View style={styles.footerContainer}>
          <Text style={styles.footerText}>Don't have an account? </Text>
          <TouchableOpacity onPress={() => navigation.navigate('CreateAccount')}>
            <Text style={styles.footerLink}>Create one</Text>
          </TouchableOpacity>
        </View>
      </SafeAreaView>
    </LinearGradient>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
  content: {
    flex: 1,
    paddingHorizontal: 24,
  },
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginTop: 20,
    marginBottom: 20,
  },
  headerTitle: {
    fontSize: 24,
    fontWeight: 'bold',
    color: 'white',
  },
  logoContainer: {
    alignItems: 'center',
    marginTop: 40,
    marginBottom: 40,
  },
  logo: {
    width: 120,
    height: 120,
    resizeMode: 'contain',
  },
  welcomeText: {
    fontSize: 28,
    fontWeight: 'bold',
    color: 'white',
    textAlign: 'center',
  },
  subText: {
    fontSize: 16,
    color: '#8e8e8e',
    textAlign: 'center',
    marginTop: 8,
    marginBottom: 32,
  },
  socialButtonsContainer: {
    gap: 16,
  },
  socialButton: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: '#333333',
    padding: 16,
    borderRadius: 12,
    gap: 12,
  },
  socialButtonText: {
    color: 'white',
    fontSize: 16,
    fontWeight: '600',
  },
  dividerContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    marginVertical: 32,
  },
  divider: {
    flex: 1,
    height: 1,
    backgroundColor: '#333333',
  },
  dividerText: {
    color: '#8e8e8e',
    paddingHorizontal: 16,
    fontSize: 14,
  },
  inputContainer: {
    gap: 16,
  },
  input: {
    backgroundColor: '#333333',
    padding: 16,
    borderRadius: 12,
    color: 'white',
    fontSize: 16,
  },
  signInButton: {
    backgroundColor: '#007AFF',
    padding: 16,
    borderRadius: 12,
    marginTop: 24,
  },
  signInButtonText: {
    color: 'white',
    fontSize: 16,
    fontWeight: '600',
    textAlign: 'center',
  },
  footerContainer: {
    flexDirection: 'row',
    justifyContent: 'center',
    marginTop: 24,
  },
  footerText: {
    color: '#8e8e8e',
    fontSize: 14,
  },
  footerLink: {
    color: '#007AFF',
    fontSize: 14,
    fontWeight: '600',
  },
});
