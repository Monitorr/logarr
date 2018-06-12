// function to reorder
$(document).ready(function(){
	// check users files and update with most recent version
	// $('#version_check_new').on('click',function(e) {
		// $('#loading').show();
		var uid = $(this).attr("id");
		var info = "uid="+uid+"&vcheck=1";
		$.ajax({
		   beforeSend: function(){
			   $('#version_check_auto').html('<img src="assets/images/loader.gif" width="16" height="16" />');
			   console.log('Logarr is checking for an application update.');
		   },
		   type: "POST",
		   url: "assets/php/version_check.php",
		   data: info,
		   dataType: "json",
		   success: function(data){
			   // clear loading information
			   $('#version_check_auto').html("");
			   // check for version verification
			   if(data.version != 0){
				   var uInfo = "uid="+uid+"&version="+data.version
					console.log('A Logarr update is available. Click "check for update" in the footer to update Logarr.');
				   	$.growlUI('An update is available');
				   	setTimeout(15000);

				   $('#version_check_auto').html(
					   '<div class="footer a" style="cursor: pointer"> <a href="https://github.com/Monitorr/logarr/releases" target = "_blank"> <b> An update is available</b></a> </div> <div class="notification">Click <strong>"check for update"</strong> above to update Logarr.</div>'
					);
			   }
			   
			   else{
				    // user has the latest version already installed
					$('#version_check_auto').html("");     
			   }
		   },
		   error: function() {
			   // error
			   console.log('An error occured while checking your Logarr version.');
			   $('#version_check').html('<strong>An error occured while checking your Logarr version.</strong>');
			   $.growlUI('An error occured while checking your Logarr version.');
			   setTimeout(10000);
		   }
		});
	// });
});