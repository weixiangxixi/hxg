/**
 * Created by dreammuse on 2016/3/27.
 */
(function ($) {
    $.fn.rolling = function (options) {
        var defaults = {
            speed: 2000,
            autoplay: 5000,
            ajax: null,
        };
        var opts = $.extend(defaults, options);
        var element = this;
        var li_arr = this.find("li.num");
        var height = li_arr.height();
        var count = li_arr.length;
        li_arr.each(function () {
            var ay = '<cite style="top:0;">';
            for (var az = 0; az < 10; az++) {
                ay += '<em>' + az + "</em>"
            }
            ay += "</cite><i></i>";
            $(this).html(ay);
        });
        var rolling = function () {
            if (opts.ajax == null) return;
            $.getJSON(opts.ajax, function (json) {
                var at = json.count.toString();
                var le = at.length;
                var au = at.split("");
                var st = 0;
                element.find("cite").each(function (aB) {
                    if (count - aB > le) {
                        st++;
                        return true;
                    }
                    var az = parseInt(au[aB - st]);
                    $(this).animate({
                            top: "-" + height * az + "px"
                        },
                        {
                            queue: false,
                            duration: opts.speed
                        })
                })
            });
            setTimeout(function () {
                rolling()
            }, opts.autoplay);
        };
        rolling();
    }
})(jQuery)