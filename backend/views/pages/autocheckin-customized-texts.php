<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
} ?>

<?php include LANG . $_SESSION['userLang'] . '/autocheckin-customized-texts.php' ?>
<div id="wrapper">
    <?php include TEMPLATES . 'hotel-sidebar.php'; ?>
    <div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'hotel-profile-menu.php'; ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70" alt="top bar logo">
        </div>
        <div class="utility-bar">
            <div class="col-lg-12">
                <h1 class="pull-left"><i class="fa fa-file-text" style="margin-right: 10px;"></i><?php echo $lang['section'] ?></h1>
                <div class="breadcrumbs pull-right">
                    <ul>
                        <?php include(TEMPLATES . 'breadcrumbs.php'); ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mainContent" id="fullContainer">
            <?php foreach ($autocheckinPersonalizedTexts["data"] as $personalizedText) { ?>
                <?php if ($personalizedText['type_name'] != 'confirmation') { ?>
                    <div class="col-lg-12 mt2">
                        <div class="panel-heading">
                            <h2 style="margin-top: 0;"><?php echo $lang[$personalizedText['type_name'] . 'Title'] ?></h2>
                        </div>
                        <div class="col-lg-12">
                            <form method="POST">
                                <div class="panel panel-default noPadding">
                                    <div class="panel-heading">
                                        <?php echo $lang[$personalizedText['type_name'] . 'Subtitle'] ?>
                                    </div>

                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12" style="max-width: 830px;" id="<?php echo $personalizedText['type_name'] ?>_customized_text">
                                                <div class="form-group">
                                                    <div class="input-group col-lg-12">
                                                        <p class="col-lg-12" for="choose_ep_text"> <?php echo $lang[$personalizedText['type_name'] . 'Text'] ?>
                                                        </p>
                                                        <label class="col-lg-12">
                                                            <input type="checkbox" name="<?php echo $personalizedText['type_name'] ?>_customized_text" <?php echo $personalizedText['active'] ? 'checked' : '' ?>>
                                                            <?php echo $lang['active'] ?>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div style="margin-top: 20px;">
                                                    <ul class="nav nav-tabs">
                                                        <?php foreach ($langs as $language) {
                                                        ?>
                                                            <li class="<?php echo array_get($language, 'name') == 'en' ? 'default-tab' : '' ?>">
                                                                <a data-toggle="tab" href="#<?php echo $personalizedText['type_name'] ?>_customized_text_<?php echo array_get($language, 'name') ?>"><img src="../../public/img/flags/<?php echo array_get($language, 'name') ?>.png" alt="<?php echo array_get($language, 'name') ?>" /></a>
                                                            </li>
                                                        <?php
                                                        } ?>
                                                    </ul>
                                                    <div class="tab-content">
                                                        <?php foreach ($langs as $language) {
                                                        ?>
                                                            <div class="tab-pane fade in <?php echo array_get($language, 'name') == 'en' ? 'default-tab' : '' ?>" id="<?php echo $personalizedText['type_name'] ?>_customized_text_<?php echo array_get($language, 'name') ?>">
                                                                <textarea name="<?php echo $personalizedText['type_name'] ?>_customized_text_<?php echo array_get($language, 'name') ?>" id="<?php echo $personalizedText['type_name'] ?>_editor_<?php echo array_get($language, 'name') ?>"></textarea>
                                                            </div>
                                                        <?php
                                                        } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                    </div>
                                </div>
                                <input type="hidden" name="type" value="<?php echo $personalizedText['type_name'] ?>">
                                <input type="hidden" name="save" value="OK">
                                <div class="col-lg-3">
                                    <input type="submit" class="btn btn-success btn-lg btn-block" style="margin-bottom: 10px" value="<?php echo $lang['save changes'] ?>">
                                </div>
                            </form>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>
</div>



<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jodit/3.1.39/jodit.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jodit/3.1.39/jodit.min.js"></script>
<script src="<?php echo DIR_JS ?>feedback-errors.js"></script>
<script>
    var wysiwygOpts = {
        width: 800,
        height: 250,
        buttons: "bold,strikethrough,underline,italic,|,,ul,ol,,outdent,indent,,|,link,,align,undo,redo,\n",
    }


    <?php foreach ($autocheckinPersonalizedTexts["data"] as $personalizedText) {
        if ($personalizedText['type_name'] != 'confirmation') {

            $personalizedTextTranslations = null;
            foreach ($personalizedText["translations"] as $translation) {
                $personalizedTextTranslations[$translation["lang"]] = $translation["text"];
            }

            foreach ($langs as $lang) {
                $texts = array_get($personalizedTextTranslations, array_get($lang, 'name'));
    ?>

            var <?php echo array_get($personalizedText, 'type_name') ?>TextsEditor1_<?php echo array_get($lang, 'name') ?> = new Jodit('#<?php echo array_get($personalizedText, 'type_name') ?>_editor_<?php echo array_get($lang, 'name') ?>', wysiwygOpts);
            <?php echo array_get($personalizedText, 'type_name') ?>TextsEditor1_<?php echo array_get($lang, 'name') ?>.value = `<?php echo addslashes($texts ?? '') ?>`;
        <?php } ?>
    <?php }
    } ?>

    $(".default-tab").each(function(index) {
        $(this).addClass("active");
    });
</script>