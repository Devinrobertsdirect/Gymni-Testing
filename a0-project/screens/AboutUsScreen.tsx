import React from 'react';
import { View, Text, StyleSheet, TouchableOpacity, Image, ScrollView } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { LinearGradient } from 'expo-linear-gradient';
import { Ionicons } from '@expo/vector-icons';

export default function AboutUsScreen({ navigation }) {
  return (
    <LinearGradient
      colors={['#1a1a1a', '#000000']}
      style={styles.container}
    >
      <SafeAreaView style={styles.safeArea}>
        <TouchableOpacity 
          style={styles.backButton}
          onPress={() => navigation.goBack()}
        >
          <Ionicons name="chevron-back" size={24} color="white" />
          <Text style={styles.backText}>Back</Text>
        </TouchableOpacity>

        <ScrollView style={styles.content} showsVerticalScrollIndicator={false}>
          <Text style={styles.title}>Welcome to Gymni Fitness</Text>

          <Text style={styles.description}>
            At Gymni, we want you to feel your best, your most confident, your strongest. Our goal is to provide fitness and nutrition programs to help you create sustainable, balanced habits that will last a lifetime. Most importantly, we want to build a community for you to stay motivated to achieve your goals and become the happiest, healthiest version of yourself. Let's get stronger together. Let's get comfortable being uncomfortable.
          </Text>

          <View style={styles.founderSection}>
            <View style={styles.founderCard}>
              <Image
                source={{ uri: 'https://api.a0.dev/assets/image?text=ally%20furbay%20profile&aspect=1:1&seed=123' }}
                style={styles.founderImage}
              />
              <Text style={styles.founderName}>Ally Furbay</Text>
              <Text style={styles.founderBio}>
                Ally graduated from Bucknell University in May 2016 with a bachelor's degree in Business and a minor in international relations. At Bucknell, Ally was a member of the Varsity Women's Lacrosse team all 4 years. After graduation, Ally pursued a career in business, working various roles at Amazon based in Seattle.
              </Text>
            </View>

            <View style={styles.founderCard}>
              <Image
                source={{ uri: 'https://api.a0.dev/assets/image?text=morgan%20furbay%20profile&aspect=1:1&seed=456' }}
                style={styles.founderImage}
              />
              <Text style={styles.founderName}>Morgan Furbay</Text>
              <Text style={styles.founderBio}>
                Morgan graduated from The University of Vermont in 2020, with a bachelor's degree in Exercise Science and a minor in Behavior Change Health Studies. At UVM, Morgan was a member of the Varsity Women's Lacrosse team all 4 years. After graduation, Morgan continued to pursue her love for fitness to her professional life, becoming a certified personal trainer (CPT) and nutrition coach (CNC).
              </Text>
            </View>
          </View>

          <View style={styles.valuesSection}>
            <Text style={styles.valuesTitle}>Our Values</Text>
            <View style={styles.valueCard}>
              <Ionicons name="heart" size={24} color="#FF9500" />
              <Text style={styles.valueTitle}>Community First</Text>
              <Text style={styles.valueDescription}>Building a supportive environment where everyone feels welcome and motivated.</Text>
            </View>
            <View style={styles.valueCard}>
              <Ionicons name="trending-up" size={24} color="#FF9500" />
              <Text style={styles.valueTitle}>Sustainable Progress</Text>
              <Text style={styles.valueDescription}>Focus on long-term success through balanced, achievable goals.</Text>
            </View>
            <View style={styles.valueCard}>
              <Ionicons name="people" size={24} color="#FF9500" />
              <Text style={styles.valueTitle}>Personal Growth</Text>
              <Text style={styles.valueDescription}>Supporting your journey to become the best version of yourself.</Text>
            </View>
          </View>
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
    flexDirection: 'row',
    alignItems: 'center',
    padding: 16,
  },
  backText: {
    color: 'white',
    fontSize: 16,
    marginLeft: 8,
  },
  content: {
    flex: 1,
    padding: 16,
  },
  title: {
    fontSize: 32,
    fontWeight: 'bold',
    color: '#FF9500',
    marginBottom: 24,
  },
  description: {
    color: 'white',
    fontSize: 16,
    lineHeight: 24,
    marginBottom: 32,
  },
  founderSection: {
    gap: 24,
    marginBottom: 32,
  },
  founderCard: {
    backgroundColor: '#1C1C1E',
    borderRadius: 16,
    padding: 16,
    alignItems: 'center',
  },
  founderImage: {
    width: 120,
    height: 120,
    borderRadius: 60,
    marginBottom: 16,
  },
  founderName: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#FF9500',
    marginBottom: 8,
  },
  founderBio: {
    color: 'white',
    fontSize: 14,
    lineHeight: 22,
    textAlign: 'center',
  },
  valuesSection: {
    gap: 16,
    marginBottom: 32,
  },
  valuesTitle: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#FF9500',
    marginBottom: 16,
  },
  valueCard: {
    backgroundColor: '#1C1C1E',
    borderRadius: 16,
    padding: 16,
    alignItems: 'center',
    gap: 8,
  },
  valueTitle: {
    fontSize: 18,
    fontWeight: '600',
    color: 'white',
    marginTop: 8,
  },
  valueDescription: {
    color: '#8e8e8e',
    fontSize: 14,
    textAlign: 'center',
    lineHeight: 20,
  },
});