import { View, Text, StyleSheet, TextInput, TouchableOpacity, Image } from 'react-native';
import { useState } from 'react';
import { AntDesign } from '@expo/vector-icons';
import { LinearGradient } from 'expo-linear-gradient';
import { SafeAreaView } from 'react-native-safe-area-context';
import { toast } from 'sonner-native';

export default function HomeScreen({ navigation }) {  const [loading, setLoading] = useState(false);

  const handleGoogleSignIn = async () => {
    try {
      setLoading(true);
      // Initialize Google Sign In with your configuration
      const clientId = ''; // Add your Google Client ID here
      const redirectUri = ''; // Add your redirect URI here
      
      // Show loading state
      toast.loading('Connecting to Google...');
      
      // For demonstration, simulating auth response
      await new Promise(resolve => setTimeout(resolve, 1500));
      
      // Here you would handle the actual Google Sign In response
      // Store tokens and user info
      
      toast.success('Successfully signed in!');
      navigation.navigate('MainHome');
    } catch (error) {
      toast.error('Failed to connect to Google');
      console.error('Google Sign In Error:', error);
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
        <View style={styles.socialButtonsContainer}>          <TouchableOpacity 
            style={styles.socialButton}            onPress={handleGoogleSignIn}
          >
            <AntDesign name="google" size={24} color="white" />
            <Text style={styles.socialButtonText}>Continue with Google</Text>
          </TouchableOpacity>

          <TouchableOpacity style={styles.socialButton}>
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