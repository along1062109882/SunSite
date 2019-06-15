new WOW().init();

$(function () {
  var mainPageBtn = $(".navigator :first");

  mainPageBtn.addClass("selected");

  $('.navigator-bar .bar').click(pressIndex);

  function pressIndex() {
    var step = $(this).index() - $('.navigator-bar .bar-selected').index();
    play(step);
  }

  $('.navigator :gt(1)')
    .hover(function () {
      $(this).addClass("selected");
      mainPageBtn.removeClass("selected");
    }, function () {
      $(this).removeClass("selected");
      mainPageBtn.addClass("selected");
    });


  $('.main-menu-btn').click(function () {
    var $navigator = $('.navigator');
    if ($navigator.css('display') === 'block') {
      $navigator.css('display', 'none');
    } else {
      $navigator.css('display', 'block');
    }
  });

  if ($('html').width() < 1080) {
    $('.navigator li').click(function () {
      $(this).children('a')[0].click();
    });
  }

  $('.footer-back').click(function () {
    $('body,html').animate({ scrollTop: 0 }, 600);
  });

  const $c = $(".js-carousel");
  $c.owlCarousel({
    rewind: true,
    nav: false,
    autoplay: true,
    responsiveClass: true,
    loop: true,
    responsive: {
      1000: {
        items: 3,
      },
      735: {
        items: 2,
      },
      0: {
        items: 1,
      }
    }
  });

  $('.js-next').click(function () {
    $c.trigger('next.owl.carousel');
  });

  $('.js-prev').click(function () {
    $c.trigger('prev.owl.carousel');
  });
  // 首页 banner 图添加手风琴效果
    var li_warp = $('.ts-ul')
    var totle = li_warp.children('li').length;
    var index = -1;
    var timeOut = null;
    $(".ts-ul li").eq(0).addClass('on');
    $(".ts-ul li").hover(function () {
      clearInterval(timeOut);
      $(".ts-ul li").removeClass("on");
      $(this).addClass("on");
      // $(this).addClass('banner-item-bg').siblings().removeClass('banner-item-bg')
      index = $(this).index();
      //  console.log(index);
    }, function () {
      autoPlay();
    });
    function autoPlay() {
      timeOut = setInterval(function () {
        if (index === totle) {
            index = 0;
        }
        index++;
        $(".ts-ul li").eq(index).addClass('on').siblings('li').removeClass('on');
        if (index == totle-1) {
          index = -1;
        }
      }, 5000);
    }
    autoPlay();
});
