<!doctype html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>BeePoint Admin LogIn</title>

    <link rel="stylesheet" href="<?=base_url('assets/css/admin/login/styles.css?v='.date('YmdHis')); ?>">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>

<div class="login-container">
    <h2>Admin Login</h2>
    <form action="../Admin/checkAccount" method="POST">
        <div class="form-group">
            <label for="id">아이디</label>
            <input type="text" id="id" name="id" value="<?=($admin_login_info['rememberId'])?$admin_login_info['id']:""?>" required>
        </div>
        <div class="form-group">
            <label for="password">비밀번호</label>
            <input type="password" id="password" name="pw" required>
        </div>
        <div class="checkbox-group">
            <label>
                <input type="checkbox" name="rememberId" <?=($admin_login_info['rememberId'])?"checked":""?>> 아이디 저장
            </label>
            <label>
                <input type="checkbox" name="autoLogin"> 자동 로그인
            </label>
        </div>
        <button type="submit" class="login-btn" onclick="_app.login(this)">Log In</button>
    </form>
</div>

    <script src="<?=base_url('assets/js/admin/login/app.js?v='.date('YmdHis')); ?>"></script>
</body>
</html>