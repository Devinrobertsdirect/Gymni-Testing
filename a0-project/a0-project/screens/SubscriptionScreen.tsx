import { View, Text, StyleSheet, TouchableOpacity, ScrollView, Image, TextInput, Modal, Linking } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { LinearGradient } from 'expo-linear-gradient';
import { AntDesign, Ionicons } from '@expo/vector-icons';
import { useState } from 'react';

const PlanFeature = ({ text }) => (
  <View style={styles.featureRow}>
    <AntDesign name="checkcircle" size={20} color="#FF9500" />
    <Text style={styles.featureText}>{text}</Text>
  </View>
);

const plans = {
  premium: {
    name: 'Premium',
    features: [
      'Access to social feed',
      'Share fitness content',
      'Follow friends',
      'Access to all fitness content',
      'Calendar sync',
      'Challenge friends'
    ],
    pricing: {
      monthly: 39.99,
      annual: 399.99
    },
    users: 1
  },
  gym: {
    name: 'Gym Plan',
    features: [
      'All Premium features',
      'Annual commitment',
      'Gym partner matching',
      'Professional workout plans',
      'Priority support'
    ],
    pricing: {
      annual: 999.99
    },
    users: 1
  }
};

export default function SubscriptionScreen({ navigation }) {
  const [selectedPlan, setSelectedPlan] = useState('premium');
  const [billingCycle, setBillingCycle] = useState('monthly');
  const [showPlans, setShowPlans] = useState(false);
  const [showOfferCode, setShowOfferCode] = useState(false);
  const [offerCode, setOfferCode] = useState('');
  const [appliedCode, setAppliedCode] = useState(null);
  const [originalPrice, setOriginalPrice] = useState(null);
  const [discountedPrice, setDiscountedPrice] = useState(null);

  const currentPlan = plans[selectedPlan];
  const price = billingCycle === 'monthly' ? 
    currentPlan.pricing.monthly : 
    currentPlan.pricing.annual;
    
  // Apply current price to original price if not set
  if (!originalPrice) {
    setOriginalPrice(price);
  }

  // Helper function to calculate discounted price
  const calculateDiscount = (code, currentPrice) => {
    if (code.toLowerCase() === 'hello10') {
      return {
        code,
        originalPrice: currentPrice,
        discountAmount: 10,
        discountType: 'fixed',
        discountedPrice: currentPrice - 10
      };
    } else if (code.toLowerCase() === 'furbfam') {
      const discount = currentPrice * 0.25; // 25% discount
      return {
        code,
        originalPrice: currentPrice,
        discountAmount: discount,
        discountType: 'percentage',
        percentage: 25,
        discountedPrice: currentPrice - discount
      };
    }
    return null;
  };
  
  // Apply the discount and show success message
  const applyDiscount = (discountInfo) => {
    setAppliedCode(discountInfo.code);
    setOriginalPrice(discountInfo.originalPrice);
    setDiscountedPrice(discountInfo.discountedPrice);
    setShowOfferCode(false);
  };

  // Handle changing plans after a discount is applied
  const handlePlanChange = (key) => {
    setSelectedPlan(key);
    setShowPlans(false);
    if (key === 'gym') setBillingCycle('annual');
    
    // Recalculate discount if a code is applied
    if (appliedCode) {
      const newPrice = key === 'gym' ? plans.gym.pricing.annual : 
        (billingCycle === 'monthly' ? plans.premium.pricing.monthly : plans.premium.pricing.annual);
      const discountInfo = calculateDiscount(appliedCode, newPrice);
      if (discountInfo) {
        applyDiscount(discountInfo);
      }
    }
  };

  // Handle changing billing cycle after a discount is applied
  const handleBillingCycleChange = (cycle) => {
    setBillingCycle(cycle);
    
    // Recalculate discount if a code is applied
    if (appliedCode) {
      const newPrice = cycle === 'monthly' ? plans.premium.pricing.monthly : plans.premium.pricing.annual;
      const discountInfo = calculateDiscount(appliedCode, newPrice);
      if (discountInfo) {
        applyDiscount(discountInfo);
      }
    }
  };

  return (
    <LinearGradient
      colors={['#1a1a1a', '#000000']}
      style={styles.container}
    >
      <SafeAreaView style={styles.content}>
        <TouchableOpacity 
          style={styles.backButton}
          onPress={() => navigation.goBack()}
        >
          <Ionicons name="chevron-back" size={24} color="white" />
          <Text style={styles.backText}>Back</Text>
        </TouchableOpacity>

        <Text style={styles.title}>Subscribe to Premium</Text>
        
        <View style={styles.trialContainer}>          
          <View style={styles.logoContainer}>
            <View style={styles.logoBox}>
              <Image
                source={{ uri: 'https://api.a0.dev/assets/image?text=modern%20minimal%20fitness%20logo%20white%20on%20transparent&aspect=1:1' }}
                style={styles.logo}
              />
            </View>
          </View>
          <Text style={styles.trialTitle}>Start Your Free Trial</Text>
          <Text style={styles.trialText}>Try all premium features free for 7 days</Text>
        </View>

        <ScrollView style={styles.scrollView} showsVerticalScrollIndicator={false}>
          <TouchableOpacity 
            style={styles.planSelector}
            onPress={() => setShowPlans(!showPlans)}
          >
            <Text style={styles.planSelectorText}>{currentPlan.name}</Text>
            <AntDesign name={showPlans ? "up" : "down"} size={20} color="white" />
          </TouchableOpacity>

          {showPlans && (
            <View style={styles.planOptions}>
              {Object.entries(plans).map(([key, plan]) => (
                <TouchableOpacity
                  key={key}
                  style={[
                    styles.planOption,
                    selectedPlan === key && styles.selectedPlanOption
                  ]}
                  onPress={() => handlePlanChange(key)}
                >
                  <Text style={styles.planOptionText}>{plan.name}</Text>
                </TouchableOpacity>
              ))}
            </View>
          )}

          <View style={styles.featuresContainer}>
            {currentPlan.features.map((feature, index) => (
              <PlanFeature key={index} text={feature} />
            ))}
          </View>

          {selectedPlan !== 'gym' && (
            <View style={styles.billingContainer}>
              <TouchableOpacity
                style={[
                  styles.billingOption,
                  billingCycle === 'monthly' && styles.selectedBillingOption
                ]}
                onPress={() => handleBillingCycleChange('monthly')}
              >
                <Text style={styles.billingOptionText}>Monthly</Text>
              </TouchableOpacity>
              <TouchableOpacity
                style={[
                  styles.billingOption,
                  billingCycle === 'annual' && styles.selectedBillingOption
                ]}
                onPress={() => handleBillingCycleChange('annual')}
              >
                <Text style={styles.billingOptionText}>Annual</Text>
              </TouchableOpacity>
            </View>
          )}

          {/* Display applied offer code if any */}
          {appliedCode && (
            <View style={styles.appliedCodeContainer}>
              <AntDesign name="checkcircle" size={16} color="#34C759" />
              <Text style={styles.appliedCodeText}>
                Offer code applied: {appliedCode}
              </Text>
            </View>
          )}

          <View style={styles.priceContainer}>
            {appliedCode ? (
              <>
                <Text style={styles.originalPriceStrikethrough}>${originalPrice.toFixed(2)}</Text>
                <Text style={styles.discountedPrice}>${discountedPrice.toFixed(2)}</Text>
              </>
            ) : (
              <Text style={styles.priceAmount}>${price}</Text>
            )}
            <Text style={styles.pricePeriod}>
              {billingCycle === 'monthly' ? '/month' : '/year'}
            </Text>
          </View>

          <View style={styles.userInfo}>
            <Text style={styles.userInfoText}>
              {currentPlan.users} {currentPlan.users === 1 ? 'user' : 'users'}
            </Text>
            <Text style={styles.userInfoText}>Auto-renewal</Text>
          </View>
        </ScrollView>

        <View style={styles.bottomContainer}>          
          <TouchableOpacity 
            style={styles.startTrialButton}
            onPress={() => navigation.navigate('SubscriptionSuccess')}
          >
            <Text style={styles.startTrialText}>Start Free Trial</Text>
          </TouchableOpacity>          
          
          <TouchableOpacity 
            style={styles.applyCodeButton}
            onPress={() => setShowOfferCode(true)}
          >
            <View style={styles.offerCodeContainer}>
              <AntDesign name="tagso" size={24} color="#FF9500" />
              <Text style={styles.applyCodeText}>Have an offer code?</Text>
            </View>
          </TouchableOpacity>
          
          <View style={styles.legalContainer}>
            <View style={styles.disclosuresContainer}>
              <Text style={styles.disclosureText}>
                Payment charged to Apple ID • Auto-renews unless cancelled 24h before period ends
              </Text>
              <Text style={styles.disclosureText}>
                ${appliedCode ? discountedPrice.toFixed(2) : price.toFixed(2)} {billingCycle === 'monthly' ? 'monthly' : 'annual'} renewal • Manage in App Store
              </Text>
              
              <Text style={styles.trialTermsText}>
                Trial ends {new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toLocaleDateString()} • Then ${appliedCode ? discountedPrice.toFixed(2) : price.toFixed(2)}/{billingCycle === 'monthly' ? 'mo' : 'yr'}
              </Text>
            </View>

            <View style={styles.termsContainer}>
              <TouchableOpacity onPress={() => Linking.openURL('https://www.gymnifitness.com/terms-and-conditions')}>
                <Text style={styles.legalText}>Terms</Text>
              </TouchableOpacity>
              <Text style={styles.legalDivider}>•</Text>
              <TouchableOpacity onPress={() => Linking.openURL('https://www.gymnifitness.com/privacy-policy')}>
                <Text style={styles.legalText}>Privacy</Text>
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </SafeAreaView>
      
      {/* Offer Code Modal */}
      <Modal
        animationType="slide"
        transparent={true}
        visible={showOfferCode}
        onRequestClose={() => setShowOfferCode(false)}
      >
        <View style={styles.modalOverlay}>
          <View style={styles.modalContent}>
            <Text style={styles.modalTitle}>Enter Offer Code</Text>
            <TextInput
              style={styles.offerInput}
              placeholder="Enter your code"
              placeholderTextColor="#8e8e8e"
              value={offerCode}
              onChangeText={setOfferCode}
              autoCapitalize="characters"
            />
            <View style={styles.modalButtons}>
              <TouchableOpacity 
                style={styles.modalButton}
                onPress={() => {
                  setOfferCode('');
                  setShowOfferCode(false);
                }}
              >
                <Text style={styles.modalButtonText}>Cancel</Text>
              </TouchableOpacity>
              <TouchableOpacity 
                style={[styles.modalButton, styles.modalButtonPrimary]}
                onPress={() => {
                  if (!offerCode) {
                    alert('Please enter an offer code');
                    return;
                  }
                  
                  const discountInfo = calculateDiscount(offerCode, price);
                  
                  if (discountInfo) {
                    applyDiscount(discountInfo);
                    
                    // Show success message
                    alert(`${offerCode.toUpperCase()} applied successfully!\n\nOriginal price: $${discountInfo.originalPrice.toFixed(2)}\nDiscount: $${discountInfo.discountAmount.toFixed(2)}\nNew price: $${discountInfo.discountedPrice.toFixed(2)}`);
                  } else {
                    alert('Invalid code. Try "hello10" for $10 off or "furbfam" for 25% off!');
                  }
                }}
              >
                <Text style={styles.modalButtonTextPrimary}>Apply</Text>
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </Modal>
    </LinearGradient>
  );
}

const styles = StyleSheet.create({
  appliedCodeContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
    backgroundColor: 'rgba(52, 199, 89, 0.1)',
    padding: 12,
    borderRadius: 12,
    marginBottom: 16,
  },
  appliedCodeText: {
    color: '#34C759',
    fontSize: 14,
    fontWeight: '500',
  },
  originalPriceStrikethrough: {
    fontSize: 24,
    color: '#8e8e8e',
    textDecorationLine: 'line-through',
    marginRight: 8,
  },
  discountedPrice: {
    fontSize: 32,
    fontWeight: 'bold',
    color: '#34C759',
  },
  modalOverlay: {
    position: 'absolute',
    top: 0,
    left: 0,
    right: 0,
    bottom: 0,
    backgroundColor: 'rgba(0, 0, 0, 0.7)',
    justifyContent: 'center',
    alignItems: 'center',
    padding: 24,
  },
  modalContent: {
    backgroundColor: '#1a1a1a',
    borderRadius: 12,
    padding: 16,
    width: '100%',
    alignItems: 'center',
  },
  modalTitle: {
    color: 'white',
    fontSize: 18,
    fontWeight: 'bold',
    marginBottom: 16,
  },
  offerInput: {
    backgroundColor: '#333',
    width: '100%',
    padding: 12,
    borderRadius: 8,
    color: 'white',
    fontSize: 16,
    marginBottom: 16,
  },
  modalButtons: {
    flexDirection: 'row',
    gap: 12,
    width: '100%',
  },
  modalButton: {
    flex: 1,
    padding: 12,
    borderRadius: 8,
    alignItems: 'center',
    backgroundColor: '#333',
  },
  modalButtonPrimary: {
    backgroundColor: '#FF9500',
  },
  modalButtonText: {
    color: '#8e8e8e',
    fontSize: 16,
    fontWeight: '600',
  },
  modalButtonTextPrimary: {
    color: 'white',
    fontSize: 16,
    fontWeight: '600',
  },
  disclosuresContainer: {
    backgroundColor: 'rgba(0, 0, 0, 0.5)',
    padding: 8,
    borderRadius: 8,
    marginBottom: 8,
  },
  disclosureText: {
    color: '#8e8e8e',
    fontSize: 10,
    textAlign: 'center',
    marginBottom: 4,
  },
  trialTermsText: {
    color: '#FF9500',
    fontSize: 11,
    textAlign: 'center',
    marginTop: 4,
  },
  termsContainer: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    gap: 8,
    marginBottom: 8,
  },
  container: {
    flex: 1,
  },
  content: {
    flex: 1,
    paddingHorizontal: 24,
  },
  backButton: {
    flexDirection: 'row',
    alignItems: 'center',
    marginTop: 8,
  },
  backText: {
    color: 'white',
    fontSize: 16,
    marginLeft: 4,
  },
  title: {
    fontSize: 28,
    fontWeight: 'bold',
    color: 'white',
    marginTop: 20,
    marginBottom: 24,
  },
  trialContainer: {
    backgroundColor: 'transparent',
    borderRadius: 16,
    padding: 20,
    alignItems: 'center',
    marginBottom: 24,
  },
  logoContainer: {
    marginBottom: 12,
  },
  logoBox: {
    width: 80,
    height: 80,
    backgroundColor: 'rgba(255, 149, 0, 0.1)',
    borderRadius: 20,
    padding: 12,
    justifyContent: 'center',
    alignItems: 'center',
  },
  logo: {
    width: 60,
    height: 60,
    resizeMode: 'contain',
  },
  trialTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#FF9500',
    marginBottom: 8,
  },
  trialText: {
    color: '#FF9500',
    textAlign: 'center',
  },
  scrollView: {
    flex: 1,
  },
  planSelector: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: '#333333',
    padding: 16,
    borderRadius: 12,
    marginBottom: 8,
  },
  planSelectorText: {
    color: 'white',
    fontSize: 18,
    fontWeight: '600',
  },
  planOptions: {
    backgroundColor: '#333333',
    borderRadius: 12,
    marginBottom: 16,
    overflow: 'hidden',
  },
  planOption: {
    padding: 16,
    borderBottomWidth: 1,
    borderBottomColor: '#444444',
  },
  selectedPlanOption: {
    backgroundColor: 'rgba(255, 149, 0, 0.1)',
  },
  planOptionText: {
    color: 'white',
    fontSize: 16,
  },
  featuresContainer: {
    gap: 12,
    marginBottom: 24,
  },
  featureRow: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 12,
  },
  featureText: {
    color: 'white',
    fontSize: 16,
    flex: 1,
  },
  billingContainer: {
    flexDirection: 'row',
    backgroundColor: '#333333',
    borderRadius: 12,
    padding: 4,
    marginBottom: 24,
  },
  billingOption: {
    flex: 1,
    padding: 12,
    borderRadius: 8,
    alignItems: 'center',
  },
  selectedBillingOption: {
    backgroundColor: 'rgba(255, 149, 0, 0.2)',
  },
  billingOptionText: {
    color: 'white',
    fontSize: 16,
    fontWeight: '500',
  },
  priceContainer: {
    flexDirection: 'row',
    alignItems: 'baseline',
    justifyContent: 'center',
    marginBottom: 8,
  },
  priceAmount: {
    fontSize: 32,
    fontWeight: 'bold',
    color: 'white',
  },
  pricePeriod: {
    fontSize: 16,
    color: '#8e8e8e',
    marginLeft: 4,
  },
  userInfo: {
    flexDirection: 'row',
    justifyContent: 'center',
    gap: 16,
    marginBottom: 24,
  },
  userInfoText: {
    color: '#8e8e8e',
    fontSize: 14,
  },
  bottomContainer: {
    gap: 12,
    marginBottom: 24,
  },
  startTrialButton: {
    backgroundColor: '#FF9500',
    padding: 16,
    borderRadius: 12,
    alignItems: 'center',
  },
  startTrialText: {
    color: 'white',
    fontSize: 16,
    fontWeight: '600',
  },
  applyCodeButton: {
    alignItems: 'center',
    backgroundColor: 'rgba(255, 149, 0, 0.1)',
    padding: 16,
    borderRadius: 12,
  },
  offerCodeContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
  },
  applyCodeText: {
    color: '#FF9500',
    fontSize: 16,
    fontWeight: '600',
  },
  legalContainer: {
    flexDirection: 'column',
    alignItems: 'center',
    width: '100%',
  },
  legalText: {
    color: '#8e8e8e',
    fontSize: 14,
  },
  legalDivider: {
    color: '#8e8e8e',
    fontSize: 14,
  },
});