<?php 
    //Dołącz zewnętrzną bibliotekę do sanityzacji
    require ($_SERVER['DOCUMENT_ROOT'] . '/hackheroes/PHP/sanitize.php');
    //Dołącz konfigurację aplikacji
    require ($_SERVER['DOCUMENT_ROOT'] . '/hackheroes/PHP/config.php');
    //Dołącz obsługę sesji
    require ($_SERVER['DOCUMENT_ROOT'] . '/hackheroes/PHP/session.php');
    //Pobierz dane z POSTa i zsanityzuj je używając biblioteki
    $login_username = sanitize_sql_string(sanitize_html_string($_POST["login_username"]));
    $login_password = sanitize_sql_string(sanitize_html_string($_POST["login_password"]));
    //Utwórz hasło z saltem, używając md5 z nazwy użytkownika + podanego hasła
    $final_password = md5(sanitize_sql_string(sanitize_html_string($_POST["login_username"])).sanitize_sql_string(sanitize_html_string($_POST["login_password"])));
    $error = "";
    $operation_error = 0;
    $form_correctly_filled = 0;

    //Zweryfikuj formularz po stronie PHP
    if ($login_username != "" and $login_password != "") {
        $form_correctly_filled = 1;
    } else {
        $form_correctly_filled = 0;
        $arr = array('result' => 'Formularz nie został poprawnie uzupełniony. Spróbuj ponownie, wypełniając poprawnie cały formularz.', 'resultType' => 'warning');
        echo json_encode($arr);
    }

    //Wykonaj kod logowania tylko jeżeli formularz jest wypełniony
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

        //Przeprowadź operację logowania
        $sql = "SELECT * FROM Users WHERE username = '$login_username' AND password = '$final_password'";
        $result=mysqli_query($conn, $sql);

        if ($result->num_rows > 0) {
            //Jeżeli użytkownik o podanych danych istnieje
            while($row = $result->fetch_assoc()) {
                $arr = array('result' => 'Zalogowano pomyślnie. Za chwilę nastąpi przekierowanie na stronę główną aplikacji.', 'resultType' => 'success', 'sessionID' => session_id());
                echo json_encode($arr);
                $_SESSION["user"]=$row["id"];
            }
            } else {
                $arr = array('result' => 'Podano złą nazwę użytkownika lub hasło. Spróbuj ponownie, używając poprawnych danych.', 'resultType' => 'danger');
                echo json_encode($arr);
                $error_instalacji = 1;
        }
    }
?>