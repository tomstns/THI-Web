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
            friendListUl.innerHTML = `<li class="list-group-item list-group-item-danger">Fehler beim Laden der Freunde (${response.status})</li>`;
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
                li.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center'; 
                
                const a = document.createElement('a');
                a.href = `chat.php?friend=${encodeURIComponent(friend.username)}`; 
                a.innerText = friend.username; 
                
                li.appendChild(a);
                friendListUl.appendChild(li);

            } else if (friend.status === 'requested') { 
                hasRequests = true;
                const li = document.createElement('li');
                li.className = 'list-group-item list-group-item-warning d-flex justify-content-between align-items-center';
                
                const textWrapper = document.createElement('span');
                textWrapper.className = 'me-auto';
                textWrapper.innerHTML = `Friend Request from <strong>${friend.username}</strong>`;

                const btn = document.createElement('button');
                btn.className = 'btn btn-sm btn-info'; 
                btn.innerText = 'See Request';
                
                btn.setAttribute('data-bs-toggle', 'modal');
                btn.setAttribute('data-bs-target', '#requestModal');
                btn.setAttribute('data-friend-username', friend.username);
                
                li.appendChild(textWrapper);
                li.appendChild(btn);
                requestListOl.appendChild(li);

                btn.addEventListener('click', () => {
                    const friendUsername = btn.getAttribute('data-friend-username');
                    
                    document.getElementById('modal-friend-name').innerText = friendUsername;
                    document.getElementById('modal-friend-input').value = friendUsername; 

                    document.getElementById('requestModalLabel').innerHTML = `Anfrage von <strong>${friendUsername}</strong>`;
                });
            }
        });

        if (!hasFriends) {
            friendListUl.innerHTML = '<li class="list-group-item">Du hast noch keine Freunde.</li>';
        }
        if (!hasRequests) {
            requestListOl.innerHTML = '<li class="list-group-item">Keine neuen Anfragen.</li>';
        }
        
    } catch (e) {
        console.error("Fehler beim Laden/Parsen der Freunde:", e);
        friendListUl.innerHTML = '<li class="list-group-item list-group-item-danger">Fehler beim Laden der Freundesliste.</li>';
    }
}