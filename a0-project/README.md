# Gymni Fitness App

A comprehensive fitness application built with React Native and Expo.

## 📱 Production Ready

This app is fully configured for App Store submission with:
- ✅ Production Firebase integration
- ✅ Secure API endpoints (HTTPS)
- ✅ Error boundary for crash handling
- ✅ Optimized asset bundling
- ✅ iOS build configuration
- ✅ Firestore security rules

## 🚀 Building for iOS

### Prerequisites
1. Install EAS CLI: `npm install -g eas-cli`
2. Login to Expo: `eas login`

### Build Production IPA
```bash
cd a0-project
eas build --platform ios --profile production
```

### Submit to App Store
```bash
eas submit --platform ios --profile production
```

## 🔧 Configuration

### Bundle Identifier
- iOS: `com.devinrobertsdirect.gymnifitnessappgymnifitness0998c33744a3`
- Android: `com.devinrobertsdirect.gymnifitnessapp`

### API Endpoints
- Backend: `https://gymni-testing-1-devinrobertsdir.replit.app/api`
- Firebase: Production project `gymni-fitness`

### Build Numbers
- Current iOS build: 9
- Current Android version: 9

## 📦 App Structure

```
a0-project/
├── App.tsx              # Main app entry with error boundary
├── index.ts             # Root entry point
├── app.json            # Expo configuration
├── eas.json            # EAS Build configuration
├── babel.config.js     # Babel transpiler config
├── metro.config.js     # Metro bundler config
├── screens/            # All app screens
├── config/             # Firebase & API config
├── utils/              # Utility functions
└── assets/             # Images and assets
```

## 🔐 Security

### Firebase Security Rules
Security rules are defined in `firestore-rules.txt`. To apply:
1. Open Firebase Console
2. Go to Firestore Database > Rules
3. Copy contents of `firestore-rules.txt`
4. Publish rules

### API Security
- All API calls use HTTPS
- Firebase authentication required
- Role-based access control (Admin/Manager/Client)

## 🎨 Features

- **Authentication**: Email, Google, Apple Sign-In
- **Workout Management**: Calendar scheduling, tracking
- **Challenges**: Create and participate in fitness challenges
- **Social Feed**: Share progress, like and comment
- **Video Workouts**: HIIT, Strength, Cardio, Mobility
- **Profile Management**: Edit profile, change password
- **Subscriptions**: In-app subscription management

## 📝 Development

```bash
# Install dependencies
npm install

# Start development server
npm start

# Run on iOS simulator
npm run ios

# Run on Android emulator
npm run android
```

## 🐛 Troubleshooting

### White Screen on Launch
- Ensure all assets are in `assets/` directory
- Check that `main` entry point is set in `app.json`
- Verify Firebase config is correct

### Build Failures
- Clear cache: `expo start -c`
- Reinstall: `rm -rf node_modules && npm install`
- Check EAS build logs for specific errors

### Asset Loading Issues
- Verify asset paths match `assetBundlePatterns` in app.json
- Ensure images are in PNG format
- Check metro.config.js asset extensions

## 📧 Support

For technical issues or questions, contact support through the app's Support & FAQ section.

## 🔄 Version History

### v1.0.0 (Build 9)
- Production release
- Full feature set
- iOS and Android support
- Firebase integration
- Secure API integration

---

**Built with ❤️ for fitness enthusiasts**



