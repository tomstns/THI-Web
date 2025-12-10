
function setInvalid(inputElement, message) {
    inputElement.classList.remove('is-valid');
    inputElement.classList.add('is-invalid');
    const feedbackElement = inputElement.closest('.form-floating').querySelector('.invalid-feedback');
    if (feedbackElement) {
        feedbackElement.innerText = message;
    }
}

function setValid(inputElement) {
    inputElement.classList.remove('is-invalid');
    inputElement.classList.add('is-valid');
    const feedbackElement = inputElement.closest('.form-floating').querySelector('.invalid-feedback');
    if (feedbackElement) {
        feedbackElement.innerText = '';
    }
}

function validateUsernameLength() {
    const usernameInput = document.getElementById('username');
    const username = usernameInput.value;
    
    if (username.length === 0) {
        usernameInput.classList.remove('is-valid', 'is-invalid');
        return false; 
    }

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
    
    if (password.length === 0) {
        passwordInput.classList.remove('is-valid', 'is-invalid');
        return false; 
    }

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
    
    if (confirmInput.value.length === 0) {
        confirmInput.classList.remove('is-valid', 'is-invalid');
        return false;
    }
    
    if (passwordInput.value !== confirmInput.value) {
        setInvalid(confirmInput, 'Passwörter stimmen nicht überein.');
        return false;
    } else {
        setValid(confirmInput);
        return true;
    }
}

function validateFormOnSubmitAjax() {
    const form = document.getElementById('register-form');
    const usernameInput = document.getElementById('username');
    const username = usernameInput.value;

    const xmlhttp = new XMLHttpRequest(); 
    const url = "ajax_check_user.php?username=" + encodeURIComponent(username);

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === 4) { 
            if (xmlhttp.status === 204) { 
                setInvalid(usernameInput, 'Dieser Nutzername ist bereits vergeben.');
                form.classList.add('was-validated'); 
            } else if (xmlhttp.status === 404) {
                setValid(usernameInput); 
                form.submit(); 
            } else {
                setInvalid(usernameInput, 'Fehler bei der Prüfung des Nutzernamens.');
                form.classList.add('was-validated');
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
        form.onsubmit = (e) => {
            e.preventDefault(); 
            
            const isUsernameLengthValid = validateUsernameLength();
            const isPasswordLengthValid = validatePasswordLength();
            const isPasswordMatchValid = validatePasswordMatch();

            if (!isUsernameLengthValid || !isPasswordLengthValid || !isPasswordMatchValid) {
                form.classList.add('was-validated'); 
                return false; 
            }
            
            return validateFormOnSubmitAjax();
        };

        document.getElementById('username').addEventListener('input', validateUsernameLength);
        document.getElementById('password').addEventListener('input', () => {
             validatePasswordLength(); 
             validatePasswordMatch();
        });
        document.getElementById('confirm-password').addEventListener('input', validatePasswordMatch);
    }
});