function setInvalid(inputElement, message) {
    inputElement.classList.remove('valid');
    inputElement.classList.add('invalid');
    document.getElementById(inputElement.id + '-error').innerText = message;
}

function setValid(inputElement) {
    inputElement.classList.remove('invalid');
    inputElement.classList.add('valid');
    document.getElementById(inputElement.id + '-error').innerText = '';
}

function validateUsernameLength() {
    const usernameInput = document.getElementById('username');
    const username = usernameInput.value;
    
    if (username.length < 3) {
        setInvalid(usernameInput, 'Nutzername muss min. 3 Zeichen lang sein.');
        return false;
    } else {
        setValid(usernameInput);
        return true;
    }
}

function validatePasswordLength() {
    const passwordInput = document.getElementById('password');
    const password = passwordInput.value;

    if (password.length < 8) {
        setInvalid(passwordInput, 'Passwort muss min. 8 Zeichen haben.');
        return false;
    } else {
        setValid(passwordInput);
        return true;
    }
}

function validatePasswordMatch() {
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('confirm-password');
    
    if (passwordInput.value !== confirmInput.value) {
        setInvalid(confirmInput, 'Passwörter stimmen nicht überein.');
        return false;
    } else {
        if (confirmInput.value.length > 0) {
            setValid(confirmInput);
        }
        return true;
    }
}

function validateFormOnSubmit() {
    const isUsernameLengthValid = validateUsernameLength();
    const isPasswordLengthValid = validatePasswordLength();
    const isPasswordMatchValid = validatePasswordMatch();

    if (!isUsernameLengthValid || !isPasswordLengthValid || !isPasswordMatchValid) {
        return false; 
    }

    const form = document.getElementById('register-form');
    const usernameInput = document.getElementById('username');
    const username = usernameInput.value;

    const xmlhttp = new XMLHttpRequest(); 
    
    const url = "ajax_check_user.php?username=" + encodeURIComponent(username);

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === 4) { 
            if (xmlhttp.status === 204) { 
                setInvalid(usernameInput, 'Dieser Nutzername ist bereits vergeben.');
            } else if (xmlhttp.status === 404) {
                setValid(usernameInput); 
                form.submit(); 
            } else {
                setInvalid(usernameInput, 'Fehler bei der Prüfung des Nutzernamens.');
            }
        }
    };

    xmlhttp.open("GET", url, true); 
    xmlhttp.send(); 

    return false;
}

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('register-form');
    if (form) {
        form.onsubmit = validateFormOnSubmit;
    }

    document.getElementById('username').addEventListener('input', validateUsernameLength);
    document.getElementById('password').addEventListener('input', validatePasswordLength);
    document.getElementById('password').addEventListener('input', validatePasswordMatch);
    document.getElementById('confirm-password').addEventListener('input', validatePasswordMatch);
});