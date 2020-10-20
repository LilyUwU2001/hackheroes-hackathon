<?php 
    //Jeżeli podano ID sesji, zmień id sesji
    if (isset($_GET["session_id"])) {
        session_id($_GET["session_id"]);
    }
    //Dołącz zewnętrzną bibliotekę do sanityzacji
    require ($_SERVER['DOCUMENT_ROOT'] . '/hackheroes/PHP/sanitize.php');
    //Dołącz konfigurację aplikacji
    require ($_SERVER['DOCUMENT_ROOT'] . '/hackheroes/PHP/config.php');
    //Dołącz obsługę sesji
    require ($_SERVER['DOCUMENT_ROOT'] . '/hackheroes/PHP/session.php');
    $current_user_id = $_SESSION["user"];
    $basicEmotionImage = $_POST["basicEmotionImage"];
    $basicEmotion = sanitize_sql_string(sanitize_html_string($_POST["basicEmotion"]));
    $extendedEmotion = sanitize_sql_string(sanitize_html_string($_POST["extendedEmotion"]));
    $explanation = sanitize_sql_string(sanitize_html_string($_POST["explanation"]));
    $mysqldate = date ('Y-m-d');
    $last_ID = 0;
    $operation_error = 0;

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

    //Dodaj emocję jeżeli zalogowany
    if ($current_user_id <> 0) {
        $sql = "INSERT INTO Emotions (userID, insertionDate, basicEmotionImage, basicEmotion, extendedEmotion, explanation)
        VALUES (\"$current_user_id\", \"$mysqldate\", \"$basicEmotionImage\", \"$basicEmotion\", \"$extendedEmotion\", \"$explanation\");";

        if ($conn->query($sql) === TRUE) {
            //Pobierz ostatnie ID
            $last_ID = $conn->insert_id;
        } else {
            $error = $error.", ".$conn->error;
            $operation_error = 1;
        }

        //Czy operacja się powiodła, i nie ma takiego użytkownika? Wypisz komunikat.
        if ($operation_error == 0) {
            $arr = array('result' => 'Pomyślnie dodano wpis do dziennika emocji.', 'resultType' => 'success', 'ID' => $last_ID );
            echo json_encode($arr);
        } else {
            $arr = array('result' => 'Wystąpił błąd operacji. Spróbuj ponownie. Dane dla nerdów: '.$error, 'resultType' => 'danger', 'ID' => '0');
            echo json_encode($arr);
        }
    } else {
        $arr = array('result' => 'Użytkownik nie jest zalogowany.', 'resultType' => 'danger', 'ID' => '0');
        echo json_encode($arr);
    }
?>