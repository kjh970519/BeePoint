var _app = {};
$(document).ready(function() {
    _app = {
        is_touch: false,
        init: function() {
            _app.eventHandler();
        },
        eventHandler: function() {
            $(".btn-point-add").on("click", _app.changePage);
        },
        signIn: function() {
            if (!_app.is_touch) return;
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
                url: "User/SignIn",
                method: "POST",
                data: db,
                dataType: "JSON",
                success: function(obj) {
                    if (obj.status == 'ok') {
                        location.reload();
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
            if (!_app.is_touch) return;
            _app.is_touch = true;

            let page = $(obj.currentTarget).closest('section').data('page');
            let target = $(obj.currentTarget).data('target');
            $(`.${page}`).addClass('displaynone');
            $(`.${target}`).removeClass('displaynone');

            _app.is_touch = false;
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