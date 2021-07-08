<?php include 'skeleton.php'; ?>
<script>
var lFollowX = 0,
    lFollowY = 0,
    x = 0,
    y = 0,
    friction = 1 / 200;

function moveBackground() {
  x += (lFollowX - x) * friction;
  y += (lFollowY - y) * friction;
  
  translate = 'translate(' + x + 'px, ' + y + 'px) scale(1.1)';

  $('.render').css({
    '-webit-transform': translate,
    '-moz-transform': translate,
    'transform': translate
  });

  window.requestAnimationFrame(moveBackground);
}

$(window).on('mousemove click', function(e) {

  var lMouseX = Math.max(-100, Math.min(100, $(window).width() / 2 - e.clientX));
  var lMouseY = Math.max(-100, Math.min(100, $(window).height() / 2 - e.clientY));
  lFollowX = (20 * lMouseX) / 100; // 100 : 12 = lMouxeX : lFollow
  lFollowY = (10 * lMouseY) / 100;

});

moveBackground();
</script>
<div class="infos_content_1">
<div class="info_item" >
        <div class="render"></div>
        <div class="title_user">Zeatlan</div>
    </div>
    <div class="info_item">
        <div class="img_infos_user" style="background:url('img/elecban.png') center;background-size:cover;"></div>
    </div>
</div>
<div class="infos_subtitle">Présentent</div>
<div class="infos_content_2"><div class="logo_i"><div class="logop1_i">FAE</div>Tierlist</div></div>
<div class="infos_subtitle">en version <font color="#5957F2" style="font-size:16pt;">BETA 0.1</font> </div>
<div class="infos_content_1">
    <div class="info_item">
    <div class="content_info_item"><i class="fab fa-discord"></i><i class="fab fa-steam"></i><i class="fab fa-facebook-square"></i></div>
</div>
<div class="info_item">
<div class="content_info_item"><i class="fab fa-discord"></i><i class="fab fa-twitter"></i> <i class="fab fa-steam"></i><i class="fab fa-instagram"></i></div>
</div>

</div>
<div class="infos_subtitle"  style="font-size:10pt;">Optimisé pour <i class="fab fa-chrome"></i> <i class="fas fa-desktop"></i><div>FAETierlist © 2019 - Les couvertures d'animes et certaines images appartiennent à leurs propriétaires respectifs.</div>  </font> 