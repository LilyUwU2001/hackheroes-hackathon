<?php 
    //Jeżeli podano ID sesji, zmień id sesji
    if (isset($_GET["session_id"])) {
        session_id($_GET["session_id"]);
    }
    //Dołącz zewnętrzną bibliotekę do sanityzacji
    require ($_SERVER['DOCUMENT_ROOT'] . '/PHP/sanitize.php');
    //Dołącz konfigurację aplikacji
    require ($_SERVER['DOCUMENT_ROOT'] . '/PHP/config.php');
    //Dołącz obsługę sesji
    require ($_SERVER['DOCUMENT_ROOT'] . '/PHP/session.php');
    $current_user_id = $_SESSION["user"];
    $all_hearts_array = [];
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
    $sql = "SELECT * FROM Hearts WHERE userID = '$current_user_id'";
    $result=mysqli_query($conn, $sql);

    if ($result->num_rows > 0) {
        //Jazda z koksem, wyciągamy serduszka zalogowanego
        while($row = $result->fetch_assoc()) {
            array_push($all_hearts_array, array("postID"=>$row["postID"]));
        }
        $arr = array('result' => 'Znaleziono serduszka.', 'resultType' => 'info', 'data' => $all_hearts_array);
        echo json_encode($arr);
    } 
    else {
        $arr = array('result' => 'Nie znaleziono serduszek.', 'resultType' => 'danger', 'data' => '');
        echo json_encode($arr);
    }
?>