// Logarr main JS script
// https://github.com/Monitorr

// Set sytles for BlockUI overlays in /assets/js/jquery.blockUI.js

function refreshblockUI() {
    $.growlUI('Updating logs...');
    setTimeout(function () {
        $('.btn-visible').addClass("btn-hidden");
        refresh();
    }, 300);

    //wait 3 seconds after log update to highlight error terms:
    if (settings.autoHighlight == "true") {
        setTimeout(function () {
            highlightjs();
        }, 3000); // CHANGE ME ??
    }
}

function refresh() {
    loadLog();
    console.log('Logarr log update START');
}

function loadLog() {
    $.ajax({
        url: "assets/php/load-log.php",
        data: {'hash': window.location.hash},
        type: "POST",
        success: function (response) {
            //$('#logcontainer').fadeOut('slow').delay(500);
            $('#logcontainer').html(response);
            $('#logwrapper').fadeIn('slow');
            console.log('Loaded logs');
        }
    });
}

// highlight all "error" terms:

function highlightjs() {
    if('customHighlightTerms' in settings && settings.customHighlightTerms != "") {
        var array = settings.customHighlightTerms.split(",");
        for (let i = 0; i < array.length; i++) {
            $(".expand").highlight(array[i].trim(), {
                element: 'em',
                className: array[i].trim()
            });
            console.log("Highlighting text containing: " + array[i].trim());
        }
    }
}

// Search function:

$(function () {

    // the input field
    var $input = $("input[name='markinput']"),
        // search button
        $searchBtn = $("button[data-search='search']"),
        // next button
        $nextBtn = $("button[data-search='next']"),
        // prev button
        $prevBtn = $("button[data-search='prev']"),
        // clear button
        $clearBtn = $("button[data-search='clear']"),

        // the context where to search


        $content = $(".slide"),
        // jQuery object to save <mark> elements
        $results,
        // the class that will be appended to the current
        // focused element
        currentClass = "current",
        // top offset for the jump (the search bar)
        offsetTop = 50,
        // the current index of the focused element
        currentIndex = 0;

    //Jumps to the element matching the currentIndex

    function jumpTo() {
        if ($results.length) {
            var position,
                $current = $results.eq(currentIndex);
            $results.removeClass(currentClass);
            if ($current.length) {
                $current.addClass(currentClass);
                let currentMarkResult = $('.markresults.current');
                var currentOffset = currentMarkResult.offsetTop;
                var parent = currentMarkResult.parent();
                while (!parent.is('div')) {
                    parent = parent.parent();
                }

                /* not animated page scroll */
                $('html, body').scrollTop(
                    $(parent).offset().top
                );

                /*
                    $('html, body').animate({
                        scrollTop: $(parent).offset().top
                    }, 200); //make this value bigger if you want smoother/longer scroll
                */

                /* not animated scroll */
                parent.scrollTop(
                    currentMarkResult.offset().top - parent.offset().top + parent.scrollTop()
                );
            }
        }
    }

    function mark() {

        // Read the keyword
        var keyword = $("input[name='markinput']").val();
        $content = $(".slide");

        // Determine selected options
        var options = {
            "each": function (element) {
                setTimeout(function () {
                    $(element).addClass("current");
                }, 250);
            }
        };

        // Mark the keyword inside the context:

        $content.unmark({
            done: function () {
                $content.mark(keyword, {
                    separateWordSearch: false,
                    done: function () {
                        $results = $content.find("mark");
                        let count = $(".count");
                        count.text($results.length);
                        count.append(" occurance(s) of: '");
                        count.append(keyword);
                        count.append("'");
                        $results.addClass("markresults");
                        count.addClass("countresults");
                        currentIndex = 0;
                        if (settings.jumpOnSearch) jumpTo(); // Auto focus/scroll to first searched term after search submit, if user had enabled option in config
                    }
                });
            }
        });
    }

    $searchBtn.on("click", function () {
        console.log('Logarr is performing search');
        $('#buttonStart :checkbox').prop('checked', false).change(); // if auto-update is enabled, disable it after search submit
        $.blockUI({
            message: 'Searching...'
        });
        setTimeout(function () {
            $('.btn-visible').removeClass("btn-hidden"); // unhide next/previous buttons on search
            mark();
            $.unblockUI()
        }, 300);
    });

    // Clears the search

    $clearBtn.on("click", function () {
        console.log('Logarr cleared search results');
        $.growlUI('Clearing <br> search results');
        $content.unmark();
        $input.val("");
        $('.count').removeClass("countresults");
        $('.btn-visible').addClass("btn-hidden");
    });


    // Next and previous search jump to

    $nextBtn.add($prevBtn).on("click", function () {
        if ($results.length) {
            currentIndex += $(this).is($prevBtn) ? -1 : 1;
            if (currentIndex < 0) {
                currentIndex = $results.length - 1;
            }
            if (currentIndex > $results.length - 1) {
                currentIndex = 0;
            }
            jumpTo();
        }
    });

    // THIS WILL "LIVE SEARCH" as soon as user keyup in search field:
    /**
     * Searches for the entered keyword in the
     * specified context on input
     */
    var timeoutID = null;
    $input.keyup(function (e) {
        clearTimeout(timeoutID);
        if (settings.liveSearch == "true") {
            $('.btn-visible').removeClass("btn-hidden"); // unhide next/previous buttons on search
            timeoutID = setTimeout(() => mark(e.target.value), 500);
        }
    });
});

function refreshConfig() {
    $.ajax({
        url: "assets/php/sync-config.php",
        data: {settings: settings, preferences: preferences},
        type: "POST",
        success: function (response) {

            var json = JSON.parse(response);
            var settings = json.settings;
            var preferences = json.preferences;

            setTimeout(function () {
                refreshConfig()
            }, settings.rfconfig); //delay is rftime


            $("#auto-update-status").attr("data-enabled",settings.logRefresh);

            if (settings.logRefresh == "true" && (logInterval == false || settings.rflog != current_rflog)) {
                clearInterval(nIntervId);
                nIntervId = setInterval(refreshblockUI, settings.rflog);
                logInterval = true;
                current_rflog = settings.rflog;
                console.log("Auto update: Enabled | Interval: " + settings.rflog + " ms");
                $.growlUI("Auto update: Enabled");
            } else if(settings.logRefresh == "false" && logInterval == true) {
                clearInterval(nIntervId);
                logInterval = false;
                console.log("Auto update: Disabled");
                $.growlUI("Auto update: Disabled");
            }

            document.title = preferences.sitetitle; //update page title to configured title
            console.log('Refreshed config variables');
        }
    });
}

function updateTime() {
    setInterval(function () {
        var timeString = date.toLocaleString('en-US', {
            hour12: timeStandard,
            weekday: 'short',
            year: 'numeric',
            day: '2-digit',
            month: 'short',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        }).toString();
        var res = timeString.split(",");
        var time = res[3];
        var dateString = res[0] + '&nbsp; | &nbsp;' + res[1].split(" ")[2] + " " + res[1].split(" ")[1] + '<br>' + res[2];
        var data = '<div class="dtg">' + time + ' ' + timeZone + '</div>';
        data += '<div id="line">__________</div>';
        data += '<div class="date">' + dateString + '</div>';
        $("#timer").html(data);
    }, 1000);
}

function syncServerTime() {
    console.log('Logarr time update START | Interval: ' + settings.rftime + ' ms');
    $.ajax({
        url: "assets/php/time.php",
        type: "GET",
        success: function (response) {
            var response = $.parseJSON(response);
            servertime = response.serverTime;
            timeStandard = parseInt(response.timeStandard);
            timeZone = response.timezoneSuffix;
            rftime = response.rftime;
            date = new Date(servertime);
            setTimeout(function () {
                syncServerTime()
            }, settings.rftime); //delay is rftime
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('Logarr time update START');
        }
    });
}

function load_info() {
    document.getElementById("setttings-page-title").innerHTML = 'Information';
    document.getElementById("includedContent").innerHTML = '<object  type="text/html" class="object" data="assets/php/settings/info.php" ></object>';
    $(".sidebar-nav-item").removeClass('active');
    $("li[data-item='info']").addClass("active");
}

function load_preferences() {
    document.getElementById("setttings-page-title").innerHTML = 'User Preferences';
    document.getElementById("includedContent").innerHTML = '<object type="text/html" class="object" data="assets/php/settings/user_preferences.php" ></object>';
    $(".sidebar-nav-item").removeClass('active');
    $("li[data-item='user-preferences']").addClass("active");
}

function load_settings() {
   document.getElementById("setttings-page-title").innerHTML = 'Logarr Settings';
    document.getElementById("includedContent").innerHTML = '<object type="text/html" class="object" data="assets/php/settings/site_settings.php" ></object>';
    $(".sidebar-nav-item").removeClass('active');
    $("li[data-item='logarr-settings']").addClass("active");
}
function load_authentication() {
    document.getElementById("setttings-page-title").innerHTML = 'Logarr Authentication';
    document.getElementById("includedContent").innerHTML = '<object type="text/html" class="object" data="assets/php/settings/authentication.php" ></object>';
    $(".sidebar-nav-item").removeClass('active');
    $("li[data-item='logarr-authentication']").addClass("active");
}

function load_logs() {
    document.getElementById("setttings-page-title").innerHTML = 'Logs Settings';
    document.getElementById("includedContent").innerHTML = '<object type="text/html" class="object" data="assets/php/settings/logs_settings.php" ></object>';
    $(".sidebar-nav-item").removeClass('active');
    $("li[data-item='logs-configuration']").addClass("active");
}