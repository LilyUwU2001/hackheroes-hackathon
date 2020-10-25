//Dostępne motywy
var themes = {
    "default" : "//cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css",
    "cerulean" : "//stackpath.bootstrapcdn.com/bootswatch/4.5.2/cerulean/bootstrap.min.css",
    "cosmo" : "//stackpath.bootstrapcdn.com/bootswatch/4.5.2/cosmo/bootstrap.min.css",
    "cyborg" : "//stackpath.bootstrapcdn.com/bootswatch/4.5.2/cyborg/bootstrap.min.css",
    "darkly" : "//stackpath.bootstrapcdn.com/bootswatch/4.5.2/darkly/bootstrap.min.css",
    "flatly" : "//stackpath.bootstrapcdn.com/bootswatch/4.5.2/flatly/bootstrap.min.css",
    "journal" : "//stackpath.bootstrapcdn.com/bootswatch/4.5.2/journal/bootstrap.min.css",
    "litera"  : "//stackpath.bootstrapcdn.com/bootswatch/4.5.2/litera/bootstrap.min.css",
    "lumen" : "//stackpath.bootstrapcdn.com/bootswatch/4.5.2/lumen/bootstrap.min.css",
    "lux" : "//stackpath.bootstrapcdn.com/bootswatch/4.5.2/lux/bootstrap.min.css",
    "materia" : "//stackpath.bootstrapcdn.com/bootswatch/4.5.2/materia/bootstrap.min.css",
    "minty" : "//stackpath.bootstrapcdn.com/bootswatch/4.5.2/minty/bootstrap.min.css",
    "pulse" : "//stackpath.bootstrapcdn.com/bootswatch/4.5.2/pulse/bootstrap.min.css",
    "sandstone" : "//stackpath.bootstrapcdn.com/bootswatch/4.5.2/sandstone/bootstrap.min.css",
    "simplex" : "//stackpath.bootstrapcdn.com/bootswatch/4.5.2/simplex/bootstrap.min.css",
    "sketchy" : "//stackpath.bootstrapcdn.com/bootswatch/4.5.2/sketchy/bootstrap.min.css",
    "slate" : "//stackpath.bootstrapcdn.com/bootswatch/4.5.2/slate/bootstrap.min.css",
    "solar" : "//stackpath.bootstrapcdn.com/bootswatch/4.5.2/solar/bootstrap.min.css",
    "spacelab" : "//stackpath.bootstrapcdn.com/bootswatch/4.5.2/spacelab/bootstrap.min.css",
    "superhero" : "//stackpath.bootstrapcdn.com/bootswatch/4.5.2/superhero/bootstrap.min.css",
    "united" : "//stackpath.bootstrapcdn.com/bootswatch/4.5.2/united/bootstrap.min.css",
    "yeti" : "//stackpath.bootstrapcdn.com/bootswatch/4.5.2/yeti/bootstrap.min.css"
}

//Opisy motywów
var themeDescriptions = {
    "default" : "Domyślny motyw aplikacji.",
    "cerulean" : "Przyjazny motyw inspirowany błękitem nieba.",
    "cosmo" : "Motyw inspirowany wyglądem Windows 10.",
    "cyborg" : "Ciemny motyw dla lubiących czerń, z dominującym niebieskim.",
    "darkly" : "Nowoczesny ciemny motyw.",
    "flatly" : "Nowoczesny jasny motyw.",
    "journal" : "Czerwony motyw, czysty jak nowa kartka papieru.",
    "litera": "Minimalistyczny motyw z okrągłymi elementami strony.",
    "lumen": "Nowoczesny motyw z cieniowaniem na elementach strony.",
    "lux": "Mały podmuch elegancji.",
    "materia": "Motyw inspirowany wyglądem Androida.",
    "minty": "Mały podmuch świeżości.",
    "pulse": "Odrobina fioletu.",
    "sandstone": "Nieco ciepła w Twoim dzienniku emocji.",
    "simplex": "Prosty i minimalistyczny motyw.",
    "sketchy": "Prawie jak ręcznie rysowany.",
    "slate": "Motyw utrzymany w odcieniach szarości.",
    "solar": "Ciemny motyw w kolorach Solarized.",
    "spacelab": "Srebrny i przejrzysty motyw.",
    "superhero": "Dzielny i niebieski.",
    "united": "Inspirowany dystrybucją Linuxa Ubuntu.",
    "yeti": "Zimny, niebieski motyw."
}

//Wartości do silnika motywów
var theme = $('<link id="theme" href="'+themes["default"]+'" rel="stylesheet" />');
var common = $('<link rel="stylesheet" type="text/css" href="CSS/common.css">');

function createThemeEngine() {
    theme.appendTo('head');
    common.appendTo('head');
}

function changeTheme(themeSelect) {
    $.post("API/users/set_theme.php", {
        theme: themeSelect
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
        applyTheme(themeSelect);
    });
}

function applyTheme(themeSelect) {
    //Zaaplikuj motyw
    $("#theme").attr('href',themes[themeSelect]);
    $("#themeSelect").val(themeSelect);
    $('#themeDesc').html(themeDescriptions[themeSelect]);
}

//Stwórz silnik motywów
createThemeEngine();

//Zaaplikuj motyw zalogowanego użytkownika
$.get("API/users/get_login.php", {
    //Nie wysyłaj nic
}).done(function(response) {
    //Wyświetl zwróconą wiadomość z API
    var json = $.parseJSON(response)
    applyTheme(json.theme)
});