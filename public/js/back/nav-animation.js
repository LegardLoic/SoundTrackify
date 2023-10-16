const iconNavbarSidenav = document.getElementById("iconNavbarSidenav")
  , iconSidenav = document.getElementById("iconSidenav")
  , sidenav = document.getElementById("sidenav-main");
let body = document.getElementsByTagName("body")[0]
  , className = "g-sidenav-pinned";
function toggleSidenav() {
    body.classList.contains(className) ? (body.classList.remove(className),
    setTimeout(function() {
        sidenav.classList.add("d-none")
    }, 100),
    sidenav.classList.remove("bg-transparent")) : (body.classList.add(className),
    iconSidenav.classList.remove("d-none"),
    sidenav.classList.remove("d-none"))
}
iconNavbarSidenav && iconNavbarSidenav.addEventListener("click", toggleSidenav),
iconSidenav && iconSidenav.addEventListener("click", toggleSidenav);