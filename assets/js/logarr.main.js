// Logarr main JS script
// https://github.com/Monitorr

// Variables
let results, currentIndex = 0;
let logInterval = false;
let current_rflog = 600000;
let nIntervId = [];


let rfconfig = (typeof settings !== "undefined") ? settings.rfconfig : 1000;
nIntervId["refreshConfig"] = setInterval(refreshConfig, rfconfig);

function notify(title, text, type, confirmButtonText) {
    //CHANGE ME:
     $(function () {
       Swal.fire({
           title: title,
           text: text,
           type: type,
           confirmButtonText: confirmButtonText
       });
     });
}

// Set sytles for BlockUI overlays in /assets/js/jquery.blockUI.js
function refreshblockUI() {
    $.growlUI('Updating logs...');
    setTimeout(function () {
        loadLogs();
    }, 300);

    //wait after log update to highlight error terms:
    if (settings.autoHighlight === "true") {
        setTimeout(function () {
            highlightjs();
        }, 1500);
    }
    //wait after log update, if the searchinput field is not empty, perform search:
    if ($("input[name='markinput']").val() !== "") {
        setTimeout(function () {
            mark();
        }, 1500);
    }
}

// Load logs
function loadLogs() {
    console.log('Logarr log update START');
    var categories = [];
    var html = "";
    var filter = window.location.hash.substr(1);
    filter = filter.split(",");
    for (let i = 0; i < logs.length; i++) {
        if (logs[i].enabled == "Yes") {
            if (typeof logs[i].category == "undefined") logs[i].category = "Uncategorized";
            if ((filter[0] == "" || filter.indexOf(logs[i].category) != -1)) {
                if (document.getElementById(logs[i].logTitle.replace(/\s/g, "-") + "-log-container") == null) {
                    $("#logwrapper").append("<div id='" + logs[i].logTitle.replace(/\s/g, "-") + "-log-container' data-category='" + logs[i].category + "' data-index='" + i + "' class='flex-child log-container'></div>");
                }
                $("#" + logs[i].logTitle.replace(/\s/g, "-") + "-log-container").data("category", logs[i].category);
                $("#" + logs[i].logTitle.replace(/\s/g, "-") + "-log-container").data("index", i);
                loadLog(logs[i]);
            }
            if (typeof logs[i].category != "undefined" && categories.indexOf(logs[i].category) == -1 && logs[i].category != "All") {
                categories.push(logs[i].category);
            } else if (typeof logs[i].category == "undefined" && categories.indexOf("Uncategorized") == -1) {
                categories.push("Uncategorized");
            }
        }
    }
    if (categories.length > 0 && !(categories.length == 1 && categories[0] == "Uncategorized")) {
        //console.log("choice 1");
        var allFilter = (filter[0] == "" || arraySubset(filter, categories)) ? "true" : "false";
        var categoryFilter = window.location.hash.substr(1);
        if (allFilter == "true") categoryFilter = categories.join(",");

        html += "<div class='category-item'>" +
            // CHANGE ME:
            //"<a href='#' class='category-filter-item'>All</a>" +
            "<div class='category-filter-item'>All</div>" +
            "<label class=\"switch category-switch\" title=\"Display All\">" +
            "<span class=\"slider round\" data-enabled=\"" + allFilter + "\" onclick=\"toggleCategory('', '" + categoryFilter + "');\"></span>" +
            "</label>" +
            "</div>";

        categories.sort();
        for (let i = 0; i < categories.length; i++) {
            var catFilter = (allFilter == "true" || filter.indexOf(categories[i]) != -1) ? "true" : "false";
            html += "<div class='category-item'>" +
                // CHANGE ME:
                //"<a href='#" + categories[i] + "' class='category-filter-item'>" + categories[i] + "</a>" +
                "<div class='category-filter-item'>" + categories[i] + "</div>" +
                "<label class=\"switch category-switch\" title=\"Hide/display Category\">" +
                "<span class=\"slider round\" data-enabled=\"" + catFilter + "\" onclick=\"toggleCategory('" + categories[i] + "', '" + categoryFilter + "');\"></span>" +
                "</label>" +
                "</div>";
        }

        $('#categoryFilter').html(html);
        $('#categoryFilter').show();
    } else {
        //console.log("choice 2");
        $('#categoryFilter').hide();
    }

}

function loadLog(log) {
    var logTitle = log.logTitle;
    $.ajax({
        url: "assets/php/load-log.php",
        data: {'log': log},
        type: "POST",
        success: function (response) {
            $("#" + logTitle.replace(/\s/g, "-") + "-log-container").html(response);
            console.log("Updated log: " + logTitle);
            //TODO CHANGE ME:
            //notify("Error!", "Updated log: " + logTitle, "error", "Cool");
        }
    });
}

// highlight all "error" terms:
function highlightjs() {
    if ('customHighlightTerms' in settings && settings.customHighlightTerms !== "") {
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

// Jumps to the element matching the currentIndex
function jumpTo() {
    if (results.length) {
        let position,
            $current = results.eq(currentIndex);
        results.removeClass("current");
        if ($current.length) {
            $current.addClass("current");
            let currentMarkResult = $('.markresults.current');
            let parent = currentMarkResult.parent();
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

// Marks search results
function mark() {

    // Read the keyword
    let keyword = $("input[name='markinput']").val();
    let content = $(".slide");

    // Determine selected options

    // Mark the keyword inside the context:

    content.unmark({
        done: function () {
            content.mark(keyword, {
                separateWordSearch: false,
                done: function () {
                    results = content.find("mark");
                    let count = $(".count");
                    count.text(results.length);
                    count.append(" occurance(s) of: '");
                    count.append(keyword);
                    count.append("'");
                    results.addClass("markresults");
                    count.addClass("countresults");
                    currentIndex = 0;
                    if (settings.jumpOnSearch === "true") jumpTo(); // Auto focus/scroll to first searched term after search submit, if user had enabled option in config
                }
            });
        }
    });
}

// on page ready functions
$(function () {

    // Perform search action on click
    $("button[data-search='search']").on("click", function () {
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

    // Perform search action on enter
    $("#text-search2").keyup(function (event) {
        if (event.keyCode === 13) {
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
        }
    });

    // Clears the search
    $("button[data-search='clear']").on("click", function () {
        console.log('Logarr cleared search results');
        $.growlUI('Clearing <br> search results');
        $(".slide").unmark();
        $("input[name='markinput']").val("");
        $('.count').removeClass("countresults");
        $('.btn-visible').addClass("btn-hidden");
    });

    // Next and previous search jump to
    $("button[data-search='next']").add($("button[data-search='prev']")).on("click", function () {
        if (results.length) {
            currentIndex += $(this).is($("button[data-search='prev']")) ? -1 : 1;
            if (currentIndex < 0) {
                currentIndex = results.length - 1;
            }
            if (currentIndex > results.length - 1) {
                currentIndex = 0;
            }
            jumpTo();
        }
    });

    // Live search as soon as user keyup in search field:
    let timeoutID = null;
    $("input[name='markinput']").keyup(function (e) {
        clearTimeout(timeoutID);
        if (settings.liveSearch === "true") {
            $('.btn-visible').removeClass("btn-hidden"); // unhide next/previous buttons on search
            timeoutID = setTimeout(() => mark(e.target.value), 500);
        }
    });

    // unlink log action
    $(document).on('click', "button[data-action='unlink-log']", function (event) {
        event.preventDefault(); // stop being refreshed
        console.log('Attempting log roll');
        $.growlUI("Attempting <br> log roll");
        $.ajax({
            type: 'POST',
            url: 'assets/php/unlink.php',
            processData: false,
            data: "file=" + $(".path[data-service='" + $(this).data('service') + "']").html().trim(),
            success: function (data) {
                $('#modalContent').html(data);
                let modal = $('#responseModal');
                let span = $('.closemodal');
                modal.fadeIn('slow');
                span.click(function () {
                    modal.fadeOut('slow');
                });
                $(body).click(function (event) {
                    if (event.target !== modal) {
                        modal.fadeOut('slow');
                    }
                });
                setTimeout(function () {
                    modal.fadeOut('slow');
                }, 3000);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("ERROR: unlink ajax posting failed");
            }
        });
        return false;
    });

    // download log action
    $(document).on('click', "button[data-action='download-log']", function (event) {
        event.preventDefault(); // stop page from being refreshed
        $.growlUI("Downloading <br> log file");
        let logFilePath = ($(".path[data-service='" + $(this).data('service') + "']").html()).replace('file=', '').trim();
        console.log("Downloading log file: " + logFilePath);
        window.open('assets/php/download.php?file=' + logFilePath);
        return false;
    });

    // update log action
    $(document).on('click', "button[data-action='update-log']", function (event) {
        event.preventDefault(); // stop page from being refreshed
        loadLog(logs[$(this).parent().parent().data("index")]);
        return false;
    });

    // filter logs
    $(document).on('click', ".category-filter-item", function (event) {
        refreshblockUI();
        setTimeout(function () {
            console.log('Filtering logs on: ' + window.location.hash);
        }, 500);
    });
});

function refreshConfig() {
    $.ajax({
        url: "assets/php/sync-config.php",
        type: "GET",
        success: function (response) {

            let json = JSON.parse(response);
            settings = json.settings;
            preferences = json.preferences;
            logs = json.logs;

            if (settings.rfconfig !== rfconfig) {
                rfconfig = settings.rfconfig;
                clearInterval(nIntervId["refreshConfig"]);
                nIntervId["refreshConfig"] = setInterval(refreshConfig, rfconfig);
            }

            $("#auto-update-status").attr("data-enabled", settings.logRefresh);

            if (settings.logRefresh === "true" && (logInterval === false || settings.rflog !== current_rflog)) {
                clearInterval(nIntervId["logRefresh"]);
                nIntervId["logRefresh"] = setInterval(refreshblockUI, settings.rflog);
                logInterval = true;
                $("#autoUpdateSlider").attr("data-enabled", "true");
                current_rflog = settings.rflog;
                console.log("Auto update: Enabled | Interval: " + settings.rflog + " ms");
                $.growlUI("Auto update: Enabled");
            } else if (settings.logRefresh === "false" && logInterval === true) {
                clearInterval(nIntervId["logRefresh"]);
                logInterval = false;
                $("#autoUpdateSlider").attr("data-enabled", "false");
                console.log("Auto update: Disabled");
                $.growlUI("Auto update: Disabled");
            }

            document.title = preferences.sitetitle; //update page title to configured title
            console.log('Refreshed config variables');
        }
    });
}

function overwriteLogUpdate() {

    if (!autoUpdateOverwrite) {
        console.log("Auto update will apply after the setting is changed and this page is refreshed");
    }

    if ($("#autoUpdateSlider").attr("data-enabled") === "false") {
        autoUpdateOverwrite = true;
        $("#autoUpdateSlider").attr("data-enabled", "true");

        clearInterval(nIntervId);
        nIntervId = setInterval(refreshblockUI, settings.rflog);
        logInterval = true;

        console.log("Auto update: Enabled | Interval: " + settings.rflog + " ms");
        $.growlUI("Auto update: Enabled");
    } else {
        autoUpdateOverwrite = true;
        $("#autoUpdateSlider").attr("data-enabled", "false");

        clearInterval(nIntervId);
        logInterval = false;

        console.log("Auto update: Disabled");
        $.growlUI("Auto update: Disabled");
    }
}

function updateTime() {
    setInterval(function () {
        var res = date.toString().split(" ");
        var time = res[4];
        var timeSplit = time.split(":");
        if (timeStandard) {
            time = parseInt((timeSplit[0] > 12) ? (timeSplit[0] - 12) : timeSplit[0]) + ":" + timeSplit[1] + ":" + timeSplit[2];
            if (timeSplit[0] >= 12) {
                time += " PM";
            } else {
                time += " AM";
            }
        }
        var dateString = res[0] + ' | ' + res[2] + " " + res[1] + "<br>" + res[3];
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
    document.getElementById("setttings-page-title").innerHTML = 'Log Configuration';
    document.getElementById("includedContent").innerHTML = '<object type="text/html" class="object" data="assets/php/settings/logs_settings.php" ></object>';
    $(".sidebar-nav-item").removeClass('active');
    $("li[data-item='logs-configuration']").addClass("active");
}

function load_registration() {
    document.getElementById("setttings-page-title").innerHTML = 'Registration';
    document.getElementById("includedContent").innerHTML = '<object type="text/html" class="object" data="assets/php/settings/registration.php" ></object>';
    $(".sidebar-nav-item").removeClass('active');
    $("li[data-item='registration']").addClass("active");
}

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("myBtn").style.display = "block";
    } else {
        document.getElementById("myBtn").style.display = "none";
    }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

function checkedAll(isChecked) {
    var c = document.getElementsByName('slidebox');

    for (var i = 0; i < c.length; i++) {
        if (c[i].type == 'checkbox') {
            c[i].checked = isChecked;
        }
    }
}

function checkAll1() {
    checkedAll(true);
}

function parseGithubToHTML(result) {

    result = result.replace(/\n/g, '<br />'); //convert line breaks

    result = result.replace(/\*\*\*(.*)\*\*\*/g, '<em class="bold italic">$1</em>'); // convert bold italic text
    result = result.replace(/\*\*(.*)\*\*/g, '<em class="bold">$1</em>'); // convert bold italic text
    result = result.replace(/\*(.*)\*/g, '<em class="italic">$1</em>'); // convert bold italic text

    result = result.replace(/\_(.*)\_/g, '<em class="italic">$1</em>'); // convert to italic text

    result = result.replace(/\#\#\#(.*)/g, '<h3>$1</h3>'); // convert to H3
    result = result.replace(/\#\#(.*)/g, '<h2>$1</h2>'); // convert to H2
    result = result.replace(/\#\s(.*)/g, '<h1>$1</h1>'); // convert to H1

    result = result.replace(/\[(.*)\]\((http.*)\)/g, '<a class="releaselink" href=$2 target="_blank" title="$1">$1</a>'); // convert links with titles
    result = result.replace(/(https:\/\/github.com\/Monitorr\/logarr\/issues\/(\d*))/g, '<a class="releaselink" href="$1" title="GitHub Issue" target="_blank">#$2</a>'); // convert issue links
    result = result.replace(/\s(https?:\/\/?[-A-Za-z0-9+&@#/%?=~_|!:,.;]+[-A-Za-z0-9+&@#/%=~_|])/g, '<a class="releaselink" href="$1" target="_blank">$1</a>'); // convert normal links

    var addItems = [];
    var fixItems = [];
    var changeItems = [];


    result = result.replace(/(?:<br \/>)*\d+\.\s*ADD: (.*)/gi, function (s, match) {
        addItems.push(match);
        return "";
    });
    result = result.replace(/(?:<br \/>)*\d+\.\s*FIX: (.*)/gi, function (s, match) {
        fixItems.push(match);
        return "";
    });
    result = result.replace(/(?:<br \/>)*\d+\.\s*CHANGE: (.*)/gi, function (s, match) {
        changeItems.push(match);
        return "";
    });

    result = result.replace(/(?:\n*(?:<br.*>)*\n*\s*)*-?\s*(?:(?:Other\s*changes)|(?:Changes)):(?:(?:\n*(?:<br.*>)*\n*\s*)*(?:(?:\d+\.\s*)|(?:-\s*\t*))(.*))(?:(?:\n*(?:<br.*>)*\n*\s*)*(?:(?:\d+\.\s*)|(?:-\s*\t*))(.*))?(?:(?:\n*(?:<br.*>)*\n*\s*)*(?:(?:\d+\.\s*)|(?:-\s*\t*))(.*))?(?:\n*(?:<br.*>)*\n*\s*)*/g, function (s, m1, m2) {
        if (m1 != "") changeItems.push(m1);
        if (m2 != "") changeItems.push(m2);
        return "";
    });

    if ((addItems.length > 0) || (fixItems.length > 0) || (changeItems.length > 0)) {
        result += "<h3> - CHANGE LOG:</h3><ol>";
    }

    var i = 0;
    for (i = 0; i < addItems.length; i++) {
        result += "<li><i class='fa fa-plus'></i> ADD: " + addItems[i] + "</li>";
        if (i == addItems.length - 1 && i != 0) result += "<br>";
    }

    var i = 0;
    for (i = 0; i < fixItems.length; i++) {
        result += "<li><i class='fa fa-wrench'></i> FIX: " + fixItems[i] + "</li>";
        if (i == fixItems.length - 1 && i != 0) result += "<br>";
    }

    var i = 0;
    for (i = 0; i < changeItems.length; i++) {
        result += "<li><i class='fa fa-lightbulb'></i> CHANGE: " + changeItems[i] + "</li>";
    }

    result += "</ol>";

    return result;
}

function toggleCategory(category, categoryList) {
    var categories;
    if (category != "") {
        categories = categoryList.split(',');
        var index = categories.indexOf(category);
        if (index == -1) {
            categories.push(category);
        } else {
            $(".log-container[data-category='" + category + "']").remove();
            categories.splice(index, 1);
        }
        category = categories.join();
    }
    window.location.hash = category;
    console.log('Filtering logs on: ' + category);
    loadLogs();
}

function arraySubset(arr1, arr2) {
    for (var i = arr2.length; i--;) {
        if (arr1.indexOf(arr2[i]) == -1) return false;
    }
    return true;
}