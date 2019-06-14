new WOW().init();

$(function () {

    // 初次进入页面显示第一张页面

    $('#content_one').show().siblings().hide();
    $('.pagination_content li').eq(0).find('span').css({'background':'#a19062'});
    var num = 0;
    var mar_left = 0;
    // 点击下一步执行事件
    function chage_num(num) {
        num === 0 ? $('.prev').hide() : $('.prev').show();
        if (num === 10) {
            $('.success').show();
            $('.next').hide();
        } else {
            $('.success').hide()
            $('.next').show();
        }
        switch(num) {
            case 0:
            $('#content_one').show().siblings().hide();
            if (location.pathname.indexOf('/zh-hans') != -1) {
                $('.top_title').text('申请职位');
            } else {
                $('.top_title').text('申請職位');
            }
            break;
            case 1:
            $('#content_two').show().siblings().hide();
             if (location.pathname.indexOf('/zh-hans') != -1) {
                $('.top_title').text('介绍人资料');
            } else {
                $('.top_title').text('介紹人資料');
            }
            break;
            case 2:
            $('#content_three').show().siblings().hide();
             if (location.pathname.indexOf('/zh-hans') != -1) {
                $('.top_title').text('个人资料');
            } else {
                $('.top_title').text('個人資料');
            }
            break;
            case 3:
            $('#content_for').show().siblings().hide();
             if (location.pathname.indexOf('/zh-hans') != -1) {
                $('.top_title').text('学历信息');
            } else {
                $('.top_title').text('學歷信息');
            }
            break;
            case 4:
            $('#content_five').show().siblings().hide();
             if (location.pathname.indexOf('/zh-hans') != -1) {
                $('.top_title').text('专业资格');
            } else {
                $('.top_title').text('專業資格');
            }
            break;
            case 5:
            $('#content_six').show().siblings().hide();
             if (location.pathname.indexOf('/zh-hans') != -1) {
                $('.top_title').text('工作经验');
            } else {
                $('.top_title').text('工作經驗');
            }
            break;
            case 6:
            $('#content_seven').show().siblings().hide();
             if (location.pathname.indexOf('/zh-hans') != -1) {
                $('.top_title').text('语言能力及技能');
            } else {
                $('.top_title').text('語言能力及技能');
            }
            break;
            case 7:
            $('#content_eight').show().siblings().hide();
             if (location.pathname.indexOf('/zh-hans') != -1) {
                $('.top_title').text('家庭成员信息');
            } else {
                $('.top_title').text('家庭成員訊息');
            }
            break;
            case 8:
            $('#content_nine').show().siblings().hide();
             if (location.pathname.indexOf('/zh-hans') != -1) {
                $('.top_title').text('背景申报');
            } else {
                $('.top_title').text('背景申报');
            }
            break;
            case 9:
            $('#content_ten').show().siblings().hide();
             if (location.pathname.indexOf('/zh-hans') != -1) {
                $('.top_title').text('相关文件');
            } else {
                $('.top_title').text('相關文件');
            }
            break;
            case 10:
            $('#content_eleven').show().siblings().hide();
             if (location.pathname.indexOf('/zh-hans') != -1) {
                $('.top_title').text('得知招聘途径');
            } else {
                $('.top_title').text('得知招聘途径');
            }
            $('.pagination_content li b').hide();
            break;
        }
    }
    chage_num(0);
    // 点击下一步事件
    $('.next').on('click', function () {
        console.log($('.container div').length)
        num += 1;
        if (num >= 10  ) num = 10;
        chage_num(num)
        $('.pagination_content li').eq(num-1).find('b').show();
        $('.pagination_content li').eq(num).find('span').css({'background':'#a19062'});
    })
    // 点击上一步事件
    $('.prev').on('click', function () {
        num -= 1;
        if (num <= 0 ) num = 0;
        chage_num(num)
        $('.pagination_content li').eq(num).find('b').hide();
        $('.pagination_content li').eq(num + 1).find('span').css({'background':'#b5b5b6'});
    })
    // 页面数据请求
    $.ajax({
        url:"/zh-hant/getParam",
        type:"GET",
        success: function (data) {
            var Str_region = '';
            for (var i = 0; i < data.working_region.length; i++) {
                Str_region += `<option id='${data.working_region[i].id}'>${data.working_region[i].chinese_name}</option>`
            }
            var change_first_str = '', first_change = '';
            data.choice_id.forEach((item, index) => {
                change_first_str += `<option id='${item.id}'>${item.chinese_name}</option>`;
                console.log(item.jobs)
                // item.jobs.forEach((item1, index) => {
                //     // console.log(item1)
                //     // first_change += `<option id='${item1.parent_id}'>${item1.simple_chinese_name}</option>`;
                // })
            });

            $('.region').empty().append(Str_region);
            $('.change_first').empty().append(change_first_str);
            // ('.first_change').empty().append(first_change)
        },
        error: function () {
            console.log('error', error)
        }
    })
});
