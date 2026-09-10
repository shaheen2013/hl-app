
<?php
include LANG . $_SESSION['userLang'] . '/statistics/app_sidebar.php';
?>
<div class="item">
    <div class="header"><?= $this->e($hotel_name) ?></div>
</div>

<div class="item">
    <div class="menu">
        <a href="<?=$this->baseUrl('hotel-edit-profile-details')?>"class="item"><i class="ui icon reply"></i><?php echo $lang['return dashboard']?></a>
    </div>
</div>

<div class="item">
    <div class="header"><?php echo $lang['statistics']?></div>

    <div class="menu">
        <a href="<?=$this->pathFor('stats_users')?>" class="<?=e($this->isActiveRoute('stats_users') ? 'active' : '') ?>  item has-loader" title="<?php echo $lang['clients stats']?>"><i class="ui icon bar chart"></i><?php echo $lang['clients']?></a>
        <a href="<?=$this->pathFor('stats_clicks')?>" class="<?=e($this->isActiveRoute('stats_clicks') ? 'active' : '') ?> item has-loader" title="<?php echo $lang['clicks stats']?>"><i class="ui icon mouse pointer"></i><?php echo $lang['clicks']?></a>
        <a href="<?=$this->pathFor('stats_reputation')?>" class="<?=e($this->isActiveRoute('stats_reputation') ? 'active' : '') ?> item has-loader" title="<?php echo $lang['reputation stats']?>"><i class="ui icon heartbeat"></i><?php echo $lang['reputation']?></a>
        <a href="<?=$this->pathFor('stats_engagement')?>" class="<?=e($this->isActiveRoute('stats_engagement') ? 'active' : '') ?> item has-loader" title="<?php echo $lang['engagement stats']?>"><i class="ui icon comments"></i><?php echo $lang['engagement']?></a>
        <a href="<?=$this->pathFor('stats_staff_engagement')?>" class="<?=e($this->isActiveRoute('stats_staff_engagement') ? 'active' : '') ?> item has-loader" title="<?php echo $lang['staffEngagement stats']?>"><i class="ui icon id badge outline"></i><?php echo $lang['staffEngagement']?></a>
        <?php if (empty($_SESSION['staff_id_hotel']) && !empty($_SESSION['c_logueado'])) {?>
            <a href="<?=$this->pathFor('comparison_table')?>" class="<?=e($this->isActiveRoute('comparison_table') ? 'active' : '') ?> item has-loader" title="<?php echo $lang['comparison stats']?>"><i class="ui icon balance scale"></i><?php echo $lang['comparison']?></a>
        <?php } ?>
        <a href="<?=$this->pathFor('stats_loyalty')?>" class="<?=e($this->isActiveRoute('stats_loyalty') ? 'active' : '') ?> item has-loader" title="<?php echo $lang['loyalty_stats']?>"><i class="ui icon id badge outline"></i><?php echo $lang['loyalty_stats']?></a>
    </div>
</div>

<div class="item">
    <div class="menu">
        <!--<div class="item"><i class="ui icon power"></i>Salir</div>-->
        <a href="<?=$this->baseUrl('app/logout')?>"class="item"><i class="ui icon power"></i><?php echo $lang['exit']?></a>
    </div>
</div>
