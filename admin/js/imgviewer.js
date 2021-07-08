$( document ).ready(function() {

    //ADD GENDER
    function readURL(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          
          reader.onload = function(e) {
            $('#view').css('background', 'url('+e.target.result+') center no-repeat');
            $('#view').css('background-size', 'cover');
            
          }
          
          reader.readAsDataURL(input.files[0]);
        }
      }
      
      $("input[type='file']").change(function() {
        readURL(this);
      });


      //EDIT GENDER



    });