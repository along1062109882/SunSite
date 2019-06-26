new WOW().init();

$(function () {
  var htmlWidth = $('html').width();
  /* 处理大屏幕版职位选择侧栏的 active 状态 */

  /* end */

  /* 处理顶部页面导航的 active 状态 */
  var mainPageBtn = $(".navigator :nth-child(6)");
  mainPageBtn.addClass("selected");
  /* end */

  /* 应用 swiper */
  var initialSlide = $('.job-item-link a[href="' + location.pathname + '"]').parent().index() || 0;
  var itemCount = 3;
  if (htmlWidth <= 660) itemCount = 2;
  var swiper = new Swiper('.swiper-container', {
    initialSlide: initialSlide,
    slidesPerView: itemCount,
    spaceBetween: 1,
    slidesPerGroup: 3,
  });
  
  $('.jobs-swiper-button-prev').click(function () {
    swiper.slidePrev();
  })
  $('.jobs-swiper-button-next').click(function () {
    swiper.slideNext();
  })
  $('body').on('click', '.js-swiper-slide', function (e) {

    // $($(this).parents(".category-item").hasClass()).addClass("jobs-swiper-slide-active").siblings().removeClass('jobs-swiper-slide-active');
    $('.js-swiper-slide').removeClass('jobs-swiper-slide-active');
    $(e.currentTarget).addClass('jobs-swiper-slide-active');
  });

  $('.swiper-slide').eq($(this).index()).addClass('jobs-swiper-slide-active');
  // $('.swiper-slide').eq($(this).index()).addClass('jobs-swiper-slide-active');  $(window).resize(function () { swiper.update(); });
  
  window.sp = swiper;
  /* end */

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
  // $(document).ready(function () {
  //   // 设置jobs页面初始值为下拉菜单中第一个内容
  //   var txt = $('.job-dropdown ul li').eq(0).text();
  //   $('.defaultName').text(txt);
  // });

  $('.job-dropdown').click(function () {
    var wrapper = $(this);
    var isUnfold = wrapper.hasClass('unfold');
    var self = this;

    $(this).children('ul').slideToggle('fast', function () {
      var img = $(self).children('span').children('img');

      if (isUnfold) {
        wrapper.removeClass('unfold');
        img.attr('src', '/static/imgs/job-arrow-up.svg');
      } else {
        wrapper.addClass('unfold');
        img.attr('src', '/static/imgs/job-arrow-down.svg');
      }
    });
  });

  $('.category-wrapper:not(.selected)').click(function () {
    var txt = $(this).text();
    $('.defaultName').text(txt);
  });

  // $('.category').click(function () {
  //   var wrapper = $(this).parent();
  //   var isUnfold = wrapper.hasClass('unfold');
  //   var self = this;

  //   $(this).parent().children('ul').slideToggle('fast', function () {
  //     var img = $(self).children('span').children('img');
  //     if (isUnfold) {
  //       wrapper.removeClass('unfold');
  //       img.attr('src', '/static/imgs/job-arrow-down.svg');
  //     } else {
  //       wrapper.addClass('unfold');
  //       img.attr('src', '/static/imgs/job-arrow-up.svg');
  //     }
  //   });
  // });

  $('.category-wrapper li:not(.selected)').hover(function () {
    $(this).addClass('selected');
    activeJobItem.removeClass('selected');
  }, function () {
    $(this).removeClass('selected');
    activeJobItem.addClass('selected')
  });

  $('.footer-back').click(function () {
    $('body,html').animate({ scrollTop: 0 }, 600);
  });
  var hrefAtrributeSelector = 'a[href="' + window.location.pathname + '"]';
  var activeJobItem = $('.job-item-link ' + hrefAtrributeSelector).parent();
  $('.swiper-slide ' + hrefAtrributeSelector).parent().addClass('jobs-swiper-slide-active');
  $('.swiper-slide ' + hrefAtrributeSelector).parent().siblings().removeClass('jobs-swiper-slide-active');
  activeJobItem.addClass('jobs-swiper-slide-active');
  // activeJobItem.parent().css({ display: 'block' });
  activeJobItem.parents('.category-item').addClass('submenu-open');
  activeJobItem.addClass('current-submenu');
  $(".category").on("click", function (event) {
    event.preventDefault();
    var category_item = $(this).parents(".category-item");
    var open = category_item.hasClass('submenu-open');
    var url = $(this).attr('href');
    // console.log(window.location.pathname, url, category_item[0],  category_item.find('a[href="' + window.location.pathname + '"]')[0])
    if (open) {
      category_item.removeClass('submenu-open');
    } else {
      category_item.addClass('submenu-open');
    }
  })
});
