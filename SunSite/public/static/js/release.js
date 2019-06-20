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
      
    if ($('#drop-down-year').val() == $('.filter-list li').last().text()) {
        $('.yearPrev img').css({"opacity": 0.5, 'cursor':'not-allowed'});
        $('.yearPrev').css({'border':'solid 1px #a1906282', 'cursor':'not-allowed'});
     } else if ($('#drop-down-year').val() == $('.filter-list li').eq(0).text()){ 
        $('.yearNext img').css({"opacity": 0.5, 'cursor':'not-allowed'});
        $('.yearNext').css({'border':'solid 1px #a1906282', 'cursor':'not-allowed'});
     } else {
        $('.yearPrev img').css({"opacity": 1, 'cursor':'pointer'});
        $('.yearPrev').css({'border':'solid 1px  #a19062', 'cursor':'pointer'});
        $('.yearNext img').css({"opacity": 1, 'cursor':'pointer'});
        $('.yearNext').css({'border':'solid 1px  #a19062', 'cursor':'pointer'});
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
       // 相应的年份进行递减，最低不能低于近十年的最低年份
       $('.yearPrev').click(function () {
        var currentYear = $('.drop-down input').val();
        var yearsDom = $(".filter-list li a");
        var newYear;
        var newYears = $('.drop-down input').val();
        var keyword = $('#filter_search').val()
        yearsDom.each((index, e) => {
            if (e.innerHTML === currentYear && yearsDom[index + 1]) {
                newYear = yearsDom[index + 1].innerHTML;
            }
        })
        if (!newYear) {
            return
        }
       if (newYears === '全部') {
            newYears = Number($(".filter-list li:eq(1)").text())
       } else {
            newYears -= 1
       }
       $('#drop-down-year').val(newYears)
        if (location.pathname.indexOf('/zh-hans') != -1) {
            filter_search(newYears, keyword, 1);
        }
        else if (location.pathname.indexOf('/zh-hant') != -1) {
             filter_search(newYears, keyword, 1);
            $('#drop-down-year').val(newYear)
        }
        $('.yearNext img').css({"opacity": 1, 'cursor':'pointer'});
        $('.yearNext').css({'border':'solid 1px  #a19062', 'cursor':'pointer'});
         // 判断当前时间小于或等于当前集团新闻中心的最低年份，该按钮则显示禁用状态
        if ($('#drop-down-year').val() == $('.drop-down li').last().text()) {
            $('.yearPrev img').css({"opacity": 0.5, 'cursor':'not-allowed'});
            $('.yearPrev').css({'border':'solid 1px #a1906282', 'cursor':'not-allowed'});
        } else {
            $('.yearPrev img').css({"opacity": 1, 'cursor':'pointer'});
            $('.yearPrev').css({'border':'solid 1px  #a19062', 'cursor':'pointer'});
        }
    })
    // 判断当前时间小于或等于当前集团新闻中心的最低年份，该按钮则显示禁用状态
    // if ($('#drop-down-year').val() == $('.drop-down li').last().text()) {
    //     $('.yearPrev img').css({"opacity": 0.5, 'cursor':'not-allowed'});
    //     $('.yearPrev').css({'border':'solid 1px #a1906282', 'cursor':'not-allowed'});
    // } else {
    //     $('.yearPrev img').css({"opacity": 1, 'cursor':'pointer'});
    //     $('.yearPrev').css({'border':'solid 1px  #a19062', 'cursor':'pointer'});
    // }

    // 相应年份进行递增，最高不能超过最新的年份
    $('.yearNext').click(function () {
        if ($('#drop-down-year').val() === '全部') {
            return false;
        }
        var currentYear = $('.drop-down input').val();
        var yearsDom = $(".filter-list li a");
        var newYear;
        var newYears = $('.drop-down input').val();
        var keyword = $('#filter_search').val();
          if (newYears === $(".filter-list li:eq(1)").text()) {
            newYears = '全部';
            $('.yearNext img').css({"opacity": 0.5, 'cursor':'not-allowed'});
            $('.yearNext').css({'border':'solid 1px #a1906282', 'cursor':'not-allowed'});
          } else {
            newYears = Number(newYears) + 1;
            $('.yearPrev img').css({"opacity": 1, 'cursor':'pointer'});
            $('.yearPrev').css({'border':'solid 1px  #a19062', 'cursor':'pointer'});
          }
        if (newYears === $(".filter-list li:eq(0)").text()) {
            $('.yearNext img').css({"opacity": 0.5, 'cursor':'not-allowed'});
            $('.yearNext').css({'border':'solid 1px #a1906282', 'cursor':'not-allowed'});
            $('.yearPrev img').css({"opacity": 1, 'cursor':'pointer'});
            $('.yearPrev').css({'border':'solid 1px  #a19062', 'cursor':'pointer'});
        } 
        $('#drop-down-year').val(newYears)
        yearsDom.each((index, e) => {
            if (e.innerHTML === currentYear && yearsDom[index - 1]) {
                newYear = yearsDom[index - 1].innerHTML;
            }
        })
        if (!newYear) {
            return
        }

        if (location.pathname.indexOf('/zh-hans') != -1) {
             filter_search(newYears, keyword, 1);
            $('#drop-down-year').val(newYear) 
        }
        else if (location.pathname.indexOf('/zh-hant') != -1) {
             filter_search(newYears, keyword, 1);
            $('#drop-down-year').val(newYear) 
        }
    })
    // 判断当前时间大于或等于最新年份，则显示禁用状态
    // if ($('#drop-down-year').val() == myDate) {
    //     $('.yearNext img').css({"opacity": 0.5, 'cursor':'not-allowed'});
    //     $('.yearNext').css({'border':'solid 1px #a1906282', 'cursor':'not-allowed'});
    // } else {
    //     $('.yearNext img').css({"opacity": 1, 'cursor':'pointer'});
    //     $('.yearNext').css({'border':'solid 1px  #a19062', 'cursor':'pointer'});
    // }

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
     // 點擊下拉列表選中進行篩選
     $('.filter-list li').on('click', function () {
        var newYears = $(this).text();
        var keyword = $('#filter_search').val()
        $('#drop-down-year').val($(this).text())
         filter_search(newYears, keyword, 1);
         if ($('#drop-down-year').val() == $('.filter-list li').last().text()) {
            $('.yearPrev img').css({"opacity": 0.5, 'cursor':'not-allowed'});
            $('.yearPrev').css({'border':'solid 1px #a1906282', 'cursor':'not-allowed'});
            $('.yearNext img').css({"opacity": 1, 'cursor':'pointer'});
            $('.yearNext').css({'border':'solid 1px  #a19062', 'cursor':'pointer'});
         } else if ($('#drop-down-year').val() == $('.filter-list li').eq(0).text()){ 
            $('.yearPrev img').css({"opacity": 1, 'cursor':'pointer'});
            $('.yearPrev').css({'border':'solid 1px  #a19062', 'cursor':'pointer'});
            $('.yearNext img').css({"opacity": 0.5, 'cursor':'not-allowed'});
            $('.yearNext').css({'border':'solid 1px  #a19062', 'cursor':'not-allowed'});
         } else {
            $('.yearPrev img').css({"opacity": 1, 'cursor':'pointer'});
            $('.yearPrev').css({'border':'solid 1px  #a19062', 'cursor':'pointer'});
            $('.yearNext img').css({"opacity": 1, 'cursor':'pointer'});
            $('.yearNext').css({'border':'solid 1px  #a19062', 'cursor':'pointer'});
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
    var lan = '';
    if (location.pathname.indexOf('/zh-hans') != -1) {
        lan = '/zh-hans/'
    } else {
        lan = '/zh-hant/'
    } 
    function filter_search (newYear, keyword, page) {
        var newYear = $('.drop-down input').val();
        var keyword = $('#filter_search').val()
        $.ajax({
            url: lan + "post_release",
            method:'POST',
            data:{year: newYear, key: keyword },
            success: function (res) {
                var str = '';
                res.PostPreviews.forEach(item => {
                   item.cover_link === null ? str+= '':
                   str+= `
                      <p><a target='_blank'>${item.title}</a></p>
                   `
                })
                $('.release_content').empty().append(str)
                var Pagination_str = '';
                var pages_str = '';
                    res.Paging.Pages.forEach((item, index) => {
                        pages_str += `<a class="news-page ${index === page ?  'active' :''}" href="/${res.LanguageDisplay}/news?year=${res.Year}&page=${item.No}">${item.No}</a>`;
                    })
                    
                    Pagination_str = `
                    <a>
                        <img src="/static/imgs/last-page-botton.svg" class="first-page-button">
                    </a>
                    <a>
                        <img src="/static/imgs/next-page.svg" class="first-page">
                    </a>
                    ${pages_str}
                    <a>
                        <img src="/static/imgs/next-page.svg" class="next-page">
                    </a>
                    <a>
                        <img src="/static/imgs/last-page-botton.svg" class="last-page-button">
                    </a>`
                $('.release_content').empty().append(str);
                $('.news-page-wrapper').empty().append(Pagination_str)
            }
        })
    }
     // 分页按钮筛选
     $('.news-page-wrapper').on('click', '.news-page' ,function () {
        var newYears = $('.drop-down input').val();
        var keyword = $('#filter_search').val()
        $(this).addClass('active').siblings().removeClass('active');
        filter_search(newYears, keyword, Number($(this).text()));
    })
    // 首页
    $('.news-page-wrapper').on('click','.first-page-button' ,function () {
        var newYears = $('.drop-down input').val();
        var keyword = $('#filter_search').val()
        filter_search(newYears, keyword, 1);
    })
    // 上一页
    $('.news-page-wrapper').on('click','.first-page' ,function () {
        console.log($('.active').text())
        var page = Number($('.active').text());
        page <= 1 ? page = 1 : page -= 1
        // $()
        var newYears = $('.drop-down input').val();
        var keyword = $('#filter_search').val()
        filter_search(newYears, keyword, page);
    })
     // 下一页
     $('.news-page-wrapper').on('click','.next-page' ,function () {
        console.log($('.active').text())
        var page = Number($('.active').text());
        // page += 1;
        page >= $('.news-page-wrapper .news-page').length ? page = $('.news-page-wrapper .news-page').length : page += 1
        // $()
        var newYears = $('.drop-down input').val();
        var keyword = $('#filter_search').val()
        filter_search(newYears, keyword, page);
    })
    // 尾页
    $('.news-page-wrapper').on('click','.last-page-button' ,function () {
        var newYears = $('.drop-down input').val();
        var keyword = $('#filter_search').val()
        filter_search(newYears, keyword, $('.news-page').length);
    })
    // 输入框内容匹配进行模糊搜索   
    $('.search_btn').on('click', function() {
        filter_search();
    })
    $('#filter_search').change(function () {
        document.onkeydown = (function(e) {
            if(e.keyCode == 13){
                filter_search();
            }
        })
    })
});
