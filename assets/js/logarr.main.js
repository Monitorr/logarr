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


     // NEW Search form
        // NOT WORKING  Counting function
        // TO DO:  ADD BlockUI



    $(function () {

        // the input field
        var $input = $("input[name='markinput']"),
            // clear button
            $clearBtn = $("button[data-search='clear']"),
            // prev button
            $prevBtn = $("button[data-search='prev']"),
            // next button
            $nextBtn = $("button[data-search='next']"),
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

        /**
         * Jumps to the element matching the currentIndex
         */
        function jumpTo() {
            if ($results.length) {
                var position,
                    $current = $results.eq(currentIndex);
                $results.removeClass(currentClass);
                if ($current.length) {
                    $current.addClass(currentClass);
                    position = $current.offset().top - offsetTop;
                    window.scrollTo(0, position);
                }
            }
        }

         // count searched term occurances from mark search input field // NOT WORKING:


        var count2 = function () {
            var text = document.getElementById("text-search2").value;
            var count =
            $(".markresults").length; // append markresults class to ALL search results to count
            $(".count").text(count);
            $('.count').append(" occurance(s) of: '");
            $('.count').append(text);
            $('.count').append("'");
        };



        var mark = function () {

            // Read the keyword
            var keyword = $("input[name='markinput']").val();

            // Determine selected options
            var options = {
                "each": function (element) {
                    setTimeout(function () {
                        $(element).addClass("current");
                    }, 250);
                }
            };

            // Mark the keyword inside the context
            $content.unmark({
                done: function () {
                    $content.mark(keyword, {
                        separateWordSearch: true,
                        done: function () {
                            $results = $content.find("mark");
                            $results.addClass("markresults");
                            currentIndex = 0;
                            //jumpTo();  //Auto focus/scroll to first searched term after search submit // CHANGE ME
                        }
                    });
                }
            });
        };

       // $("input[type='button']").on("click", mark, count2); // NOT WORKING if enabled WILL count, but won't highlight // CHANGE ME
        $("input[type='button']").on("click", mark);
        mark();
        //count2(); // NOT WORKING  // CHANGE ME



        // THIS WILL "LIVE SEARCH" as soon as user keyup in search field: // DO NOT WANT THIS, but cool feature: //CHANGE ME
        /**
         * Searches for the entered keyword in the
         * specified context on input
         */
        // $input.on("click", function () {
        //     var searchVal = this.value;
        //     $content.unmark({
        //         done: function () {
        //             $content.mark(searchVal, {
        //                 separateWordSearch: true,
        //                 done: function () {
        //                     $results = $content.find("mark");
        //                     currentIndex = 0;
        //                     jumpTo();
        //                 }
        //             });
        //         }
        //     });
        // });



        /**
         * Clears the search
         */
        $clearBtn.on("click", function () {
            $content.unmark();
            $input.val("").focus();
        });

        /**
         * Next and previous search jump to
         */
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
    });


