$(document).ready(init);

function init() {
	$("a.sfg-image").click(loadImage);
	$("a.sfg-next").click(loadImage);
	$("a.sfg-previous").click(loadImage);
}

function loadImage() {
	var url = $(this).attr("href");
	
	$.ajax({
		url:		url,
		data:		{ "sfg-ajax": true },
		success:	renderResponse,
	});
	
	return false;
}

function renderResponse(response) {
	var request = this.url.replace(/^.*\/|#[^#]*|\?[^\?]*$/g, '');
	
	$("div.sfg-main").html(response);
	$("div.sfg-image").each(function() {
		$(this).removeClass("sfg-selected");
		
		var url = $(this).children("a").attr("href").replace(/^.*\/|#[^#]*|\?[^\?]*$/g, '');
		if(request == url) {
			$(this).addClass("sfg-selected");
			var scroll = $(this).parents("div.sfg-sidebar");
			var height = scroll.height()/2;
			var pos = $(this).position().top + $(this).height()/2;
			
			if(scroll != undefined && pos > height) scroll.animate({scrollTop: scroll.scrollTop() + (pos - height)}, 500);
			if(scroll != undefined && pos < height) scroll.animate({scrollTop: scroll.scrollTop() - (height - pos)}, 500);
		}
	});
	
	$("a.sfg-next").click(loadImage);
	$("a.sfg-previous").click(loadImage);
}