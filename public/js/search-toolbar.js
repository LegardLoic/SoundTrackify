// Change automatic display of twig form widget on home search toolbar
const input = document.querySelector('input#form_query');
const button = document.querySelector('button#form_search');
const form = document.querySelector('form');
const formDiv = document.querySelectorAll('div.mb-3');
const divForm = document.querySelector('div#form');

form.classList.add('search-toolbar');
form.classList.add('custom-form');
form.classList.add('mt-4');
form.classList.add('pt-2');
form.classList.add('mb-lg-0');
form.classList.add('hero-section');
input.classList.remove('form-label');
input.classList.add('search-toolbar__input');
input.setAttribute("placeholder", "Entrez votre recherche");
button.classList.remove('btn-primary');
button.classList.remove('btn');
button.classList.add('search-toolbar__button');


const clonedButton = button.cloneNode(true);
const clonedInput = input.cloneNode(true);
formDiv.forEach(singleDiv => {
    singleDiv.remove();
});

divForm.prepend(clonedButton);
clonedButton.textContent = 'Go';
divForm.prepend(clonedInput);

