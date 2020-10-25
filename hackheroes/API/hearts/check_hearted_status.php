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
    $hearted_post_id = $_POST["heart_id"];
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
    $sql = "SELECT * FROM Hearts WHERE userID = '$current_user_id' AND postID = '$hearted_post_id'";
    //Sprawdź czy dany post jest oserduszkowany przez obecnie zalogowanego użytkownika
    $result=mysqli_query($conn, $sql);

    if ($result->num_rows > 0) {
        //Jeżeli serduszko istnieje, pobierz dane
        while($row = $result->fetch_assoc()) {
            $arr = array('result' => 'Post jest oserduszkowany.', 'resultType' => 'info', 'hearted' => 'true');
            echo json_encode($arr);
        } 
    }
    else {
        $arr = array('result' => 'Post nie jest oserduszkowany.', 'resultType' => 'danger', 'hearted' => 'false');
        echo json_encode($arr);
    }
?>