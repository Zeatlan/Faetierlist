$(document).ready(function() {
  var forEach = function(t, o, r) {
    if ("[object Object]" === Object.prototype.toString.call(t))
      for (var c in t)
        Object.prototype.hasOwnProperty.call(t, c) && o.call(r, t[c], c, t);
    else for (var e = 0, l = t.length; l > e; e++) o.call(r, t[e], e, t);
  };
  var hamburgers = document.querySelectorAll(".hamburger");
  if (hamburgers.length > 0) {
    forEach(hamburgers, function(hamburger) {
      hamburger.addEventListener(
        "click",
        function() {
          this.classList.toggle("is-active");
        },
        false
      );
    });
  }

  var clickonmenu = 0;
  $(".hamburger").click(function() {
    if (clickonmenu == 1) {
      $(".menu").removeClass("anim_menu");
      $(".menu").toggleClass("anim_menu_r");
      $(".focus_menu").removeClass("open");

      clickonmenu = 0;
    } else {
      $(".menu").removeClass("anim_menu_r");
      $(".menu").toggleClass("anim_menu");
      $(".focus_menu").toggleClass("open");
      clickonmenu = 1;
    }
  });
  $(".focus_menu").click(function() {
    $(".menu").removeClass("anim_menu");
    $(".menu").toggleClass("anim_menu_r");
    $(".focus_menu").removeClass("open");
    clickonmenu = 0;
    $(".hamburger").removeClass("is-active");
  });
});
