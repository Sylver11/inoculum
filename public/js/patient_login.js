$( document ).ready(function() {
    $('form input[type="text"]').blur(function(){
        if(!$(this).val()){
            $(this).addClass("error");
        } else{
            $(this).removeClass("error");
        }
    });
    $('form').on('submit', function(e) { 
        e.preventDefault();
        var firstname = $("input[name='firstname']",this).val();
        var secondname = $("input[name='secondname']",this).val();
        var email = $("input[name='email']",this).val();
        if( firstname && secondname && email){
            var redirectString = "/booking/my-bookings/" + firstname + "/" + secondname + "/" + email
            window.location.replace(redirectString);
        }
    });

});