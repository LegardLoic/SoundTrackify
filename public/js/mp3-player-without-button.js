document.addEventListener("DOMContentLoaded", function() {
    let player = document.getElementById('player');
  
    document.querySelectorAll('.play-button').forEach(function(button) {
        button.addEventListener('click', function() {
            let musicUrl = this.getAttribute('data-url');
            let trackName = button.getAttribute('data-name');
            player.src = musicUrl;
            player.play();
            document.getElementById('trackName').textContent = trackName;
        });   
    });
});