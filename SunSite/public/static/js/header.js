var headerScreenW, headerScreenH, headerISWideScreen;

var headerNavOpen;
var headerNavTitleAboutusOpen, headerNavTitleChildcompanyOpen, headerNavTitleChildnewsOpen;

var headerNavTitleAboutus = $('#header-nav-title-aboutus');
var headerNavTitleChildcompany = $('.header-nav-title-childcompany');
var headerNavTitleChildnews = $('.header-nav-title-news');
var group_item_news = $('#header-nav-item-group-news');
var group_item_childcompany = $('#header-nav-item-group-childcompany');
var headerISTouch = window.hasOwnProperty('ontouchstart');
var lastHeaderScreenW = $(window).width();

var headerDragEvent = headerISTouch ? {
  down: 'touchstart',
  move: 'touchmove',
  up: 'touchend',
  over: 'touchstart',
  out: 'touchend'
} : {
  down: 'mousedown',
  move: 'mousemove',
  up: 'mouseup',
  over: 'mouseover',
  out: 'mouseout'
}

var headerDragEvents = {
  down: ['touchstart', 'mousedown'],
  move: ['touchmove', 'mousemove'],
  up: ['touchend', 'mouseup'],
  over: ['touchstart', 'mouseover'],
  out: ['touchend', 'mouseout'],
}

$(document).ready(function () {
  $(window).resize(headerUpdateSize);
})

headerUpdateSize();
headerHandleButton();
headerHandleNav();

setToggleLanguageHref();

// start 为了兼容chrome下调试工具切换手机切电脑，电脑切手机
function toggleDeviceBindEvent($object, eventCode, fun) {
  headerDragEvents[eventCode].forEach((eventName, index) => {
    $object.on(eventName, function() {
      // 手机事件
      if (index == 0) {
        if (!headerISTouch) return;
      // 电脑事件
      } else {
        if (headerISTouch) return;
      }
      fun.apply(this, arguments)
    })
  });
}

function headerUpdateSize(e) {
  headerScreenW = $(window).width();
  var isToggleDevice = false;

  // start 为了兼容chrome下调试工具切换手机切电脑，电脑切手机 
  if (headerScreenW - lastHeaderScreenW >= 200) {
    isToggleDevice = true;
    headerISTouch = false;
  } else if (headerScreenW - lastHeaderScreenW <= -200) {
    isToggleDevice = true;
    headerISTouch = true;
  }
  lastHeaderScreenW = headerScreenW
  
  headerScreenH = $(window).height();
  if (headerScreenW > 1439) headerISWideScreen = true;
  else headerISWideScreen = false;

  if (isToggleDevice) {
    $('.header-nav-title-news').removeAttr('style');
    $('.header-nav-title-news a').removeAttr('style');
    $('.header-nav-title-childcompany').removeAttr('style');
    $('.header-nav-title-childcompany a').removeAttr('style');

    if (headerISWideScreen) { 
      headerISWideScreen = true;
      group_item_childcompany.hide();
      group_item_news.hide();
    }
  }
  
}
function headerHandleButton() {
  toggleDeviceBindEvent($('.header-nav-button img'), 'down', function () {
    if (headerISWideScreen) {
      return;
    }
    if (headerNavOpen) {
      headerNavOpen = false;
      $('.header-nav-group').css('display', 'none');
      $(this).attr("src", "/static/imgs/news-toggle-grid.svg");
    }
    else {
      headerNavOpen = true;
      $('.header-nav-group').css('display', 'block');
      $(this).attr("src", "/static/imgs/menu-icon-copy.svg");
    }
  });
}

function headerHandleNav() {
  toggleDeviceBindEvent(headerNavTitleAboutus, 'over', function () {

    if (headerISWideScreen) {
      $('#header-nav-item-group-childcompany').css({ 'display': 'none' });
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#a19062', 'padding-bottom': '4px', 'box-shadow': '0px 4px 0px #a19062' });
      $('#header-nav-item-group-aboutus').css({ 'display': 'block' });
    }
    else {
      $(this).css({ 'background-color': '#a19062' });
      $(this).find('a').css({ 'color': '#fff', 'background-color': 'rgba(255, 255, 255, 0)' });
      if (headerNavTitleAboutusOpen) $(this).find('img').attr('src', '/static/imgs/nav-arrow-up-white.svg');
      else $(this).find('img').attr('src', '/static/imgs/nav-arrow-down-white.svg');
    }
  });

  toggleDeviceBindEvent(headerNavTitleAboutus, 'out', function (e) {
    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
      // detect out direction
      var w = $(this).width();
      var h = $(this).height();
      var x = (e.originalEvent.pageX - $(this).offset().left - (w / 2)) * (w > h ? (h / w) : 1);
      var y = (e.originalEvent.pageY - $(this).offset().top - (h / 2)) * (h > w ? (w / h) : 1);
      // direction: 0123 for up.right.down.left
      var direction = Math.round((((Math.atan2(y, x) * (180 / Math.PI)) + 180) / 90) + 3) % 4;
      if (direction != 2) {
        $('#header-nav-item-group-aboutus').css({ 'display': 'none' });
      }
    }
    else {
      $(this).css({ 'background-color': '#fff' });
      $(this).find('a').css({ 'color': '#49443d' });
      if (headerNavTitleAboutusOpen) $(this).find('img').attr('src', '/static/imgs/nav-arrow-up.svg');
      else $(this).find('img').attr('src', '/static/imgs/nav-arrow-down.svg');
    }
  });

  toggleDeviceBindEvent(headerNavTitleAboutus, 'down', function () {
    if (headerISWideScreen) {
      return;
    }
    if (headerNavTitleAboutusOpen) {
      headerNavTitleAboutusOpen = false;
      $(this).find('img').attr('src', '/static/imgs/nav-arrow-down.svg');
      $('#header-nav-item-group-aboutus').css('display', 'none');
    }
    else {
      headerNavTitleAboutusOpen = true;
      $(this).find('img').attr('src', '/static/imgs/nav-arrow-up-white.svg');
      $('#header-nav-item-group-aboutus').css('display', 'block');
    }
    if (headerNavTitleAboutusOpen) $(this).find('img').attr('src', '/static/imgs/nav-arrow-up-white.svg');
    else $(this).find('img').attr('src', '/static/imgs/nav-arrow-down-white.svg');
  });

  // ---------------------------

  //
  // headerNavTitleChildcompany.on(headerDragEvent.over, function () {
  //   if (headerISWideScreen) {
  //     $('#header-nav-item-group-aboutus').css({'display': 'none'});
  //     $(this).find('a').css({'background-color': '#fff', 'color': '#a19062', 'padding-bottom': '4px', 'box-shadow': '0px 4px 0px #a19062'});
  //     $('#header-nav-item-group-childcompany').css({'display': 'block'});
  //   }
  //   else {
  //     $(this).css({'background-color': '#a19062'});
  //     $(this).find('a').css({'color': '#fff', 'background-color': 'rgba(255, 255, 255, 0)'});
  //     if (headerNavTitleChildcompanyOpen) $(this).find('img').attr('src', '/static/imgs/nav-arrow-up-white.svg');
  //     else $(this).find('img').attr('src', '/static/imgs/nav-arrow-down-white.svg');
  //   }
  // });

  // headerNavTitleChildcompany.on(headerDragEvent.out, function (e) {
  //   if (headerISWideScreen) {
  //     $(this).find('a').css({'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px'});
  //     // detect out direction
  //     var w = $(this).width();
  //     var h = $(this).height();
  //     var x = (e.originalEvent.pageX - $(this).offset().left - (w / 2)) * (w > h ? (h / w) : 1);
  //     var y = (e.originalEvent.pageY - $(this).offset().top - (h / 2)) * (h > w ? (w / h) : 1);
  //     // direction: 0123 for up.right.down.left
  //     var direction = Math.round((((Math.atan2(y, x) * (180 / Math.PI)) + 180) / 90) + 3) % 4;
  //     if (direction != 2) {
  //       $('#header-nav-item-group-childcompany').css({'display': 'none'});
  //     }
  //   }
  //   else {
  //     $(this).css({'background-color': '#fff'});
  //     $(this).find('a').css({'color': '#49443d'});
  //     if (headerNavTitleChildcompanyOpen) $(this).find('img').attr('src', '/static/imgs/nav-arrow-up.svg');
  //     else $(this).find('img').attr('src', '/static/imgs/nav-arrow-down.svg');
  //   }
  // });

  // headerNavTitleChildcompany.on(headerDragEvent.down, function () {
  //   if (headerISWideScreen) {
  //     return;
  //   }
  //   if (headerNavTitleChildcompanyOpen) {
  //     headerNavTitleChildcompanyOpen = false;
  //     $(this).find('img').attr('src', '/static/imgs/nav-arrow-down.svg');
  //     $('#header-nav-item-group-childcompany').css('display', 'none');
  //   }
  //   else {
  //     headerNavTitleChildcompanyOpen = true;
  //     $(this).find('img').attr('src', '/static/imgs/nav-arrow-up-white.svg');
  //     $('#header-nav-item-group-childcompany').css('display', 'block');
  //   }
  //   if (headerNavTitleChildcompanyOpen) $(this).find('img').attr('src', '/static/imgs/nav-arrow-up-white.svg');
  //   else $(this).find('img').attr('src', '/static/imgs/nav-arrow-down-white.svg');
  // });

  // start 为了兼容chrome下调试工具切换手机切电脑，电脑切手机
  headerNavTitleChildcompany.on("click", function (event) {

    if (!headerISTouch) return;

    event.stopPropagation();  
    if (headerISWideScreen) {
      // $('#header-nav-item-group-aboutus').css({ 'display': 'none' });
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#a19062', 'padding-bottom': '4px', 'box-shadow': '0px 4px 0px #a19062' });
      headerNavTitleChildnews.find('a').css({'background-color': '#a19062',color:'#fff' })
      // $('#header-nav-item-group-childcompany').css({ 'display': 'block' });
      // $('#header-nav-item-group-news').css({ 'display': 'none' });
    }
    else {
      $(this).css({ 'background-color': '#a19062'})
      $(this).find('a').css({'color': '#fff'})
      headerNavTitleChildnews.css({'background-color': '#fff',color:'#a19062' })
      headerNavTitleChildnews.find('a').css({'color': '#333'})

      /*
      if (headerNavTitleChildcompanyOpen) $(this).find('img').attr('src', '/static/imgs/nav-arrow-up-white.svg');
      else $(this).find('img').attr('src', '/static/imgs/nav-arrow-down-white.svg');
      */

      if (headerNavTitleChildcompanyOpen) {
        headerNavTitleChildcompanyOpen = false;
        $(this).find('img').attr('src', '/static/imgs/nav-arrow-down.svg');
        $('#header-nav-item-group-childcompany').css('display', 'none');
      }
      else {
        headerNavTitleChildnewsOpen = false;
        headerNavTitleChildcompanyOpen = true;
        $(this).find('img').attr('src', '/static/imgs/nav-arrow-up-white.svg');
        $('#header-nav-item-group-childcompany').css('display', 'block');
        $(headerNavTitleChildnews).find('img').attr('src', '/static/imgs/nav-arrow-down.svg');
        group_item_news.css('display', 'none');
        // group_item_childcompany.css({'display':'none'})
      }
    }
  });

  $('#header-nav-item-group-childcompany').on("click", function (e) {
    if (!headerISTouch) return;

    e.stopPropagation();
  })

  $(document.body).on("click", function (e) {
    if (!headerISTouch) return;
    
    if (headerISWideScreen) {
      // $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
      $('#header-nav-item-group-childcompany').css({ 'display': 'none' });
    }
  });
  headerNavTitleChildcompany.on("mouseover", function () {
    if (headerISTouch) return;

    if (headerISWideScreen) {
      $('#header-nav-item-group-aboutus').css({ 'display': 'none' });
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#a19062', 'padding-bottom': '4px', 'box-shadow': '0px 4px 0px #a19062' });
      $('#header-nav-item-group-childcompany').css({ 'display': 'block' });
    }
    else {
      $(this).css({ 'background-color': '#a19062' });
      $(this).find('a').css({ 'color': '#fff', 'background-color': 'rgba(255, 255, 255, 0)' });
      if (headerNavTitleChildcompanyOpen) $(this).find('img').attr('src', '/static/imgs/nav-arrow-up-white.svg');
      else $(this).find('img').attr('src', '/static/imgs/nav-arrow-down-white.svg');
    }
  });

  headerNavTitleChildcompany.on("mouseout", function (e) {
    if (headerISTouch) return;

    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
      // detect out direction
      var w = $(this).width() + 20;
      var h = $(this).height();
      var x = (e.originalEvent.pageX - $(this).offset().left - (w / 2)) * (w > h ? (h / w) : 1);
      var y = (e.originalEvent.pageY - $(this).offset().top - (h / 2)) * (h > w ? (w / h) : 1);
      // direction: 0123 for up.right.down.left
      var direction = Math.round((((Math.atan2(y, x) * (180 / Math.PI)) + 180) / 90) + 3) % 4;
      if (direction != 2) {
        $('#header-nav-item-group-childcompany').css({ 'display': 'none' });
      }
    }
    else {
      $(this).css({ 'background-color': '#fff' });
      $(this).find('a').css({ 'color': '#49443d' });
      if (headerNavTitleChildcompanyOpen) $(this).find('img').attr('src', '/static/imgs/nav-arrow-up.svg');
      else $(this).find('img').attr('src', '/static/imgs/nav-arrow-down.svg');
    }
  });

  headerNavTitleChildcompany.on("mousedown", function () {
    if (headerISTouch) return;

    if (headerISWideScreen) {
      return;
    }
    if (headerNavTitleChildcompanyOpen) {
      headerNavTitleChildcompanyOpen = false;
      $(this).find('img').attr('src', '/static/imgs/nav-arrow-down.svg');
      $('#header-nav-item-group-childcompany').css('display', 'none');
    }
    else {
      headerNavTitleChildcompanyOpen = true;
      $(this).find('img').attr('src', '/static/imgs/nav-arrow-up-white.svg');
      $('#header-nav-item-group-childcompany').css('display', 'block');
    }

    if (headerNavTitleChildcompanyOpen) $(this).find('img').attr('src', '/static/imgs/nav-arrow-up-white.svg');
    else $(this).find('img').attr('src', '/static/imgs/nav-arrow-down-white.svg');
  });

  // 新闻列表
  headerNavTitleChildnews.on("click", function (event) {
    if (!headerISTouch) return;

    event.stopPropagation();
    // debugger
    if (headerISWideScreen) {
      // $('#header-nav-item-group-aboutus').css({ 'display': 'none' });
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#a19062', 'padding-bottom': '4px', 'box-shadow': '0px 4px 0px #a19062' });
      group_item_news.css({ 'display': 'block' });
    }
    else {
      $(this).css({ 'background-color': '#a19062'})
      $(this).find('a').css({'color': '#fff'})
      headerNavTitleChildcompany.css({'background-color': '#fff',color:'#a19062' })
      headerNavTitleChildcompany.find('a').css({'color': '#333'})
      if (headerNavTitleChildnewsOpen) $(this).find('img').attr('src', '/static/imgs/nav-arrow-up-white.svg');
      else $(this).find('img').attr('src', '/static/imgs/nav-arrow-down-white.svg');
      if (headerNavTitleChildnewsOpen) {
        headerNavTitleChildnewsOpen = false;
        $(this).find('img').attr('src', '/static/imgs/nav-arrow-down.svg');
        group_item_news.css('display', 'none');
      }
      else {
        headerNavTitleChildnewsOpen = true;
        headerNavTitleChildcompanyOpen = false;
        $(this).find('img').attr('src', '/static/imgs/nav-arrow-up-white.svg');
        $(headerNavTitleChildcompany).find('img').attr('src', '/static/imgs/nav-arrow-down.svg');
        group_item_news.css('display', 'block');
        group_item_childcompany.css({'display':'none'})
      }
    }
  });
  group_item_news.on("click", function (e) {
    if (!headerISTouch) return;
    e.stopPropagation();
  })
  $(document.body).on("click", function (e) {
    if (!headerISTouch) return;

    if (headerISWideScreen) {
      // $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
      group_item_news.css({ 'display': 'none' });
    }
  });

  headerNavTitleChildnews.on("mouseover", function () {
    if (headerISTouch) return;

    if (headerISWideScreen) {
      $('#header-nav-item-group-aboutus').css({ 'display': 'none' });
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#a19062', 'padding-bottom': '4px', 'box-shadow': '0px 4px 0px #a19062' });
      group_item_news.css({ 'display': 'block' });
    }
    else {
      $(this).css({ 'background-color': '#a19062' });
      $(this).find('a').css({ 'color': '#fff', 'background-color': 'rgba(255, 255, 255, 0)' });
      if (headerNavTitleChildnewsOpen) $(this).find('img').attr('src', '/static/imgs/nav-arrow-up-white.svg');
      else $(this).find('img').attr('src', '/static/imgs/nav-arrow-down-white.svg');
    }
  });

  headerNavTitleChildnews.on("mouseout", function (e) {
    if (headerISTouch) return;

    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
      // detect out direction
      var w = $(this).width() + 20;
      var h = $(this).height();
      var x = (e.originalEvent.pageX - $(this).offset().left - (w / 2)) * (w > h ? (h / w) : 1);
      var y = (e.originalEvent.pageY - $(this).offset().top - (h / 2)) * (h > w ? (w / h) : 1);
      // direction: 0123 for up.right.down.left
      var direction = Math.round((((Math.atan2(y, x) * (180 / Math.PI)) + 180) / 90) + 3) % 4;
      if (direction != 2) {
        group_item_news.css({ 'display': 'none' });
      }
    }
    else {
      $(this).css({ 'background-color': '#fff' });
      $(this).find('a').css({ 'color': '#49443d' });
      if (headerNavTitleChildnewsOpen) $(this).find('img').attr('src', '/static/imgs/nav-arrow-up.svg');
      else $(this).find('img').attr('src', '/static/imgs/nav-arrow-down.svg');
    }
  });

  headerNavTitleChildnews.on("mousedown", function () {
    if (headerISTouch) return;

    if (headerISWideScreen) {
      return;
    }
    if (headerNavTitleChildnewsOpen) {
      headerNavTitleChildnewsOpen = false;
      $(this).find('img').attr('src', '/static/imgs/nav-arrow-down.svg');
      group_item_news.css('display', 'none');
    }
    else {
      headerNavTitleChildnewsOpen = true;
      $(this).find('img').attr('src', '/static/imgs/nav-arrow-up-white.svg');
      group_item_news.css('display', 'block');
    }
    if (headerNavTitleChildnewsOpen) $(this).find('img').attr('src', '/static/imgs/nav-arrow-up-white.svg');
    else $(this).find('img').attr('src', '/static/imgs/nav-arrow-down-white.svg');
  });

  // ---------------------------

  //

  // $('#header-nav-title-news').on(headerDragEvent.over, function (e) {
  //   if (headerISWideScreen) {
  //     $(this).find('a').css({ 'background-color': '#fff', 'color': '#a19062', 'padding-bottom': '4px', 'box-shadow': '0px 4px 0px #a19062' });
  //   }
  //   else {
  //     $(this).css({ 'background-color': '#a19062' });
  //     $(this).find('a').css({ 'color': '#fff', 'background-color': 'rgba(255, 255, 255, 0)' });
  //   }
  // });

  toggleDeviceBindEvent($('#header-nav-title-news'), 'out', function (e) {
    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
    }
    else {
      $(this).css({ 'background-color': '#fff' });
      $(this).find('a').css({ 'color': '#49443d' });
    }
  });

  toggleDeviceBindEvent($('#header-nav-title-duty'), 'over', function (e) {
    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#a19062', 'padding-bottom': '4px', 'box-shadow': '0px 4px 0px #a19062' });
    }
    else {
      $(this).css({ 'background-color': '#a19062' });
      $(this).find('a').css({ 'color': '#fff', 'background-color': 'rgba(255, 255, 255, 0)' });
    }
  });
  
  toggleDeviceBindEvent($('#header-nav-title-duty'), 'out', function (e) {
    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
    }
    else {
      $(this).css({ 'background-color': '#fff' });
      $(this).find('a').css({ 'color': '#49443d' });
    }
  });

  toggleDeviceBindEvent($('#header-nav-title-event'), 'over', function (e) {
    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#a19062', 'padding-bottom': '4px', 'box-shadow': '0px 4px 0px #a19062' });
    }
    else {
      $(this).css({ 'background-color': '#a19062' });
      $(this).find('a').css({ 'color': '#fff', 'background-color': 'rgba(255, 255, 255, 0)' });
    }
  });

  toggleDeviceBindEvent($('#header-nav-title-event'), 'out', function (e) {
    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
    }
    else {
      $(this).css({ 'background-color': '#fff' });
      $(this).find('a').css({ 'color': '#49443d' });
    }
  });

  toggleDeviceBindEvent($('#header-nav-title-jobs'), 'over', function (e) {
    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#a19062', 'padding-bottom': '4px', 'box-shadow': '0px 4px 0px #a19062' });
    }
    else {
      $(this).css({ 'background-color': '#a19062' });
      $(this).find('a').css({ 'color': '#fff', 'background-color': 'rgba(255, 255, 255, 0)' });
    }
  });

  toggleDeviceBindEvent($('#header-nav-title-jobs'), 'out', function (e) {
    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
    }
    else {
      $(this).css({ 'background-color': '#fff' });
      $(this).find('a').css({ 'color': '#49443d' });
    }
  });
  toggleDeviceBindEvent($('#header-nav-title-grand'), 'over', function (e) {
    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#a19062', 'padding-bottom': '4px', 'box-shadow': '0px 4px 0px #a19062' });
    }
    else {
      $(this).css({ 'background-color': '#a19062' });
      $(this).find('a').css({ 'color': '#fff', 'background-color': 'rgba(255, 255, 255, 0)' });
    }
  });

  toggleDeviceBindEvent($('#header-nav-title-grand'), 'out', function (e) {
    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
    }
    else {
      $(this).css({ 'background-color': '#fff' });
      $(this).find('a').css({ 'color': '#49443d' });
    }
  });


  toggleDeviceBindEvent($('#header-nav-title-duty'), 'out', function (e) {
    if (headerISWideScreen) {
        $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
    }
    else {
        $(this).css({ 'background-color': '#fff' });
        $(this).find('a').css({ 'color': '#49443d' });
    }
});

// ---------------------------

  //

  toggleDeviceBindEvent($('.header-nav-item-group'), 'out', function (e) {
    if (headerISWideScreen) {
      var event = window.event || e;
      var obj = event.toElement || event.relatedTarget;
      var pa = this;
      if (pa.contains(obj)) return false;
      $(this).css('display', 'none');
    }
  })

  // ---------------------------

  //
  toggleDeviceBindEvent($('.header-nav-item-group li'), 'over', function () {
    $(this).css({ 'background-color': '#a19062' });
    $(this).find('a').css({ 'color': '#fff' });
  });

  toggleDeviceBindEvent($('.header-nav-item-group li'), 'out', function () {
    $(this).css({ 'background-color': '#fff' });
    $(this).find('a').css({ 'color': '#49443d' });
  });


  $('.header-nav-item-group li').on('click', function () {
    location.href = $(this).find('a').attr('href');
  });
}

function clickToggleLanguage(lang) {
  switch (lang) {
    case 0:
      location.href = location.pathname.replace('/zh-hans', '/zh-hant');
      break;
    case 1:
      location.href = location.pathname.replace('/zh-hant', '/zh-hans');
      break;
    case 2:
      break;
    default:
  }
}

function setToggleLanguageHref() {
  var btnToggleLanguageList = $('.header-toggle-language');
  btnToggleLanguageList.each((index, item) => {
    var btnToggleLanguage = $(item)
    var baseUrl = btnToggleLanguage.data("baseurl");
    if (baseUrl === '/zh-hans') {
      btnToggleLanguage.attr('href', location.pathname.replace('/zh-hant', '/zh-hans'));
    }
    else if (baseUrl === '/zh-hant') {
      btnToggleLanguage.attr('href', location.pathname.replace('/zh-hans', '/zh-hant'));
    }
  })

}