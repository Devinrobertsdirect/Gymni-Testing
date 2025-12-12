@echo off
echo ========================================
echo   Gymni Fitness - iOS Build & Submit
echo ========================================
echo.

cd /d "%~dp0"

echo Current directory: %CD%
echo.

echo [1/3] Checking prerequisites...
where eas >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: EAS CLI not found. Installing...
    npm install -g eas-cli
)
echo ✓ EAS CLI found
echo.

echo [2/3] Building iOS production app...
echo This will take 10-20 minutes...
echo.
eas build --platform ios --profile production --non-interactive

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo ERROR: Build failed!
    echo Check the error messages above.
    pause
    exit /b 1
)

echo.
echo ✓ Build completed successfully!
echo.
echo The IPA file has been built and is ready for download.
echo You can download it from: https://expo.dev/accounts/devinrobertsdirect/projects/gymnifitnessapp-gymnifitness-0998c33744a3/builds
echo.

set /p SUBMIT="Do you want to submit to App Store now? (y/n): "
if /i "%SUBMIT%"=="y" (
    echo.
    echo [3/3] Submitting to App Store...
    eas submit --platform ios --profile production --non-interactive
    
    if %ERRORLEVEL% EQU 0 (
        echo.
        echo ✓ Successfully submitted to App Store!
        echo Check App Store Connect for processing status.
    ) else (
        echo.
        echo ERROR: Submission failed!
        echo You can submit manually later using: eas submit --platform ios --profile production
    )
) else (
    echo.
    echo Skipping submission. You can submit later using:
    echo   eas submit --platform ios --profile production
)

echo.
echo ========================================
echo Build process complete!
echo ========================================
pause








