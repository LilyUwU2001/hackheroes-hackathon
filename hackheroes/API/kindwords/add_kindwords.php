<?php
    function checkForProfanities($text){
        //Wyzeruj status obecności wulgaryzmu.
        $profanitiesPresent = 0;
        //Dłuuuuuuuuga lista polskich wulgaryzmów.
        $filterWords = array('chuj', 'chuja', 'chujek', 'chujem', 'chujnia', 'chujowa', 'chujowe', 'chujowy', 'chuju', 'ciot', 'ciota', 'cip', 'cipa', 'cipach', 'cipami', 'cipce', 'cipe', 'cipek', 'cipie', 'cipka', 'cipkach', 'cipkami', 'cipki', 'cipko', 'cipkom', 'cipką', 'cipkę', 'cipo', 'cipom', 'cipy', 'cipą', 'cipę', 'ciul', 'debilu', 'dojebac', 'dojebal', 'dojebala', 'dojebalam', 'dojebalem', 'dojebać', 'dojebał', 'dojebała', 'dojebałam', 'dojebałem', 'dojebie', 'dojebię', 'dopieprzac', 'dopieprzać', 'dopierdala', 'dopierdalac', 'dopierdalajacy', 'dopierdalający', 'dopierdalal', 'dopierdalala', 'dopierdalać', 'dopierdalał', 'dopierdalała', 'dopierdole', 'dopierdoli', 'dopierdolic', 'dopierdolil', 'dopierdolić', 'dopierdolił', 'dopierdolę', 'downie', 'dupa', 'dupcia', 'dupe', 'dupeczka', 'dupie', 'dupy', 'dupą', 'dzifka', 'dzifko', 'dziwka', 'dziwko', 'fiucie', 'fiut', 'fuck', 'huj', 'huja', 'huje', 'hujek', 'hujem', 'hujnia', 'huju', 'hój', 'jebac', 'jebak', 'jebaka', 'jebako', 'jebal', 'jebana', 'jebane', 'jebanej', 'jebani', 'jebanka', 'jebankiem', 'jebanko', 'jebany', 'jebanych', 'jebanym', 'jebanymi', 'jebaną', 'jebać', 'jebał', 'jebcie', 'jebia', 'jebiaca', 'jebiacego', 'jebiacej', 'jebiacy', 'jebie', 'jebią', 'jebiąca', 'jebiącego', 'jebiącej', 'jebiący', 'jebię', 'jebliwy', 'jebna', 'jebnac', 'jebnal', 'jebnać', 'jebnela', 'jebnie', 'jebnij', 'jebną', 'jebnąc', 'jebnąć', 'jebnął', 'jebnęła', 'jebut', 'koorwa', 'korewko', 'kurestwo', 'kurew', 'kurewko', 'kurewska', 'kurewski', 'kurewskiej', 'kurewsko', 'kurewską', 'kurewstwo', 'kurwa', 'kurwaa', 'kurwach', 'kurwami', 'kurwe', 'kurwiarz', 'kurwic', 'kurwica', 'kurwidołek', 'kurwie', 'kurwik', 'kurwiki', 'kurwiska', 'kurwiszcze', 'kurwiszon', 'kurwiszona', 'kurwiszonem', 'kurwiszony', 'kurwiący', 'kurwić', 'kurwo', 'kurwy', 'kurwą', 'kurwę', 'kutas', 'kutasa', 'kutasach', 'kutasami', 'kutasem', 'kutasie', 'kutasow', 'kutasy', 'kutasów', 'kórewko', 'kórwa', 'lesbijko', 'matkojebca', 'matkojebcach', 'matkojebcami', 'matkojebcy', 'matkojebcą', 'morde', 'mordę', 'nabarłożyć', 'najebac', 'najebal', 'najebala', 'najebana', 'najebane', 'najebany', 'najebaną', 'najebać', 'najebał', 'najebała', 'najebia', 'najebie', 'najebią', 'nakurwiac', 'nakurwiamy', 'nakurwiać', 'nakurwiscie', 'nakurwiście', 'naopierdalac', 'naopierdalal', 'naopierdalala', 'naopierdalać', 'naopierdalał', 'naopierdalała', 'napierdalac', 'napierdalajacy', 'napierdalający', 'napierdalać', 'napierdolic', 'napierdolić', 'nawpierdalac', 'nawpierdalal', 'nawpierdalala', 'nawpierdalać', 'nawpierdalał', 'nawpierdalała', 'obsrywac', 'obsrywajacy', 'obsrywający', 'obsrywać', 'odpieprzac', 'odpieprzać', 'odpieprzy', 'odpieprzyl', 'odpieprzyla', 'odpieprzył', 'odpieprzyła', 'odpierdalac', 'odpierdalajaca', 'odpierdalajacy', 'odpierdalająca', 'odpierdalający', 'odpierdalać', 'odpierdol', 'odpierdoli', 'odpierdolic', 'odpierdolil', 'odpierdolila', 'odpierdolić', 'odpierdolił', 'odpierdoliła', 'opieprzający', 'opierdala', 'opierdalac', 'opierdalajacy', 'opierdalający', 'opierdalać', 'opierdol', 'opierdola', 'opierdoli', 'opierdolic', 'opierdolić', 'opierdolą', 'pedale', 'picza', 'piczka', 'piczo', 'pieprz', 'pieprzniety', 'pieprznięty', 'pieprzony', 'pierdel', 'pierdlu', 'pierdol', 'pierdola', 'pierdolaca', 'pierdolacy', 'pierdole', 'pierdolec', 'pierdolenie', 'pierdoleniem', 'pierdoleniu', 'pierdoli', 'pierdolic', 'pierdolicie', 'pierdolil', 'pierdolila', 'pierdolisz', 'pierdolić', 'pierdolił', 'pierdoliła', 'pierdolnac', 'pierdolnal', 'pierdolnela', 'pierdolnie', 'pierdolniety', 'pierdolnij', 'pierdolnik', 'pierdolnięty', 'pierdolny', 'pierdolnąć', 'pierdolnął', 'pierdolnęła', 'pierdolona', 'pierdolone', 'pierdolony', 'pierdolą', 'pierdoląca', 'pierdolący', 'pierdolę', 'pierdołki', 'pierdziec', 'pierdzieć', 'pierdzący', 'pizda', 'pizde', 'pizdnac', 'pizdnąć', 'pizdu', 'pizdzie', 'pizdą', 'pizdę', 'piździe', 'podjebac', 'podjebać', 'podkurwic', 'podkurwić', 'podpierdala', 'podpierdalac', 'podpierdalajacy', 'podpierdalający', 'podpierdalać', 'podpierdoli', 'podpierdolic', 'podpierdolić', 'pojeb', 'pojeba', 'pojebac', 'pojebalo', 'pojebami', 'pojebancu', 'pojebane', 'pojebanego', 'pojebanemu', 'pojebani', 'pojebany', 'pojebanych', 'pojebanym', 'pojebanymi', 'pojebać', 'pojebańcu', 'pojebem', 'popierdala', 'popierdalac', 'popierdalać', 'popierdolencu', 'popierdoleni', 'popierdoleńcu', 'popierdoli', 'popierdolic', 'popierdolić', 'popierdolone', 'popierdolonego', 'popierdolonemu', 'popierdolony', 'popierdolonym', 'porozpierdala', 'porozpierdalac', 'porozpierdalać', 'poruchac', 'poruchać', 'przejebac', 'przejebane', 'przejebać', 'przepierdala', 'przepierdalac', 'przepierdalajaca', 'przepierdalajacy', 'przepierdalająca', 'przepierdalający', 'przepierdalać', 'przepierdolic', 'przepierdolić', 'przyjebac', 'przyjebal', 'przyjebala', 'przyjebali', 'przyjebać', 'przyjebał', 'przyjebała', 'przyjebie', 'przypieprzac', 'przypieprzajaca', 'przypieprzajacy', 'przypieprzająca', 'przypieprzający', 'przypieprzać', 'przypierdala', 'przypierdalac', 'przypierdalajacy', 'przypierdalający', 'przypierdalać', 'przypierdoli', 'przypierdolic', 'przypierdolić', 'qrwa', 'rozjeb', 'rozjebac', 'rozjebali', 'rozjebaliście', 'rozjebaliśmy', 'rozjebać', 'rozjebał', 'rozjebała', 'rozjebałam', 'rozjebałaś', 'rozjebałem', 'rozjebałeś', 'rozjebało', 'rozjebały', 'rozjebałyście', 'rozjebałyśmy', 'rozjebcie', 'rozjebie', 'rozjebiecie', 'rozjebiemy', 'rozjebiesz', 'rozjebią', 'rozjebię', 'rozjebmy', 'rozpierdala', 'rozpierdalac', 'rozpierdalać', 'rozpierdole', 'rozpierdoli', 'rozpierdolic', 'rozpierdolić', 'rozpierducha', 'rucha', 'ruchacie', 'ruchaj', 'ruchajcie', 'ruchajmy', 'ruchają', 'ruchali', 'ruchaliście', 'ruchaliśmy', 'rucham', 'ruchamy', 'ruchasz', 'ruchać', 'ruchał', 'ruchała', 'ruchałam', 'ruchałaś', 'ruchałem', 'ruchałeś', 'ruchało', 'ruchałom', 'ruchałoś', 'ruchały', 'ruchałyście', 'ruchałyśmy', 'ryj', 'skurwic', 'skurwiel', 'skurwiela', 'skurwielem', 'skurwielu', 'skurwić', 'skurwysyn', 'skurwysyna', 'skurwysynem', 'skurwysynow', 'skurwysynski', 'skurwysynstwo', 'skurwysynu', 'skurwysyny', 'skurwysynów', 'skurwysyński', 'skurwysyństwo', 'skutasiony', 'spermosiorbacz', 'spermosiorbaczem', 'spieprza', 'spieprzac', 'spieprzaj', 'spieprzaja', 'spieprzajaca', 'spieprzajacy', 'spieprzajcie', 'spieprzają', 'spieprzająca', 'spieprzający', 'spieprzać', 'spierdala', 'spierdalac', 'spierdalaj', 'spierdalajacy', 'spierdalający', 'spierdalal', 'spierdalala', 'spierdalalcie', 'spierdalać', 'spierdalał', 'spierdalała', 'spierdola', 'spierdolencu', 'spierdoleńcu', 'spierdoli', 'spierdolic', 'spierdolić', 'spierdoliła', 'spierdoliło', 'spierdolą', 'srac', 'sraj', 'srajac', 'srajacy', 'srając', 'srający', 'srać', 'sukinsyn', 'sukinsynom', 'sukinsynow', 'sukinsynowi', 'sukinsyny', 'sukinsynów', 'szmata', 'szmato', 'udupić', 'ujebac', 'ujebal', 'ujebala', 'ujebana', 'ujebany', 'ujebać', 'ujebał', 'ujebała', 'ujebie', 'upierdala', 'upierdalac', 'upierdalać', 'upierdol', 'upierdola', 'upierdoleni', 'upierdoli', 'upierdolic', 'upierdolić', 'upierdolą', 'wjebac', 'wjebać', 'wjebia', 'wjebie', 'wjebiecie', 'wjebiemy', 'wjebią', 'wkurwi', 'wkurwia', 'wkurwiac', 'wkurwiacie', 'wkurwiajaca', 'wkurwiajacy', 'wkurwiają', 'wkurwiająca', 'wkurwiający', 'wkurwial', 'wkurwiali', 'wkurwiać', 'wkurwiał', 'wkurwic', 'wkurwicie', 'wkurwimy', 'wkurwią', 'wkurwić', 'wpierdalac', 'wpierdalajacy', 'wpierdalający', 'wpierdalać', 'wpierdol', 'wpierdolic', 'wpierdolić', 'wpizdu', 'wyjebac', 'wyjebali', 'wyjebany', 'wyjebać', 'wyjebał', 'wyjebała', 'wyjebały', 'wyjebia', 'wyjebie', 'wyjebiecie', 'wyjebiemy', 'wyjebiesz', 'wyjebią', 'wykurwic', 'wykurwić', 'wykurwiście', 'wypieprza', 'wypieprzac', 'wypieprzal', 'wypieprzala', 'wypieprzać', 'wypieprzał', 'wypieprzała', 'wypieprzy', 'wypieprzyl', 'wypieprzyla', 'wypieprzył', 'wypieprzyła', 'wypierdal', 'wypierdala', 'wypierdalac', 'wypierdalaj', 'wypierdalal', 'wypierdalala', 'wypierdalać', 'wypierdalał', 'wypierdalała', 'wypierdola', 'wypierdoli', 'wypierdolic', 'wypierdolicie', 'wypierdolil', 'wypierdolila', 'wypierdolili', 'wypierdolimy', 'wypierdolić', 'wypierdolił', 'wypierdoliła', 'wypierdolą', 'wypiździały', 'zajebac', 'zajebali', 'zajebana', 'zajebane', 'zajebani', 'zajebany', 'zajebanych', 'zajebanym', 'zajebanymi', 'zajebać', 'zajebała', 'zajebia', 'zajebial', 'zajebiala', 'zajebiał', 'zajebie', 'zajebiscie', 'zajebista', 'zajebiste', 'zajebisty', 'zajebistych', 'zajebistym', 'zajebistymi', 'zajebią', 'zajebiście', 'zapieprza', 'zapieprzy', 'zapieprzyc', 'zapieprzycie', 'zapieprzyl', 'zapieprzyla', 'zapieprzymy', 'zapieprzysz', 'zapieprzyć', 'zapieprzył', 'zapieprzyła', 'zapieprzą', 'zapierdala', 'zapierdalac', 'zapierdalaj', 'zapierdalaja', 'zapierdalajacy', 'zapierdalajcie', 'zapierdalający', 'zapierdalala', 'zapierdalali', 'zapierdalać', 'zapierdalał', 'zapierdalała', 'zapierdola', 'zapierdoli', 'zapierdolic', 'zapierdolil', 'zapierdolila', 'zapierdolić', 'zapierdolił', 'zapierdoliła', 'zapierdolą', 'zapierniczający', 'zapierniczać', 'zasranym', 'zasrać', 'zasrywający', 'zasrywać', 'zesrywający', 'zesrywać', 'zjebac', 'zjebal', 'zjebala', 'zjebali', 'zjebana', 'zjebancu', 'zjebane', 'zjebać', 'zjebał', 'zjebała', 'zjebańcu', 'zjebią', 'zjeby', 'śmierdziel');
        $filterCount = sizeof($filterWords);
        //Sprawdzamy czy są w podanym tekście jakieś wulgaryzmy, regexpem.
        for($i=0; $i < $filterCount; $i++) {
            if(preg_match('/'.$filterWords[$i].'/i', $text)){
                //Bum, jest.
                $profanitiesPresent = 1;
            }
        }
        return $profanitiesPresent;
    }

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
    $kindwords_post_id = sanitize_sql_string(sanitize_html_string($_POST["post_id"]));
    $kindwords_text = sanitize_sql_string(sanitize_html_string($_POST["kindwords"]));
    $last_ID = 0;
    $post_text = "";
    $user_login = "";
    $final_post_data = "";
    $final_link = "";
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

    //Wyciągnij tekst posta
    //Spreparuj SQLa wyszukiwarki
    $sql = "SELECT * FROM Emotions WHERE id = '$kindwords_post_id'";
    //Wyciągnij wszystkie emocje użytkownika
    $result=mysqli_query($conn, $sql);

    if ($result->num_rows > 0) {
        //Jeżeli emocje istnieją, pobierz dane
        while($row = $result->fetch_assoc()) {
            $post_text = $row["explanation"];
        }
    } 

    //Wyciągnij nazwę użytkownika
    $sql = "SELECT * FROM Users WHERE id = '$current_user_id'";
    $result=mysqli_query($conn, $sql);

    if ($result->num_rows > 0) {
        //Jeżeli użytkownik o podanych danych istnieje, wyciągnij jego dane
        while($row = $result->fetch_assoc()) {
            $user_login = $row["username"];
        }
    }

    //Skonstruuj treść powiadomienia
    $final_post_data = "Użytkownik ".$user_login." napisał miłe słowa w odpowiedzi na twój post ".$post_text;
    $final_link = "http://emodiary.000webhostapp.com/read_kindwords.html";

    //Znajdź właściciela posta
    $sql = "SELECT * FROM Emotions WHERE id = '$kindwords_post_id'";
    $result=mysqli_query($conn, $sql);

    if ($result->num_rows > 0) {
        //Jeżeli wpis istnieje, pobierz ID właściciela
        while($row = $result->fetch_assoc()) {
            $recipient_id = $row["userID"];
        }
    } 

    //Dodaj miłe słowa oraz powiadomienie jeśli zalogowany i nie zawierają wulgaryzmów, a także nie są puste
    if ($current_user_id <> 0) {
        if ($kindwords_text != "") {
            if (checkForProfanities($kindwords_text) == 0) {
                //Dodaj miłe słowa
                $sql = "INSERT INTO KindWords (senderID, recipientID, postID, kindWords)
                VALUES (\"$current_user_id\", \"$recipient_id\", \"$kindwords_post_id\", \"$kindwords_text\");";

                if ($conn->query($sql) === TRUE) {
                    //Pobierz ostatnie ID
                    $last_ID = $conn->insert_id;
                } else {
                    $error = $error.", ".$conn->error;
                    $operation_error = 1;
                }

                //Dodaj powiadomienie
                $sql = "INSERT INTO Notifs (recipientID, postData, link)
                VALUES (\"$recipient_id\", \"$final_post_data\",\"$final_link\");";

                if ($conn->query($sql) === TRUE) {
                    //Pobierz ostatnie ID
                    $last_ID = $conn->insert_id;
                } else {
                    $error = $error.", ".$conn->error;
                    $operation_error = 1;
                }

                //Czy operacja się powiodła? Wypisz komunikat.
                if ($operation_error == 0) {
                    $arr = array('result' => 'Twoje miłe słowa zostały wysłane do nadawcy. Miejmy nadzieję, że się ucieszy!', 'resultType' => 'success', 'ID' => $last_ID );
                    echo json_encode($arr);
                } else {
                    $arr = array('result' => 'Wystąpił błąd operacji. Spróbuj ponownie. Dane dla nerdów: '.$error, 'resultType' => 'danger', 'ID' => '0');
                    echo json_encode($arr);
                }
            }
            else {
                $arr = array('result' => 'Twoje słowa miały być miłe. Spróbuj ponownie, nie używając wulgaryzmów.', 'resultType' => 'warning', 'ID' => '0');
                echo json_encode($arr); 
            }
        }
        else {
            $arr = array('result' => 'Nie zostawiaj okienka pustego. Napisz coś miłego.', 'resultType' => 'warning', 'ID' => '0');
            echo json_encode($arr); 
        }
    } else {
        $arr = array('result' => 'Użytkownik nie jest zalogowany.', 'resultType' => 'danger', 'ID' => '0');
        echo json_encode($arr);
    }
?>