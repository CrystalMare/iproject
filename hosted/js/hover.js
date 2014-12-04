$(document).ready(function( ) {

	$(".cat").hover(
		//Hovering button
		function() {
			$("#hover").css("display", "inline");
			if ($(this).attr("id") == "hover")
				return;
			updateHover($(this).attr("id").substring(1));
		}, 
		//No longer hovering button
		function() {
			$("#hover").css("display", "none")
		}
	);
});

function updateHover(menuid) {
	if (menuid == "1")
	{
		$("#hover").html('<div class="col-md-3"><img src="img/hover_image.png" alt="Auto\'s" class="img-responsive" /></div><div class="col-md-9">Eerste MENU</div>');
	} else if (menuid == "2")
	{
		$("#hover").html('<div class="col-md-3"><img src="img/hover_image.png" alt="Auto\'s" class="img-responsive" /></div><div class="col-md-9">Tweede MENU</div>');
	}
}