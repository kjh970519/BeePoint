<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeePoint Admin</title>

    <link rel="stylesheet" href="<?=base_url('assets/css/admin/styles.css?v='.date('YmdHis')); ?>">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<div class="admin-container">
    <!-- 사이드 메뉴바 -->
    <aside class="sidebar">
        <h2>Admin Menu</h2>
        <ul class="menu">
            <li class="menu-item">
                <a href="#" onclick="_app.toggleSubmenu('submenu-dashboard')">Dashboard</a>
            </li>
            <? foreach ($categories AS $category) { ?>
            <li class="menu-item">
                <a href="#" onclick="_app.toggleSubmenu('submenu-<?=$category['category']?>')"><?=$category['category_nm']?></a>
                <ul class="submenu" id="submenu-<?=$category['category']?>">
                <? foreach ($category['sub_categories'] AS $sub_category) { ?>
                    <li><a href="#"><?=$sub_category['category_nm']?></a></li>
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
