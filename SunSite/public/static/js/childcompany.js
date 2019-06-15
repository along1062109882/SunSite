new WOW().init();

$(function () {
  var selectedIndex = 1;

  var $oBox = $(".mainImgsContainer"),
    $oUl = $(".mainImgs"),
    $oLis = $(".mainImg");

  $oLis.css({ width: $oBox.width() + "px" });
  $oUl
    .css({
      left: -$oBox.width() + "px",
      width: $oBox.width() * 4 + "px"
    })
    .prepend($oLis.eq(-1).clone(true))
    .append($oLis.eq(0).clone(true));
  $oBox.css({ height: $oUl.height() });

  function rerender() {
    var $latestLis = $(".mainImg");
    $latestLis.css({ width: $oBox.width() + "px" });
    $oUl.css({
      left: -$oBox.width() + "px",
      width: $oBox.width() * 4 + "px"
    });
    $oBox.css({ height: $latestLis.first().height() });
  }

  rerender();

  $oLis.children('img').on('load', rerender);

  $(window).resize(function () {
    rerender();
  });

  function autoPlay() {
    setInterval(function () { play(1) }, 5000);
  }

  function play(step) {
    if (selectedIndex > 1 && step === 1) {
      selectedIndex = 1;
    } else if (selectedIndex < 2 && step === -1) {
      selectedIndex = 2;
    } else {
      selectedIndex += step;
    }

    $oUl.animate({ left: -(1 + step) * $oBox.width() }, function () {
      if (step > 0) {
        $oUl.append($(".mainImgs li:gt(1):lt(" + step + ")").clone());
        $(".mainImgs li:lt(" + step + ")").remove();
      } else {
        $oUl.prepend($(".mainImgs li:lt(-2):gt(" + (step - 1) + ")").clone());
        $(".mainImgs li:gt(" + (step - 1) + ")").remove();
      }
      $oUl.css({ left: -$oBox.width() + "px" });
      $('.bar-selected')
        .addClass('bar')
        .removeClass('bar-selected')
        .click(pressIndex)

      $('.navigator-bar .bar:nth-child(' + selectedIndex + ')')
        .addClass('bar-selected')
        .removeClass('bar')
        .unbind('click', pressIndex);
    });
  }

  function pressIndex() {
    var step = $(this).index() - $('.navigator-bar .bar-selected').index();
    play(step);
  }

  $('.mainImgsContainer .bar').click(pressIndex);

  autoPlay();

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
  // 图片加载有误
  $(".onerror").on("error", function () {
    $(this).attr("src", "/static/imgs/group.svg");
  });

  $(".onerror").each(function () {
    var img_url = $(this).attr("src");
    if (img_url == '') {
      $(this).attr("src", "/static/imgs/group.svg");
    }
  });
  // 进行页面分类展示 Tab 切换
  $('.tab_one span').show();
  $('.show_type_img').eq(0).css("display", "flex").siblings('.show_type_img').hide();
  $('.show_type_content1').eq(0).css("display", "flex").siblings('.show_type_content1').hide()
  $('.tab_type p').on('click', function () {
    if ($(this).index() === 0) {
      $('.tab_one span').show();
      $('.tab_two span').hide();
      $('.show_type_img').eq(0).css("display", "flex").siblings('.show_type_img').hide();
      $('.show_type_content1').eq(0).css("display", "flex").siblings('.show_type_content1').hide()

    } else {
      $('.tab_two span').show();
      $('.tab_one span').hide();
      $('.show_type_img').eq(1).css("display", "flex").siblings('.show_type_img').hide();
      $('.show_type_content1').eq(1).css("display", "flex").siblings('.show_type_content1').hide()
    }
  })
});
// 中间大图区域添加轮播效果
  //轮播图函数
  console.log($('.swiper-container .swiper-slide').length)
  var mySwiper = new Swiper('.swiper-container', {//初始化Swiper
    autoplay: $('.swiper-container .swiper-slide').length > 0 ?{//自动切换
        delay: 3000,
        stopOnLastSlide: false,
        disableOnInteraction: false,
      } : false,
     navigation: {//前进后退
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
     },
     pagination: {//分页器
        el: '.swiper-pagination',
       clickable :true,
     },
     loop : true,//循环
 })
// childcompany latest-news slide
// item max:3
var slideLength = $(".childCompany-slide .slide-item").length;
$(".childCompany-slide").length > 0 && $(".childCompany-slide").owlCarousel({
  rewind: true,
  responsiveClass: true,
  center: slideLength >= 3,
  loop: slideLength > 3,
  autoplay: slideLength > 3,
  dots: false,
  nav: true,
  responsive: {
    880: {
      startPosition: 1,
      items: slideLength > 3 ? 3 : slideLength,
      slideBy: 3,//每次滑动个数，不需要可删除
    },
    0: {
      items: 1,
      slideBy: 1,
    }
  }
});