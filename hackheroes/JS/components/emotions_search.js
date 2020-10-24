function clearDate() {
    $('#dateSearch').val('');
}

function clearEmotion() {
    $('#emotionSearch').val('');
}

function searchEmotion() {
    //Przekaż wartości do szukajki
    var date =  $('#dateSearch').val();
    var basicEmotion =  $('#emotionSearch').val();
    spawnEmotions(date, basicEmotion);
}