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
            // console.log($(".filter-list li:eq(1)").text())
            filter_search(newYears, keyword);
        }
        else if (location.pathname.indexOf('/zh-hant') != -1) {
             filter_search(newYears, keyword);
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
     console.log($('.drop-down li').last())
     
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
    //  if ($('#drop-down-year').val() == $('.drop-down li').last().text()) {
    //      $('.yearPrev img').css({"opacity": 0.5, 'cursor':'not-allowed'});
    //      $('.yearPrev').css({'border':'solid 1px #a1906282', 'cursor':'not-allowed'});
    //  } else {
    //      $('.yearPrev img').css({"opacity": 1, 'cursor':'pointer'});
    //      $('.yearPrev').css({'border':'solid 1px  #a19062', 'cursor':'pointer'});
    //  }
    //  if ($('#drop-down-year').val() == $('.drop-down li').first().text()) {
    //     $('.yearNext img').css({"opacity": 0.5, 'cursor':'not-allowed'});
    //     $('.yearNext').css({'border':'solid 1px #a1906282', 'cursor':'not-allowed'});
    // } else {
    //     $('.yearNext img').css({"opacity": 1, 'cursor':'pointer'});
    //     $('.yearNext').css({'border':'solid 1px  #a19062', 'cursor':'pointer'});
    // }
    // 相应年份进行递增，最高不能超过最新的年份
    $('.yearNext').click(function () {
        var currentYear = $('.drop-down input').val();
        var yearsDom = $(".filter-list li a");
        var newYear;
        var newYears = $('.drop-down input').val();
        var keyword = $('#filter_search').val();
        console.log(newYears)
          if (newYears === $(".filter-list li:eq(1)").text()) {
            newYears = '全部';
            $('.yearNext img').css({"opacity": 0.5, 'cursor':'not-allowed'});
            $('.yearNext').css({'border':'solid 1px #a1906282', 'cursor':'not-allowed'});
          } else {
            newYears = Number(newYears) + 1;
          }
            if (newYears === $(".filter-list li:eq(0)").text()) {
                $('.yearNext img').css({"opacity": 0.5, 'cursor':'not-allowed'});
                $('.yearNext').css({'border':'solid 1px #a1906282', 'cursor':'not-allowed'});
                $('.yearPrev img').css({"opacity": 1, 'cursor':'pointer'});
                $('.yearPrev').css({'border':'solid 1px  #a19062', 'cursor':'pointer'});
           } else {
               
            //  $('.yearNext img').css({"opacity": 1, 'cursor':'pointer'});
            //  $('.yearNext').css({'border':'solid 1px  #a19062', 'cursor':'pointer'});
           }
        
        $('#drop-down-year').val(newYears)
        // 判断当前时间小于或等于当前集团新闻中心的最低年份，该按钮则显示禁用状态
    //    if (newYears == $('.filter-list li').eq(0).text()) {
    //        alert(1)
          
    //        newYears = ''
    //    } else {
    //        $('.yearNext img').css({"opacity": 1, 'cursor':'pointer'});
    //        $('.yearNext').css({'border':'solid 1px  #a19062', 'cursor':'pointer'});
    //    }

        // if (newYears === $(".filter-list li:eq(0)").text()) {
        //     $('.yearNext img').css({"opacity": 0.5, 'cursor':'not-allowed'});
        //     $('.yearNext').css({'border':'solid 1px #a1906282', 'cursor':'not-allowed'});
        // } else {
        //     $('.yearNext img').css({"opacity": 1, 'cursor':'pointer'});
        //     $('.yearNext').css({'border':'solid 1px  #a19062', 'cursor':'pointer'});
        // }
    
        yearsDom.each((index, e) => {
            if (e.innerHTML === currentYear && yearsDom[index - 1]) {
                newYear = yearsDom[index - 1].innerHTML;
            }
        })
        if (!newYear) {
            return
        }

        if (location.pathname.indexOf('/zh-hans') != -1) {
            // window.location.href = "/zh-hans/news?year=" + newYear;
             filter_search(newYears, keyword);
            $('#drop-down-year').val(newYear) 
        }
        else if (location.pathname.indexOf('/zh-hant') != -1) {
            // window.location.href = "/zh-hant/news?year=" + newYear;
             filter_search(newYears, keyword);
            $('#drop-down-year').val(newYear) 
        }
        // if ($('#drop-down-year').val() == "全部") {
        //     $('.yearNext img').css({"opacity": 0.5, 'cursor':'not-allowed'});
        //     $('.yearNext').css({'border':'solid 1px #a1906282', 'cursor':'not-allowed'});
        // } else {
        //     $('.yearNext img').css({"opacity": 1, 'cursor':'pointer'});
        //     $('.yearNext').css({'border':'solid 1px  #a19062', 'cursor':'pointer'});
        // }
    })
    // 判断当前时间大于或等于最新年份，则显示禁用状态
  
    // if ($('#drop-down-year').val() == myDate) {
    //     $('.yearNext img').css({"opacity": 0.5, 'cursor':'not-allowed'});
    //     $('.yearNext').css({'border':'solid 1px #a1906282', 'cursor':'not-allowed'});
    // } else {
    //     $('.yearNext img').css({"opacity": 1, 'cursor':'pointer'});
    //     $('.yearNext').css({'border':'solid 1px  #a19062', 'cursor':'pointer'});
    // }
    // 點擊下拉列表選中進行篩選
    $('.filter-list li').on('click', function () {
        var newYears = $('.drop-down input').val();
        var keyword = $('#filter_search').val()
        $('#drop-down-year').val($(this).text())
         filter_search(newYears, keyword);
    })
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
    function search(lan, years, keyword){
        $.ajax({
            type: 'POST',
            url: '/' + lan + '/post_news' ,
            data: {year: years, key: keyword},
            success: function (data) {
                console.log(data)
                // var must = new Mustache();
                Mustache.render('.news-container',data);
                $('.news-container').html(Mustache.render('.news-container',data))
            },
        });
    }
    function filter_search (newYear, keyword) {
       
        // if (location.pathname.indexOf('/zh-hans') != -1) {
        //     window.location.href = "/zh-hans/news?year=" + newYear + '&key=' + keyword;
        //     // search('zh-hans', newYear, keyword)
        // }
        // else if (location.pathname.indexOf('/zh-hant') != -1) {
        //     window.location.href = "/zh-hant/news?year=" + newYear + '&key=' + keyword;
        //     // search('zh-hant', newYear, keyword)
        // }
        var lan = '';
        if (location.pathname.indexOf('/zh-hans') != -1) {
            lan = '/zh-hans/'
        } else {
            lan = '/zh-hant/'
        }
        $.ajax({
            url: lan + "post_news",
            method:'POST',
            data:{year: newYear, key: keyword },
            success: function (res) {
                var str = '';
                res.PostPreviews.forEach(item => {
                    str += `
                    <div class="cell wow flipInY news-item">
                        <a href="/${res.LanguageDisplay}/${res.CurrentRootCategory.slug}/category/${res.CurrentCategorySlug}/${item.slug}">
                        <div>
                        <div class="ratio-16-9">
                            <img src="${item.cover_link === null ? '' :item.cover_link.url}" title="${item.cover_link === null ? '' :item.cover_link.description}" alt="${item.cover_link === null ? '' :item.cover_link.name}" srcset="${item.cover_link === null ? '' :item.cover_link.url} 2x, ${item.cover_link === null ? '' :item.cover_link.url} 3x">
                            <img src="/static/imgs/group.svg" title="${item.cover_link === null ? '' :item.cover_link.description}" alt="${item.cover_link === null ? '' :item.cover_link.name}" srcset="/static/imgs/group.svg 2x, /static/imgs/group.svg 3x">
                        </div>
                        <div class="news-info">
                            <h2 class="news-title">${item.title}</h2>
                            <p class="news-sketch">${item.excerpt}</p>
                            <span class="item-date">${item.date}</span>
                            <span class="go-more">${lan ==='/zh-hans' ? "继续阅读" : '繼續閱讀'}</span>
                        </div>
                        </div>
                    </a>
                    </div>`;
                });
                var Pagination_str = '';
                var pages_str = '';
                    res.Paging.Pages.forEach((item, index) => {
                        pages_str += `<a class="news-page ${index === 0 ?  'active' :''}" href="/${res.LanguageDisplay}/news?year=${res.Year}&page=${item.No}">${item.No}</a>`;
                    })
                    
                    Pagination_str = `
                    <a href="/${res.LanguageDisplay}/news?year=${res.Year}&page=1">
                        <img src="/static/imgs/last-page-botton.svg" class="first-page-button">
                    </a>
                    <a href="/${res.LanguageDisplay}/news?year=${res.Year}&page=${res.Paging.PreviousPage}">
                        <img src="/static/imgs/next-page.svg" class="first-page">
                    </a>
                    ${pages_str}
                    <a href="/${res.LanguageDisplay}/news?year=${res.Year}&page=${res.Paging.NextPage}">
                        <img src="/static/imgs/next-page.svg" class="next-page">
                    </a>
                    <a href="/${res.LanguageDisplay}/news?year=${res.Year}&page=${res.Paging.LastPage}">
                        <img src="/static/imgs/last-page-botton.svg" class="last-page-button">
                    </a>`
                $('.news-container').empty().append(str);
                $('.news-page-wrapper').empty().append(Pagination_str);
            }
        })
    }
    // 输入框内容匹配进行模糊搜索
    $('.search_btn').on('click', function() {
        var newYears = $('.drop-down input').val();
        var keyword = $('#filter_search').val()
        filter_search(newYears, keyword);
    })
    $('#filter_search').change(function () {
        var newYears = $('.drop-down input').val();
        var keyword = $('#filter_search').val()
        document.onkeydown = (function(e) {
            if(e.keyCode == 13){
                 filter_search(newYears, keyword);
            }
        })
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
