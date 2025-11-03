document.addEventListener('DOMContentLoaded', () => {
    
    try {
        const payload = JSON.parse(atob(window.token.split('.')[1]));
        window.currentUser = payload.user; 
    } catch (e) {
        console.error("Token konnte nicht dekodiert werden:", e);
        window.currentUser = "Tom"; 
    }
    
    document.getElementById('add-friend-button').addEventListener('click', addFriendRequest);
    
    loadFriendsAndRequests(); 
    window.setInterval(loadFriendsAndRequests, 1000); 
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
                            <button type="button">Accept</button>
                            <button type="button">Reject</button>
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

function updateFriendSuggestions(currentFriends) {
    const xmlhttp = new XMLHttpRequest();
    
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            const allUsers = JSON.parse(xmlhttp.responseText);
            const datalist = document.getElementById('friend-selector');
            
            datalist.innerHTML = ''; 
            
            allUsers.forEach(user => {
                if (user !== window.currentUser && !currentFriends.includes(user)) {
                    const option = document.createElement('option'); 
                    option.value = user;
                    datalist.appendChild(option);
                }
            });
        }
    };
    
    xmlhttp.open("GET", window.backendUrl + "/user", true); 
    xmlhttp.setRequestHeader('Authorization', 'Bearer ' + window.token);
    xmlhttp.send();
}

function addFriendRequest() {
    const input = document.getElementById('friend-request-name');
    const username = input.value;
    
    const isValid = Array.from(
        document.querySelectorAll('#friend-selector option')
    ).some(opt => opt.value === username);
    
    if (!isValid) {
        console.error("Ungültiger oder bereits hinzugefügter Benutzer ausgewählt.");
        input.classList.add('invalid'); 
        return; 
    }
    
    input.classList.remove('invalid'); 
    
    const xmlhttp = new XMLHttpRequest();
    
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 204) { 
            input.value = ''; 
        } else if (xmlhttp.readyState === 4) {
             console.error("Fehler beim Hinzufügen des Freundes:", xmlhttp.responseText);
             input.classList.add('invalid');
        }
    };

    xmlhttp.open("POST", `${window.backendUrl}/friend`, true); 
    xmlhttp.setRequestHeader('Authorization', 'Bearer ' + window.token);
    xmlhttp.setRequestHeader('Content-Type', 'application/json');
    
    const payload = JSON.stringify({ username: username });
    xmlhttp.send(payload);
}