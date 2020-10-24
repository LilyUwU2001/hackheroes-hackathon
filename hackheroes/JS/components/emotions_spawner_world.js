currentLoggedInID = ""
usernameList = []

function getLoggedIn() {
    $.get("API/users/get_login.php", {
        //Nie wysyłaj nic
    }).done(function(response) {
        //Wyświetl zwróconą wiadomość z API
        var json = $.parseJSON(response)
        currentLoggedInID = json.ID;
        spawnEmotions();
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
    $.get("API/emotions/get_emotions_world.php", {
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
                                <p class="card-text d-flex justify-content-center"><b>Wpis użytkownika `+usernameList[json.data[i].userID]+` z dnia `+json.data[i].insertionDate+`:</b></p>
                                <p class="justify">`+json.data[i].explanation+`</p>
                                <div class="mt-2 row justify-content-center">
                                    <div class="col-12 col-md-3 text-center">
                                        <button id="submitButton" onclick="readEmotion('`+json.data[i].explanation+`')" class="btn btn-primary btn-block"><i class="fa fa-volume-up"></i> Czytaj wpis na głos</button>
                                    </div>
                                    <div class="col-12 col-md-3 mt-2 mt-md-0 text-center">
                                        <button id="submitButton" onclick="shareEmotion('https://www.facebook.com/sharer/sharer.php?u=https://emodiary.000webhostapp.com/emotion_single.html?emotion_id=`+json.data[i].ID+`')" class="btn btn-primary btn-block facebook-btn"><i class="fa fa-facebook"></i> Udostępnij</button>
                                    </div>  
                                    <div class="col-12 col-md-3 mt-2 mt-md-0 text-center">
                                        <button id="submitButton" onclick="shareEmotion('https://emodiary.000webhostapp.com/emotion_single.html?emotion_id=`+json.data[i].ID+`')" class="btn btn-secondary btn-block"><i class="fa fa-link"></i> Otwórz link</button>
                                    </div>  
                                    <div class="col-12 col-md-3 mt-2 mt-md-0 text-center">
                                        <button id="submitButton" onclick="deleteEmotion(`+json.data[i].ID+`)" class="btn btn-danger btn-block"><i class="fa fa-minus"></i> Usuń wpis</button>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                `)
            } else {
                //Dla reszty: pisanie miłych słów i dawanie serduszek
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
                                <p class="card-text d-flex justify-content-center"><b>Wpis użytkownika `+usernameList[json.data[i].userID]+` z dnia `+json.data[i].insertionDate+`:</b></p>
                                <p class="justify">`+json.data[i].explanation+`</p>
                                <div class="mt-2 row justify-content-center">
                                    <div class="col-12 col-md-4 text-center">
                                        <button id="submitButton" onclick="readEmotion('`+json.data[i].explanation+`')" class="btn btn-primary btn-block"><i class="fa fa-volume-up"></i> Czytaj wpis na głos</button>
                                    </div> 
                                    <div class="col-12 col-md-4 mt-2 mt-md-0 text-center">
                                        <button id="submitButton" onclick="writeKindWords(`+json.data[i].ID+`)" class="btn btn-primary btn-block"><i class="fa fa-sticky-note-o"></i> Napisz miłe słowa</button>
                                    </div>  
                                    <div class="col-12 col-md-4 mt-2 mt-md-0 text-center">
                                        <button id="submitButton" onclick="heartEmotion(`+json.data[i].ID+`)" class="btn btn-danger btn-block"><i class="fa fa-heart"></i> Daj serduszko</button>
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

//Użyj API przeglądarki by czytać wpisy
function readEmotion(textToSpeak) {
    emotionUtterance = new SpeechSynthesisUtterance(textToSpeak);
    speechSynthesis.speak(emotionUtterance);
}