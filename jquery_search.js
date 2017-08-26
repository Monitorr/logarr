$('document').ready(function() {
    $('#button').click(function() {
        var searchbox = document.querySelector('#text-search');
        var searchForm = document.querySelector('#search');
    });

    $("text-search").on("keyup", function() {
        var g = $(this).val().replace(/[Ａ-Ｚａ-ｚ０-９]/g, function(s) {
            return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
        }).toLowerCase();
        $(".row").each(function() {
            //s is the value within tr
            var s = $(this).text().toLowerCase();
            $(this).closest('.row')[s.indexOf(g) !== -1 ? 'show' : 'hide']();
            //testing: count number of results
            var rowCount = $('#slide >tbody >tr:visible').length;
            document.getElementById('count').innerHTML = rowCount;
        });
    });
});

function highlight() {
    var text = document.getElementById("text-search").value;
    var query = new RegExp("(\\b" + text + "\\b)", "gim");
    var e = document.getElementById("body").innerHTML;
    var enew = e.replace(/(<span>|<\/span>)/igm, "");
    document.getElementById("body").innerHTML = enew;
    var newe = enew.replace(query, "<span>$1</span>");
    document.getElementById("body").innerHTML = newe;
}
