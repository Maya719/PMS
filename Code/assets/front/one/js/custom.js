$(function() {
	$('.home-menu').click(function() {
		if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
			if (target.length) {
				$('html,body').animate({
					scrollTop: target.offset().top
				}, 700);
				return false;
			}
		}
	});
});

// preloader
$(window).load(function(){
    $('.preloader').fadeOut(1000); // set duration in brackets    
});

$(function() {
    new WOW().init();
    /* Hide mobile menu after clicking on a link
    -----------------------------------------------*/
    $('.navbar-collapse a').click(function(){
        $(".navbar-collapse").collapse('hide');
    });
})

