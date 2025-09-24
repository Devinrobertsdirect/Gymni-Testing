import { View, Text, StyleSheet, TextInput, TouchableOpacity, Animated, Image } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { LinearGradient } from 'expo-linear-gradient';
import { useState, useEffect } from 'react';
import { Ionicons } from '@expo/vector-icons';
import API_CLIENT from '../utils/apiClient';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { toast } from 'sonner-native';

export default function PasswordScreen({ navigation, route }: any) {
  const [password, setPassword] = useState('');
  const [showPassword, setShowPassword] = useState(false);
  
  // Password requirements
  const requirements = [
    { id: 1, label: 'At least 8 characters', regex: /.{8,}/ },
    { id: 2, label: 'Contains a number', regex: /\d/ },
    { id: 3, label: 'Contains a special character', regex: /[!@#$%^&*]/ },
    { id: 4, label: 'Contains uppercase letter', regex: /[A-Z]/ },
  ];

  const [strengthScore, setStrengthScore] = useState(0);

  useEffect(() => {
    let score = 0;
    requirements.forEach(req => {
      if (req.regex.test(password)) {
        score++;
      }
    });
    setStrengthScore(score);
  }, [password]);

  const getStrengthColor = () => {
    if (strengthScore <= 1) return '#FF3B30';
    if (strengthScore <= 2) return '#FF9500';
    if (strengthScore <= 3) return '#FFCC00';
    return '#34C759';
  };

  const handleLogin = async () => {
    const email = route.params?.email;
    if (!email || !password) {
      toast.error('Email and password are required.');
      return;
    }

    try {
      const response: any = await API_CLIENT.post('login', { emailOrPhone: email, password });
      await AsyncStorage.setItem('userToken', response.token); // Assuming backend returns a token in 'token' field
      toast.success('Login successful!');
      navigation.navigate('MainHome');
    } catch (error: any) {
      toast.error(error.message || 'Login failed. Please try again.');
    }
  };

  return (
    <LinearGradient
      colors={['#1a1a1a', '#000000']}
      style={styles.container}
    >
      <SafeAreaView style={styles.content}>
        <TouchableOpacity 
          style={styles.backButton}
          onPress={() => navigation.goBack()}
        >
          <Ionicons name="chevron-back" size={24} color="white" />
          <Text style={styles.backText}>Back</Text>
        </TouchableOpacity>

        {/* Logo - Replace this with your actual logo file */}
        <View style={styles.logoContainer}>
          {/* Replace the uri with your local logo file when available */}
          <Image 
            source={{ uri: 'https://api.a0.dev/assets/image?text=Gymni&aspect=4%3A1&color=white&background=transparent' }} 
            style={styles.logo}
            resizeMode="contain"
          />
          {/* Example for local file: source={require('../assets/gymni-logo.png')} */}
        </View>

        <View style={styles.headerContainer}>
          <Text style={styles.title}>Welcome back!</Text>
          <Text style={styles.subtitle}>{route.params?.email}</Text>
        </View>
        
        <View style={styles.inputContainer}>
          <View style={styles.emailContainer}>
            <Text style={styles.label}>EMAIL</Text>
            <View style={styles.emailDisplay}>
              <Text style={styles.emailText}>{route.params?.email}</Text>
            </View>
          </View>
            
          <View style={styles.passwordContainer}>
            <Text style={styles.label}>PASSWORD</Text>
            <View style={styles.passwordInputContainer}>
              <TextInput
                style={styles.input}
                placeholder="Enter your password"
                placeholderTextColor="#8e8e8e"
                secureTextEntry={!showPassword}
                value={password}
                onChangeText={setPassword}
                autoFocus
              />
              <TouchableOpacity 
                style={styles.showPasswordButton}
                onPress={() => setShowPassword(!showPassword)}
              >
                <Ionicons 
                  name={showPassword ? "eye-off" : "eye"} 
                  size={24} 
                  color="#8e8e8e" 
                />
              </TouchableOpacity>
            </View>
          </View>
        </View>

        <TouchableOpacity 
          style={styles.loginButton}
          onPress={handleLogin}
        >
          <Text style={styles.loginButtonText}>Log In</Text>
        </TouchableOpacity>

        <TouchableOpacity style={styles.forgotPasswordButton}>
          <Text style={styles.forgotPasswordText}>Forgot Password?</Text>
        </TouchableOpacity>
      </SafeAreaView>
    </LinearGradient>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#000000',
  },
  content: {
    flex: 1,
    paddingHorizontal: 24,
  },
  logoContainer: {
    alignItems: 'center',
    marginTop: 20,
    marginBottom: 10,
  },
  logo: {
    width: 150,
    height: 50,
  },
  backButton: {
    flexDirection: 'row',
    alignItems: 'center',
    marginTop: 8,
  },
  backText: {
    color: 'white',
    fontSize: 16,
    marginLeft: 4,
  },
  headerContainer: {
    marginTop: 20,
    marginBottom: 40,
  },
  title: {
    fontSize: 28,
    fontWeight: 'bold',
    color: 'white',
  },
  subtitle: {
    fontSize: 16,
    color: '#8e8e8e',
    marginTop: 8,
  },
  inputContainer: {
    gap: 24,
  },
  label: {
    color: '#8e8e8e',
    fontSize: 12,
    fontWeight: '600',
    marginBottom: 8,
    letterSpacing: 1,
  },
  emailContainer: {
    marginBottom: 16,
  },
  emailDisplay: {
    backgroundColor: '#333333',
    borderRadius: 12,
    padding: 16,
  },
  emailText: {
    color: 'white',
    fontSize: 16,
  },
  passwordContainer: {
    marginBottom: 24,
  },
  passwordInputContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#333333',
    borderRadius: 12,
  },
  input: {
    flex: 1,
    padding: 16,
    color: 'white',
    fontSize: 16,
  },
  showPasswordButton: {
    padding: 16,
  },
  loginButton: {
    backgroundColor: '#007AFF',
    padding: 16,
    borderRadius: 12,
    marginTop: 'auto',
    marginBottom: 16,
  },
  loginButtonText: {
    color: 'white',
    fontSize: 16,
    fontWeight: '600',
    textAlign: 'center',
  },
  forgotPasswordButton: {
    alignItems: 'center',
    marginBottom: 24,
  },
  forgotPasswordText: {
    color: '#007AFF',
    fontSize: 14,
    fontWeight: '600',
  },
  strengthIndicator: {
    gap: 8,
  },
  strengthBar: {
    height: 4,
    borderRadius: 2,
    overflow: 'hidden',
  },
  strengthBarFill: {
    height: '100%',
    borderRadius: 2,
  },
  strengthText: {
    fontSize: 14,
  },
  requirementsContainer: {
    marginTop: 32,
    gap: 16,
  },
  requirementRow: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 12,
  },
  requirementText: {
    fontSize: 14,
  },
  continueButton: {
    backgroundColor: '#007AFF',
    padding: 16,
    borderRadius: 12,
    marginTop: 'auto',
    marginBottom: 24,
  },
  continueButtonText: {
    color: 'white',
    fontSize: 16,
    fontWeight: '600',
    textAlign: 'center',
  },
});