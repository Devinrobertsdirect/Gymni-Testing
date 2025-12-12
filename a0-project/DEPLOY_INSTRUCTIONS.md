# 🚀 Deploy to App Store Connect - Complete Guide

## Prerequisites

1. **EAS CLI Installed**
   ```bash
   npm install -g eas-cli
   ```

2. **Logged into Expo**
   ```bash
   eas login
   ```
   Use your Expo account credentials.

3. **Apple Developer Account**
   - Must be enrolled in Apple Developer Program
   - Team ID: `MLXSWDC63Q`
   - App Store Connect access

---

## 🎯 Quick Deploy (Automated)

### Option 1: One-Click Deploy
Simply run:
```bash
deploy-to-app-store.bat
```

This script will:
- ✅ Check prerequisites
- ✅ Build iOS production IPA (15-20 min)
- ✅ Submit to App Store Connect automatically
- ✅ Provide status updates

---

## 📋 Manual Deploy (Step-by-Step)

If you prefer manual control:

### Step 1: Login to EAS
```bash
cd a0-project
eas login
```

### Step 2: Build Production iOS
```bash
eas build --platform ios --profile production
```

**What happens:**
- Build runs on Expo's cloud servers
- Takes 15-20 minutes typically
- You'll get a build URL to monitor progress
- IPA file is generated and stored

**Monitor your build:**
- URL will be displayed in terminal
- Or visit: https://expo.dev/accounts/[your-account]/projects/gymnifitnessapp-gymnifitness-0998c33744a3/builds

### Step 3: Submit to App Store Connect
```bash
eas submit --platform ios --profile production
```

**What happens:**
- EAS automatically uploads IPA to App Store Connect
- Uses your Apple credentials
- Submits to TestFlight first
- Takes 5-10 minutes

---

## 🔐 Authentication

### First Time Setup

You'll be prompted for:

1. **Apple ID**
   - Your Apple Developer account email
   - Must have access to Team ID: `MLXSWDC63Q`

2. **App-Specific Password** (if using 2FA)
   - Generate at: https://appleid.apple.com
   - Account Settings → Security → App-Specific Passwords
   - Create one named "EAS CLI" or "Expo Build"

3. **ASC App ID**
   - Already configured: `6753017509`
   - This is your app's ID in App Store Connect

---

## 📱 After Submission

### 1. App Store Connect Processing (15-30 minutes)
- Apple processes your IPA
- Checks for malware and compliance
- Generates TestFlight build

**Check status:**
https://appstoreconnect.apple.com → My Apps → Gymni Fitness → TestFlight

### 2. TestFlight Available
Once processing completes:
- Build appears in TestFlight
- You can test it on devices
- Invite internal testers
- Get feedback before public release

### 3. Submit for Review
In App Store Connect:
1. Go to "App Store" tab
2. Create new version (1.0)
3. Select the build from TestFlight
4. Fill in required information:
   - App description
   - Screenshots
   - Privacy policy
   - Support URL
   - Age rating
5. Click "Submit for Review"

### 4. Apple Review (1-3 days)
- Apple reviews your app
- Tests functionality
- Checks guidelines compliance
- May request changes

### 5. App Goes Live! 🎉
- Once approved, you choose release date
- Can release immediately or schedule
- App appears on App Store

---

## 🐛 Troubleshooting

### "Not logged in to Expo"
```bash
eas login
```
Enter your Expo credentials.

### "Build failed"
Common causes:
1. **Missing dependencies**: Run `npm install` in a0-project
2. **Configuration error**: Check `eas.json` and `app.json`
3. **Network issue**: Check internet connection
4. **Expo account issue**: Verify account on expo.dev

### "Submission failed"
Options:
1. **Retry**: Run `eas submit --platform ios --profile production` again
2. **Manual upload**: 
   - Download IPA from expo.dev
   - Upload via Transporter app
   - Or upload via App Store Connect web interface

### "Invalid Apple credentials"
1. Verify Apple ID is correct
2. Generate app-specific password if using 2FA
3. Ensure account has access to Team ID `MLXSWDC63Q`

### "Build takes too long"
- Normal build time: 15-20 minutes
- If >30 minutes: Check build logs at expo.dev
- May need to wait for Expo server queue

---

## 📊 Build Information

**Current Configuration:**
- App Name: Gymni Fitness
- Bundle ID: `com.devinrobertsdirect.gymnifitnessappgymnifitness0998c33744a3`
- Version: 1.0.0
- Build Number: 9
- Platform: iOS
- Profile: production
- Team ID: MLXSWDC63Q
- ASC App ID: 6753017509

---

## 🔄 Future Updates

When releasing updates:

1. **Update version/build number** in `a0-project/app.json`:
   ```json
   {
     "expo": {
       "version": "1.0.1",  // Increment for feature updates
       "ios": {
         "buildNumber": "10"  // Increment for each build
       }
     }
   }
   ```

2. **Build and submit**:
   ```bash
   deploy-to-app-store.bat
   ```

3. **Same process** as initial submission

---

## 📞 Support Resources

- **EAS Build Docs**: https://docs.expo.dev/build/introduction/
- **EAS Submit Docs**: https://docs.expo.dev/submit/introduction/
- **App Store Connect**: https://appstoreconnect.apple.com
- **Expo Dashboard**: https://expo.dev
- **Build Logs**: Check expo.dev for detailed logs

---

## ✅ Pre-Deployment Checklist

Before running deploy script:

- [ ] In `a0-project` directory
- [ ] EAS CLI installed (`npm install -g eas-cli`)
- [ ] Logged into Expo (`eas login`)
- [ ] Apple Developer account active
- [ ] All changes tested locally
- [ ] Version/build number updated if needed
- [ ] Firebase configured correctly
- [ ] API endpoints pointing to production

---

## 🎯 Ready to Deploy!

Everything is configured and ready. Simply run:

```bash
cd a0-project
deploy-to-app-store.bat
```

Or manually:

```bash
cd a0-project
eas build --platform ios --profile production
eas submit --platform ios --profile production
```

**Good luck with your deployment! 🚀**








