function logoutUser() {
    //Wyślij dane do API POSTem
    $.post("API/users/logout.php", {
        //Nie wysyłaj nic
    }).done(function(response) {
        //Wyświetl zwróconą wiadomość z API
        var json = $.parseJSON(response)
        $('#innerMessage').bsAlert(
            {
                type: json.resultType, 
                content: json.result,
                dismissible: true
            }
        );
        //Jeśli operacja się powiodła, zablokuj link do logowania i przycisk rejestracji do momentu przekierowania
        if (json.resultType == 'success') {
            $('#loginButton').attr("disabled", true);
            setTimeout(redirectToLanding(), 2000); 
        }
});
}

function redirectToLanding() {
    //Przekieruj do landinga
    window.location.href = "landing.html";
}

$(function() {
    //Pobierz parametry z URLa
    var singleEmotionNumber = 0;
    singleEmotionNumber = $.urlParam('emotion_id')
    $.get("API/users/get_login.php", {
        //Nie wysyłaj nic
    }).done(function(response) {
        //Wyświetl zwróconą wiadomość z API
        var json = $.parseJSON(response)
        $('#username').html(json.username);
    });
    spawnSingleEmotion(singleEmotionNumber);
});

$.urlParam = function(name){
    //Malutka regexpowa funkcja do wyciagania parametrów z URLa
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results==null) {
       return null;
    }
    return decodeURI(results[1]) || 0;
}