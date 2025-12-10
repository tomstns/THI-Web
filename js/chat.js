async function sendMessage(to, msg) {
    try {
        const response = await fetch("../php/ajax_send_message.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            credentials: "include", 
            body: JSON.stringify({ msg, to })
        });

        if (!response.ok) {
            console.error("Senden fehlgeschlagen (Status: " + response.status + ")");
            const errorData = await response.text();
            console.error("Server-Antwort:", errorData);
        } else {
            console.log("Nachricht erfolgreich gesendet.");
        }
    } catch (err) {
        console.error("Netzwerkfehler oder JSON-Problem beim Senden:", err);
    }
}

async function loadMessages(chatPartner) {
    const chatContainer = document.getElementById("chatContainer");
    
    try {
        const response = await fetch(`../php/ajax_load_messages.php?to=${encodeURIComponent(chatPartner)}`, {
            method: "GET",
            credentials: "include" 
        });

        const messages = await response.json();

        if (!response.ok) {
            console.error("Fehler beim Laden:", response.status, messages.error);
            chatContainer.innerHTML = `<p class='p-3 text-danger'>Fehler: ${messages.error || 'Unbekannter Fehler'}</p>`;
            return;
        }
        
        renderMessages(messages);

    } catch (err) {
        console.error("Netzwerkfehler:", err);
        chatContainer.innerHTML = `<p class='p-3 text-danger'>Netzwerkfehler beim Laden des Chats.</p>`;
    }
}

function renderMessages(messages) {
    const chatContainer = document.getElementById("chatContainer");
    const isScrolledToBottom = chatContainer.scrollHeight - chatContainer.clientHeight <= chatContainer.scrollTop + 1;

    chatContainer.innerHTML = ""; 
    chatContainer.classList.add('p-3'); 

    if (messages.length === 0) {
        chatContainer.innerHTML = "<p class='p-3 text-center fst-italic'>Noch keine Nachrichten vorhanden.</p>";
        return;
    }

    messages.forEach((m) => {
        const isMe = m.from !== window.chatPartner;
        
        const div = document.createElement("div");
        
        div.classList.add('d-flex', 'mb-3'); 
        
        let bubbleStyle = 'padding: 12px 18px; border-radius: 20px; position: relative; max-width: 80%; min-width: 100px; line-height: 1.5; word-wrap: break-word;';
        
        if (isMe) {
            div.classList.add('ms-auto'); 
            div.style.cssText += bubbleStyle + ' background-color: #007bff; color: white; border-bottom-right-radius: 3px;';
            
        } else {
            div.classList.add('me-auto'); 
            div.style.cssText += bubbleStyle + ' background-color: #dee2e6; color: #343a40; border-bottom-left-radius: 3px;';
        }
        
        let timeString = '';
        if (m.time) {
            try {
                const date = new Date(m.time * 1000); 
                timeString = date.toLocaleTimeString('de-DE', { hour: '2-digit', minute: '2-digit' });
            } catch(e) { }
        }

        const messageContent = document.createElement('span');
        messageContent.style.paddingRight = '60px'; 
        
        const sender = isMe ? `` : `<strong class="me-1">${m.from}:</strong> `;
        messageContent.innerHTML = `${sender}${m.msg}`; 
        
        const messageTimestamp = document.createElement('span');
        messageTimestamp.innerText = timeString;
        
        messageTimestamp.style.cssText = 'position: absolute; bottom: 5px; right: 15px; font-size: 0.75em; opacity: 0.8;';
        
        if (isMe) {
            messageTimestamp.style.color = 'rgba(255, 255, 255, 0.9)'; 
        } else {
            messageTimestamp.style.color = '#6c757d'; 
        }

        div.appendChild(messageContent);
        div.appendChild(messageTimestamp);
        
        chatContainer.appendChild(div);
    });

    if (isScrolledToBottom) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
}


window.addEventListener("DOMContentLoaded", function () {
    
    const messageForm = document.getElementById("messageForm");
    
    if (messageForm) {
        
        messageForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            
            const messageInput = document.getElementById("messageInput"); 
            const message = messageInput.value.trim();
            
            if (!message || !window.chatPartner) return;

            await sendMessage(window.chatPartner, message); 
            
            messageInput.value = "";
            
            await loadMessages(window.chatPartner); 
        });

        setInterval(() => {
            if (window.chatPartner) {
                loadMessages(window.chatPartner);
            }
        }, 3000);

        if (window.chatPartner) {
            loadMessages(window.chatPartner);
        }
    }
});