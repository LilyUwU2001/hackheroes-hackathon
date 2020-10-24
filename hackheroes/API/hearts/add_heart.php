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
    $hearted_post_id = $_POST["heart_id"];
    $last_ID = 0;
    $error = '';
    $hearts_number = 0;
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

    //Dodaj serduszko jeśli zalogowany
    if ($current_user_id <> 0) {
        //Najpierw dodaj serduszko
        $sql = "INSERT INTO Hearts (userID, postID)
        VALUES (\"$current_user_id\", \"$hearted_post_id\");";

        if ($conn->query($sql) === TRUE) {
            //Pobierz ostatnie ID
            $last_ID = $conn->insert_id;
        } else {
            $error = $error.", ".$conn->error;
            $operation_error = 1;
        }

        //Potem pobierz liczbę serduszek we wpisie
        $sql = "SELECT * FROM Emotions WHERE id = ".$hearted_post_id;
        //Wyciągnij liczbę serduszek
        $result=mysqli_query($conn, $sql);

        if ($result->num_rows > 0) {
            //Jeżeli wpis istnieje, pobierz dane
            while($row = $result->fetch_assoc()) {
                //Zapisz liczbę serduszek
                $hearts_number = $row["hearts"];
                //Zinkrementuj ją o 1
                $hearts_number = $hearts_number + 1;
            }
        } 

        //Na koniec zainkrementuj liczbę serduszek we wpisie
        $sql = "UPDATE Emotions SET hearts = ".$hearts_number." WHERE id = ".$hearted_post_id;
        $result=mysqli_query($conn, $sql);

        //Czy operacja się powiodła? Wypisz komunikat.
        if ($operation_error == 0) {
            $arr = array('result' => 'Pomyślnie dano serduszko.', 'resultType' => 'success', 'ID' => $last_ID );
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