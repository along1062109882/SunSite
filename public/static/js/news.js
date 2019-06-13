new WOW().init();

$(function () {
    var mainPageBtn = $(".navigator :nth-child(3)");

    mainPageBtn.addClass("selected");

    var myDate = new Date().getFullYear();

    // // 动态创建filter-list下的标签
    // var str = '';
    // for (var i = myDate; i >= myDate - 10; i--) {
    //   str += '<li>' + `${i}` + '</li>';
    // }
    // $('.filter-list').append(str);

    // 封装从地址栏回去year参数
    function getRequest() {
        var url = window.location.search; //获取url中"?"符后的字串
        var theRequest = new Object();
        if (url.indexOf("?") != -1) {
            var str = url.substr(1);
            strs = str.split("&");
            for (var i = 0; i < strs.length; i++) {
                theRequest[strs[i].split("=")[0]] = decodeURI(strs[i].split("=")[1]);
            }
        }
        return theRequest;
    }

    var year = getRequest().year;
    // 根据地址栏返回的参数信息，改变相应的title标题
    if (location.pathname.indexOf('/zh-hans') != -1) {
        if (year == null) {
            // $('.drop-down input').val(myDate)
            $('head title').text(myDate + ' 年 - 集团动态 - 太阳城集团 SUNCITYGROUP')
        } else {
            $('.drop-down input').val(year);
            $('head title').text(year + ' 年 - 集团动态 - 太阳城集团 SUNCITYGROUP')
        }
    }
    else if (location.pathname.indexOf('/zh-hant') != -1) {
        if (year == null) {
            // $('.drop-down input').val(myDate);
            $('head title').text(myDate + ' 年 - 集團動態 - 太陽城集團 SUNCITYGROUP')
        } else {
            $('.drop-down input').val(year);
            $('head title').text(year + ' 年 - 集團動態 - 太陽城集團 SUNCITYGROUP')
        }
    }

    $('.navigator :gt(1)')
        .hover(function () {
            $(this).addClass("selected");
            mainPageBtn.removeClass("selected");
        }, function () {
            $(this).removeClass("selected");
            mainPageBtn.addClass("selected");
        });

    $('.footer-back').click(function () {
        $('body,html').animate({ scrollTop: 0 }, 600);
    });

    // 根据年份参数的变化，跳转不同的页面
    // $('.filter-list li').click(function () {
    //   var txt = $(this).text();
    //   if (location.pathname.indexOf('/zh-hans') != -1) {
    //     window.location.href = "/zh-hans/news?year=" + txt;
    //     $('head title').text(year + ' 年 - 集团动态 - 太阳城集团 SUNCITYGROUP')
    //   }
    //   else if (location.pathname.indexOf('/zh-hant') != -1) {
    //     window.location.href = "/zh-hant/news?year=" + txt;
    //     $('head title').text(year + ' 年 - 集團動態 - 太陽城集團 SUNCITYGROUP')
    //   }
    //   $('.drop-down input').val(txt);
    // })

    // 相应的年份进行递减，最低不能低于近十年的最低年份
    $('.yearPrev').click(function () {
        var currentYear = $('.drop-down input').val();
        var yearsDom = $(".filter-list li a");
        var newYear;
        yearsDom.each((index, e) => {
            if (e.innerHTML === currentYear && yearsDom[index + 1]) {
                newYear = yearsDom[index + 1].innerHTML;
            }
        })
        if (!newYear) {
            return
        }
        if (location.pathname.indexOf('/zh-hans') != -1) {
            window.location.href = "/zh-hans/news?year=" + newYear;
        }
        else if (location.pathname.indexOf('/zh-hant') != -1) {
            window.location.href = "/zh-hant/news?year=" + newYear;
        }
    })
    // 判断当前时间小于或等于当前集团新闻中心的最低年份，该按钮则显示禁用状态
    if ($('#drop-down-year').val() == $('.drop-down li').last().text()) {
        $('.yearPrev img').css({"opacity": 0.5, 'cursor':'not-allowed'});
        $('.yearPrev').css({'border':'solid 1px #a1906282', 'cursor':'not-allowed'});
    } else {
        $('.yearPrev img').css({"opacity": 1, 'cursor':'pointer'});
        $('.yearPrev').css({'border':'solid 1px  #a19062', 'cursor':'pointer'});
    }

    // 相应年份进行递增，最高不能超过最新的年份
    $('.yearNext').click(function () {
        var currentYear = $('.drop-down input').val();
        var yearsDom = $(".filter-list li a");
        var newYear;
        yearsDom.each((index, e) => {
            if (e.innerHTML === currentYear && yearsDom[index - 1]) {
                newYear = yearsDom[index - 1].innerHTML;
            }
        })
        if (!newYear) {
            return
        }
        if (location.pathname.indexOf('/zh-hans') != -1) {
            window.location.href = "/zh-hans/news?year=" + newYear;
        }
        else if (location.pathname.indexOf('/zh-hant') != -1) {
            window.location.href = "/zh-hant/news?year=" + newYear;
        }
    })
    // 判断当前时间大于或等于最新年份，则显示禁用状态
    if ($('#drop-down-year').val() == myDate) {
        $('.yearNext img').css({"opacity": 0.5, 'cursor':'not-allowed'});
        $('.yearNext').css({'border':'solid 1px #a1906282', 'cursor':'not-allowed'});
    } else {
        $('.yearNext img').css({"opacity": 1, 'cursor':'pointer'});
        $('.yearNext').css({'border':'solid 1px  #a19062', 'cursor':'pointer'});
    }

    $('.drop-down').click(function () {
        var $filter = $('.filter-list');
        var $icon = $('.icon');
        if ($filter.css('display') === 'block') {
            $filter.css('display', 'none');
            $icon.css('transform', 'rotate(0deg)')
        } else {
            $filter.css('display', 'block');
            $icon.css('transform', 'rotate(180deg)')
        }
    })
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
    // 输入框内容匹配进行模糊搜索
    $('.search_btn').on('click', function() {
        var newYear = $('.drop-down input').val();
        var keyword = $('#filter_search').val()
        if (location.pathname.indexOf('/zh-hans') != -1) {
            window.location.href = "/zh-hans/news?year=" + newYear + '&key=' + keyword;
        }
        else if (location.pathname.indexOf('/zh-hant') != -1) {
            window.location.href = "/zh-hant/news?year=" + newYear + '&key=' + keyword;
        }
        console.log($('#filter_search').val())
        console.log($('.drop-down input').val())
    })
    var listTemp = null;
    var cellTemp = null;

    // switch articles display mode
    $('.list-mode').click(function () {
        var isListMode = $(this).hasClass('list-mode');

        if (isListMode) {
            listTemp = $('.news-container').clone(true);
            if ($('.news-container-cell').length > 0) {
                $('.news-container-cell').css('display', 'block');
            } else {
                $('.gradient-border').after(cellTemp);
            }
            $('.news-container').remove();
            $(this).removeClass('list-mode');
            $(this).attr('src', '/static/imgs/news-toggle-grid.svg');
        } else {
            cellTemp = $('.news-container-cell').clone(true);
            if ($('.news-container').length > 0) {
                $('.news-container').css('display', 'block');
            } else {
                $('.gradient-border').after(listTemp);
            }
            $('.news-container-cell').remove();
            $(this).addClass('list-mode');
            $(this).attr('src', '/static/imgs/news-toggle-row.svg');
        }
    });

    // $('.info-wrapper :last-child').click(function() {
    //   location.href = './latest.html';
    // });

    var width = document.body.clientWidth
    var number = 1
    var initialSlide = window.__initialSlide__ || 0
    if (width <= 1024) {
        if (width <= 414) {
            number = 3
        }
        if (width > 414 && width < 640) {
            number = 4
        }
        if (width >= 640) {
            number = 5
        }
        if (width >= 768) {
            number = 6
        }
        if (width == 1024) {
            number = 8
        }
        var swiper = new Swiper('.swiper-container', {
            initialSlide: initialSlide,
            slidesPerView: number,
            spaceBetween: 0,
            slidesPerGroup: 3,
            // navigation: {
            //   nextEl: '.swiper-button-next',
            //   prevEl: '.swiper-button-prev',
            // },
        });

        $('.news-swiper-button-prev').click(function () {
            swiper.slidePrev();
        })
        $('.news-swiper-button-next').click(function () {
            swiper.slideNext();
        })
        $('.swiper-slide').eq(initialSlide).addClass('news-swiper-slide-active')
    }

    // highlight current item in wide screen
    $('.event-name-item').eq(initialSlide).addClass('event-name-item-active');
    // 图片加载有误
    $(".news-item .ratio-16-9 img").on("error", function () {
        $(this).attr("src", "/static/imgs/group.svg");
    });
});

// set2CellMode();

// function set2CellMode() {
//   listTemp = $('.news-container').clone(true);
//   if ($('.news-container-cell').length > 0) {
//     $('.news-container-cell').css('display', 'block');
//   } else {
//     $('.gradient-border').after(cellTemp);
//   }
//   $('.news-container').remove();
// }
