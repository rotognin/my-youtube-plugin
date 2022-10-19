(function ($) {
    $(function() {
        MyYoutube.loadVideos(my_yt_ajax.url).then((value) => {
            MyYoutube.lists.forEach((item) => {
                item.callback(value, item.container, item.layout, item.limit, item.lang);
            })
        });
    });
})(jQuery);