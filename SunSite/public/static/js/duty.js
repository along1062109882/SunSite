new WOW().init();

// [
//   [768, 2],
//   [1080, 3]
// ].forEach(function(config) {
//   const size = config[0];
//   const groupSize = config[1];

//   const $template = $('<div class="glide glide-' + size + ' js-glide">' +
//     '<div class="glide__wrapper">' +
//       '<ul class="glide__track js-glide-template-point"></ul>' +
//     '</div>' +
//     '<div class="glide__bullets"></div>' +
//     '</div>');

//   const $items = $('[data-glide] [data-glide-item]').clone(true);
//   const groups = [];
//   var length = $items.length;
//   for (var i = 0; i < length; i += groupSize) {
//     var items = [];
//     for (var j = i; j < length && j < i + groupSize; j++) {
//       items.push($items[j]);
//     }
//     groups.push(items);
//   }

//   const $groups = groups.map(function (g) {
//     return $('<li class="glide__slide">').append(g);
//   });

//   $template.find('.js-glide-template-point').append($groups);
//   $template.insertAfter($('.js-activity-slide-container span#insertion-point'));
// });

// $(".js-glide").glide({
//   type: "slider"
// });

// $(function() {
//   var mainPageBtn = $(".navigator :nth-child(4)");

//   mainPageBtn.addClass("selected");

//   $('.navigator :gt(1)')
//     .hover(function() {
//       $(this).addClass("selected");
//       mainPageBtn.removeClass("selected");
//     }, function() {
//       $(this).removeClass("selected");
//       mainPageBtn.addClass("selected");
//     });

//   $('.main-menu-btn').click(function() {
//     var $navigator = $('.navigator');
//     if ($navigator.css('display') === 'block') {
//       $navigator.css('display', 'none');
//     } else {
//       $navigator.css('display', 'block');
//     }
//   });

//   if ($('html').width() < 1080) {
//     $('.navigator li').click(function() {
//       $(this).children('a')[0].click();
//     });
//   }

//   $('.footer-back').click(function() {
//     $('body,html').animate({ scrollTop: 0 }, 600);
//   });

//   $('body').on('click', '.js-activity', function(e) {
//     window.location.href = $(e.currentTarget).data('href');
//   });
// });

$(".childCompany-slide").owlCarousel({
  rewind:true,
  // center: true,
  autoplay: true,
  // responsiveClass: true,
  dots: false,
  nav: true,
  loop: true,
  responsive: {
    880: {
      items: $(".childCompany-slide .slide-item").length>3 ? 3 : $(".childCompany-slide .slide-item").length,
      slideBy: 3,//每次滑动个数，不需要可删除
      // 原版轮播根据分辨率调整为每个轮播元素li内有不同数量的内容，另外元素为两层数组遍历，判断可能数据较多需要整页滚动，所以添加每次滑动个数
    },
    0: {
      items: 1,
      slideBy: 1,
    }
  }
});
// 图片加载错误显示预设图
$('.slide-item .ratio-16-9 img').on('error', function () {
  $(this).attr("src", "/static/imgs/group.svg");
})