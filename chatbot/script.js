
function toggleChat() {
    const chatContainer = document.getElementById('chatContainer');
    const chatToggleButton = document.getElementById('chatToggleButton');
    
    if (chatContainer.style.display === 'none') {
        chatContainer.style.display = 'flex';
        chatToggleButton.style.display = 'none';
        setTimeout(() => {
            document.getElementById('messageInput').focus();
        }, 300);
    }
}

function closeChat() {
    const chatContainer = document.getElementById('chatContainer');
    const chatToggleButton = document.getElementById('chatToggleButton');
    
    chatContainer.style.display = 'none';
    chatToggleButton.style.display = 'flex';
}

document.getElementById('chatToggleButton').addEventListener('click', toggleChat);
document.getElementById('closeButton').addEventListener('click', closeChat);


document.getElementById('chatForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    const chatMessages = document.getElementById('chatMessages');
    const typingIndicator = document.getElementById('typingIndicator');

    const message = messageInput.value.trim();
    if (!message) return;


    const userMessageElement = document.createElement('div');
    userMessageElement.className = 'message user';
    userMessageElement.innerHTML = `
                <div class="message-content">
                    ${message}
                    <div class="message-time">${new Date().toLocaleTimeString()}</div>
                </div>
            `;
    chatMessages.appendChild(userMessageElement);

    messageInput.value = '';
    sendButton.disabled = true;


    typingIndicator.style.display = 'block';
    chatMessages.scrollTop = chatMessages.scrollHeight;

    try {
        const response = await fetch('', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                'message': message
            })
        });

        const data = await response.json();


        typingIndicator.style.display = 'none';

        if (data.success) {
            const assistantMessageElement = document.createElement('div');
            assistantMessageElement.className = 'message assistant';
            assistantMessageElement.innerHTML = `
                        <div class="message-content">
                            ${data.response}
                            <div class="message-time">${new Date().toLocaleTimeString()}</div>
                        </div>
                    `;
            chatMessages.appendChild(assistantMessageElement);
        } else {
            const errorElement = document.createElement('div');
            errorElement.className = 'error-message';
            errorElement.textContent = `Erro: ${data.error}`;
            chatMessages.appendChild(errorElement);
        }

    } catch (error) {
        typingIndicator.style.display = 'none';

        const errorElement = document.createElement('div');
        errorElement.className = 'error-message';
        errorElement.textContent = 'Erro de conexão. Tente novamente.';
        chatMessages.appendChild(errorElement);
    }

    sendButton.disabled = false;
    messageInput.focus();
    chatMessages.scrollTop = chatMessages.scrollHeight;
});

