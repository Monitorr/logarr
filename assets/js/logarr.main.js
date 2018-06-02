// Logarr main JS script
// https://github.com/Monitorr

// Set sytles for BlockUI overlays in /assets/js/jquery.blockUI.js

function searchblockUI() {
    $.blockUI({
        message: 'Searching...'
    });
    console.log('Logarr is performing search');
    setTimeout(function () {
        highlightsearch();
        count()
        $.unblockUI()
    }, 300);
};

    // highlight searched terms:

function highlightsearch() {
    var text = document.getElementById("text-search").value;
    var query = new RegExp("(\\b" + text + "\\b)", "gim");
    var e = document.getElementById("logcontainer").innerHTML;
    var enew = e.replace(/(<span>|<\/span>)/igm, "");
    var newe = enew.replace(query, "<span>$1</span>");
    document.getElementById("logcontainer").innerHTML = newe;
};

    // count searched term occurances:

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
    }, 300);

        //wait 3 seconds after log update to highlight error terms:

    setTimeout(function () {
        highlightjs();
    }, 3000);  // CHANGE ME ??

};


function refresh() {
    var url = 'index.php';
    $('#logcontainer').load(url + ' #logcontainer');
    console.log('Logarr log update START');
};


    // highlight all "error" terms:

    function highlightjs() {
        $(".expand").highlight("error", {
            element: 'em',
            className: 'error'
        });

    };