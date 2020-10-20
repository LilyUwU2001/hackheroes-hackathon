function redirectToIndex() {
    //Przekieruj do aplikacji
    window.location.href = "index.html";
}

//Jeżeli zalogowany, przekieruj do apki
$(function() {
    $.get("API/users/get_login.php", {
        //Nie wysyłaj nic
    }).done(function(response) {
        //Wyświetl zwróconą wiadomość z API
        var json = $.parseJSON(response)
        if(json.ID != 0) {
            $('#innerMessage').bsAlert(
                {
                    type: json.resultType, 
                    content: json.result,
                    dismissible: true
                }
            );
            setTimeout(redirectToIndex(), 2000); 
        }
    });
});