<?php
include('../functions.php');
error_reporting(E_ALL);
?>
<html>
<head>
	<meta charset="utf-8">
	<link type="text/css" href="../../css/bootstrap.min.css" rel="stylesheet">
	<link type="text/css" href="../../css/logarr.css" rel="stylesheet">
	<link type="text/css" href="../../data/custom.css" rel="stylesheet">

	<meta name="theme-color" content="#464646"/>
	<meta name="theme_color" content="#464646"/>

	<script src="../../js/jquery.min.js"></script>
	<script src="../../js/jquery.blockUI.js" async></script>
	<script>
        function checkGithub() {
            $.ajax({
                type: "GET",
                url: "https://api.github.com/repos/Monitorr/Logarr/releases",
                dataType: "json",
                success: function(github) {
                    var currentVersion = "";
                    infoTabVersion = $('#about').find('#version');
                    infoTabVersionHistory = $('#about').find('#versionHistory');
                    infoTabNew = $('#about').find('#whatsnew');
                    infoTabDownload = $('#about').find('#downloadnow');
                    $.each(github, function(i,v) {
                        if(i === 0){
                            console.log(v.tag_name);
                            githubVersion = v.tag_name;
                            githubDescription = v.body;
                            githubName = v.name;
                        }
                        var body = v.body.replace(/\n/g, '<br />'); //convert line breaks
                        body = body.replace(/\*\*\*(.*)\*\*\*/g, '<em class="bold italic">$1</em>'); // convert bold italic text
                        body = body.replace(/\*\*(.*)\*\*/g, '<em class="bold">$1</em>'); // convert bold italic text
                        body = body.replace(/\*(.*)\*/g, '<em class="italic">$1</em>'); // convert bold italic text
                        body = body.replace(/\[(.*)\]\((http.*)\)/g, '<a href=$2 target="_blank" title="$1">$1</a>'); // convert links with titles
                        body = body.replace(/(https:\/\/github.com\/Monitorr\/logarr\/issues\/(\d*))/g, '<a href="$1">#$2</a>'); // convert issue links
                        body = body.replace(/\s(https?:\/\/?[-A-Za-z0-9+&@#/%?=~_|!:,.;]+[-A-Za-z0-9+&@#/%=~_|])/g, '<a href="$1">$1</a>'); // convert normal links
                        $(infoTabVersionHistory).append(
                            '<li>' +
                            '<div class="github-item">' +
                                '<h2 class="text-uppercase">' + v.name + '</h2>' +
                                '<time class="github-item-time" datetime="' + v.published_at + '">' +
                                    '<span>Released on: ' + v.published_at.substring(0,10) + ' at ' + v.published_at.substring(11,19) + '</span>' +
                                '</time>' +
                                '<p>' + body + '</p>' +
                            '</div>' +
                            '<hr class="releasehr"\>' +
                            '</li>'
                        );
                    });
                }
            });
        }
	</script>
</head>
<body onload="checkGithub()" style="width: 100rem; margin: auto;">
<h1>Logarr Changelog</h1>
<div id="about">
	<div id="version"></div>
	<ul id="versionHistory"></ul>
	<div id="version"></div>
	<div id="downloadnow"></div>
</div>
</body>
</html>
