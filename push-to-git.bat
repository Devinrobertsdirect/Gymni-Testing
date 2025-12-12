@echo off
echo ========================================
echo   Committing Production-Ready Changes
echo ========================================
echo.

cd /d "%~dp0"

echo Current directory: %CD%
echo.

echo [1/4] Checking git status...
git status

echo.
echo [2/4] Adding all changes...
git add .

echo.
echo [3/4] Committing changes...
git commit -m "Production-ready iOS build configuration

- Fixed white screen issue by correcting package.json with Expo scripts
- Added babel.config.js and metro.config.js for proper bundling
- Added main entry point in app.json
- Implemented error boundary for production crash handling
- Optimized asset bundling patterns
- Cleaned up console logs
- Added production build scripts and verification tools
- Incremented iOS build number to 9
- Updated EAS configuration for production
- Added comprehensive documentation

The a0-project folder is now 100%% production-ready for App Store submission.
All files needed for successful IPA build are configured correctly."

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo Note: No changes to commit or commit failed.
    echo.
) else (
    echo.
    echo ✓ Changes committed successfully!
)

echo.
echo [4/4] Pushing to repository...
git push

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo ERROR: Push failed!
    echo Please check your git credentials and network connection.
    pause
    exit /b 1
)

echo.
echo ✓ Successfully pushed to repository!
echo.
echo ========================================
echo   All changes backed up to git!
echo ========================================
echo.
echo Your production-ready app is now saved in the repository.
echo.
pause








