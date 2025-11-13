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
            chatContainer.innerHTML = `<p style="color: red; padding: 10px;">Fehler: ${messages.error || 'Unbekannter Fehler'}</p>`;
            return;
        }
        
        renderMessages(messages);

    } catch (err) {
        console.error("Netzwerkfehler:", err);
        chatContainer.innerHTML = `<p style="color: red; padding: 10px;">Netzwerkfehler beim Laden des Chats.</p>`;
    }
}

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
            console.warn("Senden fehlgeschlagen:", response.status);
        }
    } catch (err) {
        console.error("Fehler beim Senden:", err);
    }
}

function renderMessages(messages) {
    const chatContainer = document.getElementById("chatContainer");
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