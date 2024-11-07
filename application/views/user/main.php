<!doctype html>
<html lang="ko">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>BeePoint</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="<?=base_url('assets/css/styles.css'); ?>">

        <!-- alertify -->
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
        <!-- alertify -->
    </head>
    <body>
        <? if (!$this->session->userdata('user_id')) { ?>
        <section class="bg-container signin">
            <form id="signin_form">
                <div class="sec">
                    <div class="info">
                        <div class="id">
                            <input type="text" name="id" placeholder="ID" value="<?=$signin_info['check_save']?$signin_info['user_id']:"" ?>">
                        </div>
                        <div class="pw">
                            <input type="password" name="password" placeholder="PASSWORD">
                        </div>
                    </div>
                    <div class="btn-sec">
                        <div class="check-save">
                            <div>
                                <label for="check_auto"><input type="checkbox" id="check_auto" value="1" <?=$signin_info['check_auto']?"checked":"" ?> name="check_auto">자동로그인</label>
                            </div>
                            <div style="margin-left: 2rem;">
                                <label for="check_save"><input type="checkbox" id="check_save" value="1" <?=$signin_info['check_save']?"checked":"" ?> name="check_save">아이디 저장</label>
                            </div>
                        </div>
                        <button type="submit" class="btn-signin" onclick="_app.signIn()">로그인</button>
                    </div>
                </div>
            </form>
        </section>
        <? } else { ?>
        <section class="bg-container waiting" data-page="waiting">
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="<?=base_url('assets/imgs/1.jpg'); ?>">
                    </div>
                    <div class="swiper-slide">
                        <img src="<?=base_url('assets/imgs/2.jpg'); ?>">
                    </div>
                    <div class="swiper-slide">
                        <img src="<?=base_url('assets/imgs/3.jpg'); ?>">
                    </div>
                    <div class="swiper-slide">
                        <img src="<?=base_url('assets/imgs/4.jpg'); ?>">
                    </div>
                    <div class="swiper-slide">
                        <img src="<?=base_url('assets/imgs/5.jpg'); ?>">
                    </div>
                </div>
            </div>
            <div class="btn-point-add-area">
                <div>
                    <button class="btn-point-add" data-target="request">포인트 적립</button>
                </div>
            </div>
        </section>
        <section class="bg-container request displaynone" data-page="request">

        </section>
        <section class="bg-container processing displaynone" data-page="processing">

        </section>
        <section class="bg-container complete displaynone" data-page="complete">

        </section>
        <? } ?>
    </body>
</html>
<script src="<?=base_url('assets/js/app.js'); ?>"></script>

<? if ($this->session->userdata('user_id')) { ?>
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            _app.initSwiper();
        });
    </script>
<? } ?>