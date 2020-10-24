<?php
//Dołącz konfigurację
require ($_SERVER['DOCUMENT_ROOT'] . '/hackheroes/PHP/config.php');
$migration_error = 0;

//Utwórz obiekt z połączeniem
$conn = new mysqli($servername, $username, $password);

//Sprawdź połączenie z bazą
if ($conn->connect_error) {
    die("Błąd podczas łączenia z serwerem: " . $conn->connect_error);
}
echo "Połączono pomyślnie z serwerem. <br>";

//Użyj bazy
$sql = "USE ".$dbname;

if ($conn->query($sql) === TRUE) {
    echo "Połączono z bazą danych.<br>";
} else {
    echo "Błąd podczas łączenia z bazą: " . $conn->error . "<br>";
	$migration_error = 1;
}

//Usuń tabelę "Użytkownicy"
$sql = "DROP TABLE IF EXISTS Hearts";
    
if ($conn->query($sql) === TRUE) {
    echo "Usunięto pomyślnie tabelę Hearts.<br>";
} else {
    echo "Błąd podczas usuwania tabeli Hearts: " . $conn->error . "<br>";
    $migration_error = 1;
}

//Stwórz tabelę "Użytkownicy"
$sql = "CREATE TABLE Hearts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    userID BIGINT UNSIGNED,
    postID BIGINT UNSIGNED,
)";

if ($conn->query($sql) === TRUE) {
    echo "Utworzono pomyślnie tabelę Hearts.<br>";
} else {
    echo "Błąd podczas tworzenia tabeli Hearts: " . $conn->error . "<br>";
	$migration_error = 1;
}

//Czy migracja się powiodła? Wypisz komunikat.
if ($migration_error == 0) {
	echo "Migracja wykonana pomyślnie.";
} else {
	echo "Błąd migracji, spróbuj ponownie.";
}
?>