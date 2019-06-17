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

        // 頁面中所有 content_onlySign class 名的元素 用来做必填项的校验
        const current_show_el_content = $('.container .content_onlySign');

        let elvalue_isNull = true;

        // 判斷哪個 有這個 content_onlySign class 名的元素是顯示的
        current_show_el_content.each((index ,item) => {
            // 判断 当前 item 元素 是否隐藏
            if($(item).is(':visible')) {
                //获取当前页面的必填项
                const important_el = $(item).find('.important');
                important_el.each((key,el_item)=>{
                    //判断 input value 是否为空
                    const current_el_val_is_null = $(el_item).siblings('.drop-down').val();
                    if(current_el_val_is_null === ''){
                        elvalue_isNull = false;
                        return
                    }

                    //判断下拉框是否选择
                    const current_el_select_is_null = $(el_item).siblings('.selectBoxRadioBtnBox').children();
                    current_el_select_is_null.each( (inde_key,op_elItem) =>{
                        if($(op_elItem).prop('selected')){
                            if($(op_elItem).html()  === '请选择' || $(op_elItem).html()  === '请選擇' ){
                                elvalue_isNull = false;
                                return
                            }
                        }
                    })
                })

            }
        })
        
        if(!elvalue_isNull){
            alert('请填写必填项')
            return
        }




        // console.log($('.container div').length)
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
            console.log('aaaa',data.choice_id)
            var Str_region = '';
            // 地区选择
            for (var i = 0; i < data.working_region.length; i++) {
                Str_region += `<option id='${data.working_region[i].id}'>${data.working_region[i].chinese_name}</option>`
            }
            var change_first_str = '', first_change = '', second_str = '';
            // 第一选择
            data.choice_id.forEach((item, index) => {
                change_first_str += `<option id='${item.id}'>${item.chinese_name}</option>`;
                console.log(item.jobs)
            });
           var arr_change = [];
           data.choice_id.filter(item => {
               function fn() {
                    if (item.jobs !== undefined) {
                        return arr_change.push(item.jobs)
                    }
               }
               return fn();
           })
           var first_change = '';
           arr_change.forEach((item, index) => {
                item.forEach((item1, index)  => {
                    first_change += `<option id='${item1.id}'>${item1.chinese_name}</option>`
                })
           }) 
           console.log(arr_change)
            // 第二选择 选择部门
            data.choice_id_v2.forEach((item, index) => {
                second_str += `<option id='${item.id}'>${item.chinese_name}</option>`;
            });
            // 离职通知期
            var wor_back = '';
            data.applicant_notice_date.forEach((item, index) => {
                wor_back += `<option id='${item.key}'>${item.chinese_name}</option>`;
            });
            // 婚宴状况
            var applicat = '';
            data.applicant_profile_marital_status_key.forEach((item, index) => {
                applicat += `<option id='${item.key}'>${item.chinese_name}</option>`;
            });
            // 性别
            var gender = '';
            data.gender.forEach((item, index) => {
                gender += `<option id='${item.key}'>${item.chinese_name}</option>`;
            });
            // 学历
            var study = '';
            data.diploma_degree_attained_key.forEach((item, index) => {
                study += `<option id='${item.key}'>${item.chinese_name}</option>`;
            });
            // 部门
            var deparment = '';
            data.choice_id.forEach((item, index) => {
                deparment += `<option id='${item.id}'>${item.chinese_name}</option>`;
            });
            $('.region').empty().append(Str_region);
            $('.change_first').append(change_first_str);
            $('.first_change').append(first_change);
            $('.second_str').append(second_str);
            $('.work_back').append(wor_back)
            $('.applicat').append(applicat)
            $('.gender').append(gender)
            $('.jieshao').append(change_first_str);
            // $('.jieshao_job').append()
            $('.study').append(study);
            $('.deparment').append(deparment);

        },
        error: function () {
            console.log('error', error)
        }
    })
});
