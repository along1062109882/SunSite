function StartCountdown(endTime) {
	var dayDom = document.querySelector("#days"),
		hourDom = document.querySelector("#hours"),
		minuteDom = document.querySelector("#minutes"),
		secondDom = document.querySelector("#seconds"),
		date = Math.floor((endTime - Date.now()) / 1000);

	if (date > 0) {
		setTime(date);
	}

	function setTime(date) {
		var day = Math.floor(date / 86400);
		var hour = ('0' + Math.floor(date % 86400 / 3600)).slice(-2);
		var minute = ('0' + Math.floor(date % 3600 / 60)).slice(-2);
		var second = ('0' + Math.floor(date % 60)).slice(-2);
		day = day < 10 ? ('0' + day) : day;
		if (dayDom.innerHTML != day) {
			dayDom.innerHTML = day;
		}
		if (hourDom.innerHTML != hour) {
			hourDom.innerHTML = hour;
		}
		if (minuteDom.innerHTML != minute) {
			minuteDom.innerHTML = minute;
		}
		if (secondDom.innerHTML != second) {
			secondDom.innerHTML = second;
		}
		date -= 1;
		if (date >= 0) {
			setTimeout(function() {
				setTime(date);
			}, 1000);
		}
	}
}
var item1Num = $("#carousel .event-focus-item").length;
$("#carousel").owlCarousel({
	// center: true,
	rewind:true,
	nav: true,
	autoplay: item2Num>3,
	dots: false,
	// dotsEach:6,
	// responsiveClass: true,
	loop: item2Num>3,
	margin: 29,
	responsive: {
		880: {
			items: item2Num>3?3:item2Num,
			slideBy: item2Num>3?3:1
		},
		0: {
			items: 1,
			slideBy: 1
		}
	}
});
var item2Num = $("#carousel2 .event-focus-item").length;
$("#carousel2").owlCarousel({
	// center: true,
	rewind:true,
	nav: true,
	dots: false,
	autoplay: item2Num>3,
	// responsiveClass: true,
	loop: item2Num>3,
	margin: 29,
	responsive: {
		880: {
			items: item2Num>3?3:item2Num,
			slideBy: item2Num>3?3:1
			// slideBy: "page"
		},
		0: {
			items: 1,
			slideBy: 1
		}
	}
});
$('.tidbit img').click(function() {
	var img = $('.element_to_pop_up > img').attr('src', this.src);
	$('.element_to_pop_up').append(img);
	$(img).on('load', function() {
		$('.element_to_pop_up').bPopup();
	});
});
$('.btn-close').click(function() {
	$('.b-modal.__b-popup1__').click();
});