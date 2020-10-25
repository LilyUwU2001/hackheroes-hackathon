usernameList = []
postData = []
postIDs = []

function spawnNotifs() {
    $.get("API/notifs/get_notifs_by_user.php", {
        //Nie wysyłaj nic
    }).done(function(response) {
        //Zespawnuj wpis z emocją
        var json = $.parseJSON(response)
        var jsonArrayLength = json.data.length;
        $('#notifsContainer').empty();
        if (jsonArrayLength > 0) {
            for (var i = 0; i < jsonArrayLength; i++) {
                $('#notifsContainer').append(`
                    <a href='`+json.data[i].link+`'>
                        <div class="alert alert-info" role="alert">
                            `+json.data[i].postData+`
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </a>
                `)
            }
        }
        else {
           $('#notifsContainer').append(`
                <p class="text-center"><b>Brak powiadomień.</b></p>
            `) 
        }
    });
}

$(function() {
    spawnNotifs();
})