function registerUser() {
    var register_username = $('#username').val();
    var register_password = $('#password').val();

    //Wyślij dane do API POSTem
    $.post("API/users/register.php", {
        register_username: register_username,
        register_password: register_password
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
            $('#registerButton').attr("disabled", true);
            setTimeout(redirectToLogin(), 2000); 
        }
    });
}

$('form#registerForm').on('submit', function(form) {
    //Wywołaj właściwą funkcję rejestracji
    registerUser();

    //Nie pozwól przeładować strony
    form.preventDefault();
});

function redirectToLogin() {
    //Przekieruj do logowania
    window.location.href = "login.html";
}

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