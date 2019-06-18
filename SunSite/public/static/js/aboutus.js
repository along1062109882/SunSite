new WOW().init();

function onSelected() {
  var preSelected = $('.overview-list-container .selected');

  preSelected.click(onSelected);
  preSelected.children('div').css('display', 'inline-block');

  $('.overview-list-container .selected').removeClass('selected');
  $(this).children('div').css('display', 'none');
  $(this).addClass('selected');

  $('.overview-detail h1').html($(this).find('div .dataSet .title').text().trim());
  $('.overview-detail p').html($(this).find('div .dataSet .content').text().trim());
  $('.tempImg img').attr({
    'src': `${$(this).children('div.overview-detail-des').children('img').attr('src').slice(0, -3)}jpg`
  });
  $('.tempImg').addClass('mainImg');
  // $('.mainImg img').attr({
  //   'src': `${$(this).children('div.overview-detail-des').children('img').attr('src').slice(0, -3)}jpg`
  // });
}

$(function() {
  var mainPageBtn = $(".navigator :nth-child(2)");

  mainPageBtn.addClass("selected");

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

  $('.navigator :gt(1)')
    .hover(function() {
      $(this).addClass("selected");
      mainPageBtn.removeClass("selected");
    }, function() {
      $(this).removeClass("selected");
      mainPageBtn.addClass("selected");
    });

  $('.overview-list-container>div:not(.selected)').click(onSelected);

  $('.footer-back').click(function() {
    $('body,html').animate({ scrollTop: 0 }, 600);
  });

  $('.tempImg').on('animationend', () => {
    $('.tempImg').removeClass('mainImg');
  });
});
