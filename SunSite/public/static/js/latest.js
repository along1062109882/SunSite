var $oView = $(".photo-viewpager"),
    $oBox = $(".photo-wrapper"),
    $oUl = $(".photo-wrapper ul"),
    $aImg = $(".photo-wrapper img"),
    $oLis = $(".photo-wrapper li");
 var imgWidth = $(window).width();
 imgWidth = imgWidth > 660 ? 660 : imgWidth;
//  Math.ceil(2550 / ( 1278 / ($('html').width() < 640 ? 200 : 400) ))
var $cUl = $(".viewpager ul"),
    $cImg = $(".viewpager img"),
    $cNails = $(".viewpager li");

$oUl.css({ width: imgWidth * $aImg.length + "px"});
$cUl.css({ width: 90 * $cImg.length + "px"});

$(window).on('resize', function(){ 
  imgWidth =  $(window).width();
  imgWidth = imgWidth > 660 ? 660 : imgWidth;
  $oUl.css({ width: imgWidth * $aImg.length + "px"});
})

function reSize(){
  for(var i=0;i < $aImg.length;i++) {
    $oLis.eq(i).css({ width: $oView.width(), overflow: 'hidden', 'text-align': 'center' });
  }
  $oUl.css({ left: -index * $oView.width() });
}

reSize();

$(window).resize(function() {
  reSize();
});

var index = 0;
var endIndex = 0;

for(var i = 0;i < $cNails.length;i++) {
  clearInterval();
  var updater = function() {
    var _i = $(this).index();

    if (_i !== index) {
      $(this).addClass('selected').siblings().removeClass('selected');
      $cUl.animate({ left: -$(this).index() * 90 });
      $oUl.css({ left: -$(this).index() * $oView.width() });
      index = endIndex = _i;
    }
  }
  $cNails.eq(i).click(updater);
}

function autoPlay() {
  setInterval(play, 5000);
}
if ($cNails.length > 6) {
  autoPlay()
} else {
  clearInterval(setInterval(play, 5000));
}

function play(step) {
  var imgLen = $aImg.length;
  var imgWidth = $oView.width();

  if (index === imgLen - 1) {
    index = 0;
    $oLis.eq(0).css({ position: "relative" });
    $oLis.eq(0).css({ left: imgLen * imgWidth });
    $cNails.eq(0).css({ position: "relative" });
    $cNails.eq(0).css({ left: imgLen * 90 });
  } else {
    index++;
  }
  endIndex++;
  $cNails.eq(index).addClass("selected").siblings().removeClass("selected");
  $cUl.animate({ left: -endIndex * 90 }, function() {
    if (index === 0) {
      endIndex = 0;
      $cNails.eq(0).css({ position: "static" });
      $cUl.css({ left: 0 });
    }
  });
  $oUl.animate({ left: -index * imgWidth }, function() {
    if (index === 0) {
      endIndex = 0;
      $oLis.eq(0).css({ position: 'static' });
      $oUl.css({ left: 0 });
    }
  });
}

$('.prev-btn').click(function() {
  var imgLen = $aImg.length;
  var imgWidth = $oView.width();

  if (index !== 0) {
    index--;
    endIndex--;
    $cNails.eq(index).addClass("selected").siblings().removeClass("selected");
    $cUl.animate({ left: -endIndex * 90 });
    $oUl.animate({ left: -index * imgWidth });
  }
});

$('.next-btn').click(play);

  // 获取浏览器高度并且给弹出框设置高度
  // $('.b-modal.__b-popup1__').click(function () {
  //    $(this).css({ 'height': document.body.clientHeight+ "px", 'overflow': 'hidden'});
  //    $("body").css({"overflow":"hidden","height":"100%"});
  // })

$(function() {
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

  $('.photo-wrapper li').click(function() {
    var img = $('.element_to_pop_up > img').attr('src', $(this).find('img')[0].src);
    $('.element_to_pop_up').append(img);
    $(img).on('load', function() {
      $('.element_to_pop_up').bPopup({
        onOpen: function() { $("body").css({"overflow":"hidden","height":"100%"}); }, 
        onClose: function() { $("body").css({"overflow":"unset","height":"100%"}); }
      });
      // $("body").css({"overflow":"hidden","height":"100%"});
    });
  });

  $('.btn-close').click(function() {
    $('.b-modal.__b-popup1__').click();
    // $("body").css({"overflow":"unset","height":"100%"});
  });

  $('.footer-back').click(function() {
    $('body,html').animate({ scrollTop: 0 }, 600);
  });
  
});

////////

function popupwindow(url, title, w, h) {
    wLeft = window.screenLeft ? window.screenLeft : window.screenX;
    wTop = window.screenTop ? window.screenTop : window.screenY;
    var left = wLeft + (window.innerWidth / 2) - (w / 2);
    var top = wTop + (window.innerHeight / 2) - (h / 2);
    return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
}

window.onload = function () {
  var pic = document.getElementsByClassName('photo-wrapper')[0].querySelector('img');
  document.getElementById('share_fb').onclick = function(){
    var shareUrl = "http://www.facebook.com/sharer/sharer.php?u=" + encodeURI(window.location.href) + "&t=" + encodeURI(document.title);
    popupwindow(shareUrl, 'Facebook', 600, 400);
  }
  document.getElementById('share_wb').onclick = function(){
    var shareUrl = "http://v.t.sina.com.cn/share/share.php?url=" + encodeURI(window.location.href) + "&title=" + encodeURI(document.title) + "&pic=" + encodeURI(pic.src);
    popupwindow(shareUrl, 'SinaWeibo', 600, 400);
  }
  document.getElementById('share_wx').onclick = function(){
    if (getUA().mobile) {
      // mobile platform
    }
    else {
      jQuery('#share_wx_qr').qrcode({text: encodeURI(window.location.href), width: 100, height: 100});
    }
  }
}

  // 判断是否能正确加载图片
  if ($('.photo-wrapper ul').length < 1) {
      $('.thumbnails-viewpager').hide()
  } else {
    $('.thumbnails-viewpager').show();
  }
  // 判断图片加载是否正确
  $('.viewpager ul li img').on('error', function () {
    $(this).attr("src", "/static/imgs/group.svg");
  })

  $('.event-list-box li .event-list-img-box img').on('error', function () {
    $(this).attr("src", "/static/imgs/group.svg");
  })
  $('.photo-wrapper ul li .ratio-16-9 img').on('error', function () {
     $(this).attr("src", "/static/imgs/group.svg");
  })