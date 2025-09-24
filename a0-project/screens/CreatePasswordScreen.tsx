import { View, Text, StyleSheet, TextInput, TouchableOpacity, Animated } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { LinearGradient } from 'expo-linear-gradient';
import { useState, useEffect, useRef } from 'react';
import { Ionicons, AntDesign } from '@expo/vector-icons';
import API_CLIENT from '../utils/apiClient';
import { toast } from 'sonner-native';

export default function CreatePasswordScreen({ navigation, route }: any) {
  const [password, setPassword] = useState('');
  const [showPassword, setShowPassword] = useState(false);
  const strengthBarWidth = useRef(new Animated.Value(0)).current;
  
  const requirements = [
    { id: 1, label: 'At least 8 characters', regex: /.{8,}/ },
    { id: 2, label: 'Contains a number', regex: /\d/ },
    { id: 3, label: 'Contains a special character', regex: /[!@#$%^&*]/ },
    { id: 4, label: 'Contains uppercase letter', regex: /[A-Z]/ },
  ];

  const [meetsRequirements, setMeetsRequirements] = useState<{ [key: number]: boolean }>(
    requirements.reduce((acc, req) => ({ ...acc, [req.id]: false }), {})
  );

  useEffect(() => {
    const newMeetsRequirements = requirements.reduce((acc, req) => ({
      ...acc,
      [req.id]: req.regex.test(password)
    }), {});
    
    setMeetsRequirements(newMeetsRequirements);
    
    const strengthScore = Object.values(newMeetsRequirements).filter(Boolean).length;
    Animated.timing(strengthBarWidth, {
      toValue: strengthScore / requirements.length,
      duration: 300,
      useNativeDriver: false
    }).start();
  }, [password]);

  const getStrengthColor = () => {
    const score = Object.values(meetsRequirements).filter(Boolean).length;
    if (score <= 1) return '#FF3B30';
    if (score <= 2) return '#FF9500';
    if (score <= 3) return '#FFCC00';
    return '#34C759';
  };

  const allRequirementsMet = Object.values(meetsRequirements).every(Boolean);

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
          <AntDesign name="left" size={24} color="white" />
          <Text style={styles.backText}>Back</Text>
        </TouchableOpacity>

        <Text style={styles.title}>Create a Password</Text>
        <Text style={styles.subtitle}>Make sure it's secure</Text>

        <View style={styles.inputContainer}>
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

        <View style={styles.strengthIndicator}>
          <View style={styles.strengthBar}>
            <Animated.View 
              style={[
                styles.strengthBarFill,
                {
                  backgroundColor: getStrengthColor(),
                  width: strengthBarWidth.interpolate({
                    inputRange: [0, 1],
                    outputRange: ['0%', '100%']
                  })
                }
              ]}
            />
          </View>
          <Text style={[styles.strengthText, { color: getStrengthColor() }]}>
            {Object.values(meetsRequirements).filter(Boolean).length === 4 
              ? 'Strong password' 
              : 'Password strength'}
          </Text>
        </View>

        <View style={styles.requirementsContainer}>
          {requirements.map((req) => (
            <View key={req.id} style={styles.requirementRow}>
              <AntDesign 
                name={meetsRequirements[req.id] ? "checkcircle" : "closecircle"} 
                size={20} 
                color={meetsRequirements[req.id] ? '#34C759' : '#FF3B30'} 
              />
              <Text style={[
                styles.requirementText,
                { color: meetsRequirements[req.id] ? '#34C759' : '#8e8e8e' }
              ]}>
                {req.label}
              </Text>
            </View>
          ))}
        </View>

        <TouchableOpacity 
          style={[
            styles.continueButton,
            !allRequirementsMet && styles.buttonDisabled
          ]}
          disabled={!allRequirementsMet}
          onPress={async () => {
            const fullName = route.params?.fullName;
            const email = route.params?.email;
            if (!fullName || !email || !password) {
              toast.error('Please fill all fields');
              return;
            }
            try {
              const response: any = await API_CLIENT.post('signup', {
                name: fullName,
                emailOrPhone: email,
                password
              });
              toast.success('Account created!');
              navigation.navigate('Subscription', { user_id: response.user_id, email, fullName });
            } catch (error: any) {
              toast.error(error.message || 'Sign up failed. Please try again.');
            }
          }}
        >
          <Text style={styles.continueButtonText}>Create Account</Text>
        </TouchableOpacity>
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
    paddingTop: 20,
  },
  backButton: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 32,
  },
  backText: {
    color: 'white',
    fontSize: 16,
    marginLeft: 8,
  },
  title: {
    fontSize: 28,
    fontWeight: 'bold',
    color: 'white',
    marginBottom: 8,
  },
  subtitle: {
    fontSize: 16,
    color: '#8e8e8e',
    marginBottom: 32,
  },
  inputContainer: {
    marginBottom: 24,
  },
  label: {
    color: '#8e8e8e',
    fontSize: 12,
    fontWeight: '600',
    marginBottom: 8,
    letterSpacing: 1,
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
  strengthIndicator: {
    gap: 8,
    marginBottom: 32,
  },
  strengthBar: {
    height: 4,
    backgroundColor: '#333333',
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
  buttonDisabled: {
    opacity: 0.5,
  },
  continueButtonText: {
    color: 'white',
    fontSize: 16,
    fontWeight: '600',
    textAlign: 'center',
  },
});