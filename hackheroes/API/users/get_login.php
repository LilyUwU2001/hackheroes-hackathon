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
        //Nie rób nic
    }

    //Przeprowadź operację pobierania danych sesji
    $sql = "SELECT * FROM Users WHERE id = '$current_user_id'";
    $result=mysqli_query($conn, $sql);

    if ($result->num_rows > 0) {
        //Jeżeli użytkownik o podanych danych istnieje, wyciągnij jego dane
        while($row = $result->fetch_assoc()) {
            $arr = array('result' => 'Użytkownik jest zalogowany.', 'resultType' => 'info', 'ID' => $row["id"], 'username' => $row["username"], 'theme' => $row["theme"], 'sessionID' => session_id());
            echo json_encode($arr);
        }
        } else {
            $arr = array('result' => 'Użytkownik nie jest zalogowany.', 'resultType' => 'danger', 'ID' => 0, 'username' => 'not_logged_in', 'theme' => "default", 'sessionID' => session_id());
            echo json_encode($arr);
    }
?>