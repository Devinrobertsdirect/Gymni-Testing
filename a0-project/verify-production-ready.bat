@echo off
setlocal enabledelayedexpansion

echo ========================================
echo   Production Readiness Verification
echo ========================================
echo.

cd /d "%~dp0"

set PASSED=0
set FAILED=0

echo Checking a0-project folder...
echo.

REM Check critical files
set "files=package.json app.json eas.json index.ts App.tsx babel.config.js metro.config.js"
for %%f in (%files%) do (
    if exist "%%f" (
        echo [PASS] %%f exists
        set /a PASSED+=1
    ) else (
        echo [FAIL] %%f is missing!
        set /a FAILED+=1
    )
)

echo.
echo Checking config files...
echo.

set "configFiles=config\firebase.ts config\api.ts config\workoutAssets.ts"
for %%f in (%configFiles%) do (
    if exist "%%f" (
        echo [PASS] %%f exists
        set /a PASSED+=1
    ) else (
        echo [FAIL] %%f is missing!
        set /a FAILED+=1
    )
)

echo.
echo Checking assets...
echo.

set "assetFiles=assets\icon.png assets\splash-icon.png assets\adaptive-icon.png"
for %%f in (%assetFiles%) do (
    if exist "%%f" (
        echo [PASS] %%f exists
        set /a PASSED+=1
    ) else (
        echo [FAIL] %%f is missing!
        set /a FAILED+=1
    )
)

REM Check workout images
if exist "assets\workout-images" (
    echo [PASS] workout-images directory exists
    set /a PASSED+=1
    
    dir /b "assets\workout-images\*.png" >nul 2>&1
    if !ERRORLEVEL! EQU 0 (
        echo [PASS] Workout images found
        set /a PASSED+=1
    ) else (
        echo [WARN] No workout images found
    )
) else (
    echo [FAIL] workout-images directory missing!
    set /a FAILED+=1
)

echo.
echo Checking Firebase files...
echo.

if exist "GoogleService-Info.plist" (
    echo [PASS] iOS Google Services file exists
    set /a PASSED+=1
) else (
    echo [FAIL] GoogleService-Info.plist missing!
    set /a FAILED+=1
)

if exist "google-services.json" (
    echo [PASS] Android Google Services file exists
    set /a PASSED+=1
) else (
    echo [WARN] google-services.json missing (Android only)
)

echo.
echo Checking package.json content...
findstr /C:"expo start" package.json >nul 2>&1
if !ERRORLEVEL! EQU 0 (
    echo [PASS] Expo scripts configured
    set /a PASSED+=1
) else (
    echo [FAIL] Expo scripts not found in package.json!
    set /a FAILED+=1
)

echo.
echo Checking app.json content...
findstr /C:"\"main\"" app.json >nul 2>&1
if !ERRORLEVEL! EQU 0 (
    echo [PASS] Main entry point configured
    set /a PASSED+=1
) else (
    echo [FAIL] Main entry point not set in app.json!
    set /a FAILED+=1
)

findstr /C:"bundleIdentifier" app.json >nul 2>&1
if !ERRORLEVEL! EQU 0 (
    echo [PASS] iOS bundle identifier configured
    set /a PASSED+=1
) else (
    echo [FAIL] Bundle identifier not set!
    set /a FAILED+=1
)

echo.
echo ========================================
echo   VERIFICATION RESULTS
echo ========================================
echo.
echo Tests Passed: %PASSED%
echo Tests Failed: %FAILED%
echo.

if %FAILED% EQU 0 (
    echo ✓ ALL CHECKS PASSED!
    echo.
    echo Your app is PRODUCTION READY! 🚀
    echo.
    echo Next steps:
    echo   1. Run: build-and-submit.bat
    echo   2. Or manually: eas build --platform ios --profile production
    echo.
) else (
    echo ✗ SOME CHECKS FAILED!
    echo.
    echo Please fix the issues above before building.
    echo.
)

echo ========================================
pause




