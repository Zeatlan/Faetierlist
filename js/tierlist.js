$( document ).ready(function() {
  


            $(".vignettes").each(function(){
                var width_vcslide = $(".vcontent_slide",this).width()+20;
                var nb_item_show = 0;
                //150 is 130 + 20 <=> width cover + margin
                nb_item_show = Math.trunc(width_vcslide/150);
                var slidevalue = nb_item_show*150;
                console.log(width_vcslide);
                console.log(nb_item_show);
                var nb_item = $(".vcontent_slide",this).children().length;
                var nb_page = Math.ceil(nb_item/nb_item_show)-1;
                
          var count_rclick = 0;
          var count_lclick = 0;
          var pos = 0;
          var left = $(".v_arrow_left", this);
          var right = $(".v_arrow_right", this);
          var content = $('.vcontent_slide', this);
        
        
          left.css('background', 'rgba(158,158,158 ,1)');
        
          if ( nb_page <= 0) {
        
              right.css('background', 'rgba(158,158,158 ,1)');
          }
        
          if (count_rclick >= 0) {
        
              right.click(function() {
                  if (count_rclick < nb_page) {
                      count_rclick++;
                      pos = pos + (-slidevalue);
                      content.animate({
                          'margin-left': pos + "px"
                      }, 500);
        
                      content.css('margin-left', '-1050px');
                      left.css('background', '#5586d0');
                      if (count_rclick >= nb_page) {
                          right.css('background', 'rgba(158,158,158 ,1)');
                      }
                  }
              });
          }
        
          left.click(function() {
        
              if (count_rclick > 0) {
                  count_lclick++;
                  count_rclick--;
                  right.css('background', '#5586d0');
                  pos = pos + slidevalue;
                  content.animate({
                      'margin-left': pos + "px"
                  }, 500);
                  content.css('margin-left', '0px');
              }
        
              if (count_rclick == 0) {
                  left.css('background', 'rgba(158,158,158 ,1)');
              }
        
          });
        
        });
        
        $( "i" ).hover(
            function() {
              $( this ).siblings( ".box_hover_info" ).addClass('box_visible');
            }, function() {
              $( this ).siblings( ".box_hover_info" ).removeClass('box_visible');
            }
          );
        
        });