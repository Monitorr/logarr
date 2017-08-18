<?php
function readExternalLog($filename)
{
    $log = file($filename);
    $log = array_reverse($log);
    foreach ($log as $line) {
        echo $line.'<br/>';
    }
}
include 'config/config.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title><?php echo $config['title']; ?></title>
        <link rel="apple-touch-icon-precomposed" sizes="57x57" href="images/favicon/apple-touch-icon-57x57.png" />
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/favicon/apple-touch-icon-114x114.png" />
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/favicon/apple-touch-icon-72x72.png" />
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/favicon/apple-touch-icon-144x144.png" />
		<link rel="apple-touch-icon-precomposed" sizes="60x60" href="images/favicon/apple-touch-icon-60x60.png" />
		<link rel="apple-touch-icon-precomposed" sizes="120x120" href="images/favicon/apple-touch-icon-120x120.png" />
		<link rel="apple-touch-icon-precomposed" sizes="76x76" href="images/favicon/apple-touch-icon-76x76.png" />
		<link rel="apple-touch-icon-precomposed" sizes="152x152" href="images/favicon/apple-touch-icon-152x152.png" />
		<link rel="icon" type="image/png" href="images/favicon/favicon-196x196.png" sizes="196x196" />
		<link rel="icon" type="image/png" href="images/favicon/favicon-96x96.png" sizes="96x96" />
		<link rel="icon" type="image/png" href="images/favicon/favicon-32x32.png" sizes="32x32" />
		<link rel="icon" type="image/png" href="images/favicon/favicon-16x16.png" sizes="16x16" />
		<link rel="icon" type="image/png" href="images/favicon/favicon-128.png" sizes="128x128" />
		<meta name="application-name" content="Logarr"/>
		<meta name="msapplication-TileColor" content="#FFFFFF" />
		<meta name="msapplication-TileImage" content="images/favicon/mstile-144x144.png" />
		<meta name="msapplication-square70x70logo" content="images/favicon/mstile-70x70.png" />
		<meta name="msapplication-square150x150logo" content="images/favicon/mstile-150x150.png" />
		<meta name="msapplication-wide310x150logo" content="images/favicon/mstile-310x150.png" />
		<meta name="msapplication-square310x310logo" content="images/favicon/mstile-310x310.png" />
	    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"/>
	    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,400" />
		<link rel="stylesheet" href="logarr.css" />
		
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	    <meta name="description" content="Demo of Real-Time Search in JavaScript" />
	    <meta name="robots" content="NOINDEX, NOFOLLOW">
	    <meta name="viewport" content="width=device-width,initial-scale=1" />

        
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
				<?php include "config/timezone.php" ?>
                <p>Server Local Date: <strong><?php echo "$server_date"?></strong></p>
                <p>Server Local Time: <strong><?php echo "$server_time"?></strong></p>
            </div>

            <div id="logo" class="Column">
                <img src="images/log-icon.png" height="125px" />
            </div>
            			
			<div class="faq">
					
				<input type="search" value="" placeholder="search" />
  					
				<ul>
						<li id="faq-1">
								
							<p>
							Search Function is NOT working.
							</p>
								
						</li>
				</ul>  
					
					<div class="faq__notfound">
					<p><strong>No matches were found.</strong></p>
					</div>
					
			</div>
		
		</div>


    	<?php foreach ($logs as $k => $v) { ?>
			<div class="w3-container w3-center">
				<h3><span class="w3-text-indigo"><strong><?php echo $k; ?>:</strong></span></h3>
			</div>
					<div id="slide">
						<div class="<?php echo $k; ?>" id="slide-body" >
						<p><?php readExternalLog($v); ?></p>
						</div>
						<div id="more">more...</div>
					</div>
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