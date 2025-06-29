import React, { useState } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, ScrollView, TextInput, Linking } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { LinearGradient } from 'expo-linear-gradient';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';
import { toast } from 'sonner-native';

const FAQData = [
  {
    question: "How do I cancel my subscription?",
    answer: "You can cancel your subscription at any time through your App Store settings > Subscriptions > Gymni Fitness."
  },
  {
    question: "How do I track my progress?",
    answer: "Your progress is automatically tracked in the Profile section. You can view your workout history, achievements, and statistics there."
  },
  {
    question: "Can I download workouts for offline use?",
    answer: "Premium members can download workouts for offline viewing. Look for the download icon on any workout video."
  },
  {
    question: "How do I connect with friends?",
    answer: "Go to the Social tab and use the search function to find friends. You can also invite friends using their email or phone number."
  },
  {
    question: "What equipment do I need?",
    answer: "Each workout specifies required equipment. We offer many bodyweight-only workouts that require no equipment."
  }
];

export default function SupportFAQScreen({ navigation }) {
  const [expandedQuestion, setExpandedQuestion] = useState<number | null>(null);
  const [chatMessage, setChatMessage] = useState('');
  const [chatHistory, setChatHistory] = useState([]);
  const [isLoading, setIsLoading] = useState(false);

  const handleSendMessage = async () => {
    if (!chatMessage.trim()) return;

    const userMessage = chatMessage.trim();
    setChatMessage('');
    
    // Add user message to chat
    setChatHistory(prev => [...prev, { type: 'user', text: userMessage }]);
    setIsLoading(true);

    try {
      // Make API call to our AI support system
      const response = await fetch('https://api.a0.dev/ai/llm', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          messages: [
            { 
              role: 'system', 
              content: 'You are a helpful fitness app support assistant. Be concise and friendly.' 
            },
            { role: 'user', content: userMessage }
          ]
        })
      });

      const data = await response.json();
      
      // Add AI response to chat
      setChatHistory(prev => [...prev, { type: 'assistant', text: data.completion }]);
    } catch (error) {
      toast.error('Failed to send message', {
        description: 'Please try again later'
      });
    } finally {
      setIsLoading(false);
    }
  };

  const handleLink = (url: string) => {
    Linking.openURL(url).catch(() => {
      toast.error('Could not open link');
    });
  };

  return (
    <LinearGradient
      colors={['#1a1a1a', '#000000']}
      style={styles.container}
    >
      <SafeAreaView style={styles.safeArea}>
        <View style={styles.header}>
          <TouchableOpacity 
            style={styles.backButton}
            onPress={() => navigation.goBack()}
          >
            <Ionicons name="chevron-back" size={24} color="white" />
            <Text style={styles.backText}>Back</Text>
          </TouchableOpacity>
          <Text style={styles.headerTitle}>Support & FAQs</Text>
          <View style={{ width: 70 }} />
        </View>

        <ScrollView style={styles.content}>
          {/* Quick Links */}
          <View style={styles.quickLinksSection}>
            <Text style={styles.sectionTitle}>Quick Links</Text>            <View style={styles.linksGrid}>
              <TouchableOpacity 
                style={styles.linkButton}
                onPress={() => handleLink('https://gymni.com/terms')}
              >
                <MaterialCommunityIcons name="file-document-outline" size={24} color="#FF9500" />
                <Text style={styles.linkText}>Terms of Service</Text>
              </TouchableOpacity>
              
              <TouchableOpacity 
                style={styles.linkButton}
                onPress={() => handleLink('https://gymni.com/privacy')}
              >
                <Ionicons name="shield-outline" size={24} color="#FF9500" />
                <Text style={styles.linkText}>Privacy Policy</Text>
              </TouchableOpacity>
            </View>
          </View>

          {/* FAQs Section */}
          <View style={styles.faqSection}>
            <Text style={styles.sectionTitle}>Frequently Asked Questions</Text>
            {FAQData.map((faq, index) => (
              <TouchableOpacity
                key={index}
                style={styles.faqItem}
                onPress={() => setExpandedQuestion(expandedQuestion === index ? null : index)}
              >
                <View style={styles.faqHeader}>
                  <Text style={styles.faqQuestion}>{faq.question}</Text>
                  <Ionicons 
                    name={expandedQuestion === index ? "chevron-up" : "chevron-down"} 
                    size={24} 
                    color="#FF9500" 
                  />
                </View>
                {expandedQuestion === index && (
                  <Text style={styles.faqAnswer}>{faq.answer}</Text>
                )}
              </TouchableOpacity>
            ))}
          </View>

          {/* Chat Support Section */}
          <View style={styles.chatSection}>
            <Text style={styles.sectionTitle}>Chat Support</Text>
            <View style={styles.chatContainer}>
              <ScrollView style={styles.chatHistory}>
                {chatHistory.map((message, index) => (
                  <View 
                    key={index} 
                    style={[
                      styles.messageContainer,
                      message.type === 'user' ? styles.userMessage : styles.assistantMessage
                    ]}
                  >
                    <Text style={styles.messageText}>{message.text}</Text>
                  </View>
                ))}
                {isLoading && (
                  <View style={styles.loadingContainer}>
                    <Text style={styles.loadingText}>Support is typing...</Text>
                  </View>
                )}
              </ScrollView>
              
              <View style={styles.inputContainer}>
                <TextInput
                  style={styles.input}
                  placeholder="Type your message..."
                  placeholderTextColor="#8e8e8e"
                  value={chatMessage}
                  onChangeText={setChatMessage}
                  multiline
                />
                <TouchableOpacity 
                  style={[
                    styles.sendButton,
                    !chatMessage.trim() && styles.sendButtonDisabled
                  ]}
                  onPress={handleSendMessage}
                  disabled={!chatMessage.trim() || isLoading}
                >
                  <Ionicons 
                    name="send" 
                    size={24} 
                    color={chatMessage.trim() && !isLoading ? "#FF9500" : "#666"} 
                  />
                </TouchableOpacity>
              </View>
            </View>
          </View>

          {/* Contact Info */}
          <View style={styles.contactSection}>
            <Text style={styles.sectionTitle}>Contact Us</Text>
            <View style={styles.contactInfo}>
              <TouchableOpacity 
                style={styles.contactItem}
                onPress={() => handleLink('mailto:support@gymni.com')}
              >
                <Ionicons name="mail-outline" size={24} color="#FF9500" />
                <Text style={styles.contactText}>support@gymni.com</Text>
              </TouchableOpacity>
              <TouchableOpacity 
                style={styles.contactItem}
                onPress={() => handleLink('tel:+1-800-GYMNI')}
              >
                <Ionicons name="call-outline" size={24} color="#FF9500" />
                <Text style={styles.contactText}>1-800-GYMNI</Text>
              </TouchableOpacity>
            </View>
          </View>
        </ScrollView>
      </SafeAreaView>
    </LinearGradient>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
  safeArea: {
    flex: 1,
  },
  header: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    padding: 16,
    borderBottomWidth: 1,
    borderBottomColor: '#333',
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
  headerTitle: {
    color: 'white',
    fontSize: 20,
    fontWeight: 'bold',
  },
  content: {
    flex: 1,
    padding: 16,
  },
  quickLinksSection: {
    marginBottom: 24,
  },
  sectionTitle: {
    color: '#FF9500',
    fontSize: 18,
    fontWeight: '600',
    marginBottom: 16,
  },
  linksGrid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    gap: 12,
  },
  linkButton: {
    backgroundColor: '#1C1C1E',
    borderRadius: 12,
    padding: 16,
    alignItems: 'center',
    width: '48%',
    gap: 8,
  },
  linkText: {
    color: 'white',
    fontSize: 14,
    textAlign: 'center',
  },
  faqSection: {
    marginBottom: 24,
  },
  faqItem: {
    backgroundColor: '#1C1C1E',
    borderRadius: 12,
    padding: 16,
    marginBottom: 8,
  },
  faqHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  faqQuestion: {
    color: 'white',
    fontSize: 16,
    fontWeight: '600',
    flex: 1,
    marginRight: 16,
  },
  faqAnswer: {
    color: '#8e8e8e',
    fontSize: 14,
    marginTop: 12,
    lineHeight: 20,
  },
  chatSection: {
    marginBottom: 24,
  },
  chatContainer: {
    backgroundColor: '#1C1C1E',
    borderRadius: 12,
    height: 300,
  },
  chatHistory: {
    flex: 1,
    padding: 16,
  },
  messageContainer: {
    maxWidth: '80%',
    marginBottom: 12,
    padding: 12,
    borderRadius: 12,
  },
  userMessage: {
    backgroundColor: '#FF9500',
    alignSelf: 'flex-end',
  },
  assistantMessage: {
    backgroundColor: '#2C2C2E',
    alignSelf: 'flex-start',
  },
  messageText: {
    color: 'white',
    fontSize: 14,
  },
  loadingContainer: {
    padding: 8,
  },
  loadingText: {
    color: '#8e8e8e',
    fontSize: 14,
    fontStyle: 'italic',
  },
  inputContainer: {
    flexDirection: 'row',
    alignItems: 'flex-end',
    padding: 12,
    borderTopWidth: 1,
    borderTopColor: '#333',
    gap: 12,
  },
  input: {
    flex: 1,
    backgroundColor: '#2C2C2E',
    borderRadius: 20,
    paddingHorizontal: 16,
    paddingVertical: 8,
    color: 'white',
    fontSize: 16,
    maxHeight: 100,
    minHeight: 40,
  },
  sendButton: {
    padding: 8,
  },
  sendButtonDisabled: {
    opacity: 0.5,
  },
  contactSection: {
    marginBottom: 24,
  },
  contactInfo: {
    backgroundColor: '#1C1C1E',
    borderRadius: 12,
    padding: 16,
  },
  contactItem: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 12,
    paddingVertical: 12,
  },
  contactText: {
    color: 'white',
    fontSize: 16,
  },
});