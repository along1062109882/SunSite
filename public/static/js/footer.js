
var footerIconLinkFM = $('#footer-links-sns-text-wechat-fm');
var footerIconLinkSC = $('#footer-links-sns-text-wechat-sc');

footerIconLinkFM.on('mouseover', function () {
  footerToggleQR('#footer-links-sns-text-wechat-fm', '#footer-links-sns-icons-pop-img', 'block', 0, '/static/imgs/footer-links-sns-wechat-fm.png');
});

footerIconLinkFM.on('mouseout', function () {
  footerToggleQR('#footer-links-sns-text-wechat-fm', '#footer-links-sns-icons-pop-img', 'none', 0, '');
});

footerIconLinkSC.on('mouseover', function () {
  footerToggleQR('#footer-links-sns-text-wechat-sc', '#footer-links-sns-icons-pop-img', 'block', 16, '/static/imgs/footer-links-sns-wechat-sc.png');
});

footerIconLinkSC.on('mouseout', function () {
  footerToggleQR('#footer-links-sns-text-wechat-sc', '#footer-links-sns-icons-pop-img', 'none', 16, '');
});



var footerFoldIconLinkFM = $('#footer-fold-links-sns-text-wechat-fm');
var footerFoldIconLinkSC = $('#footer-fold-links-sns-text-wechat-sc');

footerFoldIconLinkFM.on('mouseover', function () {
  footerToggleQR('#footer-fold-links-sns-text-wechat-fm', '#footer-fold-links-sns-icons-pop-img', 'block', 0, '/static/imgs/footer-links-sns-wechat-fm.png');
});

footerFoldIconLinkFM.on('mouseout', function () {
  footerToggleQR('#footer-fold-links-sns-text-wechat-fm', '#footer-fold-links-sns-icons-pop-img', 'none', 0, '');
});

footerFoldIconLinkSC.on('mouseover', function () {
  footerToggleQR('#footer-fold-links-sns-text-wechat-sc', '#footer-fold-links-sns-icons-pop-img', 'block', 16, '/static/imgs/footer-links-sns-wechat-sc.png');
});

footerFoldIconLinkSC.on('mouseout', function () {
  footerToggleQR('#footer-fold-links-sns-text-wechat-sc', '#footer-fold-links-sns-icons-pop-img', 'none', 16, '');
});



function footerToggleQR(node, pop, style, offset, src) {
  var snsPopImg = $(pop);
  snsPopImg.css({'display': style, 'width': '120px', 'height': '130px'});
  snsPopImg.attr('src', src);
  snsPopImg.offset({'left': $(node).offset().left + $(node).width() / 2 - 60, 'top': $(node).offset().top + $(node).height() + offset});
}



var footerFoldLinkGroupAboutusOpen, footerFoldLinkGroupChildcompanyOpen, footerFoldLinkGroupMagazineOpen;

var footerFoldLinkGroupAboutus = $('.footer-fold-links-aboutus-title');
var footerFoldLinkGroupChildcompany = $('.footer-fold-links-childcompany-title');
var footerFoldLinkGroupChildnews = $('.footer-fold-links-childcompany-news');
var footerFoldLinkGroupMagazine = $('.footer-fold-links-magazine-title');

footerFoldLinkGroupAboutus.click(function () {
  if (footerFoldLinkGroupAboutusOpen) {
    footerFoldLinkGroupAboutusOpen = false;
    $('.footer-fold-links-aboutus-title img').attr('src', '/static/imgs/nav-arrow-down.svg');
    $('.footer-fold-links-aboutus-group').css('display', 'none');
  }
  else {
    footerFoldLinkGroupAboutusOpen = true;
    $('.footer-fold-links-aboutus-title img').attr('src', '/static/imgs/nav-arrow-up.svg');
    $('.footer-fold-links-aboutus-group').css('display', 'block');
  }
});

footerFoldLinkGroupChildcompany.click(function () {
  if (footerFoldLinkGroupChildcompanyOpen) {
    footerFoldLinkGroupChildcompanyOpen = false;
    $('.footer-fold-links-childcompany-title img').attr('src', '/static/imgs/nav-arrow-down.svg');
    $('.footer-fold-links-childcompany-group').css('display', 'none');
  }
  else {
    footerFoldLinkGroupChildcompanyOpen = true;
    $('.footer-fold-links-childcompany-title img').attr('src', '/static/imgs/nav-arrow-up.svg');
    $('.footer-fold-links-childcompany-group').css('display', 'block');
  }
});
footerFoldLinkGroupChildnews.click(function () {
  if (footerFoldLinkGroupChildcompanyOpen) {
    footerFoldLinkGroupChildcompanyOpen = false;
    $('.footer-fold-links-childcompany-news img').attr('src', '/static/imgs/nav-arrow-down.svg');
    $('.footer-fold-links-childcompany-group-news').css('display', 'none');
  }
  else {
    footerFoldLinkGroupChildcompanyOpen = true;
    $('.footer-fold-links-childcompany-news img').attr('src', '/static/imgs/nav-arrow-up.svg');
    $('.footer-fold-links-childcompany-group-news').css('display', 'block');
  }
});
footerFoldLinkGroupMagazine.click(function () {
  if (footerFoldLinkGroupMagazineOpen) {
    footerFoldLinkGroupMagazineOpen = false;
    $('.footer-fold-links-magazine-title img').attr('src', '/static/imgs/nav-arrow-down.svg');
    $('.footer-fold-links-magazine-group').css('display', 'none');
  }
  else {
    footerFoldLinkGroupMagazineOpen = true;
    $('.footer-fold-links-magazine-title img').attr('src', '/static/imgs/nav-arrow-up.svg');
    $('.footer-fold-links-magazine-group').css('display', 'block');
  }
});



var footerToTop = $('.footer-to-top');

window.onscroll = function(){
     var t = document.documentElement.scrollTop || document.body.scrollTop;
     if( t >= 300 ) {
       footerToTop.css({'display': 'block'});
     } else {
       footerToTop.css({'display': 'none'});
     }
}

footerToTop.click(function () {
  $('body,html').animate({ scrollTop: 0 }, 600);
});
