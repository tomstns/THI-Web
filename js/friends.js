document.addEventListener('DOMContentLoaded', () => {
    
    loadFriendsAndRequests(); 
    
    window.setInterval(loadFriendsAndRequests, 3000); 

});

async function loadFriendsAndRequests() {
    
    const friendListUl = document.getElementById('friend-list');
    const requestListOl = document.getElementById('request-list');

    try {
        const response = await fetch('ajax_load_friends.php', {
            method: 'GET',
            credentials: 'include' 
        });

        if (!response.ok) {
            friendListUl.innerHTML = `<li>Fehler beim Laden der Freunde (${response.status})</li>`;
            return;
        }

        const friends = await response.json();

        friendListUl.innerHTML = ''; 
        requestListOl.innerHTML = '';

        let hasFriends = false;
        let hasRequests = false;

        friends.forEach(friend => {
            if (friend.status === 'accepted') { 
                hasFriends = true;
                const li = document.createElement('li');
                const a = document.createElement('a');
                
                a.href = `chat.php?friend=${encodeURIComponent(friend.username)}`; 
                a.innerText = friend.username; 
                
                li.appendChild(a);
                friendListUl.appendChild(li);

            } else if (friend.status === 'requested') { 
                hasRequests = true;
                const li = document.createElement('li');
                li.innerHTML = `Freundschaftsanfrage von <strong>${friend.username}</strong>
                    <form method="post" action="freundesliste.php" style="display:inline;">
                        <input type="hidden" name="friendUsername" value="${friend.username}">
                        <button type="submit" name="action" value="accept_friend">Annehmen</button>
                        <button type="submit" name="action" value="reject_friend">Ablehnen</button>
                    </form>`;
                requestListOl.appendChild(li);
            }
        });

        if (!hasFriends) {
            friendListUl.innerHTML = '<li>Du hast noch keine Freunde.</li>';
        }
        if (!hasRequests) {
            requestListOl.innerHTML = '<li>Keine neuen Anfragen.</li>';
        }
        
    } catch (e) {
        console.error("Fehler beim Laden/Parsen der Freunde:", e);
        friendListUl.innerHTML = '<li>Fehler beim Laden der Freundesliste.</li>';
    }
}