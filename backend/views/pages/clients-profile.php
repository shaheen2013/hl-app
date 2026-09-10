<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}?>

<div id="wrapper">
    <?php include TEMPLATES . 'hotel-sidebar.php';?>
    <div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'referrals-menu.php'?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70" alt="top bar logo">
        </div>
        <div class="utility-bar">
            <div class="col-lg-12">
                <h1 class="pull-left"><i class="fa fa-folder-open"></i> <?php echo $clientsProfileLang['Client Profile'] ?> </h1>
                <div class="breadcrumbs pull-right">
                    <ul>
                        <?php include TEMPLATES . 'breadcrumbs.php';?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="mainContent" id="fullContainer">
            <div class="mainContent mt">
            <div class="pl pr">
                <div class="panel panel-default panel-client-profile">
                    <?php if ($clientProfileDisplay) { ?>
                        <div class="panel-heading">
                            <img class="img-circle img-thumbnail" src="<?php echo data_get($clientProfile, '0.image', 'public/img/avatar.jpg') ?>" alt="user avatar" width="50" height="50">
                            <h3 class = "client-profile-subtitle"><?php echo data_get($clientProfile, '0.name')?></h3>
                            <?php if (data_get($lastConnection, 'access_code')) { ?>
                                <div class ="pull-right client-profile-subtitle room-number">
                                    <?php echo $clientsProfileLang['room number'] ?> <?php echo data_get($lastConnection, 'access_code') ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <div id="basicInfo" class="tab-pane fade in active">
                                    <table class="clients-profile-table">
                                        <tbody>
                                            <!-- personal info -->
                                            <tr>
                                                <td class="td-main"> <?php echo $clientsProfileLang['Personal Information'] ?></td>
                                                <td>
                                                    <?php if (data_get($clientProfile, '0.email')) { ?>
                                                        <a href="mailto:<?php echo data_get($clientProfile, '0.email') ?>">
                                                            <div>
                                                                <p>
                                                                    <i class = "fa fa-envelope client-profile-subtitle"></i>
                                                                    <?php echo data_get($clientProfile, '0.email') ?>
                                                                </p>
                                                            </div>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if (data_get($clientProfile, '0.country')) { ?>
                                                        <div>
                                                            <p>
                                                                <i class = "fa fa-comment-o client-profile-subtitle"></i>
                                                                <?php echo data_get($clientProfile, '0.country') ?>
                                                            </p>
                                                        </div>
                                                    <?php } ?>
                                                    <?php if (data_get($clientProfile, '0.gender') && data_get($clientProfile, '0.gender') != "null") { ?>
                                                        <div>
                                                            <p>
                                                                <i class = "fa fa-gender client-profile-subtitle"></i>
                                                                <?php echo data_get($clientProfile, '0.gender') ?>
                                                            </p>
                                                        </div>
                                                    <?php } ?>
                                                    <?php if (data_get($clientProfile, '0.birthday')) { ?>
                                                        <div>
                                                            <p>
                                                                <i class = "fa fa-birth client-profile-subtitle"></i>
                                                                <?php echo data_get($clientProfile, '0.birthday') ?>
                                                            </p>
                                                        </div>
                                                    <?php } ?>
                                                    <?php if (data_get($clientProfile, '0.location')) { ?>
                                                        <div>
                                                            <p>
                                                                <i class = "fa fa-map-marker client-profile-subtitle"></i>
                                                                <?php echo data_get($clientProfile, '0.location') ?>
                                                            </p>
                                                        </div>
                                                    <?php } ?>
                                                    <?php if (data_get($clientProfile, '0.phone_number')) { ?>
                                                        <div>
                                                            <p>
                                                                <i class = "fa fa-phone client-profile-subtitle"></i>
                                                                <?php echo data_get($clientProfile, '0.phone_number') ?>                                                        
                                                            </p>
                                                        </div>
                                                    <?php } ?>

                                                    <?php if (data_get($clientProfile, '0.card')) { ?>
                                                        <div>
                                                            <p>
                                                                <span class="client-profile-subtitle"> <?php echo $clientsProfileLang['ID Card'] ?></span>
                                                                <?php echo data_get($clientProfile, '0.card') ?>
                                                            </p>
                                                        </div>
                                                    <?php } ?>
                                                    <?php if ($firstConnection) {
                                                    ?>
                                                        <div>
                                                            <p>
                                                                <span class="client-profile-subtitle"> <?php echo $clientsProfileLang['First Login'] ?>:</span>
                                                                <?php echo data_get($firstConnection, 'created_at') ?>
                                                            </p>
                                                        </div>
                                                     <?php
                                                    } ?>

                                                    <?php if ($lastConnection) {
                                                        ?>
                                                        <div>
                                                            <p>
                                                                <span class="client-profile-subtitle"> <?php echo $clientsProfileLang['Last connection'] ?></span>
                                                                <?php echo data_get($lastConnection, 'created_at') ?>
                                                            </p>
                                                        </div>
                                                    <?php
                                                    } ?>
                                                </td>
                                            </tr>
                                            <!-- social media info -->
                                            <?php if (data_get($clientProfile, '0.friends') > 0) { ?>
                                                <tr>
                                                    <td class="td-main">
                                                        <?php echo $clientsProfileLang['Data on social networks'] ?>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <a class="social-network-btn facebook-btn" href="<?php echo data_get($clientProfile, '0.link') ?>">
                                                                <p>
                                                                    <i class = "fa fa-facebook-square client-profile-subtitle"></i> <?php echo data_get($clientProfile, '0.friends') . ' ' . $clientsProfileLang['Friends on facebook'] ?>
                                                                </p>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            
                                            <!-- aditional info from satisfaction -->
                                            <?php if (data_get($clientProfile, '0.property_visits') > 0) { ?>
                                                <tr>
                                                    <td class="td-main">
                                                        <?php echo $clientsProfileLang['About our hotel'] ?>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p>
                                                                <span class="client-profile-subtitle"> <?php echo $clientsProfileLang['visits to our hotel'] ?></span>
                                                                <?php echo data_get($clientProfile, '0.property_visits') ?>
                                                            </p>
                                                        </div>
                                                    </td>
                                                <tr>
                                            <?php } ?>
                                            <?php if (data_get($clientProfile, '0.chain_visits') > 0) { ?>
                                                <tr>
                                                    <td class="td-main"> <?php echo $clientsProfileLang['About our chain'] ?></td>
                                                    <td>
                                                        <div>
                                                            <p>
                                                                <span class="client-profile-subtitle"> <?php echo $clientsProfileLang['chain_visits'] ?></span>
                                                                <?php echo data_get($clientProfile, '0.chain_visits') ?>
                                                            </p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>

                                            <tr>
                                                <td class="td-main"> <?php echo $clientsProfileLang['Hotels Timeline'] ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div style="overflow: auto;">
                                        <table class="table table-responsive table-striped">
                                            <tr class="table-header">
                                                <td><?php echo $clientsProfileLang['visit'] ?></td>
                                                <td><?php echo $clientsProfileLang['pms info'] ?></td>
                                                <td><?php echo $clientsProfileLang['connections header'] ?></td>
                                                <td><?php echo $clientsProfileLang['satisfactions'] ?></td>
                                            </tr>
                                            <?php foreach ($visits as $visit) { ?>
                                                <tr>
                                                    <td style="min-width:13em">
                                                        <p><?php echo data_get($visit, 'brandName') ?></p>
                                                        <p><?php echo data_get($visit, 'check_in') . ' - ' . data_get($visit, 'check_out') ?></p>
                                                    </td>
                                                    <td style="min-width:16em">
                                                        <p>
                                                            <span class="client-profile-subtitle"><?php echo $clientsProfileLang['pms validated'] ?></span>
                                                            <?php echo data_get($visit, 'pms_validated') ? $clientsProfileLang['Yes'] :  $clientsProfileLang['No'] ?>                                                        
                                                        </p>
                                                        <p>
                                                            <span class="client-profile-subtitle"><?php echo $clientsProfileLang['PMS ID'] ?></span>
                                                            <?php echo data_get($visit, 'pms_id', '-') ?>                                                        
                                                        </p>
                                                        <p>
                                                            <span class="client-profile-subtitle"><?php echo $clientsProfileLang['reservation_id'] ?></span>
                                                            <?php echo data_get($visit, 'reservation_id', '-') ?>                                                        
                                                        </p>
                                                        <p>
                                                            <span class="client-profile-subtitle"><?php echo $clientsProfileLang['channel'] ?></span>
                                                            <?php echo data_get($visit, 'channel', '-') ?>                                                        
                                                        </p>
                                                        <p>
                                                            <span class="client-profile-subtitle"><?php echo $clientsProfileLang['agency'] ?></span>
                                                            <?php echo data_get($visit, 'agency', '-') ?>                                                        
                                                        </p>
                                                    </td>
                                                    <td style="min-width:45em">
                                                        <table style="margin-top:0;overflow: auto;width:100%;" class="table-condensed">
                                                            <?php $groupedConnections = groupConnections(data_get($visit, 'connections'));
                                                                foreach ($groupedConnections as $connection) { 
                                                                    $deviceInfo = getDeviceInfo($connection);    
                                                            ?>
                                                                    <tr>
                                                                        <td style="width:13em;">
                                                                            <i class = "fa fa-calendar ?> client-profile-subtitle"></i> 
                                                                            <?php echo data_get($connection, 'created_at') ?>
                                                                        </td>
                                                                        <td style="width: 7em;">
                                                                            <i class = "fa fa-sign-in ?> client-profile-subtitle"></i> 
                                                                            <a class="social-network-btn facebook-btn" <?php echo data_get($connection, 'source') == "Facebook" && data_get($clientsProfileLang, 'link') ? "href=" . data_get($clientsProfileLang, 'link') : ''?>><?php echo data_get($connection, 'source') ?></a>
                                                                        </td>
                                                                        <td style="width: 8em;">
                                                                            <i class = "fa fa-tag ?> client-profile-subtitle"></i> 
                                                                            <?php echo data_get($connection, 'access_code') ? data_get($connection, 'access_code') : "-" ?>
                                                                        </td>
                                                                        <td style="width:25em">   
                                                                            <i class = "fa <?php echo data_get($deviceInfo, 'type') ?> client-profile-subtitle"></i> 
                                                                            <span><?php echo data_get($deviceInfo, 'info') ?></span>
                                                                            <?php echo '(' . data_get($connection, 'count') . ' ' . (data_get($connection, 'count') > 1 ? $clientsProfileLang['connections'] : $clientsProfileLang['connection']) . ')' ?>
                                                                        </td>
                                                                    </tr>
                                                            <?php } ?>
                                                        </table>
                                                    </td>
                                                    <td style="min-width: 20em;">
                                                        <?php if (data_get($visit, 'satisfaction.comment')) { ?>
                                                            <p>
                                                                <i class = "fa fa-calendar ?> client-profile-subtitle"></i> 
                                                                <?php echo data_get($visit, 'satisfaction.created_at') ?> 
                                                            </p>
                                                            <p>
                                                                <i class = "fa fa-star ?> client-profile-subtitle"></i> 
                                                                <?php echo data_get($visit, 'satisfaction.answer') ?> 
                                                            </p>
                                                            <p>
                                                                <i class = "fa fa-comment ?> client-profile-subtitle"></i> 
                                                                <?php echo data_get($visit, 'satisfaction.comment') ?> 
                                                            </p>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </table>
                                    </div>
                                </div>
                                <div id="fcInfo" class="tab-pane fade in active">
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="panel-heading">
                            <h3 class = "client-profile-subtitle"><?php echo $clientsProfileLang["User don't found"] ?></h3>
                        </div>
                    <?php } ?>
                </div>
            </div>
            </div>
        </div>
    </div>
    <!--  if exist user show buttons-->
        <?php if ($clientProfileDisplay) { ?>
            <?php if (isset($chainId)) { ?>
                <button type="button" class="btn btn-danger pull-right" data-toggle="modal" data-target="#modalChain" style="margin-right: 10px; margin-left:  10px; margin-bottom: 20px;"><?php echo $clientsProfileLang["button delete chain"] ?></button>
            <?php }
            if ($userInHotel) {?>
                <button type="button" data-toggle="modal" data-target="#modalHotel" class="btn btn-danger pull-right" style="margin-right: 10px; margin-left:  10px; margin-bottom: 20px;"><?php echo $clientsProfileLang["button delete hotel"] ?></button>
            <?php }
            if ($userInHotel && !$isUserUnsubscribe) { ?>
                <button type="button" data-toggle="modal" data-target="#modalUnsubscribe" class="btn btn-warning pull-right" style="margin-right: 10px; margin-left:  10px; margin-bottom: 20px;background-color:#f16400;border-color:#f16400"><?php echo $clientsProfileLang["unsubscribe"] ?></button>
            <?php }else{ ?>
                <button type="button" data-toggle="modal" data-target="#modalUnsubscribe" class="btn btn-warning pull-right disabled" style="margin-right: 10px; margin-left:  10px; margin-bottom: 20px;background-color:#f16400;border-color:#f16400"><?php echo $clientsProfileLang["unsubscribe"] ?></button>
            <?php }?>
        <?php }?>

     <!-- Modal Chain -->
  <div class="modal fade" id="modalChain" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo "<b>".$clientsProfileLang["button delete chain"]."</b>" ?></h4>
        </div>
        <div class="modal-body">
          <p><?php echo $clientsProfileLang["modal delete chain"] ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $clientsProfileLang["cancel"] ?></button>
          <a class="unlink-client-chain"><button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo $clientsProfileLang["delete"] ?></button></a>
        </div>
      </div>
    </div>
  </div>

     <!-- Modal Hotel -->
  <div class="modal fade" id="modalHotel" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo "<b>".$clientsProfileLang["button delete hotel"]." \"". $hotelName."\" </b>"?></h4>
        </div>
        <div class="modal-body">
          <p><?php echo $clientsProfileLang["modal delete hotel"] ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $clientsProfileLang["cancel"] ?></button>
          <a class="unlink-client-hotel"><button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo $clientsProfileLang["delete"] ?></button></a>
        </div>
      </div>
    </div>
  </div>

     <!-- Modal unsubscribe -->
    <div class="modal fade" id="modalUnsubscribe" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo "<b>".$clientsProfileLang["button unsubscribe hotel"]." \"". $hotelName. "\"</b>" ?></h4>
                </div>
                <div class="modal-body">
                    <p><?php echo $clientsProfileLang["modal unsubscribe"] ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $clientsProfileLang["cancel"] ?></button>
                    <a href="<?php echo $url['dir1']?>/<?php echo $url['dir2'] ?>/?unsubscribe=ok"><button type="button" class="btn btn-danger" style="background-color:#f16400;border-color:#f16400"><?php echo $clientsProfileLang["unsubscribe"] ?></button></a>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- JavaScript -->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/alertify.min.js"></script>
<!-- CSS -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/alertify.min.css"/>
<!-- Semantic UI theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/themes/semantic.min.css"/>

<script>
    $(document).ready(function(){
        //desvincular un cliente de una cadena
        $('.unlink-client-chain').on('click', function(e){
            unlinkClient("chain")
        });
        //desvincular un cliente de un hotel
        $('.unlink-client-hotel').on('click', function(e){
             unlinkClient()
        });

        function unlinkClient(chain){
            var datos = {
                "user_id" : <?php echo data_get($clientProfile, '0.id', 0) ?>,
                "hotel_id" : <?php echo $hotelId ?>
            };
            if (chain){
                datos.chain_id = '<?php echo $chainId ?>' ;
            }

            $.ajax({
                "url": '<?php echo(SECURE_BASE_PATH . LIB . 'webservices/unlink-client.php') ?>',
                "data": datos,
                type: 'POST',
                success: function(response) {
                    location.href="<?php echo $urlTree['clients'] ?>";
                },
                error: function (response){
                    alertify.error("<?php echo $clientsProfileLang["delete error"] ?>");
                }
            });
        }
    });

</script>
