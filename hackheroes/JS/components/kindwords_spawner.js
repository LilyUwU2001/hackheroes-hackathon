usernameList = []
postData = []
postIDs = []

function spawnKindWords() {
    $.get("API/kindwords/get_kindwords_by_user.php", {
        //Nie wysyłaj nic
    }).done(function(response) {
        //Zespawnuj wpis z emocją
        var json = $.parseJSON(response)
        var jsonArrayLength = json.data.length;
        $('#emotionContainer').empty();
        for (var i = 0; i < jsonArrayLength; i++) {
            $('#emotionContainer').append(`
                <div class="mt-4 row">
                    <div class="card col-12">
                        <div class="card-body">
                            <p class="card-text d-flex justify-content-center"><b>Do twojego posta:</b></p>
                            <p class="justify">`+postData[json.data[i].postID]+`</p>
                            <p class="card-text d-flex justify-content-center"><b>`+usernameList[json.data[i].senderID]+` chce przekazać Ci coś miłego:</b></p>
                            <p class="justify">`+json.data[i].kindWords+`</p>
                            <div class="mt-2 row justify-content-center">
                                <div class="col-12 col-lg-6 text-center">
                                    <button id="submitButton" onclick="readKindWords('Do twojego posta: `+postData[json.data[i].postID]+` `+usernameList[json.data[i].senderID]+` chce przekazać Ci coś miłego: `+json.data[i].kindWords+`')" class="btn btn-primary btn-block"><i class="fa fa-volume-up"></i> Czytaj na głos</button>
                                </div>   
                                <div class="col-12 col-lg-6 mt-2 mt-lg-0 text-center">
                                    <button id="submitButton" onclick="deleteKindWords(`+json.data[i].ID+`)" class="btn btn-danger btn-block"><i class="fa fa-minus"></i> Usuń miłe słowa</button>
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
            `)
        }
    });
}

function getPostData() {
    $.get("API/emotions/get_emotions_world.php", {
        //Nie wysyłaj nic
    }).done(function(response) {
        //Pobierz liste postów
        var json = $.parseJSON(response)
        var jsonArrayLength = json.data.length;
        for (var i = 0; i < jsonArrayLength; i++) {
            postData[json.data[i].ID] = json.data[i].explanation;
        }
        spawnKindWords();
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
        getPostData();
    });
}

function deleteKindWords(delete_id) {
    //Wyślij dane do API POSTem
    $.post("API/kindwords/delete_kindwords.php", {
        kindwordsID: delete_id
    }).done(function(response) {
        alert(response)
        //Wyświetl zwróconą wiadomość z API
        var json = $.parseJSON(response)
        $('#innerMessage').bsAlert(
            {
                type: json.resultType, 
                content: json.result,
                dismissible: true
            }
        );
        spawnKindWords();
    });
}

//Użyj API przeglądarki by czytać wpisy
function readKindWords(textToSpeak) {
    emotionUtterance = new SpeechSynthesisUtterance(textToSpeak);
    speechSynthesis.speak(emotionUtterance);
}