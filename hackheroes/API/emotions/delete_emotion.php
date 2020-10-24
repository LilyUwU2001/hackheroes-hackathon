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
    $emotionID = $_POST["emotionID"];
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

    //Spreparuj SQLa wyszukiwarki
    $sql = "DELETE FROM Emotions WHERE userID = '$current_user_id' AND id = '$emotionID'";
    //Wyciągnij emocję o danym ID
    $result=mysqli_query($conn, $sql);

    if ($operation_error == 0) {
        //Na koniec wypluj z API tablicę zawierającą informację do jako JSON
        $arr = array('result' => 'Usunięto wpis w dzienniku emocji.', 'resultType' => 'success');
        echo json_encode($arr);
    } 
    else {
        $arr = array('result' => 'Nie znaleziono wpisu w dzienniku emocji.', 'resultType' => 'danger');
        echo json_encode($arr);
    }
?>