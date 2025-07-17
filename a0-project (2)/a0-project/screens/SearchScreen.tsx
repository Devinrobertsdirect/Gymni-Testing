import React, { useState } from 'react';
import { View, Text, TextInput, StyleSheet } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';

export default function SearchScreen() {
  const [query, setQuery] = useState('');
  return (
    <SafeAreaView style={styles.container}>
      <Text style={styles.title}>Search</Text>
      <TextInput
        style={styles.input}
        placeholder="Type to search..."
        value={query}
        onChangeText={setQuery}
      />
      <Text style={styles.message}>Search functionality coming soon.</Text>
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
    marginBottom: 24,
  },
  input: {
    backgroundColor: '#1a1a1a',
    color: 'white',
    borderRadius: 8,
    padding: 12,
    fontSize: 18,
    marginBottom: 24,
  },
  message: {
    color: '#8e8e8e',
    fontSize: 16,
  },
}); 