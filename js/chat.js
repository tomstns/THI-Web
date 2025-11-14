let chatPartnerName = null;
window.chatPartner = null;

document.addEventListener('DOMContentLoaded', () => {
    chatPartnerName = getChatpartner(); 
    window.chatPartner = chatPartnerName; 

    if (chatPartnerName) {
        document.getElementById('chat-title').innerText = `Chat with ${chatPartnerName}`;
    } else {
        document.getElementById('chat-title').innerText = 'Chatpartner not found';
        console.error("Kein 'friend'-Parameter in der URL gefunden.");
        return; 
    }

    document.getElementById('send-message-button').addEventListener('click', sendMessage);
    
    loadMessages();
    window.setInterval(loadMessages, 3000);
});

function getChatpartner() {
    const url = new URL(window.location.href);
    const queryParams = url.searchParams;
    const friendValue = queryParams.get("friend");
    return friendValue;
}

function renderMessages(messages) {
    const chatContainer = document.getElementById("chat-history"); 
    if (!chatContainer) return;
    
    const isScrolledToBottom = chatContainer.scrollHeight - chatContainer.clientHeight <= chatContainer.scrollTop + 1;
    chatContainer.innerHTML = ""; 

    if (messages.length === 0) {
        chatContainer.innerHTML = "<p style='padding: 10px; text-align: center; font-style: italic;'>Noch keine Nachrichten vorhanden.</p>";
        return;
    }

    messages.forEach((m) => {
        const div = document.createElement("div");
        div.className = m.from === window.chatPartner ? "message friend" : "message me";
        
        let timeString = '';
        if (m.time) {
            try {
                const date = new Date(m.time * 1000); 
                timeString = date.toLocaleTimeString('de-DE', { hour: '2-digit', minute: '2-digit' });
            } catch(e) { }
        }

        const messageContent = document.createElement('span');
        messageContent.className = 'message-content';
        messageContent.innerHTML = `<strong>${m.from}:</strong> ${m.msg}`; 

        const messageTimestamp = document.createElement('span');
        messageTimestamp.className = 'message-timestamp';
        messageTimestamp.innerText = timeString;

        div.appendChild(messageContent);
        div.appendChild(messageTimestamp);
        
        chatContainer.appendChild(div);
    });

    if (isScrolledToBottom) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
}

function loadMessages() {
    if (!chatPartnerName) return; 

    const xmlhttp = new XMLHttpRequest();
    
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            const messages = JSON.parse(xmlhttp.responseText);
            renderMessages(messages); 
        }
    };
    
    const url = `${window.backendUrl}/message/${encodeURIComponent(chatPartnerName)}`;
    xmlhttp.open("GET", url, true); 
    xmlhttp.setRequestHeader('Authorization', 'Bearer ' + window.token); 
    xmlhttp.send();
}

function sendMessage() {
    const input = document.getElementById('new-message-input'); 
    const messageText = input.value;
    
    if (messageText.trim() === '' || !chatPartnerName) {
        return; 
    }

    const xmlhttp = new XMLHttpRequest();
    
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 204) {
            input.value = ''; 
            loadMessages();   
        } else if (xmlhttp.readyState === 4) {
            console.error("Fehler beim Senden der Nachricht:", xmlhttp.responseText);
        }
    };

    const url = `${window.backendUrl}/message`;
    xmlhttp.open("POST", url, true);
    xmlhttp.setRequestHeader('Authorization', 'Bearer ' + window.token);
    xmlhttp.setRequestHeader('Content-Type', 'application/json'); 
    
    const payload = JSON.stringify({ 
        message: messageText, 
        to: chatPartnerName 
    });
    xmlhttp.send(payload);
}