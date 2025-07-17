import React from 'react';
import { View, Text, StyleSheet, ScrollView } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';

export default function PostDetailScreen({ route }) {
  const { post } = route.params;
  return (
    <SafeAreaView style={styles.container}>
      <ScrollView>
        <Text style={styles.title}>Post Details</Text>
        <Text style={styles.user}>{post.user?.name || 'User'}</Text>
        <Text style={styles.content}>{post.content}</Text>
        <Text style={styles.commentsTitle}>Comments</Text>
        <Text style={styles.commentsMessage}>Comment functionality coming soon.</Text>
      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#000',
    padding: 24,
  },
  title: {
    color: '#FF9500',
    fontSize: 28,
    fontWeight: 'bold',
    marginBottom: 16,
  },
  user: {
    color: '#8e8e8e',
    fontSize: 18,
    marginBottom: 8,
  },
  content: {
    color: 'white',
    fontSize: 20,
    marginBottom: 24,
  },
  commentsTitle: {
    color: '#FF9500',
    fontSize: 22,
    fontWeight: 'bold',
    marginTop: 24,
    marginBottom: 8,
  },
  commentsMessage: {
    color: '#8e8e8e',
    fontSize: 16,
  },
}); 