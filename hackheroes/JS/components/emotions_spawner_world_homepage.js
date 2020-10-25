//BAAAAAAAAAAAAAAAAAAAAAARDZO dużo redundacji na stronie głównej
currentLoggedInID = ""
usernameList = []
heartsList = []

function getHeartedIDs() {
    heartsList = []
    $.get("API/hearts/get_current_hearted_ids.php", {
        //Nie wysyłaj nic
    }).done(function(response) {
        //Wrzuć listę serduszek do tabeli
        var json = $.parseJSON(response)
        var jsonArrayLength = json.data.length;
        for (var i = 0; i < jsonArrayLength; i++) {
            heartsList[i] = json.data[i].postID;
        }
        spawnEmotions();
    });
}

function checkIfHearted(ID) {
    var found = 0;
    var heartsArrayLength = heartsList.length;
    for (var i = 0; i < heartsArrayLength; i++) {
        if (heartsList[i] == ID) {
            found = 1;
        }
    }
    return found;
}

function getLoggedIn() {
    $.get("API/users/get_login.php", {
        //Nie wysyłaj nic
    }).done(function(response) {
        //Pobierz dane o obecnie zalogowanym
        var json = $.parseJSON(response)
        currentLoggedInID = json.ID;
        getHeartedIDs();
    });
}

function getUsernames() {
    $.get("API/users/get_usernames.php", {
        //Nie wysyłaj nic
    }).done(function(response) {
        //Pobierz liste userów
        var json = $.parseJSON(response)
        var jsonArrayLength = json.data.length;
        for (var i = 0; i < jsonArrayLength; i++) {
            usernameList[json.data[i].id] = json.data[i].username;
        }
        getLoggedIn();
    });
}

//Sporo redundancji
function spawnEmotions(date, basicEmotion) {
    //Pobierz listę użytkowników i obecnie zalogowanego
    $.get("API/emotions/get_emotions_world_homepage.php", {
        date: date,
        basicEmotion: basicEmotion
    }).done(function(response) {
        //Zespawnuj wpis z emocją
        var json = $.parseJSON(response)
        var jsonArrayLength = json.data.length;
        $('#emotionContainer').empty();
        for (var i = 0; i < jsonArrayLength; i++) {
            //dla autora wpisu otwórz dodatkowe możliwości
            if (json.data[i].userID == currentLoggedInID) {
                $('#emotionContainer').append(`
                    <div class="mt-4 row">
                        <div class="card col-12 col-md-3 col-lg-2">
                            <img class="mt-4 img-fluid" src="`+json.data[i].basicEmotionImage+`">
                            <b class="text-center">Wtedy `+usernameList[json.data[i].userID]+` czuł/a się:</b>
                            <p class="text-center">`+json.data[i].basicEmotion+`</p>
                            <b class="text-center">A dokładniej:</b>
                            <p class="text-center">`+json.data[i].extendedEmotion+`</p>
                        </div>
                        <div class="card col-12 col-md-9 col-lg-10">
                            <div class="card-body">
                                <div class="mt-2 row">
                                    <p class="col-6 text-left"><b>Wpis użytkownika `+usernameList[json.data[i].userID]+` z dnia `+json.data[i].insertionDate+`:</b></p>
                                    <p class="col-6 text-right"><b>❤ `+json.data[i].hearts+`</b></p>
                                </div>
                                <p class="justify">`+json.data[i].explanation+`</p>
                                <div class="mt-2 row justify-content-center">
                                    <div class="col-12 col-lg-3 text-center">
                                        <button id="submitButton" onclick="readEmotion('`+json.data[i].explanation+`')" class="btn btn-primary btn-block"><i class="fa fa-volume-up"></i> Czytaj na głos</button>
                                    </div>
                                    <div class="col-12 col-lg-3 mt-2 mt-lg-0 text-center">
                                        <button id="submitButton" onclick="shareEmotion('https://www.facebook.com/sharer/sharer.php?u=http://emodiary.000webhostapp.com/emotion_single.html?emotion_id=`+json.data[i].ID+`')" class="btn btn-primary btn-block facebook-btn"><i class="fa fa-facebook"></i> Udostępnij</button>
                                    </div>  
                                    <div class="col-12 col-lg-3 mt-2 mt-lg-0 text-center">
                                        <button id="submitButton" onclick="shareEmotion('emotion_single.html?emotion_id=`+json.data[i].ID+`')" class="btn btn-secondary btn-block"><i class="fa fa-link"></i> Otwórz link</button>
                                    </div>  
                                    <div class="col-12 col-lg-3 mt-2 mt-lg-0 text-center">
                                        <button id="submitButton" onclick="deleteEmotion(`+json.data[i].ID+`)" class="btn btn-danger btn-block"><i class="fa fa-minus"></i> Usuń wpis</button>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                `)
            } else {
                //Dla reszty: pisanie miłych słów i dawanie serduszek
                //Jeżeli serduszko jest dane, wyświetl opcję daj, jeśli nie, zabierz
                if (checkIfHearted([json.data[i].ID]) == 0) {
                    word="Daj"
                } else {
                    word="Zabierz"
                }
                $('#emotionContainer').append(`
                        <div class="mt-4 row">
                            <div class="card col-12 col-md-3 col-lg-2">
                                <img class="mt-4 img-fluid" src="`+json.data[i].basicEmotionImage+`">
                                <b class="text-center">Wtedy `+usernameList[json.data[i].userID]+` czuł/a się:</b>
                                <p class="text-center">`+json.data[i].basicEmotion+`</p>
                                <b class="text-center">A dokładniej:</b>
                                <p class="text-center">`+json.data[i].extendedEmotion+`</p>
                            </div>
                            <div class="card col-12 col-md-9 col-lg-10">
                                <div class="card-body">
                                    <div class="mt-2 row">
                                        <p class="col-6 text-left"><b>Wpis użytkownika `+usernameList[json.data[i].userID]+` z dnia `+json.data[i].insertionDate+`:</b></p>
                                        <p class="col-6 text-right"><b>❤ `+json.data[i].hearts+`</b></p>
                                    </div>
                                    <p class="justify">`+json.data[i].explanation+`</p>
                                    <div class="mt-2 row justify-content-center">
                                        <div class="col-12 col-lg-4 text-center">
                                            <button id="submitButton" onclick="readEmotion('`+json.data[i].explanation+`')" class="btn btn-primary btn-block"><i class="fa fa-volume-up"></i> Czytaj na głos</button>
                                        </div> 
                                        <div class="col-12 col-lg-4 mt-2 mt-lg-0 text-center">
                                            <button id="submitButton" onclick="shareEmotion('write_kindwords.html?emotion_id=`+json.data[i].ID+`')" class="btn btn-primary btn-block"><i class="fa fa-sticky-note-o"></i> Napisz miłe słowa</button>
                                        </div>  
                                        <div class="col-12 col-lg-4 mt-2 mt-lg-0 text-center">
                                            <button id="submitButton" onclick="heartEmotion(`+json.data[i].ID+`)" class="btn btn-danger btn-block"><i class="fa fa-heart"></i> `+word+` serduszko (`+json.data[i].hearts+`)</button>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>
                    `)
            }
        }
    });
}

function deleteEmotion(delete_id) {
    //Wyślij dane do API POSTem
    $.post("API/emotions/delete_emotion.php", {
        emotionID: delete_id
    }).done(function(response) {
        //Wyświetl zwróconą wiadomość z API
        var json = $.parseJSON(response)
        $('#innerMessage').bsAlert(
            {
                type: json.resultType, 
                content: json.result,
                dismissible: true
            }
        );
        spawnEmotions();
    });
}

function shareEmotion(share_url) {
    //Podziel się na medium społecznościowym
    window.open(share_url);
}

function heartEmotion(postID) {
    //Sprawdź status serduszka dla obecnie zalogowanego ID i postu
    $.post("API/hearts/check_hearted_status.php", {
        heart_id: postID
    }).done(function(response) {
        //Daj serduszko jeżeli nie ma, zabierz jeśli jest
        var json = $.parseJSON(response)
        if (json.hearted == "true") {
            $.post("API/hearts/delete_heart.php", {
                heart_id: postID
            }).done(function(response) {
                //Wyświetl zwróconą wiadomość z API
                var json = $.parseJSON(response)
                $('#innerMessage').bsAlert(
                    {
                        type: json.resultType, 
                        content: json.result,
                        dismissible: true
                    }
                );
                getHeartedIDs();
            });
        } else {
            $.post("API/hearts/add_heart.php", {
                heart_id: postID
            }).done(function(response) {
                //Wyświetl zwróconą wiadomość z API
                var json = $.parseJSON(response)
                $('#innerMessage').bsAlert(
                    {
                        type: json.resultType, 
                        content: json.result,
                        dismissible: true
                    }
                );
                getHeartedIDs();
            });
        }
    });
}

//Użyj API przeglądarki by czytać wpisy
function readEmotion(textToSpeak) {
    emotionUtterance = new SpeechSynthesisUtterance(textToSpeak);
    speechSynthesis.speak(emotionUtterance);
}