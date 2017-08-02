<?php
function readExternalLog($filename)
{
    $log = file($filename);
    $log = array_reverse($log);
    foreach ($log as $line) {
        echo $line.'<br/>';
    }
}
// ** Add Logs BELOW this paragraph, under the term "array" **
//Ensure correct permissions are set on the target log file
//If this page is exposed ot your WAN, check the logging applications' settings for senstive data within logs. 

	$logs = array(
    "Sonarr" => '/home/plex/.config/NzbDrone/logs/sonarr.txt',
    "Radarr" => '/home/plex/.config/Radarr/logs/radarr.txt',
    "Headphones" => "/home/plex/logs/headphones/headphones.log",
    /*"NZBGet" => "/home/plex/logs/nzbget/nzbget.log",*/
    "MP4 Converter" => "/home/plex/bin/sickbeard_mp4_automator/info.log",
    "NZBHydra" => "/home/plex/logs/nzbhydra/nzbhydra.log",

	// ** Add Logs ABOVE this line **

);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        
        <title>MY Logs</title>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	    <meta name="description" content="Demo of Real-Time Search in JavaScript" />
	    <meta name="robots" content="all">
	    <meta name="viewport" content="width=device-width,initial-scale=1" />
	    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,400" />
		<link rel="stylesheet" src="logarr.css"/>
        
        
        <script language='javascript' src="serverDate.js"></script>
        
        <script type="text/javascript" src="hilitor.js"></script>
        <script type="text/javascript">

            var myHilitor; // global variable
            document.addEventListener("DOMContentLoaded", function(e) {
            myHilitor = new Hilitor("content");
            myHilitor.apply("error");
            }, false);

        </script>

        <script type='text/javascript'>//<![CDATA[
            window.onload=function(){
            document.getElementById( 'slide' ).addEventListener( 'click', function() {
                var body = document.getElementById( 'slide-body' );
                if( body.className == 'expanded' ) {
                    body.className = '';
                    document.getElementById( 'more' ).textContent = 'more...';
                } else {
                    body.className = 'expanded';
                    document.getElementById( 'more' ).textContent = 'less...';
                };
            } );
            }//]]> 
        </script>

    </head>
    
    <body style="border: 10px solid #252525; color: #FFFFFF; background-color: #252525;">
        
        <div class="Row">
        
            <div id="time" class="Column">
                <p>
                    <strong>Local Server DTG:</strong>
                </p>

                <p>
                    <script language="javascript">
                    var localTime = srvTime();
                    document.write(localTime );
                    </script>
                </p>
            </div>

            <div id="logo" class="Column">
                <img src="log-icon.png" height="125px" />
            </div>
            			
			<div class="faq">
					
				<input type="search" value="" placeholder="search" />
  					
				<ul>
						<li id="faq-1">
								
							<p>
							test search data
							</p>
								
						</li>
				</ul>  
					
					<div class="faq__notfound">
					<p><strong>No matches were found.</strong></p>
					</div>
					
			</div>
		
		</div>


    	<?php foreach ($logs as $k => $v) { ?>

			<li id="faq-2">
				<div class="w3-container w3-center">
				<h3><span class="w3-text-indigo"><strong><?php echo $k; ?>:</strong></span></h3>
				</div>

					<div id="slide">
						<div id="slide-body" style="background-color: #404040; word-wrap: break-word; width: auto; height: 200px; overflow-y: scroll;">
						<p><?php readExternalLog($v); ?></p>
						</div>
						<div id="more">more...</div>
					</div>
			</li>
		
		<?php } ?>


		<script>

			'use strict';

			;( function ( document, window, index )
			{
				var hasElementClass		= function( element, className ){ return element.classList ? element.classList.contains( className ) : new RegExp( '(^| )' + className + '( |$)', 'gi' ).test( element.className ); },
					addElementClass		= function( element, className ){ element.classList ? element.classList.add( className ) : element.className += ' ' + className; },
					removeElementClass	= function( element, className ){ element.classList ? element.classList.remove( className ) : element.className = element.className.replace( new RegExp( '(^|\\b)' + className.split( ' ' ).join( '|' ) + '(\\b|$)', 'gi' ), ' ' ); };


				// search & highlight

				;( function ( document, window, index )
				{
					var container = document.querySelector( '.faq' );
					if( !container ) return true;

					var input			= container.querySelector( 'input' ),
						notfound		= container.querySelector( '.faq__notfound' ),
						items			= document.querySelectorAll( '.faq > ul > li' ),
						item			= {},
						itemsIndexed	= [];

					[].forEach.call( items, function( entry )
					{
						itemsIndexed.push( entry.textContent.replace( /\s{2,}/g, ' ' ).toLowerCase() );
					});

					input.addEventListener( 'keyup', function( e )
					{
						if( e.keyCode == 13 ) // enter
						{
							input.blur();
							return true;
						}

						[].forEach.call( items, function( entry )
						{
							entry.innerHTML = entry.innerHTML.replace( /<span class="highlight">([^<]+)<\/span>/gi, '$1' );
						});

						var searchVal = input.value.trim().toLowerCase();
						if( searchVal.length )
						{
							itemsIndexed.forEach( function( entry, i )
							{
								if( itemsIndexed[ i ].indexOf( searchVal ) != -1 )
								{
									removeElementClass( items[ i ], 'is-hidden' );
									items[ i ].innerHTML = items[ i ].innerHTML.replace( new RegExp( searchVal+'(?!([^<]+)?>)', 'gi' ), '<span class="highlight">$&</span>' );
								}
								else
									addElementClass( items[ i ], 'is-hidden' );
							});
						}
						else [].forEach.call( items, function( entry ){ removeElementClass( entry, 'is-hidden' ); });

						if( items.length == [].filter.call( items, function( entry ){ return hasElementClass( entry, 'is-hidden' ) } ).length )
							addElementClass( notfound, 'is-visible' );
						else
							removeElementClass( notfound, 'is-visible' );
							
					});
				}( document, window, 0 ));


				// auto-show item content when show results reduces to single

				;( function ( document, window, index )
				{
					var container = document.querySelector( '.faq' );
					if( !container ) return true;

					var input	= container.querySelector( 'input' ),
						items	= document.querySelectorAll( '.faq > ul > li' ),
						item	= {};

					input.addEventListener( 'keyup', function( e )
					{
						item = [].filter.call( items, function( entry ){ return !hasElementClass( entry, 'is-hidden' ); } )

						if( item.length == 1 )
						{
							addElementClass( item[ 0 ], 'js--autoshown' );
							addElementClass( item[ 0 ], 'is-active' );
						}
						else
							[].forEach.call( items, function( entry )
							{
								if( hasElementClass( entry, 'js--autoshown' ) )
								{
									removeElementClass( entry, 'js--autoshown' );
									removeElementClass( entry, 'is-active' );
								}
							});
					});
				}( document, window, 0 ));

			}( document, window, 0 ));

		</script>

    </body>
    
</html>
