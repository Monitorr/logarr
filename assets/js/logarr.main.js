// Logarr main JS script
// https://github.com/Monitorr

// Set sytles for BlockUI overlays in /assets/js/jquery.blockUI.js

function searchblockUI() {
    $.blockUI({
        message: 'Searching...'
    });
    setTimeout(function () {
        highlight();
        count()
        $.unblockUI()
    }, 300);
};

function highlight() {
    var text = document.getElementById("text-search").value;
    var query = new RegExp("(\\b" + text + "\\b)", "gim");
    var e = document.getElementById("logcontainer").innerHTML;
    var enew = e.replace(/(<span>|<\/span>)/igm, "");
    var newe = enew.replace(query, "<span>$1</span>");
    document.getElementById("logcontainer").innerHTML = newe;
};


function count() {
    var text = document.getElementById("text-search").value;
    var count =
        $("#expand span").length;
        $(".count").text(count);
        $('.count').append(" occurance(s) of: '");
        $('.count').append( text );
        $('.count').append("'");
};


function refreshblockUI() {
    $.growlUI('Updating Logs...');
    setTimeout(function () {
        refresh();
        // highlighterror()
    }, 300);
};


function refresh() {
    var url = 'index.php';
    $('#logcontainer').load(url + ' #logcontainer');
};


function highlighterror() {
    text = 'error';
    var query = new RegExp("(\\b" + text + "\\b)", "gim");
    var e = document.getElementById("logcontainer").innerHTML;
    var enew = e.replace(/(<span>|<\/span>)/igm, "");
    var newe = enew.replace(query, "<span>$1</span>");
    document.getElementById("logcontainer").innerHTML = newe;
};
