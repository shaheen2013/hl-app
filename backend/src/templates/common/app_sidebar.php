<?php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}
include LANG . $_SESSION['userLang'] . '/statistics/app_sidebar.php';
?>

<div class="ui vertical pointing menu secondary" id="side_bar">
    <div id="side_bar_menu">
        <a href="<?php echo $this->baseUrl('hotel-edit-profile-details') ?>"class="item"><i class="ui icon reply"></i><?php echo $lang['return dashboard']?></a>
        <div class="ui dropdown item link left pointing <?php echo e($this->isActiveGroup('/statistics') ? 'active-dropdown' : '') ?>">
            <i class="ui icon line chart"></i>
            <span><?php echo $lang['statistics']?></span>
            <i class="angle right icon"></i>
            <div class="menu">
                <a href="<?php echo $this->pathFor('stats_users') ?>"
                   class="<?php echo e($this->isActiveRoute('stats_users') ? 'active' : '') ?>  item has-loader"
                   title="<?php echo $lang['clients stats']?>"><?php echo $lang['clients']?></a>
                <a href="<?php echo $this->pathFor('stats_clicks') ?>"
                   class="<?php echo e($this->isActiveRoute('stats_clicks') ? 'active' : '') ?> item has-loader"
                   title="<?php echo $lang['clicks stats']?>"><?php echo $lang['clicks']?></a>
                <a href="<?php echo $this->pathFor('stats_reputation') ?>"
                   class="<?php echo e($this->isActiveRoute('stats_reputation') ? 'active' : '') ?> item has-loader"
                   title="<?php echo $lang['reputation stats']?>"><?php echo $lang['reputation']?></a>
                <a href="<?php echo $this->pathFor('stats_engagement') ?>"
                   class="<?php echo e($this->isActiveRoute('stats_engagement') ? 'active' : '') ?> item has-loader"
                   title="<?php echo $lang['engagement stats']?>"><?php echo $lang['engagement']?></a>
                <a href="<?php echo $this->pathFor('stats_staff_engagement') ?>"
                   class="<?php echo e($this->isActiveRoute('stats_staff_engagement') ? 'active' : '') ?> item has-loader"
                   title="<?php echo $lang['staffEngagement stats']?>"><?php echo $lang['staffEngagement']?></a>
                <?php if (empty($_SESSION['staff_id_hotel']) && !empty($_SESSION['c_logueado'])) { ?>
                    <a href="<?php echo $this->pathFor('comparison_table') ?>"
                       class="<?php echo e($this->isActiveRoute('comparison_table') ? 'active' : '') ?> item has-loader"
                       title="<?php echo $lang['comparison stats']?>"><?php echo $lang['comparison']?></a>
                <?php } ?>
                <a href="<?php echo $this->pathFor('stats_loyalty') ?>"
                   class="<?php echo e($this->isActiveRoute('stats_loyalty') ? 'active' : '') ?> item has-loader"
                   title="<?php echo $lang['loyalty_stats']?>"><?php echo $lang['loyalty_stats']?></a>

            </div>
        </div>
        <a href="<?php echo $this->baseUrl('app/logout') ?>"class="item"><i class="ui icon power"></i><?php echo $lang['exit']?></a>
    </div>
</div>
