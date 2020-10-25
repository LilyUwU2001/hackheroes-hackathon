<?php 
    //Jeżeli podano ID sesji, zmień id sesji
    if (isset($_POST["session_id"])) {
        session_id($_POST["session_id"]);
    }
    //Dołącz zewnętrzną bibliotekę do sanityzacji
    require ($_SERVER['DOCUMENT_ROOT'] . '/PHP/sanitize.php');
    //Dołącz konfigurację aplikacji
    require ($_SERVER['DOCUMENT_ROOT'] . '/PHP/config.php');
    //Dołącz obsługę sesji
    require ($_SERVER['DOCUMENT_ROOT'] . '/PHP/session.php');
    $current_user_id = $_SESSION["user"];
    $changePassword = sanitize_sql_string(sanitize_html_string($_POST["password"]));
    $passwordAgain = sanitize_sql_string(sanitize_html_string($_POST["passwordAgain"]));
    $password_length = strlen($changePassword);
    $current_user_id = $_SESSION["user"];
    $error = '';
    $operation_error = 0;

    //Zweryfikuj formularz po stronie PHP
    if ($changePassword != "" and $passwordAgain != "") {
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

    //Zweryfikuj czy oba hasła są takie same
    if ($form_correctly_filled == 1) {
        if ($changePassword != $passwordAgain) {
            $form_correctly_filled = 0;
            $arr = array('result' => 'Hasła nie są takie same. Spróbuj ponownie, wpisując w oba pola te same hasło.', 'resultType' => 'warning');
            echo json_encode($arr);
        }
    }

    //Utwórz obiekt z połączeniem
    $conn = new mysqli($servername, $username, $password);

    //Sprawdź połączenie z bazą
    if ($conn->connect_error) {
        $error = $conn->connect_error;
        $operation_error = 1;
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

    //Pobierz nazwę użytkownika
    //Przeprowadź uproszczoną procedurę operację pobierania danych sesji
    $sql = "SELECT * FROM Users WHERE id = '$current_user_id'";
    $result=mysqli_query($conn, $sql);

    if ($result->num_rows > 0) {
        //Jeżeli użytkownik o podanych danych istnieje, wyciągnij jego dane
        while($row = $result->fetch_assoc()) {
            $current_username = $row["username"];
        }
    }

    //Utwórz hasło z saltem, używając md5 z nazwy użytkownika + podanego hasła
    $final_password = md5(sanitize_sql_string(sanitize_html_string($current_username)).sanitize_sql_string(sanitize_html_string($_POST["password"])));

    //Wykonaj kod zmiany hasła tylko jeżeli formularz jest wypełniony
    if ($form_correctly_filled == 1) {
        $sql = "UPDATE Users SET password = '$final_password' WHERE id = '$current_user_id'";
        $result=mysqli_query($conn, $sql);

        if ($conn->query($sql) === TRUE) {
            //Nie rób nic
        } else {
            $error = $error.", ".$conn->error;
            $operation_error = 1;
        }
        if ($operation_error == 0) {
            $arr = array('result' => 'Pomyślnie zmieniono hasło.', 'resultType' => 'success',);
            echo json_encode($arr);
        }
        else {
            $arr = array('result' => 'Błąd podczas zmiany hasła. Dane dla nerdów: '.$error, 'resultType' => 'danger');
            echo json_encode($arr);
        }
    }
?>