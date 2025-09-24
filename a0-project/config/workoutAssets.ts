// Workout Assets Configuration
// Maps workout types to their corresponding asset files

export const WORKOUT_ASSETS = {
  cardio: require('../assets/workout-images/cardio.png'),
  strength: require('../assets/workout-images/strength.png'),
  hiit: require('../assets/workout-images/hiit.png'),
  mobility: require('../assets/workout-images/mobility.png'),
  default: require('../assets/workout-images/dumbbell.png'),
  trophy: require('../assets/workout-images/trophy.png'),
} as const;

export const getWorkoutAsset = (type: string) => {
  return WORKOUT_ASSETS[type as keyof typeof WORKOUT_ASSETS] || WORKOUT_ASSETS.default;
};

export const WORKOUT_TYPES = [
  { 
    id: 'strength', 
    name: 'Strength', 
    icon: 'barbell',
    asset: WORKOUT_ASSETS.strength,
    description: 'Build muscle and increase strength'
  },
  { 
    id: 'cardio', 
    name: 'Cardio', 
    icon: 'heart',
    asset: WORKOUT_ASSETS.cardio,
    description: 'Improve cardiovascular health'
  },
  { 
    id: 'hiit', 
    name: 'HIIT', 
    icon: 'flash',
    asset: WORKOUT_ASSETS.hiit,
    description: 'High-intensity interval training'
  },
  { 
    id: 'mobility', 
    name: 'Mobility', 
    icon: 'body',
    asset: WORKOUT_ASSETS.mobility,
    description: 'Flexibility and movement quality'
  }
] as const;
