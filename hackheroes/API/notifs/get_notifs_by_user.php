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
    $error = '';
    $operation_error = 0;
    $all_notifs_array = [];

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
    $sql = "SELECT * FROM Notifs WHERE recipientID = ".$current_user_id;
    $sql = $sql." ORDER BY id DESC";
    //Wyciągnij wszystkie powiadomienia przeznaczone dla użytkownika
    $result=mysqli_query($conn, $sql);

    if ($result->num_rows > 0) {
        //Jeżeli powiadomienia istnieją, pobierz dane
        while($row = $result->fetch_assoc()) {
            //Rób tablicę wszystkich powiadomienia dla użytkownika!
            array_push($all_notifs_array, array("ID"=>$row["id"], "recipientID"=>$row["recipientID"], "postData"=>$row["postData"], "link"=>$row["link"]));
        }
        //Na koniec wypluj z API tablicę zawierającą wszystkie wpisy powiadomień jako JSON i usuń wszystkie załadowane powiadomienia
        $sql = "DELETE FROM Notifs WHERE recipientID = ".$current_user_id;
        //Usuń wpis
        $result=mysqli_query($conn, $sql);

        $arr = array('result' => 'Znaleziono powiadomienia', 'resultType' => 'info', 'data' => $all_notifs_array);
        echo json_encode($arr);
    } 
    else {
        $arr = array('result' => 'Nie znaleziono powiadomień.', 'resultType' => 'danger', 'data' => '');
        echo json_encode($arr);
    }
?>