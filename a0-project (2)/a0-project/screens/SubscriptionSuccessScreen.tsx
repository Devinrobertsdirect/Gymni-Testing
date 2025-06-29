import { View, Text, StyleSheet, TouchableOpacity, Image, Animated } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { LinearGradient } from 'expo-linear-gradient';
import { useEffect, useRef } from 'react';
import { AntDesign } from '@expo/vector-icons';

export default function SubscriptionSuccessScreen({ navigation }) {
  const scaleAnim = useRef(new Animated.Value(0)).current;
  
  useEffect(() => {
    Animated.spring(scaleAnim, {
      toValue: 1,
      tension: 50,
      friction: 7,
      useNativeDriver: true,
    }).start();    // Auto navigate after 2 seconds
    const timer = setTimeout(() => {
      navigation.replace('CreateProfile');
    }, 2000);

    return () => clearTimeout(timer);
  }, []);

  return (
    <LinearGradient
      colors={['#1a1a1a', '#000000']}
      style={styles.container}
    >
      <SafeAreaView style={styles.content}>
        <Animated.View 
          style={[
            styles.successContainer,
            {
              transform: [{ scale: scaleAnim }]
            }
          ]}
        >
          <View style={styles.iconContainer}>
            <AntDesign name="checkcircle" size={64} color="#FF9500" />
          </View>
          
          <Text style={styles.title}>Welcome to Premium!</Text>
          <Text style={styles.subtitle}>Your 7-day free trial has started</Text>
          
          <View style={styles.detailsContainer}>
            <Text style={styles.detailText}>• Access to all premium features</Text>
            <Text style={styles.detailText}>• Cancel anytime during trial</Text>
            <Text style={styles.detailText}>• First payment after trial ends</Text>
          </View>
        </Animated.View>
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
    justifyContent: 'center',
    alignItems: 'center',
    paddingHorizontal: 24,
  },
  successContainer: {
    alignItems: 'center',
    gap: 16,
  },
  iconContainer: {
    marginBottom: 24,
  },
  title: {
    fontSize: 28,
    fontWeight: 'bold',
    color: 'white',
    textAlign: 'center',
  },
  subtitle: {
    fontSize: 16,
    color: '#8e8e8e',
    textAlign: 'center',
    marginBottom: 16,
  },
  detailsContainer: {
    gap: 12,
    marginTop: 24,
  },
  detailText: {
    fontSize: 16,
    color: '#FF9500',
    textAlign: 'center',
  },
});