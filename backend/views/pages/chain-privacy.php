<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
} ?>

<?php include LANG . $_SESSION['userLang'] . '/eprivacy-management.php' ?>
    <div id="wrapper">
        <?php include TEMPLATES . 'hotel-sidebar.php'; ?>
        <div id="page-content-wrapper">
            <div class="top-bar">
                <?php include TEMPLATES . 'chain-management-menu.php'; ?>
                <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77"
                     height="70"
                     alt="top bar logo">
            </div>
            <div class="utility-bar">
                <div class="col-lg-12">
                    <h1 class="pull-left"><i class="fa fa-user-secret"></i><?php echo $lang['Title'] ?></h1>
                    <div class="breadcrumbs pull-right">
                        <ul>
                            <?php include(TEMPLATES . 'breadcrumbs.php'); ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="mainContent" id="fullContainer">
                <div class="col-lg-12 mt2">
                    <div class="col-lg-12">
                        <form method="POST">
                            <div class="panel panel-default noPadding">
                                <div class="panel-heading">
                                    <?php echo $lang['As responsable you have the next options'] ?>
                                </div>

                                <div class="panel-body">
                                    <div class="row">
                                        <?php if ($chainAllowed) { ?>
                                            <div class="col-lg-9">

                                                <div class="form-group">
                                                    <label for="companyName"><?php echo $lang['Company name'] ?></label>
                                                    <input type="text" class="form-control" name="company_name"
                                                           id="companyName"
                                                           value="<?php echo array_get($brand_eprivacy_info, 'company_name') ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="companyAddress"><?php echo $lang['Company address'] ?></label>
                                                    <input type="text" class="form-control" name="company_address"
                                                           id="companyAddress"
                                                           value="<?php echo array_get($brand_eprivacy_info, 'company_address') ?>">
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="companyNIF"><?php echo $lang['Company NIF'] ?></label>
                                                            <input type="text" class="form-control" name="company_nif"
                                                                   id="companyNIF"
                                                                   value="<?php echo array_get($brand_eprivacy_info, 'company_nif') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="companyEmail"><?php echo $lang['Company email'] ?></label>
                                                            <input type="text" class="form-control" name="company_email"
                                                                   id="companyEmail"
                                                                   value="<?php echo array_get($brand_eprivacy_info, 'company_email') ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="col-lg-3">
                                            <!--                                            <div class="form-group">-->
                                            <!--                                                <div class="input-group col-lg-12">-->
                                            <!--                                                    <label class="col-lg-12"-->
                                            <!--                                                           for="restricted_portal"> --><?php //echo $lang['Choose portal captive type'] ?>
                                            <!--                                                        :</label>-->
                                            <!--                                                    <select class="form-control" id="restricted_portal"-->
                                            <!--                                                            name="restricted_portal">-->
                                            <!--                                                        <option value="restrictive" -->
                                            <?php //echo array_get($brand_eprivacy_info, 'restricted_portal') ? 'selected' : '' ?><!-->
                                            <?php //echo $lang['Restrictive'] ?><!--</option>-->
                                            <!--                                                        <option value="classic" -->
                                            <?php //echo !array_get($brand_eprivacy_info, 'restricted_portal') ? 'selected' : '' ?><!-->
                                            <?php //echo $lang['Classic'] ?><!--</option>-->
                                            <!--                                                    </select>-->
                                            <!---->
                                            <!--                                                </div>-->
                                            <!--                                            </div>-->
                                            <!--                                            --><?php //if($chainAllowed){ ?>
                                            <!--                                                <div class="form-group">-->
                                            <!--                                                    <div class="input-group col-lg-12">-->
                                            <!--                                                        <label class="col-lg-12"-->
                                            <!--                                                               for="choose_ep_text"> --><?php //echo $lang['Choose eprivacy text'] ?>
                                            <!--                                                            : </label>-->
                                            <!--                                                        <select class="form-control" id="choose_ep_text" name="choose_ep_text">-->
                                            <!--                                                            <option value="default" -->
                                            <?php //echo array_get($first_eprivacy_page_module_content, 'chain_config') == 'default' ? 'selected' : '' ?><!-->
                                            <?php //echo $lang['Use default configuration'] ?><!--</option>-->
                                            <!--                                                            <option value="custom_content" -->
                                            <?php //echo array_get($first_eprivacy_page_module_content, 'chain_config') == 'custom_content' ? 'selected' : '' ?><!-->
                                            <?php //echo $lang['Set your Eprivacy legal text'] ?><!--</option>-->
                                            <!--                                                            <option value="own_vars" -->
                                            <?php //echo array_get($first_eprivacy_page_module_content, 'chain_config') == 'own_vars' ? 'selected' : '' ?><!-->
                                            <?php //echo $lang['Set your Info'] ?><!--</option>-->
                                            <!--                                                        </select>-->
                                            <!---->
                                            <!--                                                    </div>-->
                                            <!--                                                </div>-->
                                            <!--                                            --><?php //} ?>
                                        </div>
                                    </div>


                                    <?php if ($chainAllowed) { ?>
                                        <div class="row" style="min-height: 39rem;">
                                            <ul class="nav nav-tabs">
                                                <li class="default-tab"><a data-toggle="tab"
                                                                           href="#first_eprivacy_page"><?php echo $lang['First Eprivay page'] ?></a>
                                                </li>
                                                <li><a data-toggle="tab"
                                                       href="#second_eprivacy_page"><?php echo $lang['Second Eprivay page'] ?></a>
                                                </li>
                                                <li><a data-toggle="tab"
                                                       href="#legal_text"><?php echo $lang['Legal text'] ?></a>
                                                </li>

                                            </ul>
                                            <div class="tab-content">
                                                <div class="col-lg-12 tab-pane fade in default-tab"
                                                     style="max-width: 830px;" id="first_eprivacy_page">
                                                    <label style="margin-top: 30px;"> <?php echo $lang['First Eprivay page'] ?></label>
                                                    <label class="pull-right" style="margin-top: 30px;"><input
                                                                type="checkbox"
                                                                name="eprivacy_text"
                                                                class="" <?php echo array_get($first_eprivacy_page_module_content, 'chain_active') ? 'checked' : '' ?>> <?php echo $lang['Active'] ?>
                                                    </label>
                                                    <div>
                                                        <ul class="nav nav-tabs">
                                                            <?php foreach ($langs as $language) { ?>
                                                                <li class="<?php echo array_get($language, 'name') == 'en' ? 'default-tab' : '' ?>">
                                                                    <a data-toggle="tab"
                                                                       href="#first_eprivacy_page_<?php echo array_get($language, 'name') ?>"><img
                                                                                src="../../public/img/flags/<?php echo array_get($language, 'name') ?>.png"
                                                                                alt="<?php echo array_get($language, 'name') ?>"/></a>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                        <div class="tab-content">
                                                            <?php foreach ($langs as $language) { ?>

                                                                <div class="tab-pane fade in <?php echo array_get($language, 'name') == 'en' ? 'default-tab' : '' ?>"
                                                                     id="first_eprivacy_page_<?php echo array_get($language, 'name') ?>">
                                                                    <div class="input-group">
                                                            <textarea
                                                                    name="eprivacy_text_<?php echo array_get($language, 'name') ?>"
                                                                    id="editor_<?php echo array_get($language, 'name') ?>"
                                                            ></textarea>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12  tab-pane fade in" style="max-width: 830px;"
                                                     id="second_eprivacy_page">
                                                    <label style="margin-top: 30px;"> <?php echo $lang['Second Eprivay page'] ?></label>
                                                    <label class="pull-right" style="margin-top: 30px;"><input
                                                                type="checkbox"
                                                                name="second_eprivacy_text"
                                                                class="" <?php echo array_get($second_eprivacy_page_module_content, 'chain_active') ? 'checked' : '' ?>> <?php echo $lang['Active'] ?>
                                                    </label>
                                                    <div>
                                                        <ul class="nav nav-tabs">
                                                            <?php foreach ($langs as $language) { ?>
                                                                <li class="<?php echo array_get($language, 'name') == 'en' ? 'default-tab' : '' ?>">
                                                                    <a data-toggle="tab"
                                                                       href="#second_eprivacy_page_<?php echo array_get($language, 'name') ?>"><img
                                                                                src="../../public/img/flags/<?php echo array_get($language, 'name') ?>.png"
                                                                                alt="<?php echo array_get($language, 'name') ?>"/></a>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                        <div class="tab-content">
                                                            <?php foreach ($langs as $language) { ?>

                                                                <div class="tab-pane fade in <?php echo array_get($language, 'name') == 'en' ? 'default-tab' : '' ?>"
                                                                     id="second_eprivacy_page_<?php echo array_get($language, 'name') ?>">
                                                                    <div class="input-group">
                                                            <textarea
                                                                    name="second_eprivacy_text_<?php echo array_get($language, 'name') ?>"
                                                                    id="editor2_<?php echo array_get($language, 'name') ?>"
                                                            ></textarea>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <?php if(!$restrictive) { ?>
                                                        <label style="margin-top: 30px;"> <?php echo "NOT CLIENT" ?></label>
                                                        <label class="pull-right" style="margin-top: 30px;"><input
                                                                    type="checkbox"
                                                                    name="second_eprivacy_not_client_text"
                                                                    class="" <?php echo array_get($second_eprivacy_page_restrictive_module_content, 'chain_active') ? 'checked' : '' ?>> <?php echo $lang['Active'] ?>
                                                        </label>
                                                        <div>
                                                            <ul class="nav nav-tabs">
                                                                <?php foreach ($langs as $language) {
                                                                    ?>
                                                                    <li class="<?php echo array_get($language, 'name') == 'en' ? 'default-tab' : '' ?>">
                                                                        <a data-toggle="tab"
                                                                           href="#second_eprivacy_page_not_client_<?php echo array_get($language, 'name') ?>"><img
                                                                                    src="../../public/img/flags/<?php echo array_get($language, 'name') ?>.png"
                                                                                    alt="<?php echo array_get($language, 'name') ?>"/></a>
                                                                    </li>
                                                                    <?php
                                                                } ?>
                                                            </ul>
                                                            <div class="tab-content">
                                                                <?php foreach ($langs as $language) {
                                                                    ?>

                                                                    <div class="tab-pane fade in <?php echo array_get($language, 'name') == 'en' ? 'default-tab' : '' ?>"
                                                                         id="second_eprivacy_page_not_client_<?php echo array_get($language, 'name') ?>">
                                                                        <div class="input-group">
                                                            <textarea
                                                                    name="second_eprivacy_not_client_text_<?php echo array_get($language, 'name') ?>"
                                                                    id="editor2_not_client_<?php echo array_get($language, 'name') ?>"
                                                            ></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                } ?>
                                                            </div>
                                                        </div>
                                                    <?php } ?>


                                                </div>

                                                <div class="col-lg-12 tab-pane fade in" style="max-width: 830px;"
                                                     id="legal_text">
                                                    <label style="margin-top: 30px;"> <?php echo $lang['Legal text'] ?></label>
                                                    <label class="pull-right" style="margin-top: 30px;"><input
                                                                type="checkbox"
                                                                name="legal_text"
                                                                class="" <?php echo array_get($legal_text_page_module_content, 'chain_active') ? 'checked' : '' ?>> <?php echo $lang['Active'] ?>
                                                    </label>
                                                    <div>
                                                        <ul class="nav nav-tabs">
                                                            <?php foreach ($langs as $language) { ?>
                                                                <li class="<?php echo array_get($language, 'name') == 'en' ? 'default-tab' : '' ?>">
                                                                    <a data-toggle="tab"
                                                                       href="#legal_text_<?php echo array_get($language, 'name') ?>"><img
                                                                                src="../../public/img/flags/<?php echo array_get($language, 'name') ?>.png"
                                                                                alt="<?php echo array_get($language, 'name') ?>"/></a>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                        <div class="tab-content">
                                                            <?php foreach ($langs as $language) { ?>

                                                                <div class="tab-pane fade in <?php echo array_get($language, 'name') == 'en' ? 'default-tab' : '' ?>"
                                                                     id="legal_text_<?php echo array_get($language, 'name') ?>">
                                                                    <div class="input-group">
                                                             <textarea
                                                                     name="legal_text_<?php echo array_get($language, 'name') ?>"
                                                                     id="editor3_<?php echo array_get($language, 'name') ?>"
                                                             ></textarea>

                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <input type="hidden" name="save" value="OK">
                            <div class="col-lg-3">
                                <input type="submit" class="btn btn-success btn-lg btn-block"
                                       value="<?php echo $lang['Save changes'] ?>">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>


<?php if ($chainAllowed) { ?>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jodit/3.1.39/jodit.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jodit/3.1.39/jodit.min.js"></script>
    <script src="<?php echo DIR_JS ?>feedback-errors.js"></script>
    <script>

        var wysiwygOpts = {
            width: 800,
            height: 250,
            buttons: "bold,strikethrough,underline,italic,|,,ul,ol,,outdent,indent,,|,link,,align,undo,redo,\n",
        }
        <?php foreach ($langs as $lang ){?>

        var privacyPolicyEditor1_<?php echo array_get($lang, 'name') ?> = new Jodit('#editor_<?php echo array_get($lang, 'name') ?>', wysiwygOpts);
        privacyPolicyEditor1_<?php echo array_get($lang, 'name') ?>.value = "<?php echo preg_replace('#<script(.*?)>(.*?)</script>#is', '', array_get($first_eprivacy_page_module_content, 'chain_info.' . array_get($lang, 'name'))) ?>";

        <?php } ?>

        const pell = window.pell;
        <?php foreach ($langs as $lang ){?>

        var privacyPolicyEditor2_<?php echo array_get($lang, 'name') ?> = new Jodit('#editor2_<?php echo array_get($lang, 'name') ?>', wysiwygOpts);
        privacyPolicyEditor2_<?php echo array_get($lang, 'name') ?>.value = "<?php echo preg_replace('#<script(.*?)>(.*?)</script>#is', '', array_get($second_eprivacy_page_module_content, 'chain_info.' . array_get($lang, 'name'))) ?>";

        <?php } ?>

        <?php if(!$restrictive) {foreach ($langs as $lang) {
        ?>
        var privacyPolicyEditor2_<?php echo array_get($lang, 'name') ?> = new Jodit('#editor2_not_client_<?php echo array_get($lang, 'name') ?>', wysiwygOpts);
        privacyPolicyEditor2_<?php echo array_get($lang, 'name') ?>.value = "<?php echo preg_replace('#<script(.*?)>(.*?)</script>#is', '', array_get($second_eprivacy_page_restrictive_module_content, 'chain_info.' . array_get($lang, 'name'))) ?>";

        <?php
        }} ?>





        <?php foreach ($langs as $lang ){?>

        var privacyPolicyEditor3_<?php echo array_get($lang, 'name') ?> = new Jodit('#editor3_<?php echo array_get($lang, 'name') ?>', wysiwygOpts);
        privacyPolicyEditor3_<?php echo array_get($lang, 'name') ?>.value = "<?php echo preg_replace('#<script(.*?)>(.*?)</script>#is', '', array_get($legal_text_page_module_content, 'chain_info.' . array_get($lang, 'name'))) ?>";

        <?php } ?>

        $(".default-tab").each(function (index) {
            $(this).addClass("active");
        });


    </script>
<?php } ?>