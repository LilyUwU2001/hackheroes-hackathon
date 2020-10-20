<?php
    //Dołącz zewnętrzną bibliotekę do sanityzacji
    require ($_SERVER['DOCUMENT_ROOT'] . '/hackheroes/PHP/sanitize.php');
    //Dołącz konfigurację aplikacji
    require ($_SERVER['DOCUMENT_ROOT'] . '/hackheroes/PHP/config.php');
    //Pobierz dane z POSTa i zsanityzuj je używając biblioteki
    $register_username = sanitize_sql_string(sanitize_html_string($_POST["register_username"]));
    $register_password = sanitize_sql_string(sanitize_html_string($_POST["register_password"]));
    $password_length = strlen($register_password);
    //Utwórz hasło z saltem, używając md5 z nazwy użytkownika + podanego hasła
    $final_password = md5(sanitize_sql_string(sanitize_html_string($_POST["register_username"])).sanitize_sql_string(sanitize_html_string($_POST["register_password"])));
    $error = "";
    $operation_error = 0;
    $form_correctly_filled = 0;
    $user_exists = 0;

    //Zweryfikuj formularz po stronie PHP
    if ($register_username != "" and $register_password != "") {
        $form_correctly_filled = 1;
    } else {
        $form_correctly_filled = 0;
        $arr = array('result' => 'Formularz nie został poprawnie uzupełniony. Spróbuj ponownie, wypełniając poprawnie cały formularz.', 'resultType' => 'warning');
        echo json_encode($arr);
    }

    //Zweryfikuj liczbę znaków w haśle
    if ($form_correctly_filled == 1 && $password_length < 8) {
        $form_correctly_filled = 0;
        $arr = array('result' => 'Twoje hasło ma długość krótszą od wymaganej długości 8 znaków. Spróbuj ponownie, wpisując dłuższe hasło.', 'resultType' => 'warning');
        echo json_encode($arr);
    }

    //Wykonaj kod rejestracji tylko jeżeli formularz jest wypełniony
    if ($form_correctly_filled == 1) {
        //Utwórz obiekt z połączeniem
        $conn = new mysqli($servername, $username, $password);

        //Sprawdź połączenie z bazą
        if ($conn->connect_error) {
            $error = $conn->connect_error;
            die();
        }

        //Użyj bazy
        $sql = "USE ".$dbname;

        if ($conn->query($sql) === TRUE) {
            //Nie rób nic
        } else {
            $error = $error.", ".$conn->error;
            $operation_error = 1;
        }

        //Sprawdź czy użytkownik przypadkiem nie istnieje
        $sql = "SELECT * FROM Users WHERE username = '$register_username'";
        $result=mysqli_query($conn, $sql);

        if(mysqli_num_rows($result)!=0)
        {
            $arr = array('result' => 'Taki użytkownik istnieje w systemie ASToolbox. Proszę się zalogować lub założyć konto o innej nazwie.', 'resultType' => 'warning');
            echo json_encode($arr);
            $user_exists = 1;
        }

        //Rejestruj tylko jeżeli taki użytkownik nie istnieje
        if($user_exists == 0 )
        {  
            //Zarejestruj użytkownika
            $sql = "INSERT INTO Users (username, password)
            VALUES (\"$register_username\", \"$final_password\");";

            if ($conn->query($sql) === TRUE) {
                //Nie rób nic
            } else {
                $error = $error.", ".$conn->error;
                $operation_error = 1;
            }
        }

        //Czy migracja się powiodła, i nie ma takiego użytkownika? Wypisz komunikat.
        if ($operation_error == 0) {
            if($user_exists == 0) {
            $arr = array('result' => 'Zarejestrowano pomyślnie. Za chwilę nastąpi przekierowanie do strony logowania.', 'resultType' => 'success');
            echo json_encode($arr);
            }
        } else {
            $arr = array('result' => 'Wystąpił błąd operacji. Spróbuj ponownie. Dane dla nerdów: '.$error, 'resultType' => 'danger');
            echo json_encode($arr);
        }
    }
?>