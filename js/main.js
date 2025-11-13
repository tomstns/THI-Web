window.addEventListener("DOMContentLoaded", function () {
    
    const chatContainer = document.getElementById("chatContainer");
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

    const friendList = document.getElementById("friendList");
    
    if (friendList) {
        console.log("Freundesliste-Logik würde hier laufen.");
    }
    
});