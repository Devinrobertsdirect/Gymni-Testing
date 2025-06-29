# Replit Deployment Guide for TestFlight

## Great Choice! 🎉
Replit is perfect for deploying your Laravel backend quickly and easily.

## Step 1: Get Your Replit API URL
1. Go to your Replit project
2. Look for the "Webview" tab or "Run" button
3. Your API URL will be something like:
   - `https://your-project-name.your-username.repl.co`
   - Or `https://your-project-name.repl.co`

## Step 2: Test Your API Endpoints
Test these endpoints to make sure they work:
- `https://your-replit-url.repl.co/api/login`
- `https://your-replit-url.repl.co/api/signup`
- `https://your-replit-url.repl.co/api/get_profile`

## Step 3: Update Frontend Configuration
Update `a0-project (2)/a0-project/config/api.ts`:
```typescript
export const BASE_API_URL = 'https://your-replit-url.repl.co/api';
```

## Step 4: Build for TestFlight
```bash
cd "a0-project (2)/a0-project"

# Install Expo CLI if not installed
npm install -g @expo/cli

# Login to Expo
expo login

# Build for iOS
npx eas build --platform ios
```

## Step 5: Submit to TestFlight
1. Go to [App Store Connect](https://appstoreconnect.apple.com)
2. Create app "Gymni Fitness"
3. Upload your build
4. Add testers
5. Test!

## Replit Advantages:
- ✅ Free hosting
- ✅ Automatic HTTPS
- ✅ Easy to update
- ✅ Built-in database options
- ✅ Great for testing

## Common Replit Issues & Solutions:
- **Database**: Use Replit's built-in SQLite or connect external MySQL
- **Environment Variables**: Set them in Replit's Secrets tab
- **File Permissions**: Replit handles this automatically
- **CORS**: Configure in your Laravel app for mobile access

## Next Steps:
1. Get your Replit URL
2. Update frontend API configuration
3. Build and submit to TestFlight
4. Test on real devices!

Your backend is now live on Replit! 🚀 