<?php 
    //Jeżeli podano ID sesji, zmień id sesji
    //Dołącz zewnętrzną bibliotekę do sanityzacji
    require ($_SERVER['DOCUMENT_ROOT'] . '/PHP/sanitize.php');
    //Dołącz konfigurację aplikacji
    require ($_SERVER['DOCUMENT_ROOT'] . '/PHP/config.php');
    //Dołącz obsługę sesji
    require ($_SERVER['DOCUMENT_ROOT'] . '/PHP/session.php');
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
    $sql = "SELECT * FROM Emotions WHERE public = TRUE ORDER BY id DESC LIMIT 5";
    //Wyciągnij wszystkie emocje
    $result=mysqli_query($conn, $sql);

    if ($result->num_rows > 0) {
        //Jeżeli emocje istnieją, pobierz dane
        while($row = $result->fetch_assoc()) {
            //Rób tablicę wszystkich wpisów!
            array_push($all_emotions_array, array("ID"=>$row["id"], "userID"=>$row["userID"], "insertionDate"=>$row["insertionDate"], "basicEmotionImage"=>$row["basicEmotionImage"], 
                "basicEmotion"=>$row["basicEmotion"], "extendedEmotion"=>$row["extendedEmotion"],"explanation"=>$row["explanation"],"public"=>$row["public"],"hearts"=>$row["hearts"]));
        }
        //Na koniec wypluj z API tablicę zawierającą wszystkie wpisy do świata emocji jako JSON
        $arr = array('result' => 'Znaleziono emocje.', 'resultType' => 'info', 'data' => $all_emotions_array);
        echo json_encode($arr);
    } 
    else {
        $arr = array('result' => 'Nie znaleziono emocji.', 'resultType' => 'danger', 'data' => '');
        echo json_encode($arr);
    }
?>