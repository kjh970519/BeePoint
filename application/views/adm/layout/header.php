<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeePoint Admin</title>

    <link rel="stylesheet" href="<?=base_url('assets/css/admin/styles.css?v='.date('YmdHis')); ?>">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <!-- JavaScript -->
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/alertify.min.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/alertify.min.css"/>
    <!-- Default theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/themes/default.min.css"/>
    <!-- Semantic UI theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/themes/semantic.min.css"/>
    <!-- Bootstrap theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/themes/bootstrap.min.css"/>

    <script>
        window.base_url = "<?=base_url()?>";
    </script>
</head>
<body>
<div class="admin-container">
    <!-- 사이드 메뉴바 -->
    <aside class="sidebar">
        <h2>Admin Menu</h2>
        <input type="hidden" id="path" value="<?=$path?>">
        <div style="text-align: right;">
            <a href="<?=base_url('Admin/Logout')?>" style="color: #FFFFFF; text-decoration-line: blink;">로그아웃</a>
        </div>
        <ul class="menu">
            <li class="menu-item">
                <a onclick="_app.toggleSubmenu('submenu-dashboard')">Dashboard</a>
            </li>
            <? foreach ($categories AS $category) { ?>
            <li class="menu-item">
                <a onclick="_app.toggleSubmenu('submenu-<?=$category['category']?>')"><?=$category['category_nm']?></a>
                <ul class="submenu" id="submenu-<?=$category['category']?>">
                <? foreach ($category['sub_categories'] AS $sub_category) { ?>
                    <li><a href="<?=base_url($sub_category['path'])?>" data-path="<?=$sub_category['path']?>" data-category="<?=$category['category']?>"><?=$sub_category['category_nm']?></a></li>
                <? } ?>
                </ul>
            </li>
        <?
            }
        ?>
        </ul>
    </aside>

    <!-- 메인 콘텐츠 영역 -->
    <main class="content">
        <div class="top-area">
            <h2><?=@$path_nm?></h2>
        </div>

