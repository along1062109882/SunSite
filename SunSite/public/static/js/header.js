var headerScreenW, headerScreenH, headerISWideScreen;

var headerNavOpen;
var headerNavTitleAboutusOpen, headerNavTitleChildcompanyOpen, headerNavTitleChildnewsOpen;

var group_item_news = $('#header-nav-item-group-news');
var group_item_childcompany = $('#header-nav-item-group-childcompany');
var headerISTouch = window.hasOwnProperty('ontouchstart');
var lastHeaderScreenW = $(window).width();

$(document).ready(function () {
  $(window).resize(headerUpdateSize);
})

headerUpdateSize();
headerHandleButton();
addMenuHandle();

setToggleLanguageHref();

function headerUpdateSize(e) {
  // headerScreenW = $(window).width();
  headerScreenW = window.innerWidth;
  var isToggleDevice = false;
  lastHeaderScreenW = headerScreenW
  
  headerScreenH = $(window).height();
  if (headerScreenW > 1439) {
    headerISWideScreen = true;
    $(".nav-submenu-open").find('img').attr('src', '/static/imgs/nav-arrow-down.svg');
    $(".nav-submenu-open").removeClass('nav-submenu-open');
  } else {
    headerISWideScreen = false;
  }

  if (isToggleDevice) {
    if (headerISWideScreen) { 
      headerISWideScreen = true;
      group_item_childcompany.hide();
      group_item_news.hide();
    }
  }
  
}
function headerHandleButton() {
  $('#header-nav-button').on('click', function () {
    if (headerISWideScreen) {
      return;
    }
    if (headerNavOpen) {
      headerNavOpen = false;
      $('.header-nav-group').css('display', 'none');
      $(this).find('img').attr("src", "/static/imgs/news-toggle-grid.svg");
    }
    else {
      headerNavOpen = true;
      $('.header-nav-group').css('display', 'block');
      $(this).find('img').attr("src", "/static/imgs/menu-icon-copy.svg");
    }
  });
}


function addMenuHandle() {
  $('.submenu-item, .main-menu').on('mouseover', function () {
    $(this).addClass('hover');
  });
  $('.submenu-item, .main-menu').on('mouseout', function (e) {
    $(this).removeClass('hover');
  });

  // $('.main-menu').on("mouseover", function () {
  //   $(this).addClass('hover');
  // });
  // $('.main-menu').on("mouseout", function (e) {
  //   $(this).removeClass('hover');
  // });

  $('.main-wrapper').on('mouseover', function () {
    if (headerISWideScreen) {
      $(".nav-submenu-open").removeClass('nav-submenu-open');
      $(this).find('.main-menu').addClass('nav-submenu-open');
    }
  });
  $('.main-wrapper').on('mouseout', function () {
    if (headerISWideScreen) {
      $(this).find('.main-menu').removeClass('nav-submenu-open');
    }
  });
  $('.main-menu').on("click", function (event) {
    event.stopPropagation();  
    if (headerISWideScreen) {
    }
    else {
      var isOpen = $(this).hasClass('nav-submenu-open');
      if (isOpen) {
        $(this).removeClass('nav-submenu-open');
        $(this).find('img').attr('src', '/static/imgs/nav-arrow-down.svg');
      } else {
        var prevOpen = $(".nav-submenu-open");
        prevOpen.find('img').attr('src', '/static/imgs/nav-arrow-down.svg');
        prevOpen.removeClass('nav-submenu-open');
        $(this).addClass('nav-submenu-open');
        $(this).find('img').attr('src', '/static/imgs/nav-arrow-up-white.svg');
      }
    }
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