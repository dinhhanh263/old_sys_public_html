$(function(){
	//ページトップ
	$("#pagetop").click(function() {
		$('body, html').animate({ scrollTop: 0 }, 500);
		return false;
 	});
	
	$("#spmenubtn").click(function(){
		$("#spnav").slideToggle();
		$(this).toggleClass("open");
		return false;
	});

	//ページ内スクロール
	$('a.scroll').click(function(){
		var speed = 500;
		var href= $(this).attr("href");
		var target = $(href == "#" || href == "" ? 'html' : href);
		var position = target.offset().top - 140;
		$("html, body").animate({scrollTop:position}, speed, "swing");
		return false;
	});

	//salon tabs
	$(".tabs a").hover(function(event) {
			$(this).parent().addClass("current");
			$(this).parent().siblings().removeClass("current");
			var tab = $(this).attr("href");
			$(tab).siblings(".tab-content").hide();
			$(tab).show();
	},function(){
		$(this).parent().removeClass("current");
		var tab = $(this).attr("href");
		$(tab).hide();
	});
	//salon tabs
	$(".tabs02 a").hover(function(event) {
			event.preventDefault();
			$(".tabs02 li").removeClass("current");
			$(this).parent().addClass("current");
			var tab = $(this).attr("href");
			$(tab).siblings(".tab-content").hide();
			$(tab).show();
	},function(){
		$(this).parent().removeClass("current");
		var tab = $(this).attr("href");
		$(tab).hide();
	});

	$(".tabs a, .tabs02 a").click(function(){
		return false;
	});

	//tabs fadein
	$(".tabs03 a").click(function(event) {
			event.preventDefault();
			$(this).parent().addClass("current");
			$(this).parent().siblings().removeClass("current");
			var tab = $(this).attr("href");
			$(tab).siblings(".tab-content").hide();
			$(tab).fadeIn();
	});


});