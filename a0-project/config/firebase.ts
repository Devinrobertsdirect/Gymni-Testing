import { initializeApp } from 'firebase/app';
import { getAuth } from 'firebase/auth';
import { getFirestore } from 'firebase/firestore';
import { getStorage } from 'firebase/storage';

const firebaseConfig = {
  apiKey: 'AIzaSyDARuAmiDKTAOtwRR3VMwFd__ehGkRXypw',
  authDomain: 'gymni-fitness.firebaseapp.com',
  projectId: 'gymni-fitness',
  storageBucket: 'gymni-fitness.appspot.com',
  messagingSenderId: '796288733107',
  appId: '1:796288733107:web:99e83c111ad841d8a605c6',
  measurementId: 'G-J7FY0W6ZC8'
};

export const app = initializeApp(firebaseConfig);
export const auth = getAuth(app);
export const db = getFirestore(app);
export const storage = getStorage(app);
