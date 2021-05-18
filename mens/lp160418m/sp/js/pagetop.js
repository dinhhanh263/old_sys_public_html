$(function() {
    var showFlug = false;
    var topBtn = $('#pageTop');
    topBtn.css('bottom', '-100px');
    var showFlug = false;
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            if (showFlug == false) {
                showFlug = true;
                topBtn.stop().animate({'bottom' : '0.5em'}, 200);
            }
        } else {
            if (showFlug) {
                showFlug = false;
                topBtn.stop().animate({'bottom' : '-100px'}, 200);
            }
        }
    });
    //スクロールしてトップ
    topBtn.click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 500);
        return false;
    });
});
