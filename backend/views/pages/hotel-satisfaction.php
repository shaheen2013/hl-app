<?php include LANG . $_SESSION['userLang'] . '/hotel-satisfaction.php' ?>
<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
} ?>

<div id="wrapper">
    <?php include TEMPLATES . 'hotel-sidebar.php'; ?>
    <div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'hotel-profile-menu.php'; ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
        <div class="utility-bar">
            <div class="col-lg-12">
                <h1 class="pull-left"><i
                            class="fa fa-envelope-o"></i>
                    <?php echo $hotelSatisfactionLang['Satisfaction automation'] ?>
                </h1>
               
                <div class="breadcrumbs pull-right">
                    <ul>
                        <?php include(TEMPLATES . 'breadcrumbs.php'); ?>
                    </ul>
                </div>
            </div>
        </div>   
        <div class="mainContent" id="fullContainer">
            <div class="col-lg-12 mt2">
                <form name="satisfactionConfigurationForm" id="satisfactionConfigurationForm" action="<?php echo $url['dir1'] ?>" method="post">

                    <!-- Hotel satisfaction surveys area -->
                    <?php if (!$hotelSatisfactionSurveysProductActive) { ?>

                        <!-- Hotel satisfaction survey activation disabled -->
                        <div class="alert alert-warning" role="alert">
                            <?php echo $hotelSatisfactionLang['no hotel surveys activated message'] ?>
                        </div>
                        <!-- End hotel satisfaction survey activation disabled -->

                    <?php } else { ?>

                    <!-- Send timing options panel -->
                    <div class="panel panel-default noPadding">
                        <div class="panel-heading">
                            <?php echo $hotelSatisfactionLang['Diás que han de pasar para el envio de Satisfaction email'] ?>
                        </div>
                        <div class="panel-body">
                            <p><?php echo $hotelSatisfactionLang['Timing send message'] ?></p>
                            <div class="row form-row">
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input type="number" min="0" name="diasEnvioSatisf" class="form-control" value="<?php echo $satisfactionHotel['sendAfterDays'] ?>">
                                        <div class="input-group-addon">días</div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input type="number" min="0" name="sendHourSatisf" class="form-control" value="<?php echo $satisfactionHotel['sendHour'] ?>">
                                        <div class="input-group-addon">horas</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-row">
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <input type="checkbox" id="sendThanksMail" name="sendThanksMail" aria-label="Checkbox for following text input" <?php echo (isset($satisfactionHotel['sendThanksMail']) && $satisfactionHotel['sendThanksMail'] == 1) ? "checked" : "" ?>>
                                        <label style="margin-left: 1rem" for="sendThanksMail"><?php echo $hotelSatisfactionLang['Send Thans for your feedback email'] ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End send timing options panel -->

                    <!-- Non customer or access code panel -->
                    <div class="panel panel-default noPadding">
                        <div class="panel-heading">
                            <?php echo $hotelSatisfactionLang['Non customers or access code title'] ?>
                        </div>
                        <div class="panel-body">
                            <div class="row form-row">
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <input type="checkbox" id="sendToNonCustomers" name="sendToNonCustomers" <?php echo (isset($satisfactionHotel['sendToNonCustomers']) && $satisfactionHotel['sendToNonCustomers'] == 1) ? "checked" : "" ?>>
                                        <label style="margin-left: 1rem" for="sendToNonCustomers"><?php echo $hotelSatisfactionLang['Send satisfaction survey to no clients'] ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End non customer or access code panel -->

                    <!-- Warnings panel -->
                    <div class="panel panel-default noPadding">
                        <div class="panel-heading">
                            <?php echo $hotelSatisfactionLang['Warnings title'] ?>
                        </div>
                        <div class="panel-body">
                            <div class="column">
                                <div class="row col-lg-12">
                                    <div class="input-group">
                                        <input onclick="hideElementsOnClick('.warningElement')" type="checkbox" id="sendRegardlessScore" name="filter_warning"  <?php echo (isset($satisfactionHotel['filterWarning']) && $satisfactionHotel['filterWarning'] == 1) ? "" : "checked" ?>>
                                        <label style="margin-left: 1rem" for="filter_warning"><?php echo $hotelSatisfactionLang['Send satisfaction warning regardless score'] ?></label>
                                    </div>
                                </div>
                                <div class="row col-lg-12 warningElement">
                                    <p class="slider-text"><?php echo $hotelSatisfactionLang['warning_emails_to_staff_before'] ?> <span class="span-warning"><?php echo $satisfactionHotel['puntMin']; ?></span> <?php echo $hotelSatisfactionLang['warning_emails_to_staff_after'] ?></p>
                                </div>
                                <div class="row col-lg-6 warningElement">
                                    <div class="slider-wrapper">
                                        <input class="slider warning_satisfaction_slider" id="warning" name="puntMin" type="range" value="<?php echo $satisfactionHotel['puntMin']; ?>" min="0" max="10" oninput="updateInputStyles(this.value, this.id);" />   
                                    </div>
                                    <span id="thumb-warning-value" class="span-warning" ></span>
                                </div>
                            </div>
                            <div class="row form-row">
                                <p class="slider-text col-lg-12"><?php echo $hotelSatisfactionLang['Warnings message 02'] ?></p>
                                <div class="col-lg-6">
                                    <?php if(!empty($_SESSION['c_logueado'])) : ?>
                                        <textarea rows="5" name="warning_email" class="form-control" placeholder="<?php echo $hotelSatisfactionLang['If no email, no warning will be sent...'] ?>"><?php echo $satisfactionHotel['warningEmail'] ?></textarea>
                                        <br>
                                        <div class="input-group">
                                            <input type="checkbox" id="chain-email" name="chain-email" aria-label="Checkbox for following text input">
                                            <label style="margin-left: 1rem" for="chain-email"><?php echo $hotelSatisfactionLang['Use this email for all hotels'] ?></label>
                                        </div>
                                    <?php else : ?>
                                        <textarea rows="5" name="warning_email" class="form-control" placeholder="<?php echo $hotelSatisfactionLang['If no email, no warning will be sent...'] ?>"><?php echo $satisfactionHotel['warningEmail'] ?></textarea>
                                        <br>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End warnings panel -->
                    
                    <!-- Review panel -->
                    <div class="panel panel-default noPadding">
                        <div class="panel-heading">
                            <?php echo $hotelSatisfactionLang['Reviews title'] ?>
                        </div>
                        <div class="panel-body">
                            <div class="column">
                                <div class="row col-lg-12">
                                    <p class="slider-text"><?php echo $hotelSatisfactionLang['client_email_review_before'] ?> <span class="span-review"><?php echo $satisfactionHotel['reviewAverageScore'] ?? 6 ?></span> <?php echo $hotelSatisfactionLang['client_email_review_after'] ?></p>
                                </div>
                                <div class="row col-lg-6">
                                    <div class="slider-wrapper">
                                        <input class="slider review_satisfaction_slider" id="review" name="reviewAverageScore" type="range" value="<?php echo $satisfactionHotel['reviewAverageScore'] ?? 6 ?>" min="0" max="10" oninput="updateInputStyles(this.value, this.id);" />
                                    </div>
                                    <span id="thumb-review-value" class="span-review" ></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End review panel -->
                    
                    <!-- Default score panel -->
                    <div class="panel panel-default noPadding">
                        <div class="panel-heading">
                            <?php echo $hotelSatisfactionLang['default score title'] ?>
                        </div>
                        <div class="panel-body">
                            <div class="column">
                                <div class="row col-lg-12">
                                    <p class="slider-text"><?php echo $hotelSatisfactionLang['default score explanation before'] ?> <span class="span-defaultScore"><?php echo $satisfactionHotel['defaultScore'] ?? 6 ?></span> <?php echo $hotelSatisfactionLang['default score explanation after'] ?></p>
                                </div>
                                <div class="row col-lg-6">
                                    <div class="slider-wrapper">
                                        <input class="slider default_score_slider" id="defaultScore" name="defaultScore" type="range" value="<?php echo $satisfactionHotel['defaultScore'] ?? 6 ?>" min="0" max="10" oninput="updateInputStyles(this.value, this.id);" />
                                    </div>
                                    <span id="thumb-defaultScore-value" class="span-defaultScore" ></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End default score panel -->

                    <!-- Reminders panel -->
                    <?php if ($followUpProductActive) { ?>
                        <div class="panel panel-default noPadding">
                            <div class="panel-heading">
                                <?php echo $hotelSatisfactionLang['Reminders title'] ?>
                            </div>
                            <div class="panel-body">
                                <div class="row form-row">
                                    <p class="col-lg-12"><?php echo $hotelSatisfactionLang['Reminders message 01'] ?></p>
                                    <p class="col-lg-12"><?php echo $hotelSatisfactionLang['Reminders message 02'] ?></p>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <input type="num" name="followupEmails" class="form-control" value="<?php echo $satisfactionHotel['totalFollowupEmail'] ?>">

                                            <div class="input-group-addon">emails</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- End reminders panel -->

                    <!-- Force comments panel -->
                    <div class="panel panel-default noPadding" style="display: none">
                        <div class="panel-heading">
                            <?php echo $hotelSatisfactionLang['force_comment'] ?>
                        </div>
                        <div class="panel-body">
                            <div class="row form-row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="checkbox" id="force_comment" name="force_comment" aria-label="Checkbox for following text input" <?php echo (isset($satisfactionHotel['forceComment']) && $satisfactionHotel['forceComment'] == 1) ? "checked" : "" ?>>
                                        <label style="margin-left: 1rem" for="force_comment"><?php echo $hotelSatisfactionLang["force_comment_legend"] ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End force comments panel -->

                    <!-- Customized satisfaction surveys configuration area -->
                    <?php if (!$customizedSatisfactionSurveysProductActive) { ?>

                        <!-- Customized satisfaction survey activation disabled -->
                        <div class="alert alert-warning" role="alert">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <div class="configuration">
                                        <input id="activeCustomizedSatisfaction" type="checkbox" name="activeCustomizedSatisfaction" aria-label="Checkbox for following text input" <?php echo array_get($configuration, 'customizedActive') ? "checked" : "" ?> onclick="hideElementsOnClick('.customizedSatisfactionElement')" disabled>
                                        <label style="margin-left: 1rem" for="activeCustomizedSatisfaction"><?php echo $hotelSatisfactionLang['Active customized satisfaction'] ?></label>
                                    </div>
                                </div>
                            </div>
                            <?php echo $hotelSatisfactionLang['no customized surveys activated message'] ?>
                        </div>
                        <!-- End customized satisfaction survey activation disabled -->

                    <?php } else { ?>

                        <!-- Customized satisfaction survey activation enabled -->
                        <div class="panel panel-default noPadding">
                            <div class="panel-heading">
                                <?php echo $hotelSatisfactionLang['custom surveys'] ?>
                            </div>
                            <div class="panel-body">
                                <div class="row form-row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="configuration">
                                                <input id="activeCustomizedSatisfaction" onclick="hideElementsOnClick('.customizedSatisfactionElement')" type="checkbox" name="activeCustomizedSatisfaction" aria-label="Checkbox for following text input" <?php echo array_get($configuration, 'customizedActive') ? "checked" : "" ?>>
                                                <label style="margin-left: 1rem" for="activeCustomizedSatisfaction"><?php echo $hotelSatisfactionLang['Active customized satisfaction'] ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End customized satisfaction survey activation enabled -->

                        <!-- Customized satisfaction surveys configuration -->
                        <div class="panel panel-default noPadding customizedSatisfactionElement">
                            <div class="panel-heading">
                                <?php echo $hotelSatisfactionLang['custom surveys configuration'] ?>
                            </div>
                            <div class="panel-body">
                                <div class="row form-row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="customizedSatisfactionElement">
                                                <input id="customizedComment" type="checkbox"
                                                       name="customizedComment"
                                                       aria-label="Checkbox for following text input" <?php echo array_get($configuration, 'customizedComment') ? "checked" : "" ?>>
                                                <label style="margin-left: 1rem"
                                                       for="customizedComment"><?php echo $hotelSatisfactionLang['customized comment'] ?></label>
                                            </div>
                                            <div class="customizedSatisfactionElement">
                                                <input id="customizedWarning" type="checkbox"
                                                       name="customizedWarning"
                                                       aria-label="Checkbox for following text input" <?php echo array_get($configuration, 'customizedWarning') ? "checked" : "" ?>>
                                                <label style="margin-left: 1rem"
                                                       for="customizedWarning"><?php echo $hotelSatisfactionLang['customized warning'] ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End customized satisfaction surveys configuration -->

                        <!-- Time to send panel -->
                        <div class="panel panel-default noPadding customizedSatisfactionElement">
                            <div class="panel-heading">
                                <?php echo $hotelSatisfactionLang['Time to send'] ?>
                            </div>
                            <div class="panel-body">
                                <div class="row form-row">
                                    <div class="col-lg-12">
                                        <div class="input-group">
                                            <input onclick="showCustomizedDateConfiguration()"
                                                   id="sendSimultaneousSatisfaction" type="radio"
                                                   name="sendSimultaneousSatisfaction"
                                                   aria-label="Checkbox for following text input" value="1" <?php echo array_get($configuration, 'customizedType') === 'Joined with satisfaction' ? "checked" : "" ?>>
                                            <label style="margin-left: 1rem"
                                                   for="sendSimultaneousSatisfaction"><?php echo $hotelSatisfactionLang['Send in same email'] ?></label>
                                        </div>
                                        <div class="input-group">
                                            <input onclick="showCustomizedDateConfiguration()"
                                                   id="sendDelayedSatisfaction" type="radio"
                                                   name="sendSimultaneousSatisfaction"
                                                   aria-label="Checkbox for following text input" value="2" <?php echo array_get($configuration, 'customizedType') === 'In a later email' ? "checked" : "" ?>>
                                            <label style="margin-left: 1rem"
                                                   for="sendDelayedSatisfaction"><?php echo $hotelSatisfactionLang['Send in differents emails'] ?></label>
                                        </div>
                                        <?php if ($portalProProductActive) { ?>
                                            <div class="input-group">
                                                <input onclick="showCustomizedDateConfiguration()"
                                                    id="sendAfterCheckout" type="radio"
                                                    name="sendSimultaneousSatisfaction"
                                                    aria-label="Checkbox for following text input" value="3" <?php echo array_get($configuration, 'customizedType') === 'After PMS checkout date' ? "checked" : "" ?>>
                                                <label style="margin-left: 1rem"
                                                    for="sendAfterCheckout"><?php echo $hotelSatisfactionLang['Send after checkout'] ?></label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row form-row" id="sendDates">
                                    <div class="col-lg-12">
                                        <p id="afterTimeExplanation" style="margin-bottom: 20px;"><i><?php echo $hotelSatisfactionLang['Send in differents emails message'] ?></i></p>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <input type="number" min="0" name="diasEnvioCustomizedSatisf" class="form-control" value="<?php echo array_get($configuration, 'customizedSendDays') ?>">
                                            <div class="input-group-addon">días</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <input type="number" min="0" name="sendHourCustomizedSatisf" class="form-control" value="<?php echo array_get($configuration, 'customizedSendHours') ?>">
                                            <div class="input-group-addon">horas</div>
                                        </div>
                                    </div>
                                </div>
                                <?php if (!$portalProProductActive) { ?>
                                    <div class="alert alert-warning mt2" role="alert">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <input type="radio" aria-label="Checkbox for following text input" disabled>
                                                <label style="margin-left: 1rem"><?php echo $hotelSatisfactionLang['Send after checkout'] ?></label>
                                            </div>
                                        </div>
                                        <?php echo $hotelSatisfactionLang['no portal pro activated message'] ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <!-- End time to send panel -->

                    <?php } ?>
                    <!-- End customized satisfaction surveys configuration area -->

                    <!-- Save configuration button -->
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" name="categoryOrder" id="categoryOrder">
                            <input type="hidden" name="questionOrder" id="questionOrder">
                            <input type="hidden" name="useBrandQuestions" value="<?php echo array_get($configuration, 'customizedChainActivated') ? "on" : "off" ?>">
                            <button type="submit" class="btn btn-success" name="hotelConfirmButton"><?php echo $hotelSatisfactionLang['Guardar cambios'] ?></button>
                            <a onclick="openPreviewSurvey()" class="btn btn-primary" name="hotelConfirmButton">
                            <?php echo $hotelSatisfactionLang['Preview survey'] ?>
                            </a>
                        </div>
                    </div>
                    <!-- End save configuration button -->
                </form>
                <?php if ($customizedSatisfactionSurveysProductActive) { ?>
                    <hr>
                    <!-- Categories and questions list switch -->
                    <?php if($_SESSION['loggedParentBrandID']) : ?>
                        <div class="row mb2 customizedSatisfactionElement" style="margin-top: 30px;">
                            <div class="col-md-12">
                                <form name="questionListTypeForm" id="questionListTypeForm" action="<?php echo $url['dir1'] ?>" method="post">
                                    <div class="customizedSatisfactionElement">
                                        <span style="display: inline-block; font-weight: bold;"><?php echo $hotelSatisfactionLang['questions switch label chain'] ?>&nbsp;</span>
                                        <label class="customized-switch-left">
                                            <input type="checkbox" style="opacity:0;" onchange="submitForms(event, 'questionListTypeForm')" <?php echo !array_get($configuration, 'customizedChainActivated') ? "checked" : "" ?>>
                                            <span class="customized-slider customized-slider-unique-color round"></span>
                                        </label>
                                        <span style="display: inline-block; font-weight: bold;">&nbsp;<?php echo $hotelSatisfactionLang['questions switch label hotel'] ?></span>
                                        <span class="customized-switch-text-left" style="line-height: 1.8em"><?php echo $hotelSatisfactionLang['use questions'] ?></span>

                                        <input type="hidden" name="hotelConfirmButton" value="confirm">
                                        <input type="hidden" name="useBrandQuestions" value="<?php echo array_get($configuration, 'customizedChainActivated') ? "off" : "on" ?>">
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                    <!-- Categories and questions list switch -->

                    <!-- Categories and questions creation list header -->
                    <div class="row mb2 customizedSatisfactionElement">
                        <div class="col-md-8">
                            <?php if(!array_get($_SESSION, 'loggedParentBrandID')) { ?>
                                <h3 style="margin-top: 0px"><?php echo $hotelSatisfactionLang['Create independent questions and categories'] ?></h3>
                            <?php } else if(!array_get($configuration, 'customizedChainActivated')) { ?>
                                <h3 style="margin-top: 0px"><?php echo $hotelSatisfactionLang['Create hotel questions and categories'] ?></h3>
                            <?php } else { ?>
                                <h3 style="margin-top: 0px"><?php echo $hotelSatisfactionLang['Create chain questions and categories'] ?></h3>
                            <?php } ?>
                        </div>
                        <?php if (array_get($_SESSION, 'chain.id') && !array_get($configuration, 'customizedChainActivated')) { ?>
                            <div class="col-md-4 pull-right">
                                <button data-toggle="modal" data-target="#createCategory"
                                        class="btn btn-success pull-right"
                                        name="hotelConfirmButton"><?php echo $hotelSatisfactionLang['Create Category'] ?></button>
                            </div>
                            <div class="clearfix"></div>
                        <?php } else if (!array_get($_SESSION, 'chain.id') && !array_get($configuration, 'customizedChainActivated')) { ?>
                            <div class="col-md-4 pull-right">
                                <button data-toggle="modal" data-target="#createCategory"
                                        class="btn btn-success pull-right"
                                        name="hotelConfirmButton"><?php echo $hotelSatisfactionLang['Create Category'] ?></button>
                            </div>
                            <div class="clearfix"></div>
                        <?php } else if (array_get($_SESSION, 'chain.id') && array_get($configuration, 'customizedChainActivated')) { ?>
                            <div class="col-md-4 pull-right">
                                <button data-toggle="modal" data-target="#createCategory"
                                        class="btn btn-success pull-right"
                                        name="hotelConfirmButton"><?php echo $hotelSatisfactionLang['Create Category'] ?></button>
                            </div>
                            <div class="clearfix"></div>
                        <?php } ?>
                    </div>
                    <!-- End categories and questions creation list header -->

                    <!-- Categories panels -->
                    <div id="categoriesContainer">
                    <?php
                    $categoryIndex = 1;
                    foreach ($questionsConfiguration as $category) { ?>
                        <div class="panel panel-default noPadding customizedSatisfactionElement" data-id="<?php echo $category['id'] ?>">
                            <div class="panel-heading">

                                <!-- Category header-->
                                <?php foreach ($category['category_text'] as $categoryLang => $categoryLangText) { ?>
                                    <span id="categoryTitle-<?php echo $category['id'] ?>-<?php echo $categoryLang ?>">
                                        <?php echo $categoryLangText; 
                                        if (array_get($_SESSION, 'superadmin')) { ?>
                                            <i 
                                                class="fa fa-pencil edit-icon"
                                                data-category-id="<?php echo $category['id'] ?>"
                                                data-category-texts="<?php print_r(htmlspecialchars(json_encode($category['category_text']), ENT_QUOTES)) ?>"
                                                data-toggle="modal" 
                                                data-target="#updateCategory" 
                                                data-toggle="tooltip" 
                                                data-placement="top" 
                                                title="" 
                                                data-original-title="<?php echo $hotelSatisfactionLang['Edit']?>" 
                                                data-content="<?php echo $hotelSatisfactionLang['Edit']?>" 
                                                data-variation="wide"
                                            >
                                            </i>
                                        <?php } ?>
                                    </span>
                                <?php } ?>

                                <?php if (array_get($_SESSION, 'chain.id') && !array_get($configuration, 'customizedChainActivated')) { ?>
                                    <button type="button" class="btn btn-xs btn-danger pull-right" data-toggle="modal"
                                            data-target="#deleteCategory"
                                            onclick=" $('#delete_category_id').val(<?php echo $category['id'] ?>)"><?php echo $hotelSatisfactionLang['Delete Category'] ?>
                                    </button>
                                <?php } else if (!array_get($_SESSION, 'chain.id') && !array_get($configuration, 'customizedChainActivated')) { ?>
                                    <button type="button" class="btn btn-xs btn-danger pull-right" data-toggle="modal"
                                            data-target="#deleteCategory"
                                            onclick=" $('#delete_category_id').val(<?php echo $category['id'] ?>)"><?php echo $hotelSatisfactionLang['Delete Category'] ?>
                                    </button>
                                <?php } else if (array_get($_SESSION, 'chain.id') && array_get($configuration, 'customizedChainActivated')) { ?>
                                    <button type="button" class="btn btn-xs btn-danger pull-right" data-toggle="modal"
                                            data-target="#deleteCategory"
                                            onclick=" $('#delete_category_id').val(<?php echo $category['id'] ?>)"><?php echo $hotelSatisfactionLang['Delete Category'] ?>
                                    </button>
                                <?php } ?>
                                <!-- End category header-->

                                <div class="clearfix"></div>
                            </div>
                            <div class="panel-body">

                                <!-- Questions header -->
                                <div class="row mb2">
                                    <div class="col-sm-8"><strong><?php echo $hotelSatisfactionLang['Questions label'] ?></strong></div>
                                    <?php if (array_get($_SESSION, 'chain.id') && !array_get($configuration, 'customizedChainActivated')) { ?>
                                        <div class="col-sm-2 text-center"><strong><?php echo $hotelSatisfactionLang['Type label'] ?></strong></div>
                                        <div class="col-sm-1 text-center"><strong><?php echo $hotelSatisfactionLang['Optional label'] ?></strong></div>
                                        <div class="col-sm-1 text-center"><strong><?php echo $hotelSatisfactionLang['Delete label'] ?></strong></div>
                                    <?php } else if (!array_get($_SESSION, 'chain.id') && !array_get($configuration, 'customizedChainActivated')) { ?>
                                        <div class="col-sm-2 text-center"><strong><?php echo $hotelSatisfactionLang['Type label'] ?></strong></div>
                                        <div class="col-sm-1 text-center"><strong><?php echo $hotelSatisfactionLang['Optional label'] ?></strong></div>
                                        <div class="col-sm-1 text-center"><strong><?php echo $hotelSatisfactionLang['Delete label'] ?></strong></div>
                                    <?php } else if (array_get($_SESSION, 'chain.id') && array_get($configuration, 'customizedChainActivated')) { ?>
                                        <div class="col-sm-2 text-center"><strong><?php echo $hotelSatisfactionLang['Type label'] ?></strong></div>
                                        <div class="col-sm-1 text-center"><strong><?php echo $hotelSatisfactionLang['Optional label'] ?></strong></div>
                                        <div class="col-sm-1 text-center"><strong><?php echo $hotelSatisfactionLang['Delete label'] ?></strong></div>
                                    <?php } ?>
                                </div>
                                <!-- End questions header -->

                                <!-- Questions -->
                                <div class="questionsContainer" data-category-id="<?php echo $category['id'] ?>">
                                <?php foreach ($category['questions'] as $question) { ?>
                                    <div data-id="<?php echo $question['id'] ?>">
                                    <?php foreach ($question['question_text'] as $questionLang => $questionLangText) { ?>
                                        <form method="post" id="category-<?php echo $category['id'] ?>-question-<?php echo $question['id'] ?>-<?php echo $questionLang ?>" name="mandatoryQuestionForm-<?php echo $question['id'] ?>">
                                            <div class="row mb2" data-id="<?php echo $question['id'] ?>">
                                                <div class="col-sm-8 question-row">
                                                    <p>
                                                        <label class="customized-switch-left">
                                                            <input 
                                                                type="checkbox" 
                                                                style="opacity:0;"
                                                                name="questionActive"
                                                                onchange="submitForms(event, 'category-<?php echo $category['id'] ?>-question-<?php echo $question['id'] ?>-<?php echo $questionLang ?>')" 
                                                                <?php echo array_get($question, 'active') ? "checked" : "" ?>
                                                            >
                                                            <span class="customized-slider customized-slider-unique-color round"></span>
                                                        </label>
                                                        <?php echo $questionLangText; 
                                                        if (array_get($_SESSION, 'superadmin')) { ?>
                                                            <i 
                                                                class="fa fa-pencil edit-icon"
                                                                data-toggle="modal" 
                                                                data-target="#updateQuestion" 
                                                                data-question-id="<?php echo $question['id'] ?>"
                                                                data-question-texts="<?php print_r(htmlspecialchars(json_encode($question['question_text']), ENT_QUOTES)) ?>"
                                                                data-question-type="<?php echo $question['type'] ?>"
                                                                data-question-responses="<?php print_r(htmlspecialchars(json_encode($question['question_responses']), ENT_QUOTES)) ?>"
                                                                data-allow-multiple-responses="<?php echo $question['allow_multiple_responses'] ?>"
                                                                data-toggle="tooltip"
                                                                data-placement="top"
                                                                title=""
                                                                data-original-title="<?php echo $hotelSatisfactionLang['Edit']?>" 
                                                                data-content="<?php echo $hotelSatisfactionLang['Edit']?>" 
                                                                data-variation="wide">
                                                            </i>
                                                        <?php } ?>
                                                    </p>
                                                    
                                                    <?php foreach($question['question_responses'] as $questionResponses) { ?>
                                                        <p>
                                                            &emsp;- <?php echo $questionResponses[$questionLang] ?? null ?>
                                                        </p>
                                                    <?php } ?>
                                                </div>

                                                <?php if (array_get($_SESSION, 'chain.id') && !array_get($configuration, 'customizedChainActivated')) { ?>
                                                    <div class="col-sm-2 text-center">
                                                        <?php echo $hotelSatisfactionLang[$question['type']]?>
                                                    </div>
                                                    <div class="col-sm-1 text-center">
                                                        <input name="questionID" type="hidden" value="<?php echo $question['id'] ?>">
                                                        <input type="checkbox" id="mandatoryCheck-question-<?php echo $question['id'] ?>-<?php echo $questionLang ?>" name="mandatoryQuestion" onchange="submitForms(event, 'category-<?php echo $category['id'] ?>-question-<?php echo $question['id'] ?>-<?php echo $questionLang ?>')" <?php echo !array_get($question, 'required') == 1 ? 'checked' : '' ?>>
                                                    </div>
                                                    <div class="col-sm-1 text-center">
                                                        <div
                                                            data-toggle="modal"
                                                            data-target="#deleteQuestion"
                                                            onclick=" $('#delete_question_id').val(<?php echo array_get($question, 'id') ?>)">
                                                            <div class="removeGoal btn btn-xs btn-danger"
                                                                 data-question-id="<?php echo array_get($question, 'id') ?>">
                                                                <i class="fa fa-trash"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } else if (!array_get($_SESSION, 'chain.id') && !array_get($configuration, 'customizedChainActivated')) { ?>
                                                    <div class="col-sm-2 text-center">
                                                        <?php echo $hotelSatisfactionLang[$question['type']]?>
                                                    </div>
                                                    <div class="col-sm-1 text-center">
                                                        <input name="questionID" type="hidden" value="<?php echo $question['id'] ?>">
                                                        <input type="checkbox" id="mandatoryCheck-question-<?php echo $question['id'] ?>-<?php echo $questionLang ?>" name="mandatoryQuestion" onchange="submitForms(event, 'category-<?php echo $category['id'] ?>-question-<?php echo $question['id'] ?>-<?php echo $questionLang ?>')" <?php echo !array_get($question, 'required') == 1 ? 'checked' : '' ?>>
                                                    </div>
                                                    <div class="col-sm-1 text-center">
                                                        <div
                                                            data-toggle="modal"
                                                            data-target="#deleteQuestion"
                                                            onclick=" $('#delete_question_id').val(<?php echo array_get($question, 'id') ?>)">
                                                            <div class="removeGoal btn btn-xs btn-danger"
                                                                 data-question-id="<?php echo array_get($question, 'id') ?>">
                                                                <i class="fa fa-trash"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } else if (array_get($_SESSION, 'chain.id') && array_get($configuration, 'customizedChainActivated')) { ?>
                                                    <div class="col-sm-2 text-center">
                                                        <?php echo $hotelSatisfactionLang[$question['type']]?>
                                                    </div>
                                                    <div class="col-sm-1 text-center">
                                                        <input name="questionID" type="hidden" value="<?php echo $question['id'] ?>">
                                                        <input type="checkbox" id="mandatoryCheck-question-<?php echo $question['id'] ?>-<?php echo $questionLang ?>" name="mandatoryQuestion" onchange="submitForms(event, 'category-<?php echo $category['id'] ?>-question-<?php echo $question['id'] ?>-<?php echo $questionLang ?>')" <?php echo !array_get($question, 'required') == 1 ? 'checked' : '' ?>>
                                                    </div>
                                                    <div class="col-sm-1 text-center">
                                                        <div
                                                            data-toggle="modal"
                                                            data-target="#deleteQuestion"
                                                            onclick=" $('#delete_question_id').val(<?php echo array_get($question, 'id') ?>)">
                                                            <div class="removeGoal btn btn-xs btn-danger"
                                                                 data-question-id="<?php echo array_get($question, 'id') ?>">
                                                                <i class="fa fa-trash"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>

                                            </div>
                                        </form>
                                    <?php } ?>
                                    </div>
                                <?php } ?>
                                </div>
                                <!-- End questions -->

                            </div>
                            <div class="panel-footer">

                                <!-- Create question button -->
                                <div class="col-md-6" style="padding-left: 0px">
                                    <?php if (array_get($_SESSION, 'chain.id') && !array_get($configuration, 'customizedChainActivated')) { ?>
                                        <button class="btn btn-success btn-xs"
                                                onclick="$('#create_question_category_id').val(<?php echo $category['id'] ?>)"
                                                data-toggle="modal" data-target="#createQuestion"><?php echo $hotelSatisfactionLang['Add question btn label'] ?>
                                        </button>
                                    <?php } else if (!array_get($_SESSION, 'chain.id') && !array_get($configuration, 'customizedChainActivated')) { ?>
                                        <button class="btn btn-success btn-xs"
                                                onclick="$('#create_question_category_id').val(<?php echo $category['id'] ?>)"
                                                data-toggle="modal" data-target="#createQuestion"><?php echo $hotelSatisfactionLang['Add question btn label'] ?>
                                        </button>
                                    <?php } else if (array_get($_SESSION, 'chain.id') && array_get($configuration, 'customizedChainActivated')) { ?>
                                        <button class="btn btn-success btn-xs"
                                                onclick="$('#create_question_category_id').val(<?php echo $category['id'] ?>)"
                                                data-toggle="modal" data-target="#createQuestion"><?php echo $hotelSatisfactionLang['Add question btn label'] ?>
                                        </button>
                                    <?php } ?>
                                </div>
                                <!-- End create question button -->

                                <!-- Languages dropdown -->
                                <div class="col-md-6" style="padding-right: 0px">
                                    <div class="btn-group dropup pull-right">
                                        <button type="button" class="btn btn-xs btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <?php echo $hotelSatisfactionLang['Selected language'] ?>:
                                            <img id="dropdownSelectedLanguageImage<?php echo $category['id'] ?>" src="<?php echo BASE_PATH . DIR_IMG . 'flags/' . $_SESSION['userLang'] . '.png' ?>" class="pl flag-icon">
                                            <span id="dropdownSelectedLanguage<?php echo $category['id'] ?>"><strong><?php echo  $_SESSION['userLang'] ?></strong></span>
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu" aria-labelledby="languageSelector">
                                            <?php foreach ($langs as $lang) { ?>
                                                <li role="presentation" onclick="setDropdownCategoryLanguage('<?php echo $lang ?>', <?php echo $category['id'] ?>)">
                                                    <a class="langLia">
                                                        <img src="<?php echo BASE_PATH . DIR_IMG . 'flags/' . $lang . '.png' ?>" class="pl flag-icon">
                                                        <strong><?php echo $lang ?></strong>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                                <!-- End languages dropdown -->

                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <?php
                        $categoryIndex++;
                    }?>
                    <!-- End categories panels -->

                    <?php } ?>
                    </div>
                <hr>

                <?php } ?>
                <!-- End hotel satisfaction surveys area -->

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var categoryOrderInput = document.getElementById('categoryOrder');
        var questionOrderInput = document.getElementById('questionOrder');

            var sortableCategories = new Sortable(document.getElementById('categoriesContainer'), {
                animation: 150,
                handle: '.panel-heading',
                dataIdAttr: 'data-id',
                onEnd: function(){
                    var categoryOrder = sortableCategories.toArray();
                    categoryOrderInput.value = JSON.stringify(categoryOrder);
                }
            });

            document.querySelectorAll('.questionsContainer').forEach(function (element) {
                var sortable = new Sortable(element, {
                    animation: 150,
                    handle: '.question-row',
                    dataIdAttr: 'data-id',
                    onEnd: function(){
                        var questionOrder = questionOrderInput.value ? JSON.parse(questionOrderInput.value) : {};
                        questionOrder[element.dataset.categoryId] = sortable.toArray();
                        questionOrderInput.value = JSON.stringify(questionOrder);
                    }
                });
            });
         
    })

    $( document ).ready(function() {
        var numberResponses = 0;
        var savedResponses = 1;
        let userLanguage = <?php echo json_encode($_SESSION['userLang']) ?>;
        $("[id^=categoryTitle]").hide();
        $("[id^=categoryTitle][id$="+userLanguage+"]").show();
        $("[id^=category][id*=question]").hide();
        $("[id^=category][id*=question][id$="+userLanguage+"]").show();
        $("#multiresponse").hide();
        $("#createQuestionButton").attr("disabled", "disabled");
        $(".edit-icon").tooltip();
        $(".edit-icon").css('cursor', 'pointer');


        setFirstInputStyles($('.warning_satisfaction_slider').val(), $('.review_satisfaction_slider').val(), $('.default_score_slider').val());

        $('body').on('click', '#addAnswerButton', function () {
            numberResponses = numberResponses + 1;
            $("#responses").prepend('<div id="responses_' + numberResponses + '" class="mt2"><strong><?php echo $hotelSatisfactionLang['OptionAnswer'] ?> <span class="number-response-title">' + numberResponses +'</span></strong><?php foreach ($langs as $lang) { ?>
                <div style="display:flex" class="form-group mt2">'
                    + '<label style="display:flex" for="answer_' + numberResponses + '_<?php echo $lang ?>">'
                        + '<img'
                            + ' style="height: fit-content;margin: auto;"' 
                            + ' src="<?php echo BASE_PATH . DIR_IMG . 'flags/' . $lang . '.png' ?>"'
                            + ' alt=<?php echo 'flags/' . $lang ?>"'
                        + '>' 
                    + '</label>'
                    + '<input style="margin-left: 1em" id="answer_' + numberResponses + '_<?php echo $lang ?>" name="answer_' + numberResponses + '_<?php echo $lang ?>"'
                            + ' class="form-control" type="text" required'
                            + ' placeholder="<?php echo $hotelSatisfactionLang['WriteAnswerPlaceholder'] ?>"/>'
                + '</div>'
            + '<?php } ?><div class="choice-actions"><button type="button" class="btn btn-success btn-xs save-choice">'
                + '<?php echo $hotelSatisfactionLang['SaveChoice'] ?>'
            + '</button>'
            + '<button type="button" class="btn btn-default btn-xs ml cancel-choice">' 
                + '<?php echo $hotelSatisfactionLang['Cancel'] ?>'
            + '</button></div></div>');

            if (numberResponses >= 6) {
                $(this).attr("disabled", "disabled");
            }
        });

        $('body').on('click', '.cancel-choice', function () {
            $(this).closest("[id^=responses_]").remove();
            numberResponses = numberResponses - 1;        
            recalculateNumberChoice();
        });
        
        $('body').on('click', '#otherResponse', function () {
            if ($(this).is(":checked")) {
                savedResponses = savedResponses + 1;
            } else {
                savedResponses = savedResponses - 1;
            }

            checkQuestionButtonStatus(savedResponses);

        });
        
        $('body').on('click', '.save-choice', function () {
            var emptyChoices = $(this).closest("[id^=responses_]").find("[id^=answer_]").filter(function() {
                return !this.value;
            }).length

            if (!emptyChoices) {
                $(this).closest("[id^=responses_]").find("[id^=answer_]").prop( "readonly", true );

                var choicesActionSelector = $(this).closest("[id^=responses_]").find(".choice-actions")
                choicesActionSelector.empty();
                choicesActionSelector.append('<button type="button" class="btn btn-success btn-xs edit-choice">'
                    + '<?php echo $hotelSatisfactionLang['EditChoice'] ?>'
                + '</button>'
                + '<button type="button" class="btn btn-danger btn-xs ml remove-choice">' 
                    + '<i class="fa fa-trash"></i>'
                    + ' <?php echo $hotelSatisfactionLang['Remove'] ?>'
                + '</button>');
                
                savedResponses = savedResponses + 1;
            }

            checkQuestionButtonStatus(savedResponses);
        });

        $('body').on('click', '.remove-choice', function () {
            $(this).closest("[id^=responses_]").remove();   
            savedResponses = savedResponses - 1;
            numberResponses = numberResponses - 1;        

            checkQuestionButtonStatus(savedResponses);
            recalculateNumberChoice();
        });
        
        $('body').on('click', '.edit-choice', function () {
            savedResponses = savedResponses - 1;

            $(this).closest("[id^=responses_]").find("[id^=answer_]").prop( "readonly", false ); 
            var choicesActionSelector = $(this).closest("[id^=responses_]").find(".choice-actions")
                choicesActionSelector.empty();
                choicesActionSelector.append('<button type="button" class="btn btn-success btn-xs save-choice">'
                    + '<?php echo $hotelSatisfactionLang['SaveChoice'] ?>'
                + '</button>'
                + '<button type="button" class="btn btn-default btn-xs ml cancel-choice">' 
                    + '<?php echo $hotelSatisfactionLang['Cancel'] ?>'
                + '</button>');

            checkQuestionButtonStatus(savedResponses);
        });

        // Enable multiresponse section when selected
        $('input[type=radio][name=questionType]').change(function() {
            if (this.value == 'Multiresponse') {
                $("#multiresponse").show();
            }
            else {
                $("#multiresponse").hide();
            }

            checkQuestionButtonStatus(savedResponses);

        });

        // Enable create question button logic
        $("[id^=question_]").keyup(function() {
            checkQuestionButtonStatus(savedResponses);
        })

        $('body').on('click', '#createQuestionButton', function () {
            submitForms(event, 'createQuestionForm')
        });
        
        $('body').on('click', '#updateQuestionButton', function () {
            submitForms(event, 'updateQuestionForm')
        });
    });

    if (!$('#activeCustomizedSatisfaction').parent().find('input').is(':checked')) {
        $('.customizedSatisfactionElement').hide();
    }
    
    if ($('#sendRegardlessScore').parent().find('input').is(':checked')) {
        $('.warningElement').hide();
    }

    if ($('#sendSimultaneousSatisfaction').parent().find('input').is(':checked')) {
        $('#sendDates').hide();
    }

    $('body').on('show.bs.modal', '#updateCategory', function (e) {
        var categoryId = $(e.relatedTarget).data('category-id');
        var categoryTexts = $(e.relatedTarget).data('category-texts');

        $(this).find('[name="category_id"]').val(categoryId);

        <?php foreach ($langs as $lang) { ?>
            $(this).find('[name="category_<?php echo $lang ?>"]').val(categoryTexts["<?php echo $lang ?>"]);
        <?php } ?>
    });
    
    $('body').on('show.bs.modal', '#updateQuestion', function (e) {
        var questionId = $(e.relatedTarget).data('question-id');
        var questionTexts = $(e.relatedTarget).data('question-texts');
        var questionType = $(e.relatedTarget).data('question-type');
        var questionResponses = $(e.relatedTarget).data('question-responses');
        var allowMultipleResponses = $(e.relatedTarget).data('allow-multiple-responses');

        $("#responsesToUpdate").empty();
        $("#updateOtherResponse").prop('checked', false);

        $(this).find('[name="question_id"]').val(questionId);

        <?php foreach ($langs as $lang) { ?>
            $(this).find('[name="updateQuestion_<?php echo $lang ?>"]').val(questionTexts["<?php echo $lang ?>"]);
        <?php } ?>

        $(this).find('[value="' + questionType + '"]').prop("checked", true);

        if (questionType === "Multiresponse") {
            $("#updateMultiresponse").show();
            $("#updateMultipleSelection").prop('checked', allowMultipleResponses);
            questionResponses.forEach( function(response, index, array) {
                if (response.es == "Otros") {
                    $("#updateOtherResponse").prop('checked', true);
                } else {
                    numberResponse = index + 1;
                    $("#responsesToUpdate").prepend('<div id="responses_' + numberResponse + '" class="mt2"><strong><?php echo $hotelSatisfactionLang['OptionAnswer'] ?> <span class="number-response-title">' + numberResponse +'</span></strong><?php foreach ($langs as $lang) { ?>
                        <div style="display:flex" class="form-group mt2">'
                            + '<label style="display:flex" for="answer_' + numberResponse + '_<?php echo $lang ?>">'
                                + '<img'
                                    + ' style="height: fit-content;margin: auto;"' 
                                    + ' src="<?php echo BASE_PATH . DIR_IMG . 'flags/' . $lang . '.png' ?>"'
                                    + ' alt=<?php echo 'flags/' . $lang ?>"'
                                + '>' 
                            + '</label>'
                            + '<input style="margin-left: 1em" id="answer_' + numberResponse + '_<?php echo $lang ?>" name="answer_' + numberResponse + '_<?php echo $lang ?>"'
                                    + ' class="form-control" type="text" required'
                                    + ' placeholder="<?php echo $hotelSatisfactionLang['WriteAnswerPlaceholder'] ?>"'
                                    + 'value="'+ (response["<?php echo $lang ?>"] ?? "") + '"/>'
                            + '<input type="hidden" name="answer_' + numberResponse + '_id" value="' + response['id'] + '"/>'
                        + '</div>'
                        + '<?php } ?>'
                    );
                }
                
            });
        } else {
            $("#updateMultiresponse").hide();
        }

    });

    function checkQuestionButtonStatus(savedResponses) {
        var emptyElements = $("[id^=question_]").filter(function() {
                return !this.value;
            }).length

        if (emptyElements) {
            $("#createQuestionButton").attr("disabled", "disabled");
        } else if ($('input[type=radio][name=questionType]:checked').val() != 'Multiresponse') {
            $("#createQuestionButton").removeAttr("disabled");
        } else if (savedResponses > 1) {
            $("#createQuestionButton").removeAttr("disabled");
        } else {
            $("#createQuestionButton").attr("disabled", "disabled");
        }
    }
    
    function recalculateNumberChoice() {
        var choices = $("#responses").children('[id^=responses_]').length;

        $("#responses").children('[id^=responses_]').each(function () {
            $(this).attr("id","responses_" + choices);

            $(this).find(".number-response-title").text(choices); 
            
            $(this).find('[for^=answer_]').each(function () {
                $(this).attr("for",$(this).attr('for').replace(/_.*_/, '_' + choices + '_'));
            });
            
            $(this).find('[id^=answer_]').each(function () {
                $(this).attr("id", $(this).attr('id').replace(/_.*_/, '_' + choices + '_'));
            });

            $(this).find('[name^=answer_]').each(function () {
                $(this).attr("name", $(this).attr('name').replace(/_.*_/, '_' + choices + '_'));
            });

            choices = choices - 1;

        });

        $("#addAnswerButton").attr("disabled", false);
    }

    function setDropdownCategoryLanguage(lang, categoryId) {
        let languageImagePath = '<?php echo BASE_PATH . DIR_IMG . 'flags/'?>' + lang + '.png';
        $('#dropdownSelectedLanguageImage' + categoryId).attr("src", languageImagePath);
        $('#dropdownSelectedLanguage' + categoryId).html("<strong>" + lang + "</strong>");

        let categoryTitleFilterKey = 'categoryTitle-' + categoryId;
        $("[id^="+categoryTitleFilterKey+"]").hide();
        $("[id^="+categoryTitleFilterKey+"][id$="+lang+"]").show();

        let categoryQuestionFilterKey = 'category-' + categoryId;
        $("[id^="+categoryQuestionFilterKey+"][id*=question]").hide();
        $("[id^="+categoryQuestionFilterKey+"][id*=question][id$="+lang+"]").show();
    }

    function showCustomizedDateConfiguration() {
        var customizedQuestionTypeValue = $("[name='sendSimultaneousSatisfaction']:checked").val();
        if (customizedQuestionTypeValue == 1) {
            $("#sendDates").hide();
        } else if (customizedQuestionTypeValue == 2) {
            $("#sendDates").show();
            $("#afterTimeExplanation").text("<?php echo $hotelSatisfactionLang['Send in differents emails message'] ?>");
        } else {
            $("#sendDates").show();
            $("#afterTimeExplanation").text("<?php echo $hotelSatisfactionLang['Send after checkout message'] ?>");
        }
    }

    function hideElementsOnClick(elementToActice) {
        if ($(elementToActice).is(":visible")) {
            $(elementToActice).hide();
        }
        else {
            $(elementToActice).show();
        }
    }

    function submitForms(event, formName) {
        event.preventDefault();
        $('#' + formName + ' :input').not(':submit').clone().hide().appendTo('#satisfactionConfigurationForm');
        document.forms["satisfactionConfigurationForm"].submit();
        return true;
    }
    

    function updateInputStyles(value, id) {
        if(id == 'warning'){
            $('.' + id + '_satisfaction_slider' ).css('background', 'linear-gradient(to right, red 0%, red ' + value * 10 + '%, #fff ' + value * 10 + '%, lightgray 100%)');
        } else if(id == 'review') {
            $( '.review_satisfaction_slider').css('background', 'linear-gradient(to left, #90f55d ' + (100 - value * 10) + '%, #90f55d 0%, #fff ' + (100 - value * 10) + '%, lightgray 100%)');
        } else if(id == 'defaultScore') {
            $( '.default_score_slider').css('background', 'linear-gradient(to left, #90f55d ' + (100 - value * 10) + '%, #90f55d 0%, #fff ' + (100 - value * 10) + '%, lightgray 100%)');
        }
       
        $('.span-' + id).html(value);

        if ($(window).width() < 1500) {
            // Medium Screens
            $('#thumb-' + id + '-value').css('margin-left', (value * 9.1) + 3.7 + '%'); 

            if(value == 10){
                $('#thumb-' + id + '-value').css('margin-left', (value * 9) + 3.9 + '%'); 
            }
        } else {
            // Big Screens
            $('#thumb-' + id + '-value').css('margin-left', (value * 9.3) + 2.8 + '%'); 

            if(value == 10){
                $('#thumb-' + id + '-value').css('margin-left', (value * 9.25) + 2.8 + '%'); 
            }
        }

    }


    function setFirstInputStyles(valueWarningInput, valueReviewInput, valueDefaultScore) {
        $( '.warning_satisfaction_slider' ).css('background', 'linear-gradient(to right, red 0%, red ' + valueWarningInput * 10 + '%, #fff ' + valueWarningInput * 10 + '%, lightgray 100%)');
        $( '.review_satisfaction_slider').css('background', 'linear-gradient(to left, #90f55d ' + (100 - valueReviewInput * 10) + '%, #90f55d 0%, #fff ' + (100 - valueReviewInput * 10) + '%, lightgray 100%)');
        $( '.default_score_slider').css('background', 'linear-gradient(to left, #90f55d ' + (100 - valueDefaultScore * 10) + '%, #90f55d 0%, #fff ' + (100 - valueDefaultScore * 10) + '%, lightgray 100%)');

        $('.span-warning').html(valueWarningInput);
        $('.span-review').html(valueReviewInput);
        $('.span-defaultScore').html(valueDefaultScore);

        if ($(window).width() < 1500) {
            // Medium Screens
            $('#thumb-warning-value').css('margin-left', (valueWarningInput * 9.1) + 3.5 + '%'); 
            $('#thumb-review-value').css('margin-left', (valueReviewInput * 9.1) + 3.5 + '%'); 
            $('#thumb-defaultScore-value').css('margin-left', (valueDefaultScore * 9.1) + 3.5 + '%'); 

            if(valueWarningInput == 10){
                $('#thumb-warning-value').css('margin-left', (valueWarningInput * 9) + 3.9 + '%'); 
            }

            if(valueDefaultScore == 10){
                $('#thumb-review-value').css('margin-left', (valueReviewInput * 9) + 3.9 + '%'); 
                $('#thumb-defaultScore-value').css('margin-left', (valueDefaultScore * 9) + 3.9 + '%'); 
            }
        } else {
            // Big Screens
            $('#thumb-warning-value').css('margin-left', (valueWarningInput * 9.3) + 2.8 + '%'); 
            $('#thumb-review-value').css('margin-left', (valueReviewInput * 9.3) + 2.8 + '%'); 
            $('#thumb-defaultScore-value').css('margin-left', (valueDefaultScore * 9.3) + 2.8 + '%'); 

            if(valueWarningInput == 10){
                $('#thumb-warning-value').css('margin-left', (valueWarningInput * 9.25) + 2.8 + '%'); 
            }

            if(valueReviewInput == 10){
                $('#thumb-review-value').css('margin-left', (valueReviewInput * 9.25) + 2.8 + '%'); 
            }
            
            if(valueDefaultScore == 10){
                $('#thumb-defaultScore-value').css('margin-left', (valueDefaultScore * 9.25) + 2.8 + '%'); 
            }
        }
    }

    function openPreviewSurvey(){
        const brandId = <?php echo array_get($_SESSION, 'loggedBrandID') ?>;
        const hotelName = <?php echo json_encode(array_get($_SESSION, 'hotelName')); ?>;
        const logoHotel = <?php echo json_encode(array_get($_SESSION, 'logoHotel')); ?>;
        const randomSurveyId = 1;
        
        const surveyInfoString = '{"brand_id": ' + brandId + ', "survey_id": ' + randomSurveyId + ' , "hotel_name": "' + hotelName + '", "logo_hotel": "' + logoHotel + '"}';

        const base64Survey = btoa(surveyInfoString);

        const SURVEYS_URL = "<?php echo SURVEYS_URL; ?>"
        const surveyUrl = SURVEYS_URL + '/survey/' + base64Survey + '?demo=true'

        window.open(surveyUrl, '_blank');
      
    }

</script>

<!-- Create category modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="createCategory">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><?php echo $hotelSatisfactionLang['Create Category'] ?></h4>
            </div>
            <form name="createCategoryForm" id="createCategoryForm" method="post" action="<?php echo $url['dir1'] ?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">

                            <?php foreach ($langs as $lang) { ?>
                                <div class="form-group">
                                    <label for="category_ <?php echo $lang ?>"> <img
                                                src="<?php echo BASE_PATH . DIR_IMG . 'flags/' . $lang . '.png' ?>"
                                                alt="<?php echo 'flags/' . $lang ?>"
                                                class="pl flag-icon"> </label>
                                    <input id="category_<?php echo $lang ?>" name="category_<?php echo $lang ?>"
                                           class="form-control" type="text" required
                                           placeholder="<?php echo $hotelSatisfactionLang['Create Category Lang'] ?>"/>

                                </div>
                            <?php } ?>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input name="create_category" type="hidden" value="create_category">
                    <input type="submit" value="<?php echo $hotelSatisfactionLang['Create Category'] ?>"
                           class="btn btn-primary" onclick="submitForms(event, 'createCategoryForm')"/>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<!-- End create category modal -->

<!-- Update category modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="updateCategory">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><?php echo $hotelSatisfactionLang['Update Category'] ?></h4>
            </div>
            <form name="updateCategoryForm" id="updateCategoryForm" method="post" action="<?php echo $url['dir1'] ?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="alert alert-warning" role="alert">
                                <p><?php echo $hotelSatisfactionLang['Update Category Explanation'] ?></p>
                            </div>

                            <?php foreach ($langs as $lang) { ?>
                                <div class="form-group">
                                    <label for="category_ <?php echo $lang ?>"> <img
                                                src="<?php echo BASE_PATH . DIR_IMG . 'flags/' . $lang . '.png' ?>"
                                                alt="<?php echo 'flags/' . $lang ?>"
                                                class="pl flag-icon"> </label>
                                    <input
                                        type="text"
                                        id="category_<?php echo $lang ?>"
                                        name="category_<?php echo $lang ?>"
                                        class="form-control"
                                        placeholder="<?php echo $hotelSatisfactionLang['Create Category Lang'] ?>"
                                        required
                                    />

                                </div>
                            <?php } ?>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input name="update_category" type="hidden" value="update_category">
                    <input name="category_id" type="hidden">
                    <input type="submit" value="<?php echo $hotelSatisfactionLang['Update Category'] ?>"
                           class="btn btn-primary" onclick="submitForms(event, 'updateCategoryForm')"/>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<!-- End create category modal -->

<!-- Delete category modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="deleteCategory">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><?php echo $hotelSatisfactionLang['Delete Category'] ?></h4>
            </div>
            <form name="deleteCategoryForm" id="deleteCategoryForm" method="post" action="<?php echo $url['dir1'] ?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <input type="hidden" id="delete_category_id" name="delete_category_id">
                            <p><?php echo $hotelSatisfactionLang['Delete Category Text'] ?></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input name="delete_category" type="hidden" value="delete_category">
                    <input type="submit" value="<?php echo $hotelSatisfactionLang['Delete Category'] ?>"
                           class="btn btn-primary" onclick="submitForms(event, 'deleteCategoryForm')"/>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End delete category modal -->

<!-- Create question modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="createQuestion">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><?php echo $hotelSatisfactionLang['Create Question'] ?></h4>
            </div>
            <form name="createQuestionForm" id="createQuestionForm" method="post" action="<?php echo $url['dir1'] ?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb2">
                                <input type="hidden" name="category_id" id="create_question_category_id"/>
                                <strong style="vertical-align: top;"><?php echo $hotelSatisfactionLang['SelectResponseType'] ?></strong>
                            </div>
                            <div class="mb2">
                                <input type="radio" id="ratingType" name="questionType" value="Rating" checked>
                                <label class="vMiddle" for="ratingType">
                                    <?php echo $hotelSatisfactionLang['RatingAndCommnet'] ?>
                                </label>
                                <input style="margin-left: 1em" type="radio" id="multiType" name="questionType" value="Multiresponse">
                                <label class="vMiddle" for="multiType">
                                    <?php echo $hotelSatisfactionLang['Multiresponse'] ?>
                                </label>
                                <input style="margin-left: 1em" type="radio" id="openQuestion" name="questionType" value="Open Question">
                                <label class="vMiddle" for="openQuestion">
                                    <?php echo $hotelSatisfactionLang['Open Question'] ?>
                                </label>
                            </div>
                            
                            <?php foreach ($langs as $lang) { ?>
                                <div style="display:flex" class="form-group">
                                    <label style="display:flex" for="question_<?php echo $lang ?>"> 
                                        <img
                                            style="height: fit-content;margin: auto;"
                                            src="<?php echo BASE_PATH . DIR_IMG . 'flags/' . $lang . '.png' ?>"
                                            alt="<?php echo 'flags/' . $lang ?>"
                                        > 
                                    </label>
                                    <input style="margin-left: 1em" id="question_<?php echo $lang ?>" name="question_<?php echo $lang ?>"
                                           class="form-control" type="text" required
                                           placeholder="<?php echo $hotelSatisfactionLang['Create Question Lang'] ?>"/>

                                </div>
                            <?php } ?>

                            <div id="multiresponse">
                                <h4><?php echo $hotelSatisfactionLang['Responses'] ?></h4>
                                <p><strong><?php echo $hotelSatisfactionLang['Responses Explanation'] ?></strong></p>
                                <div class="input-group">
                                    <input style="vertical-align: top" type="checkbox" id="otherResponse" name="otherResponse" checked>
                                    <label style="display:initial; margin-left: 1em" for="otherResponse"><?php echo $hotelSatisfactionLang['Other option explanation'] ?></label>
                                </div>
                                <div class="input-group">
                                    <input style="vertical-align: top" type="checkbox" id="multipleSelection" name="multipleSelection">
                                    <label style="display:initial; margin-left: 1em" for="multipleSelection"><?php echo $hotelSatisfactionLang['Multiple selection explanation'] ?></label>
                                </div>
                                <button id="addAnswerButton" type="button" class="btn btn-success btn-xs mt2">
                                    <?php echo $hotelSatisfactionLang['AddAnswer'] ?>
                                </button>

                                <div id="responses">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input name="create_question" type="hidden" value="create_question">
                    <input id="createQuestionButton" type="submit" value="<?php echo $hotelSatisfactionLang['Create Question'] ?>"
                           class="btn btn-primary"/>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<!-- End create question modal -->

<!-- Update question modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="updateQuestion">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><?php echo $hotelSatisfactionLang['Update Question'] ?></h4>
            </div>
            <form name="updateQuestionForm" id="updateQuestionForm" method="post" action="<?php echo $url['dir1'] ?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="alert alert-warning" role="alert">
                                <p><?php echo $hotelSatisfactionLang['Update Question Explanation'] ?></p>
                            </div>
                            <div class="mb2">
                                <strong style="vertical-align: top;"><?php echo $hotelSatisfactionLang['SelectResponseType'] ?></strong>
                            </div>
                            <div class="mb2">
                                <input type="radio" id="ratingType" name="questionType" value="Rating" checked disabled>
                                <label class="vMiddle" for="ratingType">
                                    <?php echo $hotelSatisfactionLang['RatingAndCommnet'] ?>
                                </label>
                                <input style="margin-left: 1em" type="radio" id="multiType" name="questionType" value="Multiresponse" disabled>
                                <label class="vMiddle" for="multiType">
                                    <?php echo $hotelSatisfactionLang['Multiresponse'] ?>
                                </label>
                                <input style="margin-left: 1em" type="radio" id="openQuestion" name="questionType" value="Open Question" disabled>
                                <label class="vMiddle" for="openQuestion">
                                    <?php echo $hotelSatisfactionLang['Open Question'] ?>
                                </label>
                            </div>
                            
                            <?php foreach ($langs as $lang) { ?>
                                <div style="display:flex" class="form-group">
                                    <label style="display:flex" for="updateQuestion_<?php echo $lang ?>"> 
                                        <img
                                            style="height: fit-content;margin: auto;"
                                            src="<?php echo BASE_PATH . DIR_IMG . 'flags/' . $lang . '.png' ?>"
                                            alt="<?php echo 'flags/' . $lang ?>"
                                        > 
                                    </label>
                                    <input style="margin-left: 1em" id="updateQuestion_<?php echo $lang ?>" name="updateQuestion_<?php echo $lang ?>"
                                           class="form-control" type="text" required
                                           placeholder="<?php echo $hotelSatisfactionLang['Create Question Lang'] ?>"/>

                                </div>
                            <?php } ?>

                            <div id="updateMultiresponse">
                                <h4><?php echo $hotelSatisfactionLang['Responses'] ?></h4>
                                <p><strong><?php echo $hotelSatisfactionLang['Responses Explanation'] ?></strong></p>
                                <div class="input-group">
                                    <input style="vertical-align: top" type="checkbox" id="updateOtherResponse" name="updateOtherResponse" disabled>
                                    <label style="display:initial; margin-left: 1em" for="updateOtherResponse"><?php echo $hotelSatisfactionLang['Other option explanation'] ?></label>
                                </div>
                                <div class="input-group">
                                    <input style="vertical-align: top" type="checkbox" id="updateMultipleSelection" name="updateMultipleSelection" disabled>
                                    <label style="display:initial; margin-left: 1em" for="updateMultipleSelection"><?php echo $hotelSatisfactionLang['Multiple selection explanation'] ?></label>
                                </div>
                                <div id="responsesToUpdate">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input name="update_question_text" type="hidden" value="update_question_text">
                    <input name="question_id" type="hidden">
                    <input id="updateQuestionButton" type="submit" value="<?php echo $hotelSatisfactionLang['Update Question'] ?>"
                           class="btn btn-primary"/>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<!-- End update question modal -->

<!-- Delete question modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="deleteQuestion">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><?php echo $hotelSatisfactionLang['Delete Question'] ?></h4>
            </div>
            <form name="deleteQuestionForm" id="deleteQuestionForm" method="post" action="<?php echo $url['dir1'] ?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <input type="hidden" id="delete_question_id" name="delete_question_id">
                            <p><?php echo $hotelSatisfactionLang['Delete Question Text'] ?></p>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input name="delete_query" type="hidden" value="delete_query">
                    <input type="submit" value="<?php echo $hotelSatisfactionLang['Delete Question'] ?>"
                           class="btn btn-primary" onclick="submitForms(event, 'deleteQuestionForm')"/>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $hotelSatisfactionLang['close_modal'] ?></button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End dDelete question modal -->