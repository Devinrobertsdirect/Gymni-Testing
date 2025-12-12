@echo off
echo.
echo ========================================
echo   DEPLOYING TO APP STORE CONNECT
echo ========================================
echo.
echo Starting deployment process...
echo.

cd /d "%~dp0"

echo Checking EAS CLI...
where eas >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo EAS CLI not found. Installing...
    call npm install -g eas-cli
    if %ERRORLEVEL% NEQ 0 (
        echo.
        echo ERROR: Could not install EAS CLI.
        echo Please run manually: npm install -g eas-cli
        echo Then run this script again.
        pause
        exit /b 1
    )
)

echo.
echo ========================================
echo   STEP 1: BUILDING iOS APP
echo ========================================
echo.
echo This will take 15-20 minutes...
echo.

call eas build --platform ios --profile production

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo BUILD FAILED!
    echo.
    echo Please check:
    echo   1. Are you logged in? Run: eas login
    echo   2. Check your internet connection
    echo   3. Review error message above
    echo.
    pause
    exit /b 1
)

echo.
echo ========================================
echo   BUILD SUCCESSFUL!
echo ========================================
echo.

set /p SUBMIT="Submit to App Store Connect now? (y/n): "
if /i not "%SUBMIT%"=="y" (
    echo.
    echo Build complete. IPA available at: https://expo.dev
    echo You can submit later with: eas submit --platform ios --profile production
    pause
    exit /b 0
)

echo.
echo ========================================
echo   STEP 2: SUBMITTING TO APP STORE
echo ========================================
echo.

call eas submit --platform ios --profile production

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo SUBMISSION FAILED!
    echo.
    echo Your build was successful. You can:
    echo   1. Try again: eas submit --platform ios --profile production
    echo   2. Download IPA from expo.dev and submit manually
    echo.
    pause
    exit /b 1
)

echo.
echo ========================================
echo   SUCCESS! DEPLOYED TO APP STORE!
echo ========================================
echo.
echo Your app has been submitted to App Store Connect!
echo.
echo Next steps:
echo   1. Go to https://appstoreconnect.apple.com
echo   2. Wait 15-30 min for Apple to process
echo   3. Check TestFlight for your build
echo   4. Submit for App Store review
echo.
echo Congratulations! 🎉
echo.
pause








