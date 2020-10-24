<?php 
    //Dołącz zewnętrzną bibliotekę do sanityzacji
    require ($_SERVER['DOCUMENT_ROOT'] . '/hackheroes/PHP/sanitize.php');
    //Dołącz konfigurację aplikacji
    require ($_SERVER['DOCUMENT_ROOT'] . '/hackheroes/PHP/config.php');
    //Dołącz obsługę sesji
    require ($_SERVER['DOCUMENT_ROOT'] . '/hackheroes/PHP/session.php');
    if (isset($_GET["emotion_ID"])) {
        $emotion_ID = $_GET["emotion_ID"];
    }
    $error = '';
    $operation_error = 0;
    $all_emotions_array = [];

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
    $sql = "SELECT * FROM Emotions WHERE id = '$emotion_ID'";
    //Wyciągnij wszystkie emocje użytkownika
    $result=mysqli_query($conn, $sql);

    if ($result->num_rows > 0) {
        //Jeżeli emocje istnieją, pobierz dane
        while($row = $result->fetch_assoc()) {
            //Rób tablicę wszystkich wpisów użytkownika!
            array_push($all_emotions_array, array("ID"=>$row["id"], "userID"=>$row["userID"], "insertionDate"=>$row["insertionDate"], "basicEmotionImage"=>$row["basicEmotionImage"], 
                "basicEmotion"=>$row["basicEmotion"], "extendedEmotion"=>$row["extendedEmotion"],"explanation"=>$row["explanation"]));
        }
        //Na koniec wypluj z API tablicę zawierającą wszystkie wpisy do dziennika emocji jako JSON
        $arr = array('result' => 'Znaleziono emocje.', 'resultType' => 'info', 'data' => $all_emotions_array);
        echo json_encode($arr);
    } 
    else {
        $arr = array('result' => 'Nie znaleziono emocji.', 'resultType' => 'danger', 'data' => '');
        echo json_encode($arr);
    }
?>