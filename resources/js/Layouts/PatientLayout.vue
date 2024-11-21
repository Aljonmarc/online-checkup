<script setup>
import { ref } from "vue";

import { Head, Link } from "@inertiajs/vue3";




const isMenuOpen = ref(false);

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};





const isChatbotOpen = ref(false);
const messages = ref([
    { type: "bot", text: "Hello! How can I assist you today? Choose an option or type your query." },
]);
const userMessage = ref("");

// Predefined quick replies
const quickReplies = ref([
    { label: "Appointment Process", text: "Tell me about the appointment process" },
    { label: "Total Doctors", text: "How many doctors are there?" },
    { label: "Total Departments", text: "What departments are available?" },
    { label: "Operating Hours", text: "What are the operating hours?" },
]);

// Open chatbot modal
function openChatbot() {
    isChatbotOpen.value = true;
}

// Close chatbot modal
function closeChatbot() {
    isChatbotOpen.value = false;
}

// Handle button click for quick replies
function handleQuickReply(replyText) {
    messages.value.push({ type: "user", text: replyText });

    // Simulate chatbot response
    setTimeout(() => {
        const botResponse = getChatbotResponse(replyText);
        messages.value.push({ type: "bot", text: botResponse });
    }, 1000);
}

// Send user message and get bot response
function sendMessage() {
    if (userMessage.value.trim() !== "") {
        // Add user's message
        messages.value.push({ type: "user", text: userMessage.value });

        // Simulate chatbot response
        setTimeout(() => {
            const botResponse = getChatbotResponse(userMessage.value);
            messages.value.push({ type: "bot", text: botResponse });
        }, 1000);

        // Clear input field
        userMessage.value = "";
    }
}

// Simulate bot response logic
function getChatbotResponse(userInput) {
   // Replace with your own AI/chatbot backend integration
   const responses = {
        "tell me about the appointment process": "You can book an appointment by visiting the 'Doctors' section and selecting a date and time.",
        "how many doctors are there?": "We currently have 42 experienced doctors in various specialties.",
        "what departments are available?": "Our hospital includes Cardiology, Neurology, Pediatrics, Orthopedics, and many more.",
        "what are the operating hours?": "We are open from Monday 6:00 AM - 5:00 PM Tuesday 6:00 AM - 5:00 PM Wednesday 6:00 AM - 5:00 PM Thursday 6:00 AM - 5:00 PM Friday 6:00 AM - 5:00 PM Saturday 6:00 AM - 5:00 PM Sunday CLOSED.",
    };

    // Match user input
    return (
        responses[userInput.toLowerCase()] ||
        "I'm sorry, I didn't understand that. Can you rephrase?"
    );
}
</script>

<template>
    <Head title="Home" />
    
    <div>

  
      

        <main>
            <slot />
        </main>

     
    </div>

 
    <div>
        <!-- Floating Chatbot Icon -->
        <button
            @click="openChatbot"
            class="fixed bottom-4 right-4 text-white p-3 rounded-full shadow-lg bg-blue-600 hover:bg-blue-700 transition"
        >
            <img src="/chatbot.PNG" alt="Chatbot" class="w-12 h-12 rounded-full" />
        </button>

        <!-- Chatbot Modal -->
        <div
            v-if="isChatbotOpen"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white w-full max-w-md rounded-lg shadow-lg overflow-hidden">
                <!-- Modal Header -->
                <div class="bg-blue-600 text-white px-4 py-2 flex justify-between items-center">
                    <h2 class="text-lg">Chatbot</h2>
                    <button @click="closeChatbot" class="text-white hover:text-gray-200">
                        ✖
                    </button>
                </div>

                <!-- Chat Interface -->
                <div class="p-4 h-96 overflow-y-auto bg-gray-50">
                    <!-- Messages -->
                    <div v-for="(message, index) in messages" :key="index" class="mb-4">
                        <div
                            v-if="message.type === 'user'"
                            class="text-right"
                        >
                            <p
                                class="inline-block bg-blue-500 text-white rounded-lg px-3 py-2"
                            >
                                {{ message.text }}
                            </p>
                        </div>
                        <div v-else>
                            <p
                                class="inline-block bg-gray-200 text-gray-900 rounded-lg px-3 py-2"
                            >
                                {{ message.text }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Button Options -->
                <div class="p-4 border-t flex flex-wrap gap-2">
                    <button
                        v-for="(option, index) in quickReplies"
                        :key="index"
                        @click="handleQuickReply(option.text)"
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600"
                    >
                        {{ option.label }}
                    </button>
                </div>

                <!-- Input Field -->
                <div class="p-4 border-t">
                    <form @submit.prevent="sendMessage">
                        <div class="flex">
                            <input
                                v-model="userMessage"
                                type="text"
                                placeholder="Type your message..."
                                class="flex-1 border rounded-l-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            <button
                                type="submit"
                                class="bg-blue-500 text-white px-4 rounded-r-lg hover:bg-blue-600"
                            >
                                Send
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>


<style scoped>


::-webkit-scrollbar {
    width: 5px;
}
::-webkit-scrollbar-thumb {
    background-color: #cbd5e0;
    border-radius: 10px;
}
::-webkit-scrollbar-thumb:hover {
    background-color: #a0aec0;
}
</style>
