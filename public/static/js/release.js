new WOW().init();

$(function () {
    var mainPageBtn = $(".navigator :nth-child(3)");

    mainPageBtn.addClass("selected");

    var myDate = new Date().getFullYear();
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
            $('head title').text(myDate + ' 年 - 集团动态 - 太阳城集团 SUNCITYGROUP')
        } else {
            $('.drop-down input').val(year);
            $('head title').text(year + ' 年 - 集团动态 - 太阳城集团 SUNCITYGROUP')
        }
    }
    else if (location.pathname.indexOf('/zh-hant') != -1) {
        if (year == null) {
            $('head title').text(myDate + ' 年 - 集團動態 - 太陽城集團 SUNCITYGROUP')
        } else {
            $('.drop-down input').val(year);
            $('head title').text(year + ' 年 - 集團動態 - 太陽城集團 SUNCITYGROUP')
        }
    }

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
            window.location.href = "/zh-hans/release?year=" + newYear;
        }
        else if (location.pathname.indexOf('/zh-hant') != -1) {
            window.location.href = "/zh-hant/release?year=" + newYear;
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
            window.location.href = "/zh-hans/release?year=" + newYear;
        }
        else if (location.pathname.indexOf('/zh-hant') != -1) {
            window.location.href = "/zh-hant/release?year=" + newYear;
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
            window.location.href = "/zh-hans/release?year=" + newYear + '&key=' + keyword;
        }
        else if (location.pathname.indexOf('/zh-hant') != -1) {
            window.location.href = "/zh-hant/release?year=" + newYear + '&key=' + keyword;
        }
    })
});
