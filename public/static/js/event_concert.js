new WOW().init();

$(function() {
	$('.footer-back').click(function() {
		$('body,html').animate({
			scrollTop: 0
		}, 600);
	});
	$('.slide-wrapper img').click(function() {
		var img = $('.element_to_pop_up > img').attr('src', this.src);
		$('.element_to_pop_up').append(img);
		$(img).on('load', function() {
			$('.element_to_pop_up').bPopup();
		});
	});
	$('.btn-close').click(function() {
		$('.b-modal.__b-popup1__').click();
	});
	const $c = $(".js-carousel");
	var slideLength = $("#carousel img").length;
	$("#carousel").owlCarousel({
		rewind:true,
		// center: true,
		dots:false,
		nav: true,
		autoplay: slideLength>3,
		loop: slideLength>3,
		margin: 29,
		responsive: {
			880: {
				items: slideLength>3 ? 3 : slideLength,
				slideBy: 3
			},
			0: {
				items: 1,
				slideBy: 1
			}
		}
	});

	$('.js-next').click(function() {
		$c.trigger('next.owl.carousel');
	});
	$('.js-prev').click(function() {
		$c.trigger('prev.owl.carousel');
	});
});