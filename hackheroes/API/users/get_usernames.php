<?php 
    //Dołącz konfigurację aplikacji
    require ($_SERVER['DOCUMENT_ROOT'] . '/PHP/config.php');
    //Utwórz obiekt z połączeniem
    $conn = new mysqli($servername, $username, $password);
    $all_users_array = [];

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

    //Przeprowadź operację pobierania listy użytkowników
    $sql = "SELECT * FROM Users";
    $result=mysqli_query($conn, $sql);

    if ($result->num_rows > 0) {
        //Jazda z koksem, wyciągamy użytkowników
        while($row = $result->fetch_assoc()) {
            array_push($all_users_array, array("id"=>$row["id"], "username"=>$row["username"]));
        }
        $arr = array('result' => 'Znaleziono użytkowników.', 'resultType' => 'info', 'data' => $all_users_array);
        echo json_encode($arr);
    } 
    else {
        $arr = array('result' => 'Nie znaleziono użytkowników.', 'resultType' => 'danger', 'data' => '');
        echo json_encode($arr);
    }
?>