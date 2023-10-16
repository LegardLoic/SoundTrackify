let shuffledPlaylist = [];

// Une fonction pour mélanger un tableau
function shuffleArray(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
}

document.addEventListener("DOMContentLoaded", function() {
    let player = document.getElementById('player');
    let playlist = [];
    let currentIndex = -1;
    let isShuffle = false;
    let shuffledPlaylist = [];  // Ajout de la playlist mélangée
    let shuffleIndex = 0;  // Pour suivre notre progression dans la playlist mélangée

    document.querySelectorAll('.play-button').forEach(function(button, index) {
        let musicUrl = button.getAttribute('data-url');
        let trackName = button.getAttribute('data-name');
        playlist.push({url: musicUrl, name: trackName});

        button.addEventListener('click', function() {
            currentIndex = index;
            player.src = playlist[currentIndex].url;
            player.play();
            document.getElementById('trackName').textContent = playlist[currentIndex].name;
        });
    });

    // Remplir shuffledPlaylist et le mélanger
    for (let i = 0; i < playlist.length; i++) {
        shuffledPlaylist.push(i);
    }
    shuffleArray(shuffledPlaylist);

    player.addEventListener('ended', function() {
        if (isShuffle) {
            shuffleIndex++;
            if (shuffleIndex >= shuffledPlaylist.length) {
                shuffleIndex = 0;
                shuffleArray(shuffledPlaylist);  // Mélanger à nouveau lorsque nous atteignons la fin
            }
            currentIndex = shuffledPlaylist[shuffleIndex];
        } else {
            currentIndex++;
        }

        if (currentIndex >= playlist.length) {
            currentIndex = 0;
        }
        player.src = playlist[currentIndex].url;
        player.play();
        document.getElementById('trackName').textContent = playlist[currentIndex].name;
    });

    // Bouton suivant
    document.getElementById('next-button').addEventListener('click', function() {
        if (isShuffle) {
            shuffleIndex++;
            if (shuffleIndex >= shuffledPlaylist.length) {
                shuffleIndex = 0;
                shuffleArray(shuffledPlaylist);
            }
            currentIndex = shuffledPlaylist[shuffleIndex];
        } else {
            currentIndex++;
            if (currentIndex >= playlist.length) {
                currentIndex = 0;
            }
        }
        player.src = playlist[currentIndex].url;
        player.play();
        document.getElementById('trackName').textContent = playlist[currentIndex].name;
    });

    // Bouton précédent
    document.getElementById('prev-button').addEventListener('click', function() {
        if (isShuffle) {
            shuffleIndex--;
            if (shuffleIndex < 0) {
                shuffleIndex = shuffledPlaylist.length - 1;
            }
            currentIndex = shuffledPlaylist[shuffleIndex];
        } else {
            currentIndex--;
            if (currentIndex < 0) {
                currentIndex = playlist.length - 1;
            }
        }
        player.src = playlist[currentIndex].url;
        player.play();
        document.getElementById('trackName').textContent = playlist[currentIndex].name;
    });

    // Bouton aléatoire
    document.getElementById('shuffle-button').addEventListener('click', function() {
        const shuffleImg = document.querySelector('.shuffle-img');
        isShuffle = !isShuffle;
        // this.textContent = isShuffle ? 'Aléatoire On' : 'Aléatoire Off';
        shuffleImg.src = isShuffle ? shuffleOnImg : shuffleOffImg

        if (isShuffle) {
            shuffleIndex = 0;
            shuffleArray(shuffledPlaylist);
            document.getElementById('next-button').click();
        }
    });
});


