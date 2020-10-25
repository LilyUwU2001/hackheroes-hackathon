<?php 
    //Jeżeli podano ID sesji, zmień id sesji
    if (isset($_POST["session_id"])) {
        session_id($_GET["session_id"]);
    }
    //Dołącz zewnętrzną bibliotekę do sanityzacji
    require ($_SERVER['DOCUMENT_ROOT'] . '/hackheroes/PHP/sanitize.php');
    //Dołącz konfigurację aplikacji
    require ($_SERVER['DOCUMENT_ROOT'] . '/hackheroes/PHP/config.php');
    //Dołącz obsługę sesji
    require ($_SERVER['DOCUMENT_ROOT'] . '/hackheroes/PHP/session.php');
    $current_user_id = $_SESSION["user"];
    $theme_name = sanitize_sql_string(sanitize_html_string($_POST["theme"]));
    $error = "";
    $operation_error = 0;

    //Utwórz obiekt z połączeniem
    $conn = new mysqli($servername, $username, $password);

    //Sprawdź połączenie z bazą
    if ($conn->connect_error) {
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

    //Przeprowadź operację aktualizacji motywu
    $sql = "UPDATE Users SET theme = '$theme_name' WHERE id = ".$current_user_id;
    if ($conn->query($sql) === TRUE) {
        //Nie rób nic
    } else {
        $error = $error.", ".$conn->error;
        $operation_error = 1;
    }

    if ($operation_error == 0) {
        //Jeżeli użytkownik o podanych danych istnieje, zmień motyw na serwerze
        $arr = array('result' => 'Pomyślnie zmieniono motyw.', 'resultType' => 'success');
        echo json_encode($arr);
    } else {
        $arr = array('result' => 'Błąd podczas zmiany motywu. Dane dla nerdów: '.$error, 'resultType' => 'danger');
        echo json_encode($arr);
    }
?>