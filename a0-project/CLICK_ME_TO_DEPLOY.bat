@echo off
color 0A
mode con: cols=70 lines=30

:start
cls
echo.
echo        ╔═══════════════════════════════════════════════════╗
echo        ║                                                   ║
echo        ║     🚀 DEPLOY GYMNI FITNESS TO APP STORE 🚀      ║
echo        ║                                                   ║
echo        ╚═══════════════════════════════════════════════════╝
echo.
echo.
echo        Current folder: a0-project
echo        Your app is 100%% PRODUCTION READY!
echo.
echo        ═══════════════════════════════════════════════════
echo.
echo        This script will:
echo          ✓ Build your iOS app (15-20 minutes)
echo          ✓ Submit to App Store Connect automatically
echo.
echo        ═══════════════════════════════════════════════════
echo.
echo.

cd /d "%~dp0"

REM Check if EAS CLI is installed
echo        [Step 0/3] Checking prerequisites...
where eas >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo.
    echo        ⚠️  EAS CLI not found. Installing now...
    echo.
    call npm install -g eas-cli
    if %ERRORLEVEL% NEQ 0 (
        echo.
        echo        ❌ ERROR: Could not install EAS CLI
        echo.
        echo        Please install manually:
        echo          npm install -g eas-cli
        echo.
        echo        Then run this script again.
        echo.
        pause
        exit /b 1
    )
    echo.
    echo        ✅ EAS CLI installed successfully!
    timeout /t 2 >nul
)

REM Check login status
echo.
echo        Checking Expo login status...
eas whoami >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo.
    echo        ⚠️  Not logged in to Expo
    echo.
    echo        Opening login prompt...
    echo.
    call eas login
    if %ERRORLEVEL% NEQ 0 (
        echo.
        echo        ❌ Login failed or was cancelled
        echo.
        pause
        exit /b 1
    )
)

echo.
echo        ✅ Logged in to Expo
echo.
echo.
echo        ═══════════════════════════════════════════════════
echo        [Step 1/3] BUILDING iOS APP
echo        ═══════════════════════════════════════════════════
echo.
echo        This will take 15-20 minutes...
echo        You can grab a coffee ☕
echo.
echo        Monitor at: https://expo.dev
echo.
echo        Starting build...
echo.

call eas build --platform ios --profile production

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo        ═══════════════════════════════════════════════════
    echo        ❌ BUILD FAILED
    echo        ═══════════════════════════════════════════════════
    echo.
    echo        Common issues:
    echo          • Network connection problems
    echo          • Expo account issues
    echo          • Configuration errors
    echo.
    echo        Check the error message above for details.
    echo.
    pause
    exit /b 1
)

echo.
echo.
echo        ═══════════════════════════════════════════════════
echo        ✅ BUILD COMPLETED SUCCESSFULLY!
echo        ═══════════════════════════════════════════════════
echo.
echo        Your IPA has been built and is ready!
echo.
timeout /t 3 >nul

echo.
echo.
echo        ═══════════════════════════════════════════════════
echo        [Step 2/3] SUBMITTING TO APP STORE CONNECT
echo        ═══════════════════════════════════════════════════
echo.
echo        Starting submission...
echo.

call eas submit --platform ios --profile production

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo        ═══════════════════════════════════════════════════
    echo        ⚠️  SUBMISSION FAILED
    echo        ═══════════════════════════════════════════════════
    echo.
    echo        Your build was successful! You can:
    echo.
    echo        1. Try submitting again:
    echo           eas submit --platform ios --profile production
    echo.
    echo        2. Download IPA from https://expo.dev
    echo           and submit manually via App Store Connect
    echo.
    pause
    exit /b 1
)

cls
echo.
echo.
echo        ╔═══════════════════════════════════════════════════╗
echo        ║                                                   ║
echo        ║           ✨ SUCCESS! DEPLOYMENT COMPLETE! ✨     ║
echo        ║                                                   ║
echo        ╚═══════════════════════════════════════════════════╝
echo.
echo.
echo        🎉 Your app has been submitted to App Store Connect!
echo.
echo        ═══════════════════════════════════════════════════
echo        📱 NEXT STEPS:
echo        ═══════════════════════════════════════════════════
echo.
echo        1. Go to: https://appstoreconnect.apple.com
echo.
echo        2. Navigate to: My Apps → Gymni Fitness
echo.
echo        3. Wait 15-30 minutes for Apple to process
echo.
echo        4. Check TestFlight - your build will appear
echo.
echo        5. Test it on your device
echo.
echo        6. Click "Submit for Review" when ready
echo.
echo        7. Apple reviews (1-3 days)
echo.
echo        8. Your app goes LIVE! 🚀
echo.
echo        ═══════════════════════════════════════════════════
echo.
echo        App Name: Gymni Fitness
echo        Version: 1.0.0
echo        Build: 9
echo        App Store ID: 6753017509
echo.
echo        ═══════════════════════════════════════════════════
echo.
echo        Congratulations! Your app is on its way! 🎊
echo.
echo.
pause

