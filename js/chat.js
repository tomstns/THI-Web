let chatPartnerName = null;

document.addEventListener('DOMContentLoaded', () => {
    
    chatPartnerName = getChatpartner(); 

    if (chatPartnerName) {
        document.getElementById('chat-title').innerText = `Chat with ${chatPartnerName}`;
    } else {
        document.getElementById('chat-title').innerText = 'Chatpartner not found';
        console.error("Kein 'friend'-Parameter in der URL gefunden.");
        return; 
    }

    document.getElementById('send-message-button').addEventListener('click', sendMessage);
    
    loadMessages();
    window.setInterval(loadMessages, 1000); 
});

function getChatpartner() {
    const url = new URL(window.location.href);
    const queryParams = url.searchParams;
    const friendValue = queryParams.get("friend");
    console.log("Friend:", friendValue);
    return friendValue;
}

function loadMessages() {
    if (!chatPartnerName) return; 

    const xmlhttp = new XMLHttpRequest();
    
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            const messages = JSON.parse(xmlhttp.responseText);
            const historyContainer = document.getElementById('chat-history');
            
            historyContainer.innerHTML = ''; 

            messages.forEach(msg => {
                
                const container = document.createElement('div');
                container.className = 'message-container';
                
                const text = document.createElement('p');
                text.innerHTML = `<strong>${msg.from}:</strong> ${msg.msg}`;
                
                const time = document.createElement('span');
                time.className = 'message-timestamp';
                
                // === DIESE ZEILE WURDE GEÄNDERT ===
                time.innerText = new Date(msg.time).toLocaleTimeString(); 
                
                container.appendChild(text);
                container.appendChild(time);
                historyContainer.appendChild(container);
            });
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