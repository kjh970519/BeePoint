<div>
    <div class="list">
        <table width="100%">
            <thead>
                <th>포인트 은행명</th>
                <th>소유자</th>
                <th>최소 사용 포인트</th>
                <th>등급별 혜택</th>
                <th>생성일</th>
                <th>
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add_point_bank" data-type="add">추가</button>
                </th>
            </thead>
            <tbody>
            <?  foreach ($lists AS $list) { ?>
                <tr>
                    <td class="text-center"><?=$list['point_bank_nm']?></td>
                    <td><?=$list['admin_id']?></td>
                    <td class="text-center"><?=number_format($list['usage_point'])?></td>
                    <td>
                        <div class="benefits row">
                <?  $header_colors = ["primary", "warning", "success", "danger", "info"];
                    $benefits = json_decode($list['benefits'], true);
                    if (count($benefits) > 0) {
                        foreach ($benefits AS $idx => $benefit) { ?>
                            <div class="col-md-4">
                                <div class="card grade-card">
                                    <div class="card-header bg-<?=$header_colors[$idx]?> text-white">
                                        <?=$benefit['grade']?>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>적립률:</strong> <?=$benefit['acc_rate']?>%</p>
                                        <? if (count($benefit['coupon'])) {?>
                                        <p><strong>쿠폰:</strong> <?print_r($benefit['coupon'])?></p>
                                        <? } ?>
                                    </div>
                                </div>
                            </div>
                <?      }
                    } ?>
                        </div>
                    </td>
                    <td><?=$list['created_at']?></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-info m-1" data-all='<?=json_encode($list)?>' data-toggle="modal" data-target="#add_point_bank" data-type="mod">수정</button>
                        <button class="btn btn-sm btn-danger m-1" data-idx="<?=$list['idx']?>" onclick="_app.DelPointBank(this)">삭제</button>
                    </td>
                </tr>
            <?  } ?>
            </tbody>
        </table>
    </div>
    <div class="pagination"><?=$links?></div>
</div>

<div class="modal fade" id="add_point_bank" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">포인트 은행 추가</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="point_bank_nm" class="col-form-label">포인트 은행명:</label>
                        <input type="text" class="form-control" id="point_bank_nm" name="point_bank_nm" required>
                    </div>
                    <div class="form-group">
                        <label for="usage_point" class="col-form-label">최소 사용 가능 포인트:</label>
                        <input type="text" class="form-control" id="usage_point" name="usage_point" required>
                    </div>
                    <div class="benefits form-group">
                        <label class="col-form-label">등급별 혜택</label>
                        <button type="button" class="btn btn-sm btn-primary" onclick="_app.addBenefit()">추가</button>
                        <div class="benefits-group form-group ">
                            <label class="col-form-label" for="grade">등급명</label>
                            <input type="text" class="form-control" name="grades[]" required>
                            <span>&nbsp;/&nbsp;</span>
                            <label class="col-form-label" for="acc_rate">적립율</label>
                            <input type="text" class="form-control" name="acc_rates[]" min="1" max="100" required>
                            <button type="button" class="btn btn-sm btn-danger" onclick="_app.delBenefit(this)">제거</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"">닫기</button>
                <button type="button" class="btn btn-primary btn-reg" onclick="_app.AddPointBank(this, 'add')">등록</button>
            </div>
        </div>
    </div>
</div>

<script id="benefits_template" type="text/html">
    <div class="benefits-group form-group">
        <label class="col-form-label">등급명</label>
        <input type="text" class="form-control" name="grades[]" required>
        <span>&nbsp;/&nbsp;</span>
        <label class="col-form-label">적립률</label>
        <input type="text" class="form-control" name="acc_rates[]" min="1" max="100" required>
        <button type="button" class="btn btn-sm btn-danger" onclick="_app.delBenefit(this)">제거</button>
    </div>
</script>

<script>
    const onlyNumbers = ["usage_point", "acc_rates[]"];
    onlyNumbers.forEach(function(onlyNumber) {
        let max_number = 100;
        if (onlyNumber == 'usage_point') max_number = 10000000;
        let numberInputs = document.querySelectorAll(`input[name="${onlyNumber}"]`);
        numberInputs.forEach((input) => {
            input.addEventListener("input", function (e) {
                e.target.value = e.target.value.replace(/[^0-9]/g, "");
                const value = parseInt(e.target.value, 10);
                if (value > max_number) {
                    e.target.value = max_number;
                } else if (value <= 0) {
                    e.target.value = "";
                }
            });
        });
    });

    $('#add_point_bank').on('hidden.bs.modal', function() {
        if (_app.is_refresh) location.reload();
        $("#add_point_bank form")[0].reset();

        let benefits_cnt = $("#add_point_bank .benefits-group").length;
        if (benefits_cnt > 1) {
            for (var i=0; i < benefits_cnt - 1; i++) {
                $("#add_point_bank .benefits-group")[0].remove();
            }
        }
    });
    $('#add_point_bank').on('show.bs.modal', function(obj) {
        let type = $(obj.relatedTarget).data('type');
        let dt = $(obj.relatedTarget).data('all');
        if (type == "mod") {
            $("#add_point_bank .modal-title").html("포인트 은행 수정");
            $("#add_point_bank .btn-reg").html("수정");
            $("#add_point_bank .btn-reg").attr("onclick", `_app.AddPointBank(this, 'mod', ${dt.idx})`);

            $("#add_point_bank form input[name=point_bank_nm]").val(dt.point_bank_nm.replace("은행명: ", ""));
            $("#add_point_bank form input[name=usage_point]").val(dt.usage_point);

            let benefits = JSON.parse(dt.benefits);
            if (benefits.length > 1) {
                benefits.forEach(function(benefit, idx) {
                    if (idx > 0) {
                        _app.addBenefit();
                    }
                    $($("#add_point_bank .benefits-group")[idx]).children("input[name='grades[]']").val(benefit.grade);
                    $($("#add_point_bank .benefits-group")[idx]).children("input[name='acc_rates[]']").val(benefit.acc_rate);
                });
            }
            else {
                $($("#add_point_bank .benefits-group")[0]).children("input[name='grades[]']").val(benefits[0].grade);
                $($("#add_point_bank .benefits-group")[0]).children("input[name='acc_rates[]']").val(benefits[0].acc_rate);
            }
        }
        else {
            $("#add_point_bank .modal-title").html("포인트 은행 추가");
            $("#add_point_bank .btn-reg").html("추가");
            $("#add_point_bank .btn-reg").attr("onclick", "_app.AddPointBank(this)");
        }
    });
</script>