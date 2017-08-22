$('document').ready(function() {
    $('#button').click(function() {
        var search = $('#text-search').val();
    });
    $('#text-search').keypress(function(event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == 13) { //Enter key pressed
            $('#button').on(); //Trigger search button click event
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
