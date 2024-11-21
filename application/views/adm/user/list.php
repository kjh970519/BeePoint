<div>
    <div class="list">
        <table width="100%">
            <thead>
                <th>유저 번호</th>
                <th>타입</th>
                <th>아이디</th>
                <th>마지막 로그인 일시</th>
                <th>마지막 로그인 아이피</th>
                <th>생성일</th>
                <th>승인 여부</th>
                <th>
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add_admin">추가</button>
                </th>
            </thead>
            <tbody>
            <?  foreach ($lists AS $list) { ?>
                <tr>
                    <td class="text-center"><?=$list['idx']?></td>
                    <td><?=$list['type']?></td>
                    <td><?=$list['id']?></td>
                    <td><?=$list['last_login_at']?></td>
                    <td><?=$list['last_login_ip']?></td>
                    <td><?=$list['created_at']?></td>
                    <td class="text-center">
                        <label class="toggle-switch">
                            <input type="checkbox" onclick="_app.modAdminAccess(this)" data-idx="<?=$list['idx']?>" <?=($list['access_yn'])? "checked" : ""?> <?=($list['idx'] == 1)? "disabled" : ""?>>
                            <span class="slider"></span>
                        </label>
                    </td>
                    <td class="text-center"><button class="btn btn-sm btn-danger m-1" data-idx="<?=$list['idx']?>" onclick="_app.DelAdmin(this)" <?=($list['idx'] == 1)? "disabled" : ""?>>삭제</button></td>
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