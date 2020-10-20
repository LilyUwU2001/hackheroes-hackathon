function loginUser() {
    var login_username = $('#username').val();
    var login_password = $('#password').val();

    //Wyślij dane do API POSTem
    $.post("API/users/set_login.php", {
        login_username: login_username,
        login_password: login_password
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
            setTimeout(redirectToIndex(), 2000); 
        }
    });
}

$('form#loginForm').on('submit', function(form) {
    //Wywołaj właściwą funkcję logowania
    loginUser();

    //Nie pozwól przeładować strony
    form.preventDefault();
});

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
