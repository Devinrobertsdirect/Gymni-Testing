# TestFlight & Google Play Console Deployment Guide

## Prerequisites
- Apple Developer Account ($99/year)
- Google Play Console Account ($25 one-time)
- Expo account (free)

## Step 1: Deploy Backend (Choose One)

### Option A: Heroku (Recommended)
```bash
# Install Heroku CLI
# Download from: https://devcenter.heroku.com/articles/heroku-cli

# Login and create app
heroku login
heroku create gymni-fitness-backend
heroku addons:create heroku-postgresql:hobby-dev

# Deploy
git add .
git commit -m "Deploy to Heroku"
git push heroku master

# Setup database
heroku run php artisan migrate

# Get your URL
heroku info
# Your API will be: https://gymni-fitness-backend.herokuapp.com
```

### Option B: Railway (Easiest)
1. Go to https://railway.app
2. Connect your GitHub/Bitbucket repo
3. Deploy automatically
4. Get your API URL

## Step 2: Update Frontend API URL

Update `a0-project (2)/a0-project/config/api.ts`:
```typescript
export const BASE_API_URL = 'https://your-deployed-backend.com/api';
```

## Step 3: Build for iOS (TestFlight)

```bash
cd "a0-project (2)/a0-project"

# Install Expo CLI
npm install -g @expo/cli

# Login to Expo
expo login

# Build for iOS
expo build:ios

# Or use EAS Build (recommended)
npx eas build --platform ios
```

## Step 4: Submit to TestFlight

1. **Go to App Store Connect**: https://appstoreconnect.apple.com
2. **Create App**: 
   - App Name: "Gymni Fitness"
   - Bundle ID: com.yourcompany.gymnifitness
   - SKU: gymni-fitness-001
3. **Upload Build**:
   - Use the build from Expo
   - Add build to TestFlight
4. **Add Testers**:
   - Internal testers (up to 100)
   - External testers (up to 10,000)
5. **Test**:
   - Install TestFlight app on your device
   - Accept invitation and test your app

## Step 5: Build for Android (Google Play Console)

```bash
# Build for Android
expo build:android

# Or use EAS Build
npx eas build --platform android
```

## Step 6: Submit to Google Play Console

1. **Go to Google Play Console**: https://play.google.com/console
2. **Create App**:
   - App name: "Gymni Fitness"
   - Package name: com.yourcompany.gymnifitness
3. **Upload APK/AAB**:
   - Use the build from Expo
   - Upload to internal testing
4. **Add Testers**:
   - Internal testers (up to 100)
   - Closed testing (up to 2,000)
5. **Test**:
   - Download from Google Play Console
   - Test on Android device

## Step 7: Production App Store Submission

### iOS App Store
1. Complete app metadata
2. Add screenshots and descriptions
3. Submit for review
4. Wait for Apple's approval (1-7 days)

### Google Play Store
1. Complete store listing
2. Add screenshots and descriptions
3. Submit for review
4. Wait for Google's approval (1-3 days)

## Testing Checklist

### Backend Testing
- [ ] All API endpoints work
- [ ] Authentication works
- [ ] Database operations work
- [ ] File uploads work
- [ ] Error handling works

### Frontend Testing
- [ ] Login/registration works
- [ ] All screens load properly
- [ ] API calls succeed
- [ ] Offline handling works
- [ ] App performance is good

## Common Issues & Solutions

### Backend Issues
- **CORS errors**: Configure CORS in Laravel
- **Database connection**: Check credentials
- **File uploads**: Check storage permissions

### Frontend Issues
- **Build failures**: Check Expo configuration
- **API connection**: Verify URL and SSL
- **App crashes**: Check error logs

## Cost Breakdown
- **Apple Developer**: $99/year
- **Google Play Console**: $25 one-time
- **Backend Hosting**: $0-50/month (depending on traffic)
- **Expo**: Free for basic builds

## Next Steps After Testing
1. Fix any issues found during testing
2. Optimize performance
3. Add analytics and crash reporting
4. Prepare for production release
5. Set up monitoring and alerts 