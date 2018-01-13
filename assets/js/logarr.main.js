// Logarr main JS script
// https://github.com/Monitorr

// Set sytles for BlockUI overlays in /assets/js/jqueryUI.js

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
    color = "#f6f";
};


function count() {
    var count =
        $("#logcontainer span").length;
    $(".count").text(count).append(" occurance(s) of searched term");
};


function refreshblockUI() {
    $.growlUI ('Updating Logs...');
    setTimeout(function () {
        refresh()
    }, 300);
};


function refresh() {
    var url = 'index.php';
    $('#logwrapper').load(url + ' #logs');
};
