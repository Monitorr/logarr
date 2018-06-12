// function to reorder
$(document).ready(function(){
	// check users files and update with most recent version
	$('#version_check').on('click',function(e) {
		//$('#loading').show();
		var uid = $(this).attr("id");
		var info = "uid="+uid+"&vcheck=1";
		$.ajax({
		   beforeSend: function(){
			   $('#version_check').html('<img src="assets/images/loader.gif" width="16" height="16" />');
			   console.log('Logarr is checking for an application update.');
		   },
		   type: "POST",
		   url: "assets/php/version_check.php",
		   data: info,
		   dataType: "json",
		   success: function(data){
			   // clear loading information
			   $('#version_check').html("");
			   // check for version verification
			   if(data.version != 0){
				   var uInfo = "uid="+uid+"&version="+data.version
			    	$.ajax({
					   beforeSend: function(){
						   $('#version_check').html('<img src="assets/images/loader.gif" width="16" height="16" />');
					   },
					   type: "POST",
					   url: "assets/php/update-functions.php",
					   data: uInfo,
					   dataType: "json",
					   success: function(data){
						   // check for version verification
			  			   if(data.copy != 0){ 
						   	   if(data.unzip == 1){ 
							       // clear loading information
						   		   $('#version_check').html("");
								   // successful update
									console.log('Logarr update successful! Reloading Logarr in 5 seconds...');
									$('#version_check').html("<strong>Update Successful!</strong>");
									$.blockUI(
										{
											css: {
												width: '35%',
												left: '32.5%',
											},
											message: 'Update Successful! <br> Reloading Logarr in 5 seconds...'
										}
									);
									setTimeout(location.reload.bind(location), 5000);
                                  // location.reload();
							   } else{
								   // error during update/unzip
								   	console.log('Logarr update: An error occured while extracting the files.');
									$('#version_check').html("<strong>An error occured while extracting the files.</strong>");
									$.growlUI('An error occured while extracting the files.');
									setTimeout(5000);
							   }

                           } else {
							   		console.log('Logarr update: An error occured while copying the files.');
									$('#version_check').html("<strong>An error occured while copying the files.</strong>");
									$.growlUI('An error occured while copying the files.');
									setTimeout(5000);
								
                           }
					   },
					   error: function() {
						   // error
						   console.log('Logarr update: An error occured while updating your files.');
						   $('#version_check').html('<strong>An error occured while updating your files.</strong>');
						   $.growlUI('An error occured while updating your files.');
						   setTimeout(5000);
					   }
					});
			   } else{
					// user has the latest version already installed
					console.log('Logarr update: You have the latest version. Reloading Logarr in 5 seconds...');
					$('#version_check').html("");   
					$('#version_check').html("<strong>You have the latest version. Reloading Logarr in 5 seconds...</strong>");
					// setTimeout(location.reload.bind(location), 5000);
					$.blockUI(
						{
						css: { 
							width: '35%',
							left: '32.5%',
						},
							message: 'You have the latest version. <br> Reloading Logarr in 5 seconds...'
						}
					);
					setTimeout(location.reload.bind(location), 5000);
			   }
		   },
		   error: function() {
			   // error
			   console.log('Logarr update: An error occured while checking your Logarr version.');
			   $('#version_check').html('<strong>An error occured while checking your Logarr version.</strong>');
			   $.growlUI('An error occured while checking your Logarr version.');
			   setTimeout(5000);
		   }
		});
	});
});
