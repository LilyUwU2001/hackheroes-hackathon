<?php 
    //Jeżeli podano ID sesji, zmień id sesji
    if (isset($_POST["session_id"])) {
        session_id($_POST["session_id"]);
    }
    //Dołącz zewnętrzną bibliotekę do sanityzacji
    require ($_SERVER['DOCUMENT_ROOT'] . '/hackheroes/PHP/sanitize.php');
    //Dołącz konfigurację aplikacji
    require ($_SERVER['DOCUMENT_ROOT'] . '/hackheroes/PHP/config.php');
    //Dołącz obsługę sesji
    require ($_SERVER['DOCUMENT_ROOT'] . '/hackheroes/PHP/session.php');
    $current_user_id = $_SESSION["user"];
    $error = "";
    $operation_error = 0;

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

    //Przeprowadź operację pobierania danych sesji
    $sql = "SELECT * FROM Users WHERE id = '$current_user_id'";
    $result=mysqli_query($conn, $sql);

    if ($result->num_rows > 0) {
        //Jeżeli użytkownik o podanych danych istnieje, wyloguj go
        while($row = $result->fetch_assoc()) {
            $_SESSION["user"] = 0;
            //Nowe ID dla sesji
            session_regenerate_id();
        }
    } else {
        $operation_error = 1;
    }

    if ($operation_error == 0) {
        $arr = array('result' => 'Wylogowano pomyślnie. Za chwilę nastąpi przekierowanie na stronę główną.', 'resultType' => 'success', 'sessionID' => session_id());
        echo json_encode($arr);
    } else {
        $arr = array('result' => 'Błąd wylogowywania. Spróbuj ponownie.', 'resultType' => 'danger', 'sessionID' => session_id());
        echo json_encode($arr);
    }

?>