var moveActived, msgStartY, msgMoveY, timer;

$(function () {
    var msgBox = $('#msg-box'),
        originalTop,
        outerH = msgBox.outerHeight(),
        msgCate = $('#msg-cate'),
        msgTime = $('#msg-time'),
        msgTitle = $('#msg-title'),
        msgDesc = $('#msg-desc');
       

    msgBox.on('touchstart touchmove touchend', function (e) {
        e.preventDefault();
        var type = e.type;
        switch (type) {
            case 'touchstart':
                moveActived = 0;
                clearTimeout(timer);
                msgStartY = e.originalEvent.touches[0].clientY;
                break;

            case 'touchmove':
                msgMoveY = e.originalEvent.touches[0].clientY;
                msgMove(msgBox, originalTop, outerH);
                break;

            case 'touchend':
                msgEnd(msgBox, originalTop, outerH);
                break;
        }
    });

});

function msgMove (msgBox, originalTop, outerH) {
    moveActived = 1;
    var minTop = 10 - outerH,
        dist = msgMoveY - msgStartY + originalTop;

    dist = dist > originalTop ? originalTop : (dist < minTop ? minTop : dist);

    // console.log(msgStartY, msgMoveY, dist);

    msgBox.css('top', dist + 'px');
}

function msgEnd (msgBox, originalTop, outerH) {
    var minTop = - outerH / 2,
        nowTop = parseInt(msgBox.css('top'));

    msgBox.css('top', originalTop + 'px');
    if (nowTop < minTop)
        msgBox.hide();
    else if (!moveActived) {
        var id =  msgBox.data('id'),
            url = msgBox.data('url');

        $.get('/index.php/mobile/message/readMsg/', {id : id});
        location.href = url;
    } else
        timer = setTimeout(function () {
            msgBox.animate({'top' : '-10rem'}, 500);
        }, 5000);

}