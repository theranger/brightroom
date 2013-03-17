$(document).ready(init);

function init() {
	$("div.image a").click(loadImage);
}

function loadImage() {
	var url = $(this).attr("href");
	
	$.get(url, function(response) {
		$("div.content").html(response);
	});
	return false;
}