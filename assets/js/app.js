var _app = {};
$(document).ready(function() {
    _app = {
        is_touch: false,
        numbers: [0, 1, 0],
        init: function() {
            _app.eventHandler();
        },
        eventHandler: function() {
            $(".btn-point-add").on("click", _app.changePage);
            $(".btn-number").on("click", _app.inputNumber);
            $(".btn-enver").on("click", _app.requestAddPoint)
        },
        signIn: function() {
            if (_app.is_touch) return;
            _app.is_touch = true;
            event.preventDefault();
            var db = $("#signin_form").serializePost();
            if (!db.id || !db.password) {
                var t;
                if (!db.id) {
                    t = "id";
                }
                else if (!db.password) {
                    t = "password";
                }
                alertify.alert("알림", `${t.toUpperCase()}를 입력해주세요`,
                    function () {
                        setTimeout(function() {
                            $(`input[name=${t}]`).focus();
                        }, 0)
                    });
                _app.is_touch = false;
                return;
            }

            $.ajax({
                url: "Sign/SignIn",
                method: "POST",
                data: db,
                dataType: "JSON",
                success: function(obj) {
                    if (obj.status == 'ok') {
                        location.reload();
                    }
                    else {

                    }
                }
            });
            _app.is_touch = false;
        },
        initSwiper: function() {
            var swiper = new Swiper(".mySwiper", {
                centeredSlides: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                loop: true,
            });
        },
        changePage: function(obj) {
            if (_app.is_touch) return;
            _app.is_touch = true;

            if (obj.type) obj = this;

            let page = $(obj).closest('section').data('page');
            let target = $(obj).data('target');
            $(`.${page}`).addClass('displaynone');
            $(`.${target}`).removeClass('displaynone');

            _app.is_touch = false;
        },
        inputNumber: function(obj) {
            let v = $(this).data('value');
            switch (v) {
                case "del":
                    _app.numbers.pop();
                    $($(".input-number .number")[_app.numbers.length]).removeClass("fill");
                    $($(".input-number .number")[_app.numbers.length]).children('span').html($($(".input-number .number")[_app.numbers.length]).data('default'));
                    break;
                case "all-del":
                    _app.numbers = [];
                    break;
                default:
                    if (_app.numbers.length > 10) return;
                    _app.numbers.push(v);
                    $($(".input-number .number")[_app.numbers.length-1]).children('span').html(v);
            }
            _app.numbers.forEach(function(number, i) {
                $($(".input-number .number")[i]).addClass("fill");
            });
            if (_app.numbers.length > 3 && _app.numbers.length < 8) {
                $($(".input-number .separator")[0]).addClass('fill');
                $($(".input-number .separator")[1]).removeClass('fill');
            }
            else if (_app.numbers.length >= 8) {
                $($(".input-number .separator")[1]).addClass('fill');
            }
            else if (_app.numbers.length <= 3 && _app.numbers.length > 0) {
                $($(".input-number .separator")[0]).removeClass('fill');
                $($(".input-number .separator")[1]).removeClass('fill');
            }
            else if (_app.numbers.length < 1) {
                for (var i=0; i < $(".input-number .number").length; i++) {
                    $($(".input-number .number")[i]).children('span').html($($(".input-number .number")[i]).data('default'));
                    $(".input-number .fill").removeClass('fill');
                }
            }
        },
        requestAddPoint: function() {
            if (_app.is_touch) return;
            _app.is_touch = true;

            if (_app.numbers.length < 10) {
                alertify.alert("알림", "전화번호를 정확히 입력해주세요.");
                return;
            }
            else {
                $.ajax({
                    url: "Point/RequestAddPoint",
                    method: "POST",
                    data: {
                        mobile: _app.numbers
                    },
                    dataType: "JSON",
                    success: function(obj) {
                        if (obj.status == 'ok') {
                            // _app.changePage(this);

                            // _app.connectWs();
                        }
                    }.bind(this)
                });
            }

            _app.is_touch = false;
        },
        connectWs: function() {
            const socket = new WebSocket('ws://localhost:8080');
            socket.onopen = function() {
                console.log("Connected to WebSocket server");
            };
            socket.onmessage = function(event) {
                // 수신한 데이터를 원하는 방식으로 업데이트
                const data = event.data;
            };
            socket.onclose = function() {
                console.log("Disconnected from WebSocket server");
            };
        }
    }
    _app.init();
});

(function($) {
    $.fn.serializePost = function () {
        var data = {};
        var formData = this.serializeArray();
        $.each(formData, function (i, o) {
            var name = o.name;
            var value = o.value;
            var index = name.indexOf('[]');

            if (index > -1) {
                name = name.substring(0, index);
                if (!(name in data)) {
                    data[name] = [];
                }
                data[name].push(value);
            } else
                data[name] = value;
        });
        return data;
    };
}($));