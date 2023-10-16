var text = document.getElementById("nowPlaying");
  var container = document.getElementById("scrollable");
  var textWidth = text.offsetWidth;
  var containerWidth = container.offsetWidth;
  
  var position = containerWidth; // On commence à la droite du conteneur
  
  function animate() {
    if (position <= -textWidth) {
      position = containerWidth;
    }
    position -= 1; // déplace le texte de 1px vers la gauche
    text.style.left = position + "px";
  }

  var intervalId = setInterval(animate, 20);