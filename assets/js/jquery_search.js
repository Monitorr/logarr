function blockUI() {
    $.blockUI({
        css: {
            'border': 'none',
            'padding': '15px',
            'backgroundColor': '#000000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            'opacity': '0.8',
            'color': '#EEEBEC',
        }
    });
    message: "Searching...";
    setTimeout($.unblockUI, 4000);
}


$('document').ready(function() {

    $('#button').click(function() {
        var searchbox = document.querySelector('#text-search');
        var searchForm = document.querySelector('#search');
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
    color = "#f6f";
}