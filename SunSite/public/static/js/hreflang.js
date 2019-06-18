new WOW().init();

$(function () {
    var str = '';
    if (location.pathname.indexOf('/zh-hans') != -1) {
        str = window.location.href;
    } else if (location.pathname.indexOf('/zh-hant') != -1) {
        str = window.location.href;
    }
       var linkTag = $('<link href="' + str + '" hreflang="zh-Hant" />');
       var linkTags = $('<link href="' + str + '" hreflang="zh-Hans" />');
    $($('head')[0]).append(linkTag,linkTags);
})