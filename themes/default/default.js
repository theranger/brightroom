$(document).ready(init);

function init() {
	$("a.image").click(loadImage);
}

function loadImage() {
	var url = $(this).attr("href");
	
	$.ajax({
		url:		url,
		data:		{ ajax: true },
		context:	$(this),
		success:	renderResponse,
	});
	
	return false;
}

function renderResponse(response) {
	$("div.content").html(response);
	$("div.image").each(function() {
		$(this).removeClass("selected");
	});
	
	$(this).closest("div.image").addClass("selected");
}