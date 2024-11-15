var _app = {};
$(document).ready(function() {
    _app = {
        init: function() {
            _app.eventHandler();
        },
        eventHandler: function() {

        },
        login: function(obj) {
            event.preventDefault();
            let frm = $(obj).closest('form');
            $.ajax({
                url: frm.attr("action"),
                method: "POST",
                data: frm.serializePost(),
                dataType: "JSON",
                success: function(data) {
                    if (data.status == 'ok') {
                        location.reload();
                    }
                }
            });
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