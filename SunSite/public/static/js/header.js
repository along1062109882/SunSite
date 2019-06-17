var headerScreenW, headerScreenH, headerISWideScreen;

var headerNavOpen;
var headerNavTitleAboutusOpen, headerNavTitleChildcompanyOpen;

var headerNavTitleAboutus = $('#header-nav-title-aboutus');
var headerNavTitleChildcompany = $('.header-nav-title-childcompany');
var headerNavTitleChildnews = $('.header-nav-title-news');
var group_item_news = $('#header-nav-item-group-news');
var group_item_childcompany = $('#header-nav-item-group-childcompany');
var headerISTouch = window.hasOwnProperty('ontouchstart');
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

$(document).ready(function () {
  $(window).resize(headerUpdateSize);
})

headerUpdateSize();
headerHandleButton();
headerHandleNav();

setToggleLanguageHref();

function headerUpdateSize() {
  headerScreenW = $(window).width();
  headerScreenH = $(window).height();
  if (headerScreenW > 1424) headerISWideScreen = true;
  else headerISWideScreen = false;
}
function headerHandleButton() {
  $('.header-nav-button img').on(headerDragEvent.down, function () {
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

  //
  headerNavTitleAboutus.on(headerDragEvent.over, function () {

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

  headerNavTitleAboutus.on(headerDragEvent.out, function (e) {
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

  headerNavTitleAboutus.on(headerDragEvent.down, function () {
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
  console.log($('html').width())
  if (headerISTouch) {
    headerNavTitleChildcompany.on("click", function (event) {
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
        if (headerNavTitleChildcompanyOpen) $(this).find('img').attr('src', '/static/imgs/nav-arrow-up-white.svg');
        else $(this).find('img').attr('src', '/static/imgs/nav-arrow-down-white.svg');

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

      }

    });
    $('#header-nav-item-group-childcompany').on("click", function (e) {
      e.stopPropagation();
    })
    $(document.body).on("click", function (e) {
      if (headerISWideScreen) {
        // $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
        $('#header-nav-item-group-childcompany').css({ 'display': 'none' });
      }
    });
  } else {
    headerNavTitleChildcompany.on("mouseover", function () {
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
  }
  // 新闻列表
  if (headerISTouch) {
    headerNavTitleChildnews.on("click", function (event) {
      event.stopPropagation();
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
        if (headerNavTitleChildcompanyOpen) $(this).find('img').attr('src', '/static/imgs/nav-arrow-up-white.svg');
        else $(this).find('img').attr('src', '/static/imgs/nav-arrow-down-white.svg');

        if (headerNavTitleChildcompanyOpen) {
          headerNavTitleChildcompanyOpen = false;
          $(this).find('img').attr('src', '/static/imgs/nav-arrow-down.svg');
          group_item_news.css('display', 'none');
        }
        else {
          headerNavTitleChildcompanyOpen = true;
          $(this).find('img').attr('src', '/static/imgs/nav-arrow-up-white.svg');
          group_item_news.css('display', 'block');
        }
      }
    });
    group_item_news.on("click", function (e) {
      e.stopPropagation();
    })
    $(document.body).on("click", function (e) {
      if (headerISWideScreen) {
        // $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
        group_item_news.css({ 'display': 'none' });
      }
    });
  } else {
    headerNavTitleChildnews.on("mouseover", function () {
      console.log($(this).index())
      if (headerISWideScreen) {
        $('#header-nav-item-group-aboutus').css({ 'display': 'none' });
        $(this).find('a').css({ 'background-color': '#fff', 'color': '#a19062', 'padding-bottom': '4px', 'box-shadow': '0px 4px 0px #a19062' });
        group_item_news.css({ 'display': 'block' });
      }
      else {
        $(this).css({ 'background-color': '#a19062' });
        $(this).find('a').css({ 'color': '#fff', 'background-color': 'rgba(255, 255, 255, 0)' });
        if (headerNavTitleChildcompanyOpen) $(this).find('img').attr('src', '/static/imgs/nav-arrow-up-white.svg');
        else $(this).find('img').attr('src', '/static/imgs/nav-arrow-down-white.svg');
      }
    });

    headerNavTitleChildnews.on("mouseout", function (e) {
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
          group_item_news.css({ 'display': 'none' });
        }
      }
      else {
        $(this).css({ 'background-color': '#fff' });
        $(this).find('a').css({ 'color': '#49443d' });
        if (headerNavTitleChildcompanyOpen) $(this).find('img').attr('src', '/static/imgs/nav-arrow-up.svg');
        else $(this).find('img').attr('src', '/static/imgs/nav-arrow-down.svg');
      }
    });

    headerNavTitleChildnews.on("mousedown", function () {
      if (headerISWideScreen) {
        return;
      }
      if (headerNavTitleChildcompanyOpen) {
        headerNavTitleChildcompanyOpen = false;
        $(this).find('img').attr('src', '/static/imgs/nav-arrow-down.svg');
        group_item_news.css('display', 'none');
      }
      else {
        headerNavTitleChildcompanyOpen = true;
        $(this).find('img').attr('src', '/static/imgs/nav-arrow-up-white.svg');
        group_item_news.css('display', 'block');
      }
      if (headerNavTitleChildcompanyOpen) $(this).find('img').attr('src', '/static/imgs/nav-arrow-up-white.svg');
      else $(this).find('img').attr('src', '/static/imgs/nav-arrow-down-white.svg');
    });
  }
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

  $('#header-nav-title-news').on(headerDragEvent.out, function (e) {
    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
    }
    else {
      $(this).css({ 'background-color': '#fff' });
      $(this).find('a').css({ 'color': '#49443d' });
    }
  });

  $('#header-nav-title-duty').on(headerDragEvent.over, function (e) {
    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#a19062', 'padding-bottom': '4px', 'box-shadow': '0px 4px 0px #a19062' });
    }
    else {
      $(this).css({ 'background-color': '#a19062' });
      $(this).find('a').css({ 'color': '#fff', 'background-color': 'rgba(255, 255, 255, 0)' });
    }
  });

  $('#header-nav-title-duty').on(headerDragEvent.out, function (e) {
    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
    }
    else {
      $(this).css({ 'background-color': '#fff' });
      $(this).find('a').css({ 'color': '#49443d' });
    }
  });

  $('#header-nav-title-event').on(headerDragEvent.over, function (e) {
    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#a19062', 'padding-bottom': '4px', 'box-shadow': '0px 4px 0px #a19062' });
    }
    else {
      $(this).css({ 'background-color': '#a19062' });
      $(this).find('a').css({ 'color': '#fff', 'background-color': 'rgba(255, 255, 255, 0)' });
    }
  });

  $('#header-nav-title-event').on(headerDragEvent.out, function (e) {
    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
    }
    else {
      $(this).css({ 'background-color': '#fff' });
      $(this).find('a').css({ 'color': '#49443d' });
    }
  });

  $('#header-nav-title-jobs').on(headerDragEvent.over, function (e) {
    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#a19062', 'padding-bottom': '4px', 'box-shadow': '0px 4px 0px #a19062' });
    }
    else {
      $(this).css({ 'background-color': '#a19062' });
      $(this).find('a').css({ 'color': '#fff', 'background-color': 'rgba(255, 255, 255, 0)' });
    }
  });

  $('#header-nav-title-jobs').on(headerDragEvent.out, function (e) {
    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
    }
    else {
      $(this).css({ 'background-color': '#fff' });
      $(this).find('a').css({ 'color': '#49443d' });
    }
  });
  $('#header-nav-title-grand').on(headerDragEvent.over, function (e) {
    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#a19062', 'padding-bottom': '4px', 'box-shadow': '0px 4px 0px #a19062' });
    }
    else {
      $(this).css({ 'background-color': '#a19062' });
      $(this).find('a').css({ 'color': '#fff', 'background-color': 'rgba(255, 255, 255, 0)' });
    }
  });

  $('#header-nav-title-grand').on(headerDragEvent.out, function (e) {
    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
    }
    else {
      $(this).css({ 'background-color': '#fff' });
      $(this).find('a').css({ 'color': '#49443d' });
    }
  });


    $('#header-nav-title-duty').on(headerDragEvent.out, function (e) {
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

  $('.header-nav-item-group').on(headerDragEvent.out, function (e) {
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
  $('.header-nav-item-group li').on(headerDragEvent.over, function () {
    $(this).css({ 'background-color': '#a19062' });
    $(this).find('a').css({ 'color': '#fff' });
  });

  $('.header-nav-item-group li').on(headerDragEvent.out, function () {
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
var headerScreenW, headerScreenH, headerISWideScreen;

var headerNavOpen;
var headerNavTitleAboutusOpen, headerNavTitleChildcompanyOpen, headerNavTitleChildnewsOpen;

var headerNavTitleAboutus = $('#header-nav-title-aboutus');
var headerNavTitleChildcompany = $('.header-nav-title-childcompany');
var headerNavTitleChildnews = $('.header-nav-title-news');
var group_item_news = $('#header-nav-item-group-news');
var group_item_childcompany = $('#header-nav-item-group-childcompany');
var headerISTouch = window.hasOwnProperty('ontouchstart');
var headerDragEvent = headerISTouch ? {
  down: 'touchstart',
  move: 'touchmove',
  up: 'touchend',
  over: 'touchstart',
  out: 'touchend'
} : { headerScreenW, headerScreenH, headerISWideScreen}

var headerNavOpen;
var headerNavTitleAboutusOpen, headerNavTitleChildcompanyOpen, headerNavTitleChildnewsOpen;

var headerNavTitleAboutus = $('#header-nav-title-aboutus');
var headerNavTitleChildcompany = $('.header-nav-title-childcompany');
var headerNavTitleChildnews = $('.header-nav-title-news');
var group_item_news = $('#header-nav-item-group-news');
var group_item_childcompany = $('#header-nav-item-group-childcompany');
var headerISTouch = window.hasOwnProperty('ontouchstart');
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

$(document).ready(function () {
    $(window).resize(headerUpdateSize);
})

headerUpdateSize();
headerHandleButton();
headerHandleNav();

setToggleLanguageHref();

function headerUpdateSize() {
    headerScreenW = $(window).width();
    headerScreenH = $(window).height();
    if (headerScreenW > 1424) headerISWideScreen = true;
    else headerISWideScreen = false;
}
function headerHandleButton() {
    $('.header-nav-button img').on(headerDragEvent.down, function () {
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

    //
    headerNavTitleAboutus.on(headerDragEvent.over, function () {

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

    headerNavTitleAboutus.on(headerDragEvent.out, function (e) {
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

    headerNavTitleAboutus.on(headerDragEvent.down, function () {
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
    console.log($('html').width())
    if (headerISTouch) {
        headerNavTitleChildcompany.on("click", function (event) {
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
                if (headerNavTitleChildcompanyOpen) $(this).find('img').attr('src', '/static/imgs/nav-arrow-up-white.svg');
                else $(this).find('img').attr('src', '/static/imgs/nav-arrow-down-white.svg');

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

            }

        });
        $('#header-nav-item-group-childcompany').on("click", function (e) {
            e.stopPropagation();
        })
        $(document.body).on("click", function (e) {
            if (headerISWideScreen) {
                // $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
                $('#header-nav-item-group-childcompany').css({ 'display': 'none' });
            }
        });
    } else {
        headerNavTitleChildcompany.on("mouseover", function () {
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
    }
    // 新闻列表
    if (headerISTouch) {
        headerNavTitleChildnews.on("click", function (event) {
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
                console.log(3)
                if (headerNavTitleChildnewsOpen) {
                    console.log(1)
                    headerNavTitleChildnewsOpen = false;
                    $(this).find('img').attr('src', '/static/imgs/nav-arrow-down.svg');
                    group_item_news.css('display', 'none');
                }
                else {
                    console.log(2)

                    headerNavTitleChildnewsOpen = true;
                    $(this).find('img').attr('src', '/static/imgs/nav-arrow-up-white.svg');
                    group_item_news.css('display', 'block');
                }
            }
        });
        group_item_news.on("click", function (e) {
            e.stopPropagation();
        })
        $(document.body).on("click", function (e) {
            if (headerISWideScreen) {
                // $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
                group_item_news.css({ 'display': 'none' });
            }
        });
    } else {
        headerNavTitleChildnews.on("mouseover", function () {
            console.log($(this).index())
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
    }
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

    $('#header-nav-title-news').on(headerDragEvent.out, function (e) {
        if (headerISWideScreen) {
            $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
        }
        else {
            $(this).css({ 'background-color': '#fff' });
            $(this).find('a').css({ 'color': '#49443d' });
        }
    });

    $('#header-nav-title-duty').on(headerDragEvent.over, function (e) {
        if (headerISWideScreen) {
            $(this).find('a').css({ 'background-color': '#fff', 'color': '#a19062', 'padding-bottom': '4px', 'box-shadow': '0px 4px 0px #a19062' });
        }
        else {
            $(this).css({ 'background-color': '#a19062' });
            $(this).find('a').css({ 'color': '#fff', 'background-color': 'rgba(255, 255, 255, 0)' });
        }
    });

    $('#header-nav-title-duty').on(headerDragEvent.out, function (e) {
        if (headerISWideScreen) {
            $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
        }
        else {
            $(this).css({ 'background-color': '#fff' });
            $(this).find('a').css({ 'color': '#49443d' });
        }
    });

    $('#header-nav-title-event').on(headerDragEvent.over, function (e) {
        if (headerISWideScreen) {
            $(this).find('a').css({ 'background-color': '#fff', 'color': '#a19062', 'padding-bottom': '4px', 'box-shadow': '0px 4px 0px #a19062' });
        }
        else {
            $(this).css({ 'background-color': '#a19062' });
            $(this).find('a').css({ 'color': '#fff', 'background-color': 'rgba(255, 255, 255, 0)' });
        }
    });

    $('#header-nav-title-event').on(headerDragEvent.out, function (e) {
        if (headerISWideScreen) {
            $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
        }
        else {
            $(this).css({ 'background-color': '#fff' });
            $(this).find('a').css({ 'color': '#49443d' });
        }
    });

    $('#header-nav-title-jobs').on(headerDragEvent.over, function (e) {
        if (headerISWideScreen) {
            $(this).find('a').css({ 'background-color': '#fff', 'color': '#a19062', 'padding-bottom': '4px', 'box-shadow': '0px 4px 0px #a19062' });
        }
        else {
            $(this).css({ 'background-color': '#a19062' });
            $(this).find('a').css({ 'color': '#fff', 'background-color': 'rgba(255, 255, 255, 0)' });
        }
    });

    $('#header-nav-title-jobs').on(headerDragEvent.out, function (e) {
        if (headerISWideScreen) {
            $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
        }
        else {
            $(this).css({ 'background-color': '#fff' });
            $(this).find('a').css({ 'color': '#49443d' });
        }
    });
    $('#header-nav-title-grand').on(headerDragEvent.over, function (e) {
        if (headerISWideScreen) {
            $(this).find('a').css({ 'background-color': '#fff', 'color': '#a19062', 'padding-bottom': '4px', 'box-shadow': '0px 4px 0px #a19062' });
        }
        else {
            $(this).css({ 'background-color': '#a19062' });
            $(this).find('a').css({ 'color': '#fff', 'background-color': 'rgba(255, 255, 255, 0)' });
        }
    });

    $('#header-nav-title-grand').on(headerDragEvent.out, function (e) {
        if (headerISWideScreen) {
            $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
        }
        else {
            $(this).css({ 'background-color': '#fff' });
            $(this).find('a').css({ 'color': '#49443d' });
        }
    });


    $('#header-nav-title-duty').on(headerDragEvent.out, function (e) {
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

    $('.header-nav-item-group').on(headerDragEvent.out, function (e) {
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
    $('.header-nav-item-group li').on(headerDragEvent.over, function () {
        $(this).css({ 'background-color': '#a19062' });
        $(this).find('a').css({ 'color': '#fff' });
    });

    $('.header-nav-item-group li').on(headerDragEvent.out, function () {
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

// down: 'mousedown',
//   move: 'mousemove',
//   up: 'mouseup',
//   over: 'mouseover',
//   out: 'mouseout'
// }

$(document).ready(function () {
  $(window).resize(headerUpdateSize);
})

headerUpdateSize();
headerHandleButton();
headerHandleNav();

setToggleLanguageHref();

function headerUpdateSize() {
  headerScreenW = $(window).width();
  headerScreenH = $(window).height();
  if (headerScreenW > 1424) headerISWideScreen = true;
  else headerISWideScreen = false;
}
function headerHandleButton() {
  $('.header-nav-button img').on(headerDragEvent.down, function () {
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

  //
  headerNavTitleAboutus.on(headerDragEvent.over, function () {

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

  headerNavTitleAboutus.on(headerDragEvent.out, function (e) {
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

  headerNavTitleAboutus.on(headerDragEvent.down, function () {
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
  console.log($('html').width())
  if (headerISTouch) {
    headerNavTitleChildcompany.on("click", function (event) {
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
        if (headerNavTitleChildcompanyOpen) $(this).find('img').attr('src', '/static/imgs/nav-arrow-up-white.svg');
        else $(this).find('img').attr('src', '/static/imgs/nav-arrow-down-white.svg');

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

      }

    });
    $('#header-nav-item-group-childcompany').on("click", function (e) {
      e.stopPropagation();
    })
    $(document.body).on("click", function (e) {
      if (headerISWideScreen) {
        // $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
        $('#header-nav-item-group-childcompany').css({ 'display': 'none' });
      }
    });
  } else {
    headerNavTitleChildcompany.on("mouseover", function () {
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
  }
  // 新闻列表
  if (headerISTouch) {
    headerNavTitleChildnews.on("click", function (event) {
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
        console.log(3)
        if (headerNavTitleChildnewsOpen) {
          console.log(1)
          headerNavTitleChildnewsOpen = false;
          $(this).find('img').attr('src', '/static/imgs/nav-arrow-down.svg');
          group_item_news.css('display', 'none');
        }
        else {
          console.log(2)

          headerNavTitleChildnewsOpen = true;
          $(this).find('img').attr('src', '/static/imgs/nav-arrow-up-white.svg');
          group_item_news.css('display', 'block');
        }
      }
    });
    group_item_news.on("click", function (e) {
      e.stopPropagation();
    })
    $(document.body).on("click", function (e) {
      if (headerISWideScreen) {
        // $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
        group_item_news.css({ 'display': 'none' });
      }
    });
  } else {
    headerNavTitleChildnews.on("mouseover", function () {
      console.log($(this).index())
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
  }
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

  $('#header-nav-title-news').on(headerDragEvent.out, function (e) {
    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
    }
    else {
      $(this).css({ 'background-color': '#fff' });
      $(this).find('a').css({ 'color': '#49443d' });
    }
  });

  $('#header-nav-title-duty').on(headerDragEvent.over, function (e) {
    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#a19062', 'padding-bottom': '4px', 'box-shadow': '0px 4px 0px #a19062' });
    }
    else {
      $(this).css({ 'background-color': '#a19062' });
      $(this).find('a').css({ 'color': '#fff', 'background-color': 'rgba(255, 255, 255, 0)' });
    }
  });

  $('#header-nav-title-duty').on(headerDragEvent.out, function (e) {
    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
    }
    else {
      $(this).css({ 'background-color': '#fff' });
      $(this).find('a').css({ 'color': '#49443d' });
    }
  });

  $('#header-nav-title-event').on(headerDragEvent.over, function (e) {
    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#a19062', 'padding-bottom': '4px', 'box-shadow': '0px 4px 0px #a19062' });
    }
    else {
      $(this).css({ 'background-color': '#a19062' });
      $(this).find('a').css({ 'color': '#fff', 'background-color': 'rgba(255, 255, 255, 0)' });
    }
  });

  $('#header-nav-title-event').on(headerDragEvent.out, function (e) {
    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
    }
    else {
      $(this).css({ 'background-color': '#fff' });
      $(this).find('a').css({ 'color': '#49443d' });
    }
  });

  $('#header-nav-title-jobs').on(headerDragEvent.over, function (e) {
    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#a19062', 'padding-bottom': '4px', 'box-shadow': '0px 4px 0px #a19062' });
    }
    else {
      $(this).css({ 'background-color': '#a19062' });
      $(this).find('a').css({ 'color': '#fff', 'background-color': 'rgba(255, 255, 255, 0)' });
    }
  });

  $('#header-nav-title-jobs').on(headerDragEvent.out, function (e) {
    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
    }
    else {
      $(this).css({ 'background-color': '#fff' });
      $(this).find('a').css({ 'color': '#49443d' });
    }
  });
  $('#header-nav-title-grand').on(headerDragEvent.over, function (e) {
    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#a19062', 'padding-bottom': '4px', 'box-shadow': '0px 4px 0px #a19062' });
    }
    else {
      $(this).css({ 'background-color': '#a19062' });
      $(this).find('a').css({ 'color': '#fff', 'background-color': 'rgba(255, 255, 255, 0)' });
    }
  });

  $('#header-nav-title-grand').on(headerDragEvent.out, function (e) {
    if (headerISWideScreen) {
      $(this).find('a').css({ 'background-color': '#fff', 'color': '#49443d', 'box-shadow': 'none', 'padding-bottom': '0px' });
    }
    else {
      $(this).css({ 'background-color': '#fff' });
      $(this).find('a').css({ 'color': '#49443d' });
    }
  });


    $('#header-nav-title-duty').on(headerDragEvent.out, function (e) {
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

  $('.header-nav-item-group').on(headerDragEvent.out, function (e) {
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
  $('.header-nav-item-group li').on(headerDragEvent.over, function () {
    $(this).css({ 'background-color': '#a19062' });
    $(this).find('a').css({ 'color': '#fff' });
  });

  $('.header-nav-item-group li').on(headerDragEvent.out, function () {
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
