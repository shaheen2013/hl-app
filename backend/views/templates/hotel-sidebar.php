<?php include LANG . $_SESSION['userLang'] . '/hotel-sidebar.php' ?>
<div id="sidebar-wrapper">
    <div id="user-wrapper" class="hotel_dropdown_sidebar">
        <div class="user-image-sb pull-left" >
                <img id="hotel-logo-sidebar" class="img-circle" width="50" height="50"
                     src="<?php echo(!empty($_SESSION['logoHotel']) ? imageSize('small', $_SESSION['logoHotel']) : DIR_IMG . 'img-placeholder.jpg') ?>"
                     alt="user image">
        </div>
        <p id="hotel-name-sidebar" class="pull-left"><?php echo $_SESSION['hotelName'];  ?></p>

        <?php if (empty($_SESSION['staff_logueado'])) { ?>
            <a href="<?php echo $urlTree['hotel-profile'] ?>" class="hasTooltip pull-right user-profile-access-btn"
                data-toggle="tooltip" data-placement="bottom" title="Access to your profile"><i id="profile-link"
                class="fa fa-cog fa-2x"></i></a>
        <?php } else { ?>
            <a href="<?php echo $urlTree['staff-profile'] ?>" class="hasTooltip pull-right user-profile-access-btn"
                data-toggle="tooltip" data-placement="bottom" title="Access to your profile"><i id="profile-link"
                class="fa fa-cog fa-2x"></i></a>
        <?php } ?>

    </div>

    <div class="list-group sidebar-hotel-menu mt2">
        <?php if (empty($_SESSION['staff_logueado'])) {
        ?>
            <a id="statistics" href="app/statistics/users" class="list-group-item"><i class="fa fa-signal"></i><span class="pl"><?php echo $slang['View statistics'] ?></span></a>
            <a href="<?php echo $urlTree['clients'] ?>" title="All Clients" class="list-group-item referrals-management-btn <?php echo $currentPage == 'clients-management' ? 'active' : ''?>"><i class="fa fa-users"></i><span class="pl"><?php echo $slang['Clients'] ?></span></a>
            <?php if (isIntegrationEnabled()) { // Solo si el INTEGRATIONS_ENABLE es TRUE?>
                <?php if (checkDatamatchActivated(array_get($_SESSION,'h_logueado'))) { // Solo si el DATAMATCH esta activado?>
                    <a href="datamatch" title="All datamatch" class="list-group-item referrals-management-btn <?php echo $currentPage == 'datamatch' ? 'active' : ''?>"><i class="fa fa-check-square"></i></i><span class="pl">Datamatch</span></a>
                <?php }?>
            <?php }?>
            <?php if (array_get($_SESSION,'hotel_activated') == 1){
                ?>
            <a href="<?php echo !empty($_SESSION['c_logueado']) ? $urlTree['hotel-gestion-ofertas'] . '/ref-chain/' : $urlTree['hotel-gestion-ofertas'] . '/ref/' ?>"
               title="Campaigns management" class="hasTooltip list-group-item campaigns-vouchers-btn <?php echo $currentPage == 'offer-management' ? 'active' : ''?>"
               data-toggle="tooltip" data-placement="right">
                <i class="rubies rubix2">rubies</i>
                <span class="pl"><?php echo $slang['Rewards campaigns'] ?></span>
            </a>

            <?php if (array_get($_SESSION, 'permisos.LY') == '1') { //SOLO APARECEN CON LY?>
                <?php if ($_SESSION['encuestas'] > 0) { //SOLO APARECE SI TIENE MÁS DE 0 ENCUESTAS?>
                    <a <?php echo(empty($onboarding) ? 'href="' . $urlTree['gestion-encuestas'] . '" title="Survey management" class="list-group-item gestion-encuestas-btn"' : 'title="Finish your onboarding before access this option" class="hasTooltip list-group-item gestion-encuestas-btn" data-toggle="tooltip" data-placement="right"') ?>><i
                                class="fa fa-check-square-o"></i><span
                                class="pl"><?php echo $slang['Satisfaction survey'] ?></span></a>
                <?php
        } else {
            ?>
                    <a href="<?php echo $urlTree['gestion-encuestas'] ?>" title="Survey management" class="list-group-item gestion-encuestas-btn"><i
                                class="fa fa-check-square-o"></i><span
                                class="pl"><?php echo $slang['Satisfaction survey'] ?></span></a>
                <?php
        } ?>
                <?php
        } ?>

            <?php if ($_SESSION['permisos']['satisfaction'] == '1') { //SOLO APARECEN CON LY?>
                <a href="<?php echo $urlTree['satisfaction-list'] ?>/" class="list-group-item <?php echo $currentPage == 'satisfaction-list' ? 'active' : ''?>"><i
                            class="fa fa-smile-o"></i><span class="pl"><?php echo $slang['Surveys'] ?></span></a>
            <?php
        } ?>
            <?php if ($_SESSION['permisos']['loyalty'] == 1) {
            ?>
                <a href="<?php echo $urlTree['loyalty-management'] ?>/" class="list-group-item <?php echo $currentPage == 'loyalty-management' ? 'active' : ''?>"><i
                            class="fa fa-star-o"></i><span class="pl"><?php echo $slang['loyalty-management'] ?></span></a>
            <?php
        } ?>
            <a href="<?php echo $urlTree['staff-management'] ?>" title="Staff management" class="list-group-item staff-management-btn <?php echo $currentPage == 'staff-management' ? 'active' : ''?>"><i
                        class="fa fa-folder-open-o"></i><span class="pl"><?php echo $slang['User Management'] ?></span></a>
                <?php if(!empty($_SESSION['c_logueado'])){?>
<!--            <a href="--><?php //echo $urlTree['clients-chain-reports-management'] ?><!--/" class="list-group-item --><?php //echo $currentPage == 'clients-chain-reports-management' ? 'active' : ''?><!--"><i-->
<!--                        class="fa fa-line-chart"></i><span class="pl">--><?php //echo $slang['clients-chain-reports-management'] ?><!--</span></a>-->
                    <?php } ?>
        <?php
            } ?>
            <?php if ($_SESSION['isIndependent'] === 1) { //SOLO APARECE SI NO ES HOTEL DE CADENA?>
                <a href="<?php echo $urlTree['chain-management'] ?>" title="chain management" class="list-group-item chain-management-btn  <?php echo $currentPage == 'chain-management' ? 'active' : ''?>"><i
                            class="fa fa-building"></i> <span
                            class="pl"><?php echo $slang['Hotel Chain settings'] ?></span></a>
            <?php
        } ?>
            <?php if (array_get($_SESSION, 'permisos.LY') == '1') {
            ?>
                <a href="<?php echo $urlTree['hotel-faq'] ?>" class="list-group-item" title="faq"><i
                            class="fa fa-question"></i><span class="pl"><?php echo $slang['hotel-faq'] ?></span></a>
            <?php
        } ?>
        <?php
    } ?>

    <!-- Menu if the the staff connected is hotel manager -->
    <?php if (array_get($_SESSION,'staff_role') == 3) { ?>
        <a href="<?php echo $urlTree['clients'] ?>" title="All Clients" class="list-group-item referrals-management-btn <?php echo $currentPage == 'clients-management' ? 'active' : ''?>"><i class="fa fa-users"></i><span class="pl"><?php echo $slang['Clients'] ?></span></a>
        <?php if ($_SESSION['permisos']['satisfaction'] == '1') { //SOLO APARECEN CON LY?>
                <a href="<?php echo $urlTree['satisfaction-list'] ?>/" class="list-group-item <?php echo $currentPage == 'satisfaction-list' ? 'active' : ''?>"><i
                            class="fa fa-smile-o"></i><span class="pl"><?php echo $slang['Surveys'] ?></span></a>
        <?php } ?>
        <a href="app/statistics/users" class="list-group-item"><i class="fa fa-signal"></i><span class="pl"><?php echo $slang['View statistics'] ?></span></a>
    <?php } ?>
        <a href="app/logout" class="list-group-item logout-option" title="Log out"><i
                    class="fa fa-power-off"></i><span class="pl"><?php echo $slang['Logout'] ?></span></a>
    </div>
</div>

<script>
    $("#statistics").click(function() {
        $("#loader").show();
    })
</script>
