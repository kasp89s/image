<?php
    use yii\helpers;
?>

<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
<div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="/admin">«admin»</a>
</div>
<!-- /.navbar-header -->

<ul class="nav navbar-top-links navbar-right">
<!-- /.dropdown -->
<li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
    </a>
    <ul class="dropdown-menu dropdown-user">
        <li>
            <a href="<?= helpers\Url::toRoute(['logout']);?>"><i class="fa fa-sign-out fa-fw"></i> Выйти</a>
        </li>
    </ul>
    <!-- /.dropdown-user -->
</li>
<!-- /.dropdown -->
</ul>
<!-- /.navbar-top-links -->

<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li>
                <a href="<?= helpers\Url::toRoute(['translation/index']);?>"><i class="fa fa-fw"></i> Переводы</a>
            </li>
            <li>
                <a href="<?= helpers\Url::toRoute(['comment/index']);?>"><i class="fa fa-fw"></i> Комментарии</a>
            </li>
        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>
<!-- /.navbar-static-side -->
</nav>
