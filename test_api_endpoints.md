# Test Your Replit API Endpoints

## Your API URL: https://Gymni-Testing.devinrobertsdir.repl.co

## Test These Endpoints:

### 1. Login Endpoint
**URL:** `https://Gymni-Testing.devinrobertsdir.repl.co/api/login`
**Method:** POST
**Test with:** Postman, curl, or browser

### 2. Signup Endpoint
**URL:** `https://Gymni-Testing.devinrobertsdir.repl.co/api/signup`
**Method:** POST

### 3. Get Profile Endpoint
**URL:** `https://Gymni-Testing.devinrobertsdir.repl.co/api/get_profile`
**Method:** POST

### 4. Get Fitness Content
**URL:** `https://Gymni-Testing.devinrobertsdir.repl.co/api/get_fitness`
**Method:** POST

## Quick Test Commands:

### Using curl (in terminal):
```bash
# Test if API is responding
curl -X POST https://Gymni-Testing.devinrobertsdir.repl.co/api/login

# Test with data
curl -X POST https://Gymni-Testing.devinrobertsdir.repl.co/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password"}'
```

### Using Browser:
1. Go to: `https://Gymni-Testing.devinrobertsdir.repl.co/api/login`
2. You should see a JSON response (even if it's an error, that means the API is working)

## Expected Responses:
- ✅ **Working API**: JSON response (even error messages mean it's working)
- ❌ **Not Working**: "Page not found" or connection error

## If API is Working:
Your frontend is now configured and ready to build for TestFlight!

## If API is Not Working:
1. Check your Replit project is running
2. Make sure the Laravel app is properly configured
3. Check environment variables in Replit Secrets

## Next Step:
Once API is confirmed working, build for TestFlight:
```bash
cd "a0-project (2)/a0-project"
npx eas build --platform ios
``` 