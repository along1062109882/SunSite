new WOW().init();

$(function() {
  var mainPageBtn = $(".navigator :nth-child(5)");

  mainPageBtn.addClass("selected");

  $('.navigator :gt(1)')
    .hover(function() {
      $(this).addClass("selected");
      mainPageBtn.removeClass("selected");
    }, function() {
      $(this).removeClass("selected");
      mainPageBtn.addClass("selected");
    });

  $('.main-menu-btn').click(function() {
    var $navigator = $('.navigator');
    if ($navigator.css('display') === 'block') {
      $navigator.css('display', 'none');
    } else {
      $navigator.css('display', 'block');
    }
  });

  if ($('html').width() < 1080) {
    $('.navigator li').click(function() {
      $(this).children('a')[0].click();
    });
  }

  $('.footer-back').click(function() {
    $('body,html').animate({ scrollTop: 0 }, 600);
  });

  const animateTo = function() {
    var slug = $(this).data('slug');
    $('body,html').animate({ scrollTop: $('.bigevent-wrapper').find('section[data-slug="' + slug + '"]').offset().top }, 600);
  };

  $('.event-name-list span').click(animateTo);
  $('.js-item').click(animateTo);

  for (var i = 0;i < $('.temp-wrapper p').length;i++) {
    if ($('.temp-wrapper p').eq(i + 1).height() > ($('html').width() < 1080 ? 120: 170)) {
      $('.temp-wrapper p').eq(i + 1).addClass('show-des');
    }
  }

  var width = document.body.clientWidth
  var number = 1
  var length = $('.js-item').length
  if (length > 1) {
    if (width <= 450) {
      number = 2
    } else {
      number = 3
    }
  }
  // if (width >= 640) {
  //   number = 5
  // } else if (width <= 375) {
  //   number = 3
  // } else if (width > 375  && width < 640) {
  //   number = 4
  // }

  var swiper = new Swiper('.swiper-container', {
    initialSlide: 0,
    slidesPerView: number,
    spaceBetween: 0,
    slidesPerGroup: 3,
    // navigation: {
    //   nextEl: '.swiper-button-next',
    //   prevEl: '.swiper-button-prev',
    // },
  });

  $('.news-swiper-button-prev').click(function(){
    swiper.slidePrev();
  })
  $('.news-swiper-button-next').click(function(){
    swiper.slideNext();
  })
  $('body').on('click', '.js-swiper-slide', function(e) {
    $('.js-swiper-slide').removeClass('news-swiper-slide-active');
    $(e.currentTarget).addClass('news-swiper-slide-active');
  });
  $('.js-swiper-slide').eq(0).addClass('news-swiper-slide-active');
});
