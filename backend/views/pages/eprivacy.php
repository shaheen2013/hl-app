<div class="top-bar">
    <div id="user-wrapper" style="width: 400px">
        <div class="user-image-sb pull-left" style="padding: 10px">
            <img id="hotel-logo-sidebar" class="img-circle" width="50" height="50"
                 src="<?php echo(!empty($info['logo']) ? imageSize('small',$info['logo']) : DIR_IMG . 'img-placeholder.jpg') ?>" alt="user image">
        </div>
        <p class="pull-left" style="width: 330px;"><?php echo array_get($info, 'hotelName')?></p>
    </div>
</div>
<div class="utility-bar">
    <div class="col-lg-12">
        <h1 class="pull-left"><i class="fa fa-folder-open"></i>Información Usuario</h1>
    </div>
</div>
<div class="mainContent" id="fullContainer">
            <div class="mainContent mt">
            <div class="pl pr">
                <div class="panel panel-default panel-client-profile">
                    <div class="panel-heading">
                        <img class="img-circle img-thumbnail" src="<?php echo  'public/img/avatar.jpg' ?>" alt="user avatar" width="50" height="50">
                        <h3 class = "client-profile-subtitle"><?php echo $info['nombre'] ? $info['nombre'] : $info['nombre'] ?></h3>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div id="basicInfo" class="tab-pane fade in active">
                                <table class="clients-profile-table">
                                    <tbody>
                                    <!-- personal info -->
                                    <tr>
                                        <td class="td-main"> <?php echo 'Personal Information' ?></td>
                                        <td>
                                            <?php if(array_get($info, 'email') != null){ ?>
                                                <a href="mailto:<?php echo $info['email'] ?>">
                                                    <div>
                                                        <p>
                                                            <i class = "fa fa-envelope client-profile-subtitle"></i> <?php echo $info['email'] ?>
                                                        </p>
                                                    </div>
                                                </a>
                                            <?php } ?>
                                            <?php if(array_get($info, 'lang') != null){ ?>
                                                <div>
                                                    <p>
                                                        <i class = "fa fa-comment-o client-profile-subtitle"></i> <?php echo $info['lang'] ?>
                                                    </p>
                                                </div>
                                            <?php } ?>
                                            <?php if(array_get($info, 'sexo') != null){ ?>
                                                <div>
                                                    <p>
                                                        <i class = "fa fa-gender client-profile-subtitle"></i> <?php echo $info['sexo'] ?>
                                                    </p>
                                                </div>
                                            <?php } ?>
                                            <?php if(array_get($info, 'fecha_nacimiento') != null){ ?>
                                                <div>
                                                    <p>
                                                        <i class = "fa fa-birth client-profile-subtitle"></i> <?php echo $info['fecha_nacimiento'] ?>
                                                    </p>
                                                </div>
                                            <?php } ?>
                                            <?php if(array_get($info,'location') != null){ ?>
                                                <div>
                                                    <p>
                                                        <i class = "fa fa-map-marker client-profile-subtitle"></i> <?php echo $info['location'] ?>
                                                    </p>
                                                </div>
                                            <?php } ?>
                                            <?php if(array_get($info, 'user_card') != null){ ?>
                                                <div>
                                                    <p>
                                                        <span class="client-profile-subtitle"> <?php echo 'ID Card' ?></span>
                                                        <?php echo $info['user_card'] ?>
                                                    </p>
                                                </div>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <table class="clients-profile-table">
                                    <tbody>
                                    <!-- personal info -->
                                    <tr>
                                        <td class="td-main"> <?php echo 'Visits to Hotel' ?></td>
                                        <td>
                                            <div class="satisfactionTable">
                                                <div class="satisfactionTableName"> <?php echo $info['hotelName'] ?></div>
                                                <div class="satisfactionComments">
                                                    <div>
                                                        <div class=""><?php echo 'fecha visita' ?></div>
                                                    </div>
                                            <?php
                                            foreach ($userVisits as $userVisit) {
                                                ?>
                                                        <div class="satisfactionCommentsContainer">
                                                                <div class="extendedComment">
                                                                    <div class=""> <?php echo $userVisit['last_login'] ?></div>
                                                                </div>
                                                        </div>

                                                <?php

                                            }
                                            ?>
                                                    </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
</div>