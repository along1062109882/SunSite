new WOW().init();
$(function () {
    // 进行页面分类展示 Tab 切换
    $('.grand_car').show().siblings('.grand_container').hide();
    $('.grand_nav li').eq(0).find('span').show()
    $('.grand_nav li').on('click', function () {
        if ($(this).index() === 0) {
            $('.grand_car').show().siblings('.grand_container').hide();
            $(this).addClass('grand_active').siblings().removeClass('grand_active')
        } else if (($(this).index() === 1)) {
            $('.grand_ten').show().siblings('.grand_container').hide();
            $(this).addClass('grand_active').siblings().removeClass('grand_active')

        } else {
            $('.grand_music').show().siblings('.grand_container').hide();
            $(this).addClass('grand_active').siblings().removeClass('grand_active')
        }
    })
    $('#slide_car').click(function () {
        $(this).addClass('swiper_active')
        $('#slide_ten').removeClass('swiper_active')
        $('#slide_music').removeClass('swiper_active')
        $('.grand_car').show().siblings('.grand_container').hide();
    })
    $('#slide_ten').click(function () {
        $('#slide_car').removeClass('swiper_active')
        $('#slide_music').removeClass('swiper_active')
        $(this).addClass('swiper_active')
        $('.grand_ten').show().siblings('.grand_container').hide();
    })
    $('#slide_music').click(function () {
        $('#slide_ten').removeClass('swiper_active')
        $('#slide_car').removeClass('swiper_active')
        $(this).addClass('swiper_active')
        $('.grand_music').show().siblings('.grand_container').hide();
    })
       
        // window.onresize=function(){  
            var num = 3;
            var htmlWidth = $(window).width();
            if(htmlWidth < 641) {
                num = 2
            // }
            // 中间大图区域添加轮播效果
        //   轮播图函数
        var mySwiper = new Swiper('.swiper-container', {//初始化Swiper
            stopOnLastSlide: true,
            disableOnInteraction: false,
            slidesPerView: num,
            navigation: {//前进后退
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            mousewheel: true,
            observer:true,
            observeParents:true
        });
    }
        // window.onresize=function(){
        //     new Swiper('.swiper-container')
        // }
});
// $('.swiper-wrapper .swiper-slide').on('click', function () {
//     alert(1)
//     $(this).addClass('swiper_active').siblings('.swiper-slide span').removeClass('')
//     if ($(this).attr("id") === 'slide_car') {
//         $('.grand_car').show().siblings('.grand_container').hide();
//     } else if ($(this).attr("id") === 'slide_ten') {
//         $('.grand_ten').show().siblings('.grand_container').hide();
//     } else {
//         $('.grand_music').show().siblings('.grand_container').hide();
//     }
// })


    
    

