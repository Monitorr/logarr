function blockUI() {
    // e.preventDefault();
    $.blockUI({
        css: {
            'border': 'none',
            'padding': '15px',
            'backgroundColor': '#000000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            'opacity': '0.8',
            'color': '#EEEBEC',
            'font-size': '32px',
        },
        message: 'Searching...',
    });
    highlight().then(function () {
        count();
        $.unblockUI()
    })
    setTimeout($.unblockUI, 5000);
};


function highlight() {
    var def = $.Deferred();
    var text = document.getElementById("text-search").value;
    var query = new RegExp("(\\b" + text + "\\b)", "gim");
    $("#body span").replaceWith(function (i, e) {
        return e.textContent
    });
    var texts = $("#body, #body *").contents().filter(function () {
        return this.nodeType === Node.TEXT_NODE;
    }).get();
    fix(texts)
    return def

    function html(s) {
        return document.createRange().createContextualFragment(s);
    }

    function fix(elements) {
        if (elements.length > 0) {
            var e = elements[0]
            var t = html(e.textContent.replace(query, "<span>$1</span>"));
            // console.log(e, t)
            e.parentNode.insertBefore(t, e);
            e.parentNode.removeChild(e);

            setTimeout(function () {
                fix(elements.slice(1))
            })
        } else {
            def.resolve()
        }
    }
};


function count() {
    var count =
        $("#body span").length;
        $(".count").text(count);
        $('#count').append(" occurance(s) of searched term");
    };
