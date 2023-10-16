const myForm = document.querySelector('form');

const passwordInput = document.querySelector('#user_password');
const passwordError = document.createElement('span');
passwordError.classList.add('user-form__error');
const passwordDiv = passwordInput.parentNode;
const passwordRegex = /^(?=.*?[A-Z])(?=.*?[a-z])(?=(.*[a-z]){1,})(?=(.*[\d]){1,})(?=(.*[\W]){1,})(?!.*\s).{8,}$/;

const forbiddenDomains = [
    '@yopmail.com',
    '@yopmail.fr',
    '@yopmail.net',
    '@cool.fr.nf',
    '@jetable.fr.nf',
    '@courriel.fr.nf',
    '@moncourrier.fr.nf',
    '@monemail.fr.nf',
    '@monmail.fr.nf',
    '@hide.biz.st',
    '@mymail.infos.st',
];
const emailInput = document.getElementById('user_email');
const emailDiv = emailInput.parentNode;
const emailError = document.createElement('span');
emailError.classList.add('user-form__error');
const emailRegex = /^[A-Za-z0-9_-][A-Za-z0-9_.-]*@[A-Za-z0-9_-]+(\.[A-Za-z0-9_-]+)+[A-Za-z]{2,4}$/;

// password pre-submit validation
myForm.addEventListener('submit', function(event){
    if (passwordRegex.test(passwordInput.value) == false) {
        
        passwordError.innerHTML = "Le mot de passe doit comporter 8 caractères, au moins une lettre majuscule et une lettre minuscule, au moins un chiffre et un caractère spécial";
        passwordDiv.appendChild(passwordError);
        passwordInput.focus();
        setTimeout(function(){passwordError.remove()}, 5000);
        event.preventDefault();
    }

})

// e-mail pre-submit validation
myForm.addEventListener('submit', function(event){
    if (emailRegex.test(emailInput.value) == false) {
        
        emailError.textContent = "L'adresse email n'est pas valide";
        emailDiv.appendChild(emailError);
        emailInput.focus();
        setTimeout(function(){emailError.remove()}, 5000);
        event.preventDefault();
    }
    else{
        for (const domain of forbiddenDomains){
            // si la saisie utilisateur contient un domaine interdit
            if (emailInput.value.includes(domain)){
                // on crée une balise p pour afficher le msg d'erreur
                emailError.textContent = 'Les adresses jetables ne sont pas admises';
                emailDiv.appendChild(emailError);
                emailInput.focus();
                setTimeout(function(){emailError.remove()}, 5000);
                event.preventDefault();
            }
        }
    }
    
})



