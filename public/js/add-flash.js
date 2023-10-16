const target = document.querySelector("div.alert");
if (target) {
    window.addEventListener('load', function() {
        target.style.transition = 'opacity 1s ease';
        
        setTimeout(() => {
            target.style.opacity = '0';            
        }, 2500);
        
        setTimeout(() => target.remove(), 3300);
    });
}