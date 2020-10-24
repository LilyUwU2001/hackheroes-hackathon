var emotionsSelect = {
    //nazwy emocji w drugiej wybierałce są wybierane na podstawie wybierałki pierwszej
    'Zaskoczony': ['Przestraszony', 'Zmieszany', 'Zdumiony', 'Podekscytowany', 'Nie potrafię doprecyzować'],
    'Słaby': ['Zmęczony', 'Zestresowany', 'Zapracowany', 'Znudzony', 'Nie potrafię doprecyzować'],
    'Lękliwy': ['Przestraszony', 'Niespokojny', 'Niepewny', 'Uległy', 'Odrzucony', 'Zastraszony', 'Nie potrafię doprecyzować'],
    'Rozgniewany': ['Rozczarowany', 'Upokorzony', 'Nienawistny', 'Zagrożony', 'Agresywny', 'Sfrustrowany', 'Zdystansowany', 'Krytyczny', 'Nie potrafię doprecyzować'],
    'Zniesmaczony': ['Nieprzychylny', 'Rozczarowany', 'Czujący się podle', 'Stroniący', 'Nie potrafię doprecyzować'],
    'Smutny': ['Samotny', 'Zraniony', 'Zrozpaczony', 'Winny', 'Przygnębiony', 'Zraniony', 'Nie potrafię doprecyzować'],
    'Szczęśliwy': ['Żartobliwy', 'Zadowolony', 'Zainteresowany', 'Dumny', 'Akceptowany', 'Pełen siły', 'Spokojny', 'Czujący bliskość', 'Optymistyczny', 'Nie potrafię doprecyzować'],
    'Nie potrafię określić': ['Nie potrafię doprecyzować']
};

var imageSources = {
    //obrazki do emocji
    'Zaskoczony': 'surprised.png',
    'Słaby': 'weak.png',
    'Lękliwy': 'scared.png',
    'Rozgniewany': 'angry.png',
    'Zniesmaczony': 'disgusted.png',
    'Smutny': 'sad.png',
    'Szczęśliwy': 'happy.png',
    'Nie potrafię określić': 'cantdecide.png'
};

//Gdy wybrano opcję, przejrzyj listę
$('#basicEmotionSelect').on('change', function() {
    //Pobierz dane o pobranej opcji
    var selectValue = $(this).val();
 
    //Wyczyść wybierałkę drugą
    $('#extendedEmotionSelect').empty();
    $('#emotion').attr('src', 'images/emotions/'+imageSources[selectValue]);
    
    //Wypisz listę opcji
    for (i = 0; i < emotionsSelect[selectValue].length; i++) {
       $('#extendedEmotionSelect').append("<option value='" + emotionsSelect[selectValue][i] + "'>" + emotionsSelect[selectValue][i] + "</option>");
    }
 });

function submitEmotion() {
    var basicEmotionImage = $('#emotion').attr('src');
    var basicEmotion = $('#basicEmotionSelect').val();
    var extendedEmotion = $('#extendedEmotionSelect').val();
    var explanation = $('#explanationTextArea').val();
    var public = trueFalse($('#publicCheckbox').is(":checked"));

    //Wyślij dane do API POSTem
    $.post("API/emotions/add_emotion.php", {
        basicEmotionImage: basicEmotionImage,
        basicEmotion: basicEmotion,
        extendedEmotion: extendedEmotion,
        explanation: explanation,
        public: public
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

//Zwróć 1 dla true, 0 dla false
function trueFalse(value) {
    if (value == true) {
        return 1
    } else {
        return 0
    }
}