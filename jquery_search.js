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
}


$(function() {
    $("#button").click(function() {
        $.ajaxSetup({
            global: false,
            beforeSend: function() {
                $(".modal").show();
            },
            complete: function() {
                $(".modal").hide();
            }
        });
        $.ajax({
            data: "{}",
            success: function(r) {
                $("#search-loading");
            }
        });
    });
});
