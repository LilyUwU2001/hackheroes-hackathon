function changePassword() {
    var password = $('#password').val();
    var passwordAgain = $('#passwordAgain').val();

    //Wyślij dane do API POSTem
    $.post("API/users/change_password.php", {
        password: password,
        passwordAgain: passwordAgain
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
        //Jeśli operacja się powiodła, wyloguj użytkownika
        if (json.resultType == 'success') {
            logoutUser();
        }
    });
}