document.addEventListener('DOMContentLoaded', () => {
    
    try {
        const payload = JSON.parse(atob(window.token.split('.')[1]));
        window.currentUser = payload.user; 
    } catch (e) {
        console.error("Token konnte nicht dekodiert werden:", e);
        window.currentUser = "Tom";
    }
    
    document.getElementById('add-friend-button').addEventListener('click', addFriendRequest);
    
    const requestList = document.getElementById('request-list');
    if (requestList) {
        requestList.addEventListener('click', (e) => {
            if (e.target.classList.contains('accept-btn')) {
                handleFriendRequest(e.target.dataset.username, 'accepted');
            }
            if (e.target.classList.contains('reject-btn')) {
                handleFriendRequest(e.target.dataset.username, 'dismissed');
            }
        });
    }
    
    loadFriendsAndRequests(); 
    window.setInterval(loadFriendsAndRequests, 3000); 
});

function loadFriendsAndRequests() {
    const xmlhttp = new XMLHttpRequest();
    
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            const friends = JSON.parse(xmlhttp.responseText);
            
            const friendListUl = document.getElementById('friend-list');
            const requestListOl = document.getElementById('request-list');
            
            friendListUl.innerHTML = ''; 
            requestListOl.innerHTML = '';
            
            let currentFriendsUsernames = [];

            friends.forEach(friend => {
                if (friend.status === 'accepted') { 
                    currentFriendsUsernames.push(friend.username);
                    
                    const li = document.createElement('li');
                    const a = document.createElement('a');
                    a.href = `chat.html?friend=${encodeURIComponent(friend.username)}`; 
                    a.innerText = friend.username; 
                    li.appendChild(a);
                    friendListUl.appendChild(li);

                } else if (friend.status === 'requested') { 
                    const li = document.createElement('li');
                    li.innerHTML = `Friend request from <strong>${friend.username}</strong>
                        <div class="form-row">
                            <button type="button" class="accept-btn" data-username="${friend.username}">Accept</button>
                            <button type="button" class="reject-btn" data-username="${friend.username}">Reject</button>
                        </div>`;
                    requestListOl.appendChild(li);
                }
            });
            
            updateFriendSuggestions(currentFriendsUsernames);
        }
    };
    
    xmlhttp.open("GET", window.backendUrl + "/friend", true); 
    xmlhttp.setRequestHeader('Authorization', 'Bearer ' + window.token); 
    xmlhttp.send();
}

function handleFriendRequest(username, status) {
    const xmlhttp = new XMLHttpRequest();
    
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 204) {
            loadFriendsAndRequests();
        } else if (xmlhttp.readyState === 4) {
            console.error("Fehler beim Akzeptieren/Ablehnen:", xmlhttp.responseText);
        }
    };

    xmlhttp.open("PUT", `${window.backendUrl}/friend/${username}`, true); 
    xmlhttp.setRequestHeader('Authorization', 'Bearer ' + window.token);
    xmlhttp.setRequestHeader('Content-Type', 'application/json');
    
    const payload = JSON.stringify({ status: status });
    xmlhttp.send(payload);
}

function updateFriendSuggestions(currentFriends) {
    const xmlhttp = new XMLHttpRequest();
    
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            const allUsers = JSON.parse(xmlhttp.responseText);
            
            const select = document.getElementById('friend-request-name');
            
            while (select.options.length > 1) {
                select.remove(1);
            }
            
            allUsers.forEach(user => {
                if (user !== window.currentUser && !currentFriends.includes(user)) {
                    const option = document.createElement('option'); 
                    option.value = user;
                    option.innerText = user;
                    select.appendChild(option);
                }
            });
        }
    };
    
    xmlhttp.open("GET", window.backendUrl + "/user", true); 
    xmlhttp.setRequestHeader('Authorization', 'Bearer ' + window.token);
    xmlhttp.send();
}

function addFriendRequest() {
    const select = document.getElementById('friend-request-name');
    const username = select.value;
    
    if (!username || username === 'none') {
        console.error("Bitte einen Benutzer auswählen.");
        select.classList.add('invalid'); 
        return; 
    }
    
    select.classList.remove('invalid'); 
    
    const xmlhttp = new XMLHttpRequest();
    
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 204) { 
            select.value = 'none'; 
            loadFriendsAndRequests();
        } else if (xmlhttp.readyState === 4) {
             console.error("Fehler beim Hinzufügen des Freundes:", xmlhttp.responseText);
             select.classList.add('invalid');
        }
    };

    xmlhttp.open("POST", `${window.backendUrl}/friend`, true); 
    xmlhttp.setRequestHeader('Authorization', 'Bearer ' + window.token);
    xmlhttp.setRequestHeader('Content-Type', 'application/json');
    
    const payload = JSON.stringify({ username: username });
    xmlhttp.send(payload);
}