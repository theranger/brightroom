$(document).ready(init);

function init() {
	$("div.image a").click(loadImage);
}

function loadImage() {
	var url = $(this).attr("href");
	
	$.ajax({
		url:		url,
		data:		{ ajax: true },
		success:	function(response) { $("div.content").html(response); }
	});
	
	return false;
}