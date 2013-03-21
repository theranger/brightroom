$(document).ready(init);

function init() {
	$("a.image").click(loadImage);
	$("a.next").click(loadImage);
	$("a.previous").click(loadImage);
}

function loadImage() {
	var url = $(this).attr("href");
	
	$.ajax({
		url:		url,
		data:		{ ajax: true },
		success:	renderResponse,
	});
	
	return false;
}

function renderResponse(response) {
	var request = this.url.replace(/^.*\/|#[^#]*|\?[^\?]*$/g, '');
	
	$("div.content").html(response);
	$("div.image").each(function() {
		$(this).removeClass("selected");
		
		var url = $(this).children("a").attr("href").replace(/^.*\/|#[^#]*|\?[^\?]*$/g, '');
		if(request == url) $(this).addClass("selected");
	});
	
	$("a.next").click(loadImage);
	$("a.previous").click(loadImage);
}