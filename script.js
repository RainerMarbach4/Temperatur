
// analytics
(function () {
    $.get("php/tracker/ip.php", function (data) {
        ip = data;
    }).complete(function () {
        var URL = document.location.href;
        arrURL = URL.split("/");
        var secondleveldomain = "";
        var pagename = "";
        for (var i = 1; i < arrURL.length; i++) {
            var regexWWW = /www/;
            var regexHTML = /html/;
            if (regexWWW.test(arrURL[i])) {
                secondleveldomain = arrURL[i];
            } else if (regexHTML.test(arrURL[i])) {
                pagename = arrURL[i];
            }
        }
        $.get("php/tracker/getIP.php", { ip: ip, secondleveldomain: secondleveldomain, pagename: pagename });
    })
})();

