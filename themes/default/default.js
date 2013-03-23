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
		if(request == url) {
			$(this).addClass("selected");
			var scroll = $(this).parents("div.sidebar");
			var height = scroll.height()/2;
			var pos = $(this).position().top;
			
			if(scroll != undefined && pos > height) scroll.animate({scrollTop: scroll.scrollTop() + (pos - height)}, 500);
			if(scroll != undefined && pos < height) scroll.animate({scrollTop: scroll.scrollTop() - (height - pos)}, 500);
		}
	});
	
	$("a.next").click(loadImage);
	$("a.previous").click(loadImage);
}