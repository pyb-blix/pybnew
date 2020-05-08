// uri segment
var uri_segment = (function(a) {
    if (a == "") return {};
    var b = {};
    for (var i = 0; i < a.length; ++i) {
        var p = a[i].split('=', 2);
        if (p.length == 1)
            b[p[0]] = "";
        else
            b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
    }
    return b;
})(window.location.search.substr(1).split('&'));
if (uri_segment['visited'] != 1) {
    var end = new Date(document.getElementById('expired_at').value).getTime();
    var _second = 1000;
    var _minute = _second * 60;
    var _hour = _minute * 60;
    var _day = _hour * 24;
    var timer;
    function showRemaining() {
        var d = new Date();
        var now = new Date(
            d.getUTCFullYear(),
            d.getUTCMonth(),
            d.getUTCDate(),
            d.getUTCHours(),
            d.getUTCMinutes(),
            d.getUTCSeconds()
            ).getTime();
        var distance = end - now;
        if (distance < 0) {
            clearInterval(timer);
            document.getElementById('countdown_2').innerHTML = 'Expired!';
            document.getElementById('countdown_1').innerHTML = 'Expired!';
            window.location.href = APP_URL + '/reservation/expire/' + $('#reservation_id').val();
            return;
        }
        var days = Math.floor(distance / _day);
        var hours = Math.floor((distance % _day) / _hour);
        var minutes = Math.floor((distance % _hour) / _minute);
        var seconds = Math.floor((distance % _minute) / _second);
        if ($("#countdown_2").length !== 0) {
            document.getElementById('countdown_2').innerHTML = hours + ':';
            document.getElementById('countdown_2').innerHTML += minutes + ':';
            document.getElementById('countdown_2').innerHTML += seconds + '';
            document.getElementById('countdown_1').innerHTML = hours + ':';
            document.getElementById('countdown_1').innerHTML += minutes + ':';
            document.getElementById('countdown_1').innerHTML += seconds + '';
        }
    }
    timer = setInterval(showRemaining, 1000);

    $(document).ready(function() {
        $("[id$='-trigger']").click(function() {
            var id = '#' + $(this).attr('id').replace('-trigger', '');
            $("#reserve_id").val(id);
            $(id).removeClass('d-none');
            $(id).addClass('d-block');
        });
    });
}