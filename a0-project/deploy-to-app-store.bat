@echo off
echo ========================================
echo   Deploy Gymni Fitness to App Store
echo ========================================
echo.

cd /d "%~dp0"

echo Current directory: %CD%
echo.
echo This will:
echo   1. Build production iOS IPA (15-20 minutes)
echo   2. Submit to App Store Connect automatically
echo.

REM Check if EAS CLI is installed
where eas >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: EAS CLI not found!
    echo.
    echo Installing EAS CLI globally...
    call npm install -g eas-cli
    
    if %ERRORLEVEL% NEQ 0 (
        echo.
        echo ERROR: Failed to install EAS CLI!
        echo Please run manually: npm install -g eas-cli
        pause
        exit /b 1
    )
    echo ✓ EAS CLI installed!
)

echo.
echo ========================================
echo   Step 1: Building iOS Production App
echo ========================================
echo.
echo This will take 15-20 minutes...
echo You can monitor progress at: https://expo.dev
echo.

call eas build --platform ios --profile production --non-interactive

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo ========================================
    echo   Build Failed!
    echo ========================================
    echo.
    echo Common issues:
    echo   - Not logged into Expo (run: eas login)
    echo   - Network connection issues
    echo   - Configuration errors
    echo.
    echo Check the error message above for details.
    echo.
    pause
    exit /b 1
)

echo.
echo ========================================
echo   ✓ Build Completed Successfully!
echo ========================================
echo.

echo.
echo ========================================
echo   Step 2: Submitting to App Store Connect
echo ========================================
echo.

call eas submit --platform ios --profile production --non-interactive

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo ========================================
    echo   Submission Failed!
    echo ========================================
    echo.
    echo The build was successful, but submission failed.
    echo You can submit manually later using:
    echo   eas submit --platform ios --profile production
    echo.
    echo Or download the IPA from https://expo.dev and submit via App Store Connect
    echo.
    pause
    exit /b 1
)

echo.
echo ========================================
echo   ✓ SUCCESS! Submitted to App Store Connect
echo ========================================
echo.
echo Next steps:
echo   1. Go to App Store Connect: https://appstoreconnect.apple.com
echo   2. Wait for Apple to process your build (15-30 minutes)
echo   3. Select your build in TestFlight
echo   4. Fill in App Store submission details
echo   5. Submit for review
echo.
echo Your app is now in Apple's hands!
echo.
echo ========================================
pause








