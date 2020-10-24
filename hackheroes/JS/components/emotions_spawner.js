function spawnEmotions(date, basicEmotion) {
    $.get("API/emotions/get_emotions_by_user.php", {
        date: date,
        basicEmotion: basicEmotion
    }).done(function(response) {
        //Zespawnuj wpis z emocją
        var json = $.parseJSON(response)
        var jsonArrayLength = json.data.length;
        $('#emotionContainer').empty();
        for (var i = 0; i < jsonArrayLength; i++) {
            $('#emotionContainer').append(`
                <div class="mt-4 row">
                    <div class="card col-12 col-md-3 col-lg-2">
                        <img class="mt-4 img-fluid" src="`+json.data[i].basicEmotionImage+`">
                        <b class="text-center">Wtedy czułem się:</b>
                        <p class="text-center">`+json.data[i].basicEmotion+`</p>
                        <b class="text-center">A dokładniej:</b>
                        <p class="text-center">`+json.data[i].extendedEmotion+`</p>
                    </div>
                    <div class="card col-12 col-md-9 col-lg-10">
                        <div class="card-body">
                            <p class="card-text d-flex justify-content-center"><b>Wpis z dnia `+json.data[i].insertionDate+`:</b></p>
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
        }
    });
}

function spawnSingleEmotion(ID) {
    $.get("API/emotions/get_emotion.php", {
        emotion_ID: ID
    }).done(function(response) {
        //Zespawnuj wpis z emocją
        var json = $.parseJSON(response)
        var jsonArrayLength = json.data.length;
        $('#emotionContainer').empty();
        for (var i = 0; i < jsonArrayLength; i++) {
            userID = json.data[i].userID;
            $('#emotionContainer').append(`
                <div class="mt-4 row">
                    <div class="card col-12 col-md-3 col-lg-2">
                        <img class="mt-4 img-fluid" src="`+json.data[i].basicEmotionImage+`">
                        <b class="text-center">Wtedy czułem się:</b>
                        <p class="text-center">`+json.data[i].basicEmotion+`</p>
                        <b class="text-center">A dokładniej:</b>
                        <p class="text-center">`+json.data[i].extendedEmotion+`</p>
                    </div>
                    <div class="card col-12 col-md-9 col-lg-10">
                        <div class="card-body">
                            <p class="card-text d-flex justify-content-center"><b>Wpis z dnia `+json.data[i].insertionDate+`:</b></p>
                            <p class="justify">`+json.data[i].explanation+`</p>
                            <div class="mt-2 row justify-content-center">
                                <div class="col-12 text-center">
                                    <button id="submitButton" onclick="readEmotion('`+json.data[i].explanation+`')" class="btn btn-primary btn-block"><i class="fa fa-volume-up"></i> Czytaj wpis na głos</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `)
            $.get("API/users/get_user_by_id.php", {
                user_ID: userID
            }).done(function(response) {
                //Wyświetl zwróconą nazwę autora wpisu z API
                var json = $.parseJSON(response)
                $('#emotionEntryUser').html(json.username);
            });
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