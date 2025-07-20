import React from 'react';
import { TouchableOpacity, StyleSheet } from 'react-native';
import { Ionicons } from '@expo/vector-icons';

const HamburgerMenuButton = ({ navigation }: { navigation: any }) => (
  <TouchableOpacity
    style={styles.menuButton}
    onPress={() => navigation.navigate('Menu')}
    accessibilityLabel="Open menu"
  >
    <Ionicons name="menu" size={28} color="white" />
  </TouchableOpacity>
);

const styles = StyleSheet.create({
  menuButton: {
    padding: 8,
    marginRight: 8,
  },
});

export default HamburgerMenuButton; 