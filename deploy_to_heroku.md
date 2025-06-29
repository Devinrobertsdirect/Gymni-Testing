# Quick Heroku Deployment for TestFlight

## Step 1: Install Heroku CLI
1. Download from: https://devcenter.heroku.com/articles/heroku-cli
2. Install and login: `heroku login`

## Step 2: Create Heroku App
```bash
# In your backend directory
heroku create gymni-fitness-backend
```

## Step 3: Add Database
```bash
heroku addons:create heroku-postgresql:hobby-dev
```

## Step 4: Configure Environment
```bash
heroku config:set APP_KEY=$(php artisan key:generate --show)
heroku config:set APP_ENV=production
heroku config:set APP_DEBUG=false
```

## Step 5: Deploy
```bash
git add .
git commit -m "Prepare for Heroku deployment"
git push heroku master
```

## Step 6: Setup Database
```bash
heroku run php artisan migrate
```

## Step 7: Get Your API URL
Your backend will be available at: `https://gymni-fitness-backend.herokuapp.com`

## Step 8: Update Frontend
Update `a0-project (2)/a0-project/config/api.ts`:
```typescript
export const BASE_API_URL = 'https://gymni-fitness-backend.herokuapp.com/api';
```

## Step 9: Build for TestFlight
```bash
cd "a0-project (2)/a0-project"
expo build:ios
```

## Step 10: Submit to TestFlight
1. Go to App Store Connect
2. Upload your build
3. Add testers
4. Test your app!

## Alternative: Use Railway (Even Faster)
1. Go to https://railway.app
2. Connect your GitHub/Bitbucket repo
3. Deploy automatically
4. Get your API URL
5. Update frontend and build 