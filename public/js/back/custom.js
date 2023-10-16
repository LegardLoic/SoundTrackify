$(document).ready(function(){
  $('.datepicker').datepicker({
      format: 'yyyy-mm-dd', // Le format de date que vous souhaitez
      autoclose: true
  });
});

var win = navigator.platform.indexOf('Win') > -1;
if (win && document.querySelector('#sidenav-scrollbar')) {
  var options = {
    damping: '0.5'
  }
  Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
}

window.onload = function() {
  for (var e = document.querySelectorAll("input"), t = 0; t < e.length; t++)
      e[t].addEventListener("focus", function(e) {
          this.parentElement.classList.add("is-focused")
      }, !1),
      e[t].onkeyup = function(e) {
          "" != this.value ? this.parentElement.classList.add("is-filled") : this.parentElement.classList.remove("is-filled")
      }
      ,
      e[t].addEventListener("focusout", function(e) {
          "" != this.value && this.parentElement.classList.add("is-filled"),
          this.parentElement.classList.remove("is-focused")
      }, !1);
  for (var s = document.querySelectorAll(".btn"), t = 0; t < s.length; t++)
      s[t].addEventListener("click", function(e) {
          var t = e.target
            , s = t.querySelector(".ripple");
          (s = document.createElement("span")).classList.add("ripple"),
          s.style.width = s.style.height = Math.max(t.offsetWidth, t.offsetHeight) + "px",
          t.appendChild(s),
          s.style.left = e.offsetX - s.offsetWidth / 2 + "px",
          s.style.top = e.offsetY - s.offsetHeight / 2 + "px",
          s.classList.add("ripple"),
          setTimeout(function() {
              s.parentElement.removeChild(s)
          }, 600)
      }, !1)
      
}
function focused(e) {
  e.parentElement.classList.contains("input-group") && e.parentElement.classList.add("focused")
}
function defocused(e) {
  e.parentElement.classList.contains("input-group") && e.parentElement.classList.remove("focused")
}