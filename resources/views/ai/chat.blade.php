@vite(['resources/css/app.css','resources/js/app.js'])
<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
@extends('layouts.navbar')
@section('navbar-content')
<section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16 ml-0 md:ml-16 lg:ml-32">
    
    <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
        <h2 class="shrink-0 text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Knowledge Base - AI Chatbot</h2>
        <div class="chat-container">
            <div class="chat-box border-spacing-2" id="chat-box">
                <!-- Chat messages will appear here -->
            </div>
            <div class="chat-input flex items-center space-x-2 mt-1">
                <textarea id="user-input" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                    placeholder="Ask anything..."></textarea>
                <button onclick="sendMessage()" 
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500">
                    Send
                </button>
            </div>
        </div>
    </div>
</section>
{{-- CSS Styles --}}
<style>
    .chat-box {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        border-bottom: 1px solid #ddd;
        display: flex;
        flex-direction: column;
    }

    .message {
        margin-bottom: 15px;
        padding: 10px;
        border-radius: 10px;
        max-width: 80%;
        display: flex;
        align-items: flex-start;
    }

    .message.user {
        text-align: right;
        font-size: small;
        background-color: #f1f1f1;
        margin-left: auto;
    }

    .message.bot {
        text-align: left;
        font-size: small;
        background-color: #e5e7eb;
        margin-right: auto;
    }

    .message .avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        margin-right: 10px;
    }

    .message .content {
        flex: 1;
    }

    .message .content h3 {
        font-size: 1.1rem;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .message .content p {
        margin-bottom: 10px;
    }

    .message .content ul {
        list-style-type: disc;
        margin-left: 20px;
        margin-bottom: 10px;
    }

    .message .content strong {
        font-weight: bold;
    }

    .message .timestamp {
        font-size: 0.75rem;
        color: #666;
        margin-top: 5px;
    }
</style>

    <script>
        async function sendMessage() {
            const userInput = document.getElementById('user-input').value;
            if (!userInput) return;

            // Add user's message to the chat box
            const chatBox = document.getElementById('chat-box');
            const timestamp = new Date().toLocaleTimeString();
            chatBox.innerHTML += `
                <div class="message user">
                    <div class="content">
                        <div>${userInput}</div>
                        <div class="timestamp">${timestamp}</div>
                    </div>
                </div>`;

            // Clear the input field
            document.getElementById('user-input').value = '';

            // Send the message to the backend
            try {
                const response = await fetch('chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message: userInput })
                });

                const data = await response.json();

                // Handle errors or display the bot's response
                if (data.error) {
                    chatBox.innerHTML += `
                        <div class="message bot">
                            <div class="content">
                                <div>${data.error}</div>
                                <div class="timestamp">${timestamp}</div>
                            </div>
                        </div>`;
                } else if (data.response) {
                    // Format the AI's response
                    const formattedResponse = formatAIResponse(data.response);
                    chatBox.innerHTML += `
                        <div class="message bot">
                            <div class="content">
                                ${formattedResponse}
                                <div class="timestamp">${timestamp}</div>
                            </div>
                        </div>`;
                } else {
                    chatBox.innerHTML += `
                        <div class="message bot">
                            <div class="content">
                                <div>Sorry, I couldn't process your request.</div>
                                <div class="timestamp">${timestamp}</div>
                            </div>
                        </div>`;
                }

                // Scroll to the bottom of the chat box
                chatBox.scrollTop = chatBox.scrollHeight;
            } catch (error) {
                console.error('Error:', error);
                chatBox.innerHTML += `
                    <div class="message bot">
                        <div class="content">
                            <div>ChatBot: An error occurred. Please try again.</div>
                            <div class="timestamp">${timestamp}</div>
                        </div>
                    </div>`;
            }
        }

        // Function to format the AI's response
        function formatAIResponse(response) {
            // Replace **text** with <strong>text</strong>
            response = response.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

            // Replace * with <li> for lists
            response = response.replace(/\n\*(.*?)\n/g, '<li>$1</li>');

            // Wrap lists in <ul> tags
            response = response.replace(/<li>(.*?)<\/li>/g, '<ul><li>$1</li></ul>');

            // Add headings for numbered sections
            response = response.replace(/\n(\d+\.\s.*?)\n/g, '<h3>$1</h3>');

            // Replace newlines with <p> tags for paragraphs
            response = response.replace(/\n\n/g, '</p><p>');

            // Wrap the entire response in <p> tags
            response = `<p>${response}</p>`;

            return response;
        }

        // Allow pressing "Enter" to send the message
        document.getElementById('user-input').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    </script>
@endsection