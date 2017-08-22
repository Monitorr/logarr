$('document').ready(function () {
    $('#searchButton').click(function () {
        var search = $('#text-search').val();
    })
    $('#text-search').keypress(function (e) {
        if (e.which == 13) { //Enter key pressed
            $('#searchButton').click(); //Trigger search button click event
        }
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
