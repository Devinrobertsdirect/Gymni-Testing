import { View, Text, StyleSheet, TouchableOpacity, ScrollView, TextInput } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { LinearGradient } from 'expo-linear-gradient';
import { AntDesign, Ionicons } from '@expo/vector-icons';
import { useState } from 'react';

const plans = {
  premium: {
    name: 'Premium Plan',
    price: {
      monthly: 39.99,
      annual: 399.99
    },
    features: [
      'Access to social feed',
      'Share fitness content',
      'Follow friends',
      'Access to all fitness content',
      'Calendar sync',
      'Challenge friends'
    ]
  },
  gym: {
    name: 'Gym Plan',
    price: {
      annual: 999.99
    },
    features: [
      'All Premium features',
      'Annual commitment',
      'Gym partner matching',
      'Professional workout plans',
      'Priority support'
    ]
  }
};

export default function SubscriptionManagementScreen({ navigation }) {
  const [showOfferCode, setShowOfferCode] = useState(false);
  const [offerCode, setOfferCode] = useState('');
  const [showConfirmation, setShowConfirmation] = useState(null);  const [currentPlan, setCurrentPlan] = useState({
    type: 'premium',
    cycle: 'monthly'
  });
  const [showSwitchOptions, setShowSwitchOptions] = useState(false);  const handlePlanSwitch = (newPlan) => {
    const planName = plans[newPlan.type].name;
    const price = newPlan.type === 'premium' 
      ? (newPlan.cycle === 'monthly' 
        ? plans.premium.price.monthly 
        : plans.premium.price.annual)
      : plans.gym.price.annual;
    const cycle = newPlan.type === 'premium' && newPlan.cycle === 'monthly' ? 'month' : 'year';    // Show confirmation modal
    setShowConfirmation({
      plan: newPlan,
      name: planName,
      price: price,
      cycle: cycle
    });
  };

  return (
    <LinearGradient
      colors={['#1a1a1a', '#000000']}
      style={styles.container}
    >
      <SafeAreaView style={styles.content}>
        <View style={styles.header}>
          <TouchableOpacity 
            style={styles.backButton}
            onPress={() => navigation.goBack()}
          >
            <Ionicons name="chevron-back" size={24} color="white" />
            <Text style={styles.backText}>Back</Text>
          </TouchableOpacity>
          <Text style={styles.title}>Subscription</Text>
        </View>

        <ScrollView style={styles.scrollView}>
          {/* Current Plan Section */}
          <View style={styles.currentPlanSection}>
            <Text style={styles.sectionTitle}>Current Plan</Text>
            <View style={styles.planCard}>              <Text style={styles.planName}>{plans[currentPlan.type].name}</Text>            <Text style={styles.planPrice}>
                ${currentPlan.type === 'premium' ? 
                  (currentPlan.cycle === 'monthly' ? plans.premium.price.monthly : plans.premium.price.annual) :
                  plans.gym.price.annual
                }
                /{currentPlan.type === 'premium' && currentPlan.cycle === 'monthly' ? 'month' : 'year'}
              </Text>
              <View style={styles.statusBadge}>
                <Text style={styles.statusText}>Active</Text>
              </View>
            </View>

            {/* Billing Cycle Selection */}
            {currentPlan === 'premium' && (              <View style={styles.billingCycleContainer}>
                <TouchableOpacity
                  style={[
                    styles.cycleOption,
                    currentPlan.type === 'premium' && currentPlan.cycle === 'monthly' && styles.selectedCycle
                  ]}
                  onPress={() => setCurrentPlan({ type: 'premium', cycle: 'monthly' })}
                >
                  <Text style={[
                    styles.cycleText,
                    currentPlan.type === 'premium' && currentPlan.cycle === 'monthly' && styles.selectedCycleText
                  ]}>
                    Monthly
                  </Text>
                </TouchableOpacity>
                <TouchableOpacity
                  style={[
                    styles.cycleOption,
                    currentPlan.type === 'premium' && currentPlan.cycle === 'annual' && styles.selectedCycle
                  ]}
                  onPress={() => setCurrentPlan({ type: 'premium', cycle: 'annual' })}
                >
                  <Text style={[
                    styles.cycleText,
                    currentPlan.type === 'premium' && currentPlan.cycle === 'annual' && styles.selectedCycleText
                  ]}>
                    Annual
                  </Text>
                </TouchableOpacity>
              </View>
            )}

            {/* Features List */}
            <View style={styles.featuresContainer}>
              <Text style={styles.featuresTitle}>Features</Text>            {plans[currentPlan.type].features.map((feature, index) => (
                <View key={index} style={styles.featureRow}>
                  <AntDesign name="check" size={20} color="#34C759" />
                  <Text style={styles.featureText}>{feature}</Text>
                </View>
              ))}
            </View>

            {/* Switch Plan Button */}            <TouchableOpacity
              style={styles.switchPlanButton}
              onPress={() => setShowSwitchOptions(true)}
            >
              <Text style={styles.switchPlanText}>Change Plan</Text>
            </TouchableOpacity>
          </View>          {/* Switch Plan Options */}          {showSwitchOptions && (
            <View style={styles.switchOptionsContainer}>
              <Text style={styles.sectionTitle}>Available Plans</Text>
              
              {/* Show Premium Monthly if not on it */}
              {!(currentPlan.type === 'premium' && currentPlan.cycle === 'monthly') && (
                <TouchableOpacity
                  style={styles.planOption}
                  onPress={() => handlePlanSwitch({ type: 'premium', cycle: 'monthly' })}
                >
                  <View style={styles.planOptionHeader}>
                    <Text style={styles.planOptionName}>Premium Monthly</Text>
                    <Text style={styles.planOptionPrice}>
                      ${plans.premium.price.monthly}/month
                    </Text>
                  </View>
                  <View style={styles.planOptionFeatures}>
                    {plans.premium.features.map((feature, index) => (
                      <View key={index} style={styles.featureRow}>
                        <AntDesign name="check" size={16} color="#34C759" />
                        <Text style={styles.planOptionFeatureText}>{feature}</Text>
                      </View>
                    ))}
                  </View>
                </TouchableOpacity>
              )}

              {/* Show Premium Annual if not on it */}
              {!(currentPlan.type === 'premium' && currentPlan.cycle === 'annual') && (
                <TouchableOpacity
                  style={styles.planOption}
                  onPress={() => handlePlanSwitch({ type: 'premium', cycle: 'annual' })}
                >
                  <View style={styles.planOptionHeader}>
                    <Text style={styles.planOptionName}>Premium Annual</Text>
                    <Text style={styles.planOptionPrice}>
                      ${plans.premium.price.annual}/year
                    </Text>
                    <Text style={styles.savingsText}>Save 17% compared to monthly</Text>
                  </View>
                  <View style={styles.planOptionFeatures}>
                    {plans.premium.features.map((feature, index) => (
                      <View key={index} style={styles.featureRow}>
                        <AntDesign name="check" size={16} color="#34C759" />
                        <Text style={styles.planOptionFeatureText}>{feature}</Text>
                      </View>
                    ))}
                  </View>
                </TouchableOpacity>
              )}

              {/* Show Gym Plan if not on it */}
              {currentPlan.type !== 'gym' && (
                <TouchableOpacity
                  style={styles.planOption}
                  onPress={() => handlePlanSwitch({ type: 'gym', cycle: 'annual' })}
                >
                  <View style={styles.planOptionHeader}>
                    <Text style={styles.planOptionName}>Gym Plan</Text>
                    <Text style={styles.planOptionPrice}>
                      ${plans.gym.price.annual}/year
                    </Text>
                  </View>
                  <View style={styles.planOptionFeatures}>
                    {plans.gym.features.map((feature, index) => (
                      <View key={index} style={styles.featureRow}>
                        <AntDesign name="check" size={16} color="#34C759" />
                        <Text style={styles.planOptionFeatureText}>{feature}</Text>
                      </View>
                    ))}
                  </View>
                </TouchableOpacity>
              )}
            </View>
          )}
        </ScrollView>

        {/* Offer Code Modal */}
        {showOfferCode && (
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
                  onPress={() => setShowOfferCode(false)}
                >
                  <Text style={styles.modalButtonText}>Cancel</Text>
                </TouchableOpacity>
                <TouchableOpacity 
                  style={[styles.modalButton, styles.modalButtonPrimary]}                  onPress={() => {
                    if (!offerCode) {
                      toast.error('Please enter an offer code');
                      return;
                    }

                    if (offerCode.toLowerCase() === 'furbfam') {
                      // Calculate the discounted price
                      const currentPrice = showConfirmation.price;
                      const discountAmount = currentPrice * 0.50; // 50% discount
                      const discountedPrice = currentPrice - discountAmount;

                      // Hide the offer code modal
                      setShowOfferCode(false);
                      setOfferCode('');

                      // Show the confirmation modal with discounted price
                      setShowConfirmation({
                        ...showConfirmation,
                        originalPrice: currentPrice,
                        discountAmount: discountAmount,
                        finalPrice: discountedPrice,
                        discountApplied: true
                      });

                      // Show a modal with the discount details
                      toast.custom((t) => (
                        <View style={{
                          backgroundColor: '#1a1a1a',
                          borderRadius: 16,
                          padding: 20,
                          width: '90%',
                          alignSelf: 'center',
                          borderWidth: 1,
                          borderColor: '#FF9500',
                        }}>
                          <View style={{ alignItems: 'center', marginBottom: 16 }}>
                            <AntDesign name="checkcircleo" size={40} color="#34C759" />
                            <Text style={{ color: '#34C759', fontSize: 20, fontWeight: 'bold', marginTop: 8 }}>
                              Offer Code Applied!
                            </Text>
                          </View>
                          
                          <View style={{ marginBottom: 16 }}>
                            <Text style={{ color: 'white', fontSize: 16, marginBottom: 8 }}>
                              Original Price: ${currentPrice.toFixed(2)}
                            </Text>
                            <Text style={{ color: '#34C759', fontSize: 16, marginBottom: 8 }}>
                              Discount (50%): -${discountAmount.toFixed(2)}
                            </Text>
                            <Text style={{ color: '#FF9500', fontSize: 18, fontWeight: 'bold' }}>
                              Final Price: ${discountedPrice.toFixed(2)}
                            </Text>
                          </View>

                          <View style={{ marginTop: 8 }}>
                            <Text style={{ color: '#8e8e8e', fontSize: 14 }}>
                              You're now part of the Furb Family! 🎉
                            </Text>
                          </View>
                        </View>
                      ), {
                        duration: 5000,
                      });
                    } else {
                      toast.error('Invalid code', {
                        description: 'Try "furbfam" for 50% off!',
                        duration: 3000
                      });
                    }
                  }}
                >
                  <Text style={styles.modalButtonTextPrimary}>Apply</Text>
                </TouchableOpacity>
              </View>
            </View>
          </View>
        )}

        {/* Plan Switch Confirmation Modal */}
        {showConfirmation && (
          <View style={styles.modalOverlay}>
            <View style={styles.modalContent}>              <Text style={styles.modalTitle}>Confirm Plan Change</Text>
              <Text style={styles.modalSubtitle}>
                You are about to change to:
              </Text>
              <Text style={styles.planName}>{showConfirmation.name}</Text>
              <Text style={styles.planPrice}>
                ${showConfirmation.price}/{showConfirmation.cycle}
              </Text>
              
              <TouchableOpacity 
                style={styles.offerCodeButton}
                onPress={() => {
                  setShowConfirmation(null);
                  setShowOfferCode(true);
                }}
              >
                <AntDesign name="tagso" size={20} color="#FF9500" />
                <Text style={styles.offerCodeText}>Have an offer code?</Text>
              </TouchableOpacity>

              <View style={styles.modalButtons}>
                <TouchableOpacity 
                  style={styles.modalButton}
                  onPress={() => setShowConfirmation(null)}
                >
                  <Text style={styles.modalButtonText}>Cancel</Text>
                </TouchableOpacity>
                <TouchableOpacity 
                  style={[styles.modalButton, styles.modalButtonPrimary]}
                  onPress={() => {
                    setCurrentPlan(showConfirmation.plan);
                    setShowSwitchOptions(false);
                    setShowConfirmation(null);
                    toast.success(`Successfully switched to ${showConfirmation.name}`);
                  }}
                >                  <Text style={styles.modalButtonTextPrimary}>Confirm Change</Text>
                </TouchableOpacity>
              </View>
            </View>
          </View>
        )}
      </SafeAreaView>
    </LinearGradient>
  );
}

const styles = StyleSheet.create({  legalDisclosures: {
    backgroundColor: 'rgba(0,0,0,0.5)',
    borderRadius: 8,
    padding: 12,
    marginTop: 16,
    marginBottom: 8,
    marginHorizontal: 16,
  },
  legalDisclosureText: {
    color: '#8e8e8e',
    fontSize: 11,
    lineHeight: 16,
    marginBottom: 6,
    textAlign: 'left',
  },
  legalContainer: {
    marginTop: 16,
    padding: 12,
    backgroundColor: 'rgba(0, 0, 0, 0.5)',
    borderRadius: 8,
  },
  legalText: {
    color: '#8e8e8e',
    fontSize: 10,
    marginBottom: 4,
    textAlign: 'center',
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
  },  modalContent: {
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
    marginBottom: 12,
  },
  modalSubtitle: {
    color: '#8e8e8e',
    fontSize: 14,
    marginBottom: 6,
  },
  planName: {
    color: '#FF9500',
    fontSize: 20,
    fontWeight: 'bold',
    marginBottom: 6,
  },
  planPrice: {
    color: 'white',
    fontSize: 18,
    marginBottom: 16,
  },
  modalButtons: {
    flexDirection: 'row',
    gap: 12,
    marginTop: 16,
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
  },  offerInput: {
    backgroundColor: '#333',
    width: '100%',
    padding: 12,
    borderRadius: 8,
    color: 'white',
    fontSize: 14,
    marginBottom: 12,
  },
  offerCodeButton: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 6,
    padding: 10,
    backgroundColor: 'rgba(255, 149, 0, 0.1)',
    borderRadius: 8,
  },
  offerCodeText: {
    color: '#FF9500',
    fontSize: 14,
  },
  savingsText: {
    color: '#34C759',
    fontSize: 14,
    marginTop: 4,
  },
  container: {
    flex: 1,
  },
  content: {
    flex: 1,
  },
  header: {
    flexDirection: 'row',
    alignItems: 'center',
    padding: 16,
  },
  backButton: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  backText: {
    color: 'white',
    fontSize: 16,
    marginLeft: 8,
  },
  title: {
    color: 'white',
    fontSize: 20,
    fontWeight: 'bold',
    marginLeft: 16,
  },
  scrollView: {
    flex: 1,
    padding: 16,
  },
  currentPlanSection: {
    marginBottom: 24,
  },
  sectionTitle: {
    color: '#FF9500',
    fontSize: 18,
    fontWeight: '600',
    marginBottom: 16,
  },
  planCard: {
    backgroundColor: 'rgba(255, 149, 0, 0.1)',
    borderRadius: 16,
    padding: 16,
  },
  planName: {
    color: 'white',
    fontSize: 24,
    fontWeight: 'bold',
    marginBottom: 8,
  },
  planPrice: {
    color: 'white',
    fontSize: 20,
    marginBottom: 8,
  },
  statusBadge: {
    backgroundColor: '#34C759',
    alignSelf: 'flex-start',
    paddingVertical: 4,
    paddingHorizontal: 8,
    borderRadius: 12,
  },
  statusText: {
    color: 'white',
    fontSize: 12,
    fontWeight: '600',
  },
  billingCycleContainer: {
    flexDirection: 'row',
    backgroundColor: '#333',
    borderRadius: 12,
    padding: 4,
    marginTop: 16,
  },
  cycleOption: {
    flex: 1,
    paddingVertical: 8,
    alignItems: 'center',
    borderRadius: 8,
  },
  selectedCycle: {
    backgroundColor: 'rgba(255, 149, 0, 0.2)',
  },
  cycleText: {
    color: '#8e8e8e',
    fontSize: 16,
  },
  selectedCycleText: {
    color: 'white',
    fontWeight: '600',
  },
  featuresContainer: {
    marginTop: 24,
  },
  featuresTitle: {
    color: 'white',
    fontSize: 18,
    fontWeight: '600',
    marginBottom: 16,
  },  featureRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 8,
    gap: 8,
  },
  featureText: {
    color: 'white',
    fontSize: 14,
  },  switchPlanButton: {
    backgroundColor: '#FF9500',
    borderRadius: 12,
    padding: 16,
    alignItems: 'center',
    marginTop: 24,
  },
  switchPlanText: {
    color: 'white',
    fontSize: 16,
    fontWeight: '600',
  },
  switchOptionsContainer: {
    marginTop: 24,
  },
  planOption: {
    backgroundColor: '#333',
    borderRadius: 16,
    padding: 16,
    marginBottom: 16,
  },
  planOptionHeader: {
    marginBottom: 16,
  },
  planOptionName: {
    color: 'white',
    fontSize: 20,
    fontWeight: 'bold',
    marginBottom: 4,
  },
  planOptionPrice: {
    color: '#FF9500',
    fontSize: 16,
  },
  planOptionFeatures: {
    gap: 8,
  },
  planOptionFeatureText: {
    color: '#8e8e8e',
    fontSize: 14,
  },
});