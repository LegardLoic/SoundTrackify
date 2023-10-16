const avatarBlock = document.querySelectorAll('.form-check-label');
// const avatarIcon = document.createElement('img');



for (let key = 0; key < avatarBlock.length; key++) {
    const avatarItem = avatarBlock[key];
    const link = avatarItem.textContent;
    avatarItem.textContent = '';
    const avatarIcon = document.createElement('img');
    avatarIcon.src = link;
    avatarIcon.setAttribute('height', '100px');
    avatarIcon.setAttribute('width', '100px');
    avatarIcon.setAttribute('alt', 'avatar #'+key);
    avatarItem.appendChild(avatarIcon);
}


const updatePlaylistBtn = document.querySelector('#create_playlist_Ajouter');
updatePlaylistBtn.textContent = 'Modifier ma playlist';




// const avatarEditDiv = document.querySelector('#user_edit_avatar');
// const avatarRegisterDiv = document.querySelector('#registration_avatar');
// console.log(avatarRegisterDiv);
// avatarEditDiv.classList.add('user-form__avatar');
// avatarRegisterDiv.classList.add('user-form__avatar');

// // Form titles selection and style
// const formTitles = document.querySelectorAll('.form-label');
// const formAvatarTitle = document.querySelector('legend');
// formTitles.forEach(singleTitle => {
//     singleTitle.classList.add('user-form__title')
// });
// formAvatarTitle.classList.add('user-form__title')


// const formButton = document.querySelector('#user_edit_Modifier');
// formButton.classList.add('user-form__button');
// formButton.classList.add('mt-4');
// console.log(formButton);