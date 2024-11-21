<div>
    <div class="list">
        <table width="100%">
            <thead>
            <th hidden></th>
            <th>상점 이름</th>
            <th>계약일</th>
            <th>만료일</th>
            <th>포인트 은행 이름</th>
            <th>승인 여부</th>
            <th>
                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add_admin">추가</button>
            </th>
            </thead>
            <tbody>
            <?  foreach ($lists AS $list) { ?>
                <tr>
                    <td class="text-center"><?=$list['store_nm']?></td>
                    <td><?=$list['contracted_at']?></td>
                    <td><?=$list['expiration_at']?></td>
                    <td><?=$list['point_bank_cd']?></td>
                    <td class="text-center">
                        <label class="toggle-switch">
                            <input type="checkbox" onclick="_app.modStoreAccess(this)" data-idx="<?=$list['idx']?>" <?=($list['access_yn'])? "checked" : ""?>>
                            <span class="slider"></span>
                        </label>
                    </td>
                    <td class="text-center"><button class="btn btn-sm btn-danger m-1" data-idx="<?=$list['idx']?>" onclick="_app.DelStore(this)">삭제</button></td>
                </tr>
            <?  } ?>
            </tbody>
        </table>
    </div>
    <div class="pagination"><?=$links?></div>
</div>

<div class="modal fade" id="add_admin" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">관리자 계정 추가</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="id" class="col-form-label">타입:</label>
                        <select class="form-control" id="type" name="type">
                            <option value="normal">normal</option>
                            <option value="admin">admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="id" class="col-form-label">ID:</label>
                        <input type="text" class="form-control" id="id" name="id" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
                <button type="button" class="btn btn-primary" onclick="_app.AddAdmin(this)">등록</button>
            </div>
        </div>
    </div>
</div>