function notif(msg) {

    if ($("#notification").length > 0) {
        $("#notification").append(msg);
    }else{
        $("body").append('<div id="notification"></div>');
        $("#notification").append(msg);

        setTimeout(function(){
            $("#notification").remove();
            $("#notif_content").remove();
        }, 10000);
    }
}
