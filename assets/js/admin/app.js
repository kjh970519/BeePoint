var _app = {};
$(document).ready(function() {
    _app = {
        is_refresh: false,
        init: function() {
            _app.eventHandler();
        },
        eventHandler: function() {
            _app.selCategory();
        },
        selCategory: function() {
            const categories = $(".menu-item .submenu a");
            const path = $("#path").val();
            for (var i=0; i < categories.length; i++) {
                if (path == $(categories[i]).data("path")) {
                    _app.toggleSubmenu(`submenu-${$(categories[i]).data("category")}`);
                }
            }
        },
        toggleSubmenu: function(id) {
            const submenu = document.getElementById(id);
            if (submenu.style.display === "block") {
                submenu.style.display = "none";
            } else {
                submenu.style.display = "block";
            }
        },
        modAdminAccess: function(obj) {
            let db = {
                idx: $(obj).data('idx'),
                checked: ($(obj).prop("checked"))? 1 : 0
            }

            $.ajax({
                url: `${window.base_url}Admin/ModAdminAccess`,
                method: "POST",
                data: db,
                dataType: "JSON",
                success: function(data) {
                    console.log(data);
                }
            });
        },
        AddAdmin: function(obj) {
            let db = $("#add_admin form").serializePost();

            if (!db.id) {
                alertify.alert("알림", "등록할 ID를 입력해주세요.");
            }

            $.ajax({
                url: `${window.base_url}Admin/AddAdmin`,
                method: "POST",
                data: db,
                dataType: "JSON",
                success: function(data) {
                    if (data.status == 'ok') {
                        alertify.alert("알림", "등록되었습니다.", function() { location.reload(); });
                    }
                    else {
                        alertify.alert("알림", data.msg);
                    }
                }
            });
        },
        DelAdmin: function(obj) {
            let db = {
                idx: $(obj).data('idx'),
            };
            alertify.confirm("알림", "삭제하시겠습니까?",
                function() {
                    $.ajax({
                        url: `${window.base_url}Admin/DelAdmin`,
                        method: "POST",
                        data: db,
                        dataType: "JSON",
                        success: function(data) {
                            if (data.status == 'ok') {
                                alertify.alert("알림", "삭제되었습니다.", function() { location.reload(); });
                            }
                            else {
                                alertify.alert("알림", data.msg);
                            }
                        }
                    });
                },
                function() {
                });
        },
        modStoreAccess: function(obj) {
            let db = {
                idx: $(obj).data('idx'),
                checked: ($(obj).prop("checked"))? 1 : 0
            }

            $.ajax({
                url: `${window.base_url}Admin/ModStoreAccess`,
                method: "POST",
                data: db,
                dataType: "JSON",
                success: function(data) {
                    if (data.status !== 'ok') {
                        alertify.alert("알림", data.msg);
                        $(obj).prop("checked", (db.checked)? "":"checked");
                    }
                }
            });
        },
        DelStore: function(obj) {
            let db = {
                idx: $(obj).data('idx'),
            };
            alertify.confirm("알림", "삭제하시겠습니까?",
                function() {
                    $.ajax({
                        url: `${window.base_url}Admin/DelStore`,
                        method: "POST",
                        data: db,
                        dataType: "JSON",
                        success: function(data) {
                            if (data.status == 'ok') {
                                alertify.alert("알림", "삭제되었습니다.", function() { location.reload(); });
                            }
                            else {
                                alertify.alert("알림", data.msg);
                            }
                        }
                    });
                },
                function() {
                });
        },
        addBenefit: function() {
            const benefits_html = $("#benefits_template").html();
            if ($("#add_point_bank .benefits-group").length > 4) {
                alertify.alert("알림", "혜택은 최대 5개까지 등록 가능합니다.");
                return;
            };
            $("#add_point_bank .benefits.form-group").append(benefits_html);
        },
        delBenefit: function(obj) {
            if ($("#add_point_bank .benefits-group").length < 2) {
                alertify.alert("알림", "혜택은 최소 1개 이상 등록해야합니다.");
                return;
            }
            $(obj).parent().remove();
        },
        AddPointBank: function(obj, type) {
            let db = $("#add_point_bank form").serializePost();
            if (!db.point_bank_nm || !db.usage_point) {
                var msg = "최소 사용 가능 포인트를 입력해주세요.";
                if (!db.point_bank_nm) {
                    msg = "등록할 포인트 은행명을 입력해주세요.";
                }
                alertify.alert("알림", msg);
                return;
            }
            let is_empty = false;
            db.acc_rates.forEach(function(acc_rate) {
                if (!acc_rate) {
                    is_empty = true;
                }
            });
            db.grades.forEach(function(grade) {
                if (!grade) {
                    is_empty = true;
                }
            });
            if (is_empty) {
                alertify.alert("알림", "등급명 및 적립률을 모두 입력해주세요.");
                return;
            }
            else {
                db.point_bank_nm = `은행명: ${db.point_bank_nm}`;
                db.type = type;
                $.ajax({
                    url: `${window.base_url}Admin/AddPointBank`,
                    method: "Post",
                    data: db,
                    dataType: "JSON",
                    success: function(data) {
                        if (data.status == 'ok') {
                            alertify.alert("알림", "등록되었습니다.", function() {
                                _app.is_refresh = true;
                                $("#add_point_bank form")[0].reset();
                            });
                        }
                        else {
                            alertify.alert("알림", data.msg);
                        }
                    }
                })
            }
        },
        DelPointBank: function(obj) {
            let db = {
                idx: $(obj).data('idx'),
            };
            alertify.confirm("알림", "삭제하시겠습니까?",
                function() {
                    $.ajax({
                        url: `${window.base_url}Admin/DelPointBank`,
                        method: "POST",
                        data: db,
                        dataType: "JSON",
                        success: function(data) {
                            if (data.status == 'ok') {
                                alertify.alert("알림", "삭제되었습니다.", function() { location.reload(); });
                            }
                            else {
                                alertify.alert("알림", data.msg);
                            }
                        }
                    });
                },
                function() {
                });
        },
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