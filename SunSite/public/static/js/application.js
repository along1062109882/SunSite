new WOW().init();

$(function () {
    var isHans = location.pathname.indexOf('/zh-hans') != -1;
    var paramDatas = {};

    $('body').on('change', 'select', function () {
        if ($(this).val() === '') {
            $(this).css('color', '#b5b5b6')
            return;
        } else {
            $(this).css('color', '#49443d')
        }
    })
    // 點擊表單驗證
    // 介绍人员工编号：
    $('body').on('input', '.input-number', function () {
        var c = $(this);
        var reg = /^[\d()\-\s]*$/;
        if ($(this).val() === '') {
            $('.input_number span').hide();
        } else {
            if (reg.test(c.val()) === false) {
                if (isHans) {
                    $('.write_text').text('介绍人员工编号只能输入数字！')
                } else {
                    $('.write_text').text('介紹人員工編號只能輸入數字！')
                }
                $('.input_number span').show();
                return;
            } else {
                $('.input_number span').hide()
            }
        }
    })
    // 电话号码(住宅) 
    $('body').on('input', '.input-phone', function () {
        var regexp = /^((0\d{2,3}-\d{7,8})|(1[3584]\d{9}))$/;
        if ($(this).val() === '') {
            $(this).siblings('.phone_text').hide()
        } else {
            if (regexp.test($(this).val()) === false) {
                if (isHans) {
                    $(this).siblings('.phone_text').text('请输入正确的电话格式（0934-3483888/ 15703448888)！')
                } else {
                    $(this).siblings('.phone_text').text('請輸入正確的電話格式（0934-3483888/ 15703448888）！')
                }
                $(this).siblings('.phone_text').show();
            } else {
                $(this).siblings('.phone_text').hide()
            }
        }
    })
    // 流動電話：
    $('body').on('input', '.input-phone-move', function () {
        var regexp = /^\+\d{2,3}-\d{7,20}$/g;
        if (regexp.test($(this).val()) === false) {
            if (isHans) {
                $(this).siblings('.phone_text').text('请输入正确的电话格式！')
            } else {
                $(this).siblings('.phone_text').text('請輸入正確的電話格式！')
            }
            $(this).siblings('.phone_text').show();
        } else {
            $(this).siblings('.phone_text').hide()
        }
        // if ($(this).val().indexOf('-') == -1 || $(this).val().indexOf('+') == -1){
        //     if (isHans) {
        //         $(this).siblings('.phone_text').text('请输入正确的电话格式！')
        //     } else {
        //         $(this).siblings('.phone_text').text('請輸入正確的電話格式！')
        //     }
        //     $(this).siblings('.phone_text').show();
        // } else {
        //     $(this).siblings('.phone_text').hide()
        // }
    })
    // 電郵地址
    $('body').on('input', '.input-email', function () {
        var regexp = /^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/;
       if ($(this).val() === '') {
            $(this).siblings('.email_text').hide();
       } else {
        if (regexp.test($(this).val()) === false) {
            if (isHans) {
                $(this).siblings('.email_text').text('请输入正确的电邮格式！');
            } else {
                $(this).siblings('.email_text').text('請輸入正確的電郵格式！');
            }
            $(this).siblings('.email_text').show();
        }else{
            $(this).siblings('.email_text').hide();
        }
       }
    }) 
    // 日期格式
    $('body').on('input', '.format_date', function () {
        var text_name = $(this).parent().parent().siblings('label').text()
        var regexp = /^(\d{2}\/)?(\d{2}\/)?\d{4}$/g;
        if ($(this).val() == '') {
            $(this).siblings('.birth_text').hide()
        } else {
            if (regexp.test($(this).val()) === false) {
                if (text_name.match('出生日期')) {
                    if (isHans) {
                        $(this).siblings('.birth_text').text('请输入正确的出生日期！')
                    } else {
                        $(this).siblings('.birth_text').text('請輸入正確的出生日期！')
                    }
                } else {
                    if (isHans) {
                        $(this).siblings('.birth_text').text('请输入正确的日期格式！')
                    } else {
                        $(this).siblings('.birth_text').text('請輸入正確的日期格式！')
                    }
                }
                $(this).siblings('.birth_text').show();
            } else {
                $(this).siblings('.birth_text').hide()
            }
        }
    })
    // 證件號碼
    $('body').on('input', '.format_card_number', function () {
        var regexp = /^\w+$/;
        if ($(this).val() === '') {
            $('.format-card-number span').hide()
        } else {
            if (regexp.test($(this).val()) === false) {
                if (isHans) {
                    $('.card_number_text').text('请输入正确的证件号码！')
                } else {
                    $('.card_number_text').text('請輸入正確的證件號碼！')
                }
                $('.format-card-number span').show();
            } else {
                $('.format-card-number span').hide()
            }
        }
    })
    // 這段代碼是用於 測試
    // $('body').on('change', '.in-service-select',function(){
    //     if (String($(this).val()) === '1') {
    //         $(this).closest('.block').after($('#relate-user-id').html());
    //     } else {
    //         $('.relate-user-id-block', $(this).closest('.multi-ul')).remove();
    //     }
    // })

    // 初始化多列表
    $('.multi-ul').each(function () {
        $multiUl = $(this);
        var ulId = $multiUl.data('id');
        var $multiTemplate = $('#' + ulId).html();
        var minNum = $multiUl.data('minNum') || 1;
        var $multiTemplates = ""

        while (minNum > 0) {
            $multiTemplates = $multiTemplates + $multiTemplate
            minNum--;
        }

        $('.multi-main', $multiUl).append($multiTemplates)
    });

    // 多列表添加
    $('.add').on('click', function () {
        var $multiUl = $(this).closest('.multi-ul');
        var ulId = $multiUl.data('id');
        var $multiLi = $('#' + ulId + ' .multi-li').clone();

        // 语言能力及技能因为是单选按钮所以要单独处理
        if (ulId === 'content_seven_ul') {
            var multiLiLen = $('.multi-main .multi-li', $multiUl).length;

            $('input[name^="other|"]', $multiLi).each(function () {
                $(this).attr('name', $(this).attr('name') + '|' + multiLiLen);
                $(this).attr('id', $(this).attr('id') + '|' + multiLiLen);
            })

            $('.radioLabel', $multiLi).each(function () {
                $(this).attr('for', $(this).attr('for') + '|' + multiLiLen);
            })
        }

        $('.multi-main', $multiUl).append($multiLi)
    })

    // 多列表删除
    $('.remove').on('click', function () {

        var $multiUl = $(this).closest('.multi-ul');
        var minNum = $multiUl.data('minNum') || 1;
        var multiLiLen = $('.multi-main .multi-li', $multiUl).length;

        if (multiLiLen > minNum) {
            $('.multi-main .multi-li', $multiUl).last().remove();
        }
    })

    // 初次进入页面显示第一张页面
    $('#content_one').show().siblings().hide();
    $('.pagination_content li').eq(0).find('span').css({ 'background': '#a19062' });
    var num = 0;
    var mar_left = 0;
    // 点击下一步执行事件
    function chage_num(num) {
        // 為了每次點擊頁面，頁面重新回到初始位置‘
        $("html,body").animate({ scrollTop: 0 }, 10);
        num === 0 ? $('.prev').hide() : $('.prev').show();
        if (num === 10) {
            $('.success').show();
            $('.next').hide();
        } else {
            $('.success').hide()
            $('.next').show();
        }
        switch (num) {
            case 0:
                $('#content_one').show().siblings().hide();
                if (isHans) {
                    $('.top_title').text('申请职位');
                } else {
                    $('.top_title').text('申請職位');
                }
                break;
            case 1:
                $('#content_two').show().siblings().hide();
                if (isHans) {
                    $('.top_title').text('介绍人资料');
                } else {
                    $('.top_title').text('介紹人資料');
                }
                break;
            case 2:
                $('#content_three').show().siblings().hide();
                if (isHans) {
                    $('.top_title').text('个人资料');
                } else {
                    $('.top_title').text('個人資料');
                }
                break;
            case 3:
                $('#content_for').show().siblings().hide();
                if (isHans) {
                    $('.top_title').text('学历信息');
                } else {
                    $('.top_title').text('學歷信息');
                }
                break;
            case 4:
                $('#content_five').show().siblings().hide();
                if (isHans) {
                    $('.top_title').text('专业资格');
                } else {
                    $('.top_title').text('專業資格');
                }
                break;
            case 5:
                $('#content_six').show().siblings().hide();
                if (isHans) {
                    $('.top_title').text('工作经验');
                } else {
                    $('.top_title').text('工作經驗');
                }
                break;
            case 6:
                $('#content_seven').show().siblings().hide();
                if (isHans) {
                    $('.top_title').text('语言能力及技能');
                } else {
                    $('.top_title').text('語言能力及技能');
                }
                break;
            case 7:
                $('#content_eight').show().siblings().hide();
                if (isHans) {
                    $('.top_title').text('家庭成员信息');
                } else {
                    $('.top_title').text('家庭成員訊息');
                }
                break;
            case 8:
                $('#content_nine').show().siblings().hide();
                if (isHans) {
                    $('.top_title').text('背景申报');
                } else {
                    $('.top_title').text('背景申报');
                }
                break;
            case 9:
                $('#content_ten').show().siblings().hide();
                if (isHans) {
                    $('.top_title').text('相关文件');
                } else {
                    $('.top_title').text('相關文件');
                }
                break;
            case 10:
                $('#content_eleven').show().siblings().hide();
                if (isHans) {
                    $('.top_title').text('得知招聘途径');
                } else {
                    $('.top_title').text('得知招聘途径');
                }
                break;
        }
    }
    chage_num(0);
    // 列表略過
    var flag = false , isflag = false;
    $('.skip_five').click(function () {
        isflag = true;
        num = num + 1
        $('#content_five').hide();
        $('.pagination_content li').eq(num - 1).find('b').show();
        $('.pagination_content li').eq(num).find('span').css({ 'background': '#a19062' });
        chage_num(num)
    })
    $('.skip_six').click(function () {
        flag = true;
        num = num + 1
        $('#content_six').hide();
        $('.pagination_content li').eq(num - 1).find('b').show();
        $('.pagination_content li').eq(num).find('span').css({ 'background': '#a19062' });
        chage_num(num)
    })
    // 点击下一步事件
    $('.next').on('click', function () {
        // 頁面中所有 content_onlySign class 名的元素 用来做必填项的校验
        const current_show_el_content = $('.container .content_onlySign');

        let elvalue_isNull = true;

        // 判斷哪個 有這個 content_onlySign class 名的元素是顯示的
        current_show_el_content.each((index, item) => {
            // 判断 当前 item 元素 是否隐藏
            if ($(item).is(':visible')) {
                //获取当前页面的必填项
                const important_el = $(item).find('.important');
                important_el.each((key, el_item) => {
                    //判断 input value 是否为空
                    const current_el_val_is_null = $(el_item).siblings('.drop-down').val();
                    if (current_el_val_is_null === '') {
                        elvalue_isNull = false;
                        return
                    }

                    //判断下拉框是否选择
                    const current_el_select_is_null = $(el_item).siblings('.selectBoxRadioBtnBox').val();

                    if (
                        current_el_select_is_null === '' ||
                        (
                            Object.prototype.toString.call(current_el_select_is_null) == '[object Array]' &&
                            current_el_select_is_null.length === 0
                        )
                    ) {

                        elvalue_isNull = false;
                        return
                    }
                    /*current_el_select_is_null.each( (inde_key,op_elItem) =>{
                        if($(op_elItem).prop('selected')){
                            if($(op_elItem).html()  === '请选择' || $(op_elItem).html()  === '请選擇' ){
                                elvalue_isNull = false;
                                return
                            }
                        }
                    })*/
                })
            }
        })

        if ($('.phone_text').is(":visible")) {
            alert(isHans ? '请输入正確的格式！' : '请输入正确的格式！');
            return;
        } 
        if(!elvalue_isNull){
            alert(isHans ? '请填写必填项' : '請輸入必填項')
            return
        }

        num += 1;
        if (num >= 10) num = 10;

        chage_num(num)
        $('.pagination_content li').eq(num - 1).find('b').show();
        $('.pagination_content li').eq(num).find('span').css({ 'background': '#a19062' });

    })
    // 点击上一步事件
    $('.prev').on('click', function () {
        num -= 1;
        if (num <= 0) num = 0;
        chage_num(num)
        console.log(num)
        if (num === 5) {
           flag = false
        }
        if (num === 4) {
            isflag = false
         }
        $('.pagination_content li').eq(num).find('b').hide();
        $('.pagination_content li').eq(num + 1).find('span').css({ 'background': '#b5b5b6' });
    })
    // 页面数据请求

    function tranType(type, value) {
        switch (type) {
            case 'boolean':
                value = value === '' || value === '0' ? false : true;
                break;
            case 'int':
                value = Number(value);
                break;
        }
        return value;
    }

    var submiting = false;

    $('.success').on('click', function () {
        var submitFormObject = {};
        var submitFormData = new FormData($('#submitForm')[0]);
        var multiple = {};

        for (var pair of submitFormData.entries()) {
            var name = pair[0];
            var value = pair[1]
            var isArr = name.indexOf('.') !== -1;
            var isOther = name.indexOf('|') !== -1;
            if (!isArr && !isOther) {

                // 需要强转类型
                if (name.indexOf('~') !== -1) {
                    var names = name.split('~');
                    name = names[0];

                    value = tranType(names[1], value);
                }

                if (name === 'working_region') {
                    submitFormObject[name] = submitFormData.getAll(name)
                } else {
                    submitFormObject[name] = value
                }

            } else if (isArr) {
                var names = name.split('.');

                var muls = multiple[names[0]] || {}
                muls[names[1]] = submitFormData.getAll(name);

                multiple[names[0]] = muls;
            }
        }

        // 数组对象
        for (var item in multiple) {
            var arr = [];
            var keys = Object.keys(multiple[item]);
            if (item == 'applicant_profile_section_v2_work_experience_items') {
                if (flag) {
                    submitFormObject[item] = [];
                    continue;
                }
            }
            if (item == 'applicant_profile_section_v2_professional_items') {
                if (isflag) {
                    submitFormObject[item] = [];
                    continue;
                }
            }
            for (var i = 0; i < multiple[item][keys[0]].length; i++) {
                var itemObj = {};

                for (var key = 0; key < keys.length; key++) {
                    var name = keys[key];
                    var value = multiple[item][keys[key]][i];


                    // 需要强转类型
                    if (name.indexOf('~') !== -1) {
                        var names = name.split('~');
                        name = names[0];

                        value = tranType(names[1], value);
                    }

                    itemObj[name] = value;
                }

                // 工作经验写死至xx的值
                if (item == 'applicant_profile_section_v2_work_experience_items') {
                    itemObj['work_experience_to_key'] = 'left'
                    
                } else if (item == 'applicant_profile_section_v2_family_member_items') {

                    itemObj['input_type'] = 'manual_input';
                }

                arr.push(itemObj);
            }

            submitFormObject[item] = arr;
        }

        // 語言能力(电脑与技能)
        var applicant_language_skill_items = [
            {
                key: 'chinese',
                name: '中文',
                writing: submitFormData.get('chinese|writing'),
            },
            {
                key: 'contanese',
                name: '廣東話',
                speaking: submitFormData.get('contanese|speaking'),
                listening: submitFormData.get('contanese|listening'),
            },
            {
                key: 'mandarin',
                name: '普通話',
                speaking: submitFormData.get('mandarin|speaking'),
                listening: submitFormData.get('mandarin|listening'),
            },
            {
                key: 'english',
                name: '英語',
                speaking: submitFormData.get('english|speaking'),
                listening: submitFormData.get('english|listening'),
                writing: submitFormData.get('english|writing'),
            },
        ]

        // 語言能力 其他
        var multiLiLen = $('.multi-ul[data-id="content_seven_ul"] .multi-li').length;

        for (var i = 0; i < multiLiLen; i++) {
            var stuff = i === 0 ? '' : '|' + i;

            if (submitFormData.get('other|name' + stuff)) {
                applicant_language_skill_items.push({
                    name: submitFormData.get('other|name' + stuff),
                    listening: submitFormData.get('other|listening' + stuff),
                    speaking: submitFormData.get('other|speaking' + stuff),
                    writing: submitFormData.get('other|writing' + stuff),
                })
            }
        }

        submitFormObject['applicant_language_skill'] = {
            computer_skill: submitFormData.get('applicant_language_skill|computer_skill'),
            language_skill: submitFormData.get('applicant_language_skill|language_skill'),
            applicant_language_skill_items: applicant_language_skill_items,
        }

        // 招聘途径
        submitFormObject['recruitment_route'] = {
            recruitment_route: submitFormData.getAll('recruitment_route|recruitment_route'),
            paper_detail: submitFormData.get('recruitment_route|paper_detail'),
            recruitment_web_detail: submitFormData.get('recruitment_route|recruitment_web_detail'),
            social_networking_platform_detail: submitFormData.get('recruitment_route|social_networking_platform_detail'),
            school_web_detail: submitFormData.get('recruitment_route|school_web_detail'),
            others_detail: submitFormData.get('recruitment_route|others_detail'),
        }
        // console.log(JSON.stringify(submitFormObject))
        // console.log(submitFormObject)
        if (submiting) return
        submiting = true;

        $.ajax({
            url: "/zh-hant/jobCommit",
            type: "POST",
            dataType: "json",
            data: {
                data: JSON.stringify(submitFormObject)
                // console.log(submitFormObject)
            },
            success: function (data) {
                if (data.msg == 'success') {
                    alert(isHans ? '提交成功！' : '提交成功！')
                    // window.location.reload();
                } else {
                     alert(isHans ? '提交失败，请稍后重试！' : '提交失敗，請稍後重試！');
                }
                submiting = false;
            },
            error: function () {
                submiting = false;
                console.log('error', error)
            }
        })
    })

    $('#submitForm').on("change", ".selectBoxRadioBtnBoxFile", function (e) {
        var $fileParent = $(this).closest('.selectBoxRadioBtnBoxFileWrap');
        var file = e.target.files[0];

        var formData = new FormData();
        formData.append('file', file)

        $('.selectBoxRadioBtnBoxValue', $fileParent).val(file.name);

        $.ajax({
            url: "/zh-hant/jobUpload",
            type: "post",
            data: formData,
            contentType: false, // 注意这里应设为false
            processData: false,
            cache: false,
            success: function (data) {
                if (data && data.id) {
                    $('.selectBoxRadioBtnBoxFileId', $fileParent).val(data.id);
                }
            },
            error: function () {
                console.log('error', error)
            }
        })

    });

    $.ajax({
        url: "/zh-hant/getParam",
        type: "GET",
        success: function (data) {
            var allRegio = [];

            paramDatas = data;

            var Str_region = '';
            // 地区选择
            for (var i = 0; i < data.working_region.length; i++) {
                // 去掉全选
                if (data.working_region[i].key == 'all') {
                    continue
                }

                allRegio.push(data.working_region[i].chinese_name);

                Str_region += `<option id='${data.working_region[i].key}' value='${data.working_region[i].key}'>${data.working_region[i].chinese_name}</option>`
            }
            var change_first_str = '', second_str = '';
            // 第一选择
            data.choice_id.forEach((item, index) => {
                if (item.jobs) {
                    change_first_str += `<option value='${item.id}'>${(isHans ? item.simple_chinese_name : item.chinese_name)}</option>`;
                }
            });

            // 第二选择 选择部门
            data.choice_id_v2.forEach((item, index) => {
                if (item.jobs) {
                    second_str += `<option value='${item.id}'>${(isHans ? item.simple_chinese_name : item.chinese_name)}</option>`;
                }
            });
            // 离职通知期
            var wor_back = '';
            data.applicant_notice_date.forEach((item, index) => {
                wor_back += `<option value='${item.key}'>${(isHans ? item.simple_chinese_name : item.chinese_name)}</option>`;
            });
            // 婚宴状况
            var applicat = '';
            data.applicant_profile_marital_status_key.forEach((item, index) => {
                applicat += `<option value='${item.key}'>${(isHans ? item.simple_chinese_name : item.chinese_name)}</option>`;
            });
            // 性别
            var gender = '';
            data.gender.forEach((item, index) => {
                gender += `<option value='${item.key}'>${(isHans ? item.simple_chinese_name : item.chinese_name)}</option>`;
            });
            // 学历
            var study = '';
            data.diploma_degree_attained_key.forEach((item, index) => {
                study += `<option value='${item.key}'>${(isHans ? item.simple_chinese_name : item.chinese_name)}</option>`;
            });

            // 文件類型
            var attachment_type = '';
            data.profile_attachment_type_id.forEach((item, index) => {
                attachment_type += `<option value='${item.id}'>${(isHans ? item.simple_chinese_name : item.chinese_name)}</option>`;
            });

            var relation_ship = ''
            data.relation_ship.forEach((item, index) => {
                relation_ship += `<option value='${item.key}'>${(isHans ? item.simple_chinese_name : item.chinese_name)}</option>`;
            });

            var type_of_id = '';
            data.type_of_id.forEach((item, index) => {
                type_of_id += `<option value='${item.key}'>${(isHans ? item.simple_chinese_name : item.chinese_name)}</option>`;
            });

            $('.region').append(Str_region);
            $('.change_first').append(change_first_str);
            $('.second_str').append(second_str);
            $('.work_back').append(wor_back)
            $('.applicat').append(applicat)
            $('.gender').append(gender)
            $('.jieshao').append(change_first_str);
            $('.study').append(study);
            $('.deparment').append(change_first_str);
            $('.attachment_type_id').append(attachment_type);
            $('.relation_ship').append(relation_ship);
            $('.type_of_id').append(type_of_id);

            $('.region_one').multipleSelect({
                minimumCountSelected: 100,
                selectAll: false,
                formatAllSelected() {
                    return allRegio.join(', ');
                },
                placeholder: isHans ? '请选择' : '请選擇',
                width: '300px'
            });
        },
        error: function () {
            console.log('error', error)
        }
    })

    $('body').on('change', '.first_change_one', function () {
        var firstChangeOneId = $(this).val();
        var choiceIdLen = paramDatas.choice_id.length;
        var firstTwoStr = '<option value="" selected="selected">' + (isHans ? '请选择职位' : '请選擇職位') + '</option>';

        for (var i = choiceIdLen - 1; i >= 0; i--) {
            if (paramDatas.choice_id[i].id == firstChangeOneId) {
                paramDatas.choice_id[i].jobs.forEach(function (item) {
                    firstTwoStr += '<option value="' + item.id + '">' + (isHans ? item.simple_chinese_name : item.chinese_name) + '</option>'
                });
            }
        }

        $('.change_first_one', $(this).closest('.content_box')).html(firstTwoStr).css('color', '#b5b5b6');
    })

    $('.second_change_one').on('change', function () {
        var firstChangeId = $(this).val();
        var choiceIdLen = paramDatas.choice_id_v2.length;
        var firstTwoStr = '<option value="" selected="selected">' + (isHans ? '请选择职位' : '请選擇職位') + '</option>';

        for (var i = choiceIdLen - 1; i >= 0; i--) {
            if (paramDatas.choice_id_v2[i].id == firstChangeId) {
                paramDatas.choice_id_v2[i].jobs.forEach(function (item) {
                    firstTwoStr += '<option value="' + item.id + '">' + (isHans ? item.simple_chinese_name : item.chinese_name) + '</option>'
                });
            }
        }

        $('.change_second_one', $(this).closest('.content_box')).html(firstTwoStr).css('color', '#b5b5b6');
    })

});
