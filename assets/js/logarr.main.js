// Logarr main JS script
// https://github.com/Monitorr

// Set styles for BlockUI overlays in /assets/js/jquery.blockUI.js


$(function () {
    $.growlUI('Loading logs...');
    console.log('Logarr is loading logs');
});


function refreshblockUI() {
    $.growlUI('Updating logs...');
    setTimeout(function () {
        $('.btn-visible').addClass("btn-hidden"); // Hide previous/next search buttons on log update (prevent memory leak)
        refresh();
    }, 300);

    //wait 3 seconds after log update to highlight error terms:

    setTimeout(function () {
        highlightjs();
    }, 3000);
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
                    var currentOffset = $('.markresults.current').offsetTop;
                    var parent = $('.markresults.current').parent();
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
                        $('.markresults.current').offset().top - parent.offset().top + parent.scrollTop()
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
                        $(".count").text($results.length);
                        $('.count').append(" occurance(s) of: '");
                        $('.count').append(keyword);
                        $('.count').append("'");
                        $results.addClass("markresults");
                        $('.count').addClass("countresults");
                        currentIndex = 0;
                        //jumpTo();  // Auto focus/scroll to first searched term after search submit
                    }
                });
            }
        });
    };

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
        var url = 'index.php';
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

});
