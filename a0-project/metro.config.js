const { getDefaultConfig } = require('expo/metro-config');

const config = getDefaultConfig(__dirname);

// Ensure assets are properly bundled
config.resolver.assetExts.push(
  // Adds support for additional asset types
  'png',
  'jpg',
  'jpeg',
  'gif',
  'webp'
);

// Fix for JavaScript bundling issues
config.resolver.platforms = ['ios', 'android', 'native', 'web'];
config.transformer.minifierConfig = {
  keep_fnames: true,
  mangle: {
    keep_fnames: true,
  },
};

module.exports = config;


