<?php

/* @var $this \yii\web\View */
/* @var $content string */
use app\manager\models\Menu;

$menus = (new Menu())->getMenu();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="Bootstrap Admin Template">
    <meta name="keywords" content="app, responsive, jquery, bootstrap, dashboard, admin">
    <title><?= $this->title ?></title>
    <link rel="stylesheet" href="/vendor/animate.css/animate.css">
    <link rel="stylesheet" href="/vendor/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/vendor/ionicons/css/ionicons.css">
    <!--<link rel="stylesheet" href="/vendor/blueimp-gallery/css/blueimp-gallery.css">
    <link rel="stylesheet" href="/vendor/blueimp-gallery/css/blueimp-gallery-indicator.css">
    <link rel="stylesheet" href="/vendor/blueimp-gallery/css/blueimp-gallery-video.css">-->
    <link rel="stylesheet" href="/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css">
    <link rel="stylesheet" href="/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">
    <!--<link rel="stylesheet" href="/vendor/rickshaw/rickshaw.css">-->
    <link rel="stylesheet" href="/vendor/select2/dist/css/select2.css">
    <link rel="stylesheet" href="/vendor/clockpicker/dist/bootstrap-clockpicker.css">
    <!--<link rel="stylesheet" href="/vendor/nouislider/distribute/nouislider.min.css">-->
    <link rel="stylesheet" href="/vendor/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css">
    <link rel="stylesheet" href="/vendor/summernote/dist/summernote.css">
    <link rel="stylesheet" href="/vendor/dropzone/dist/basic.css">
    <!--<link rel="stylesheet" href="/vendor/dropzone/dist/dropzone.css">-->
    <link rel="stylesheet" href="/vendor/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css">
    <!--<link rel="stylesheet" href="/vendor/jquery.bootgrid/dist/jquery.bootgrid.css">-->
    <link rel="stylesheet" href="/vendor/datatables/media/css/jquery.dataTables.css">
    <!--<link rel="stylesheet" href="/vendor/sweetalert/dist/sweetalert.css">-->
    <link rel="stylesheet" href="/vendor/loaders.css/loaders.css">
    <!-- <link rel="stylesheet" href="/vendor/ng-material-floating-button/mfb/dist/mfb.css">-->
    <link rel="stylesheet" href="/vendor/material-colors/dist/colors.css">
    <link rel="stylesheet" href="/css/app.css?aa2222a=233">
    <link rel="stylesheet" href="/css/app-common.css?aa2222a=233">
    <link rel="stylesheet" href="/css/main.css">
</head>
<body class="theme-1">
<script src="/vendor/modernizr/modernizr.custom.js"></script>
<script src="/vendor/jquery/dist/jquery.js"></script>
<script src="/vendor/bootstrap/dist/js/bootstrap.js"></script>
<script src="/vendor/jquery.browser/dist/jquery.browser.js"></script>
<script src="/vendor/material-colors/dist/colors.js"></script>
<script src="/vendor/bootstrap-filestyle/src/bootstrap-filestyle.js"></script>
<script src="/vendor/flot/jquery.flot.js"></script>
<script src="/vendor/flot/jquery.flot.categories.js"></script>
<script src="/vendor/flot-spline/js/jquery.flot.spline.js"></script>
<script src="/vendor/flot.tooltip/js/jquery.flot.tooltip.js"></script>
<script src="/vendor/flot/jquery.flot.resize.js"></script>
<script src="/vendor/flot/jquery.flot.pie.js"></script>
<script src="/vendor/flot/jquery.flot.time.js"></script>
<script src="/vendor/sidebysideimproved/jquery.flot.orderBars.js"></script>
<script src="/vendor/ika.jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="/vendor/ika.jvectormap/jquery-jvectormap-us-mill-en.js"></script>
<script src="/vendor/ika.jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="/vendor/jquery.easy-pie-chart/dist/jquery.easypiechart.js"></script>
<script src="/vendor/screenfull/dist/screenfull.js"></script>
<script src="/vendor/sparkline/index.js"></script>
<script src="/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script src="/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.zh-CN.js"></script>
<script src="/vendor/jquery-knob/js/jquery.knob.js"></script>
<script src="/vendor/d3/d3.js"></script>
<script src="/vendor/rickshaw/rickshaw.js"></script>
<script src="/vendor/jquery-validation/dist/jquery.validate.js"></script>
<script src="/vendor/jquery-validation/dist/additional-methods.js"></script>
<script src="/vendor/jquery.steps/build/jquery.steps.js"></script>
<script src="/vendor/select2/dist/js/select2.js"></script>
<script src="/vendor/clockpicker/dist/bootstrap-clockpicker.js"></script>
<!--<script src="/vendor/nouislider/distribute/nouislider.js"></script>
<script src="/vendor/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js"></script>
<script src="/vendor/summernote/dist/summernote.js"></script>
<script src="/vendor/dropzone/dist/dropzone.js"></script>
<script src="/vendor/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.js"></script>
<script src="/vendor/moment/min/moment-with-locales.js"></script>
<script src="/vendor/gmaps/gmaps.js"></script>
<script src="/vendor/jquery.bootgrid/dist/jquery.bootgrid.js"></script>
<script src="/vendor/jquery.bootgrid/dist/jquery.bootgrid.fa.js"></script>
<script src="/vendor/datatables/media/js/jquery.dataTables.js"></script>
<script src="/vendor/nestable/jquery.nestable.js"></script>
<script src="/vendor/sweetalert/dist/sweetalert-dev.js"></script>
<script src="/vendor/masonry/dist/masonry.pkgd.js"></script>
<script src="/vendor/imagesloaded/imagesloaded.pkgd.js"></script>
<script src="/vendor/loaders.css/loaders.css.js"></script>
<script src="/vendor/blueimp-gallery/js/blueimp-helper.js"></script>
<script src="/vendor/blueimp-gallery/js/blueimp-gallery.js"></script>
<script src="/vendor/blueimp-gallery/js/blueimp-gallery-fullscreen.js"></script>
<script src="/vendor/blueimp-gallery/js/blueimp-gallery-indicator.js"></script>
<script src="/vendor/blueimp-gallery/js/blueimp-gallery-video.js"></script>
<script src="/vendor/blueimp-gallery/js/blueimp-gallery-vimeo.js"></script>
<script src="/vendor/blueimp-gallery/js/blueimp-gallery-youtube.js"></script>
<script src="/vendor/blueimp-gallery/js/jquery.blueimp-gallery.js"></script>
<script src="/vendor/topojson/topojson.min.js"></script>
<script src="/vendor/datamaps/dist/datamaps.all.js"></script>-->
<script src="/js/app.js?time=201701"></script>
<script src="/js/jquery.form.js"></script>
<script src="/js/common.js"></script>
<script src="/vendor/layer/layer.js"></script>
<!--<script src="/js/main.js"></script>
<script language="javascript" type="text/javascript" src="/vendor/echart/echarts.min.js"></script>-->

<div class="layout-container">
    <header class="header-container">
        <nav>
            <ul class="visible-xs visible-sm">
                <li><a id="sidebar-toggler" href="#" class="menu-link menu-link-slide"><span><em></em></span></a></li>
            </ul>
            <ul class="hidden-xs">
                <li><a id="offcanvas-toggler" href="#" class="menu-link menu-link-slide"><span><em></em></span></a></li>
            </ul>
            <h2 class="header-title"><?= $this->title ?></h2>
            <ul class="pull-right">
                <!--<li><a id="header-search" href="#" class="ripple"><em class="ion-ios-search-strong"></em></a></li>-->
                <li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle has-badge ripple"><em
                            class="ion-person"></em></a>
                    <ul class="dropdown-menu dropdown-menu-right md-dropdown-menu">
                        <li><a href="/index"><em class="ion-home icon-fw"></em>首页</a></li>
                        <li role="presentation" class="divider"></li>
                        <li><a href="/user/logout"><em class="ion-log-out icon-fw"></em>退出</a></li>
                    </ul>
                </li>
                <!--<li><a id="header-settings" href="#" class="ripple"><em class="ion-gear-b"></em></a></li>-->
                <li><a href="#" data-toggle-fullscreen="" class="ripple"><em class="ion-arrow-expand"></em></a></li>
            </ul>
        </nav>
    </header>
    <aside class="sidebar-container">
        <div class="sidebar-header">
            <div class="pull-right pt-lg text-muted hidden"><em class="ion-close-round"></em></div>
            <a href="#" class="sidebar-header-logo"><img src="/img/logo.png" data-svg-replace="/img/logo.svg"
                                                         alt="Logo"><span
                    class="sidebar-header-logo-text">微信管理</span></a>
        </div>
        <div class="sidebar-content">
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="/index" class="ripple"><span class="pull-right nav-label"></span><span
                                class="nav-icon"><img src="" data-svg-replace="/img/icons/aperture.svg" alt="MenuItem"
                                                      class="hidden"></span><span>首页</span></a></li>
                    <?php foreach ($menus as $menu) { ?>
                        <li>
                            <a href="<?= $menu['level1']['href'] ?>" <?php if (isset($menu['level1']['target'])) { ?> target="<?= $menu['level1']['target'] ?>" <?php } ?>
                               class="ripple"><span class="pull-right nav-caret"><em
                                        class="ion-ios-arrow-right"></em></span><span
                                    class="pull-right nav-label"></span><span class="nav-icon"><img src=""
                                                                                                    data-svg-replace="/img/icons/<?= $menu['level1']['icon'] ?>"
                                                                                                    alt="MenuItem"
                                                                                                    class="hidden"></span><span><?= $menu['level1']['name'] ?></span></a>
                            <ul class="sidebar-subnav">
                                <?php foreach ($menu['level2'] as $mv) { ?>
                                    <li>
                                        <a href="<?= $mv['href'] ?>" <?php if (isset($mv['target'])) { ?> target="<?= $mv['target'] ?>" <?php } ?>
                                           class="ripple"><span
                                                class="pull-right nav-label"></span><span><?= $mv['name'] ?></span></a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                </ul>
            </nav>
        </div>
    </aside>
    <div class="sidebar-layout-obfuscator"></div>
    <main class="main-container">
        <section>
            <div class="loading hide">加载中。。。</div>
            <?= $content ?>
        </section>
    </main>
</div>
<div tabindex="-1" role="dialog" class="modal modal-top fade modal-search">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="pull-left">
                    <button type="button" data-dismiss="modal" class="btn btn-flat"><em
                            class="ion-arrow-left-c icon-24"></em></button>
                </div>
                <div class="pull-right">
                    <button type="button" class="btn btn-flat"><em class="ion-android-microphone icon-24"></em></button>
                </div>
                <form action="#" class="oh">
                    <div class="mda-form-control pt0">
                        <input type="text" placeholder="Search.." data-localize="header.SEARCH"
                               class="form-control header-input-search">
                        <div class="mda-form-control-line"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div tabindex="-1" role="dialog" class="modal-settings modal modal-right fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div data-dismiss="modal" class="pull-right clickable"><em class="ion-close-round text-soft"></em></div>
                <h4 class="modal-title"><span>Settings</span></h4>
            </div>
            <div class="modal-body">
                <div class="clearfix mb">
                    <div class="pull-left wd-tiny mb">
                        <div class="setting-color">
                            <label class="preview-theme-1">
                                <input type="radio" checked="checked" name="setting-theme" value="0"><span
                                    class="ion-checkmark-round"></span>
                                <div class="t-grid">
                                    <div class="t-row">
                                        <div class="t-col preview-header"></div>
                                        <div class="t-col preview-sidebar"></div>
                                        <div class="t-col preview-content"></div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="pull-left wd-tiny mb">
                        <div class="setting-color">
                            <label class="preview-theme-2">
                                <input type="radio" name="setting-theme" value="1"><span
                                    class="ion-checkmark-round"></span>
                                <div class="t-grid">
                                    <div class="t-row">
                                        <div class="t-col preview-header"></div>
                                        <div class="t-col preview-sidebar"></div>
                                        <div class="t-col preview-content"></div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="pull-left wd-tiny mb">
                        <div class="setting-color">
                            <label class="preview-theme-3">
                                <input type="radio" name="setting-theme" value="2"><span
                                    class="ion-checkmark-round"></span>
                                <div class="t-grid">
                                    <div class="t-row">
                                        <div class="t-col preview-header"></div>
                                        <div class="t-col preview-sidebar"></div>
                                        <div class="t-col preview-content"></div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="pull-left wd-tiny mb">
                        <div class="setting-color">
                            <label class="preview-theme-4">
                                <input type="radio" name="setting-theme" value="3"><span
                                    class="ion-checkmark-round"></span>
                                <div class="t-grid">
                                    <div class="t-row">
                                        <div class="t-col preview-header"></div>
                                        <div class="t-col preview-sidebar"></div>
                                        <div class="t-col preview-content"></div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="pull-left wd-tiny mb">
                        <div class="setting-color">
                            <label class="preview-theme-5">
                                <input type="radio" name="setting-theme" value="4"><span
                                    class="ion-checkmark-round"></span>
                                <div class="t-grid">
                                    <div class="t-row">
                                        <div class="t-col preview-header"></div>
                                        <div class="t-col preview-sidebar"></div>
                                        <div class="t-col preview-content"></div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="pull-left wd-tiny mb">
                        <div class="setting-color">
                            <label class="preview-theme-6">
                                <input type="radio" name="setting-theme" value="5"><span
                                    class="ion-checkmark-round"></span>
                                <div class="t-grid">
                                    <div class="t-row">
                                        <div class="t-col preview-header"></div>
                                        <div class="t-col preview-sidebar"></div>
                                        <div class="t-col preview-content"></div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="pull-left wd-tiny mb">
                        <div class="setting-color">
                            <label class="preview-theme-7">
                                <input type="radio" name="setting-theme" value="6"><span
                                    class="ion-checkmark-round"></span>
                                <div class="t-grid">
                                    <div class="t-row">
                                        <div class="t-col preview-header"></div>
                                        <div class="t-col preview-sidebar"></div>
                                        <div class="t-col preview-content"></div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="pull-left wd-tiny mb">
                        <div class="setting-color">
                            <label class="preview-theme-8">
                                <input type="radio" name="setting-theme" value="7"><span
                                    class="ion-checkmark-round"></span>
                                <div class="t-grid">
                                    <div class="t-row">
                                        <div class="t-col preview-header"></div>
                                        <div class="t-col preview-sidebar"></div>
                                        <div class="t-col preview-content"></div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="pull-left wd-tiny mb">
                        <div class="setting-color">
                            <label class="preview-theme-9">
                                <input type="radio" name="setting-theme" value="8"><span
                                    class="ion-checkmark-round"></span>
                                <div class="t-grid">
                                    <div class="t-row">
                                        <div class="t-col preview-header"></div>
                                        <div class="t-col preview-sidebar"></div>
                                        <div class="t-col preview-content"></div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                <hr>
                <p>
                    <label class="mda-checkbox">
                        <input id="sidebar-showheader" type="checkbox" checked=""><em class="bg-indigo-500"></em>Sidebar
                        header
                    </label>
                </p>
                <p>
                    <label class="mda-checkbox">
                        <input id="sidebar-showtoolbar" type="checkbox" checked=""><em class="bg-indigo-500"></em>Sidebar
                        toolbar
                    </label>
                </p>
                <p>
                    <label class="mda-checkbox">
                        <input id="sidebar-offcanvas" type="checkbox"><em class="bg-indigo-500"></em>Sidebar offcanvas
                    </label>
                </p>
                <hr>
                <p>Navigation icon</p>
                <p>
                    <label class="mda-radio">
                        <input type="radio" name="headerMenulink" value="menu-link-close"><em
                            class="bg-indigo-500"></em>Close icon
                    </label>
                </p>
                <p>
                    <label class="mda-radio">
                        <input type="radio" checked="" name="headerMenulink" value="menu-link-slide"><em
                            class="bg-indigo-500"></em>Slide arrow
                    </label>
                </p>
                <p>
                    <label class="mda-radio">
                        <input type="radio" name="headerMenulink" value="menu-link-arrow"><em
                            class="bg-indigo-500"></em>Big arrow
                    </label>
                </p>
                <hr>
                <button type="button" data-toggle-fullscreen="" class="btn btn-default btn-raised">Toggle fullscreen
                </button>
                <hr>
                <p>Change language</p>
                <div class="btn-group">
                    <button type="button" data-toggle="dropdown" class="btn btn-default btn-block btn-raised">English
                    </button>
                    <ul role="menu" class="dropdown-menu dropdown-menu-right animated fadeInUpShort">
                        <li><a href="#" data-set-lang="en">English</a></li>
                        <li><a href="#" data-set-lang="es">Spanish</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
