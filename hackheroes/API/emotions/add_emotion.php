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
    $basicEmotionImage = $_POST["basicEmotionImage"];
    $basicEmotion = sanitize_sql_string(sanitize_html_string($_POST["basicEmotion"]));
    $extendedEmotion = sanitize_sql_string(sanitize_html_string($_POST["extendedEmotion"]));
    $explanation = sanitize_sql_string(sanitize_html_string($_POST["explanation"]));
    $public = $_POST["public"];
    $mysqldate = date ('Y-m-d');
    $last_ID = 0;
    $error = '';
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

    //Dodaj emocję jeżeli zalogowany i pole nie jest puste
    if ($current_user_id <> 0) {
        if ($explanation != "") {
            $sql = "INSERT INTO Emotions (userID, insertionDate, basicEmotionImage, basicEmotion, extendedEmotion, explanation, public)
            VALUES (\"$current_user_id\", \"$mysqldate\", \"$basicEmotionImage\", \"$basicEmotion\", \"$extendedEmotion\", \"$explanation\", \"$public\");";

            if ($conn->query($sql) === TRUE) {
                //Pobierz ostatnie ID
                $last_ID = $conn->insert_id;
            } else {
                $error = $error.", ".$conn->error;
                $operation_error = 1;
            }

            //Czy operacja się powiodła? Wypisz komunikat.
            if ($operation_error == 0) {
                $arr = array('result' => 'Pomyślnie dodano wpis do dziennika emocji.', 'resultType' => 'success', 'ID' => $last_ID );
                echo json_encode($arr);
            } else {
                $arr = array('result' => 'Wystąpił błąd operacji. Spróbuj ponownie. Dane dla nerdów: '.$error, 'resultType' => 'danger', 'ID' => '0');
                echo json_encode($arr);
            }
        }
        else {
            $arr = array('result' => 'Uzupełnij opis emocji w dzienniku emocji. Okienko opisu nie może pozostać puste.', 'resultType' => 'warning', 'ID' => '0' );
                echo json_encode($arr);
        }
    } else {
        $arr = array('result' => 'Użytkownik nie jest zalogowany.', 'resultType' => 'danger', 'ID' => '0');
        echo json_encode($arr);
    }
?>