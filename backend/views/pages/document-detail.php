<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
} ?>

<?php include LANG . $_SESSION['userLang'] . '/document-detail.php' ?>
<div id="wrapper">
    <?php include TEMPLATES . 'hotel-sidebar.php'; ?>
    <div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'hotel-profile-menu.php'; ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77"
                 height="70"
                 alt="top bar logo">
        </div>
        <div class="utility-bar">
            <div class="col-lg-12">
                <h1 class="pull-left"><i class="fa fa-file-text"></i><?php echo $lang['Title'] ?></h1>
                <div class="breadcrumbs pull-right">
                    <ul>
                        <?php include(TEMPLATES . 'breadcrumbs.php'); ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="mainContent" id="fullContainer">
            <form method="POST" name="document_form" id="document_form">

                <!-- Document data -->
                <div class="row" style="padding-left: 30px; padding-right: 30px">
                    <div class="col-lg-6 mt2">
                        <div class="alert alert-info" role="alert" style="margin-bottom: 20px">
                            <?php echo $lang['Document name info'] ?>
                        </div>
                        <div class="form-group">
                            <label for="document_name"><?php echo $lang['Document name label'] ?></label>
                            <input type="text" class="form-control" name="document_name" id="document_name">
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"
                                       name="active"
                                       id="active"
                                       value="1"><?php echo $lang['Document active check label'] ?>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Available document variables list -->
                <div class="row" style="padding-left: 30px; padding-right: 30px">
                    <div class="col-lg-12 mt2">
                        <div class="panel panel-default noPadding">
                            <div class="panel-heading">
                                <?php echo $lang['Document variables list'] ?>
                            </div>
                            <ul class="list-group" style="columns: 3; padding: 15px 0;">
                                <?php foreach ($availableVariablesList as $variable) { ?>
                                    <li class="list-group-item" style="border: none">
                                        <?php echo $variable['placeholder'] ?>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Document translations data -->
                <div class="row" style="padding-left: 30px; padding-right: 30px">
                    <div class="col-lg-12 mt2">
                        <ul id="documentTab" class="nav nav-tabs">
                            <?php foreach ($languages as $language) { ?>
                                <li class="<?php echo $language['name'] == 'en' ? 'default-tab' : '' ?>">
                                    <a data-toggle="tab" href="#document_translation_<?php echo $language['name'] ?>">
                                       <img src="../../public/img/flags/<?php echo $language['name'] ?>.png"
                                            alt="<?php echo $language['name'] ?>"/>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                        <div class="tab-content mt2">
                            <?php foreach ($languages as $language) { ?>
                                <div 
                                    class="tab-pane fade in <?php echo $language['name'] == 'en' ? 'default-tab' : '' ?>"
                                    id="document_translation_<?php echo $language['name'] ?>"
                                >
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="alert alert-info" role="alert" style="margin-bottom: 20px">
                                                <?php if ($language['name'] == 'en') { ?>
                                                    <?php echo $lang['Document english translation info'] ?><br>
                                                <?php } ?>
                                                <?php echo $lang['Document translation info'] ?>
                                            </div>
                                            <div class="form-group">
                                                <label for="document_title_<?php echo $language['name'] ?>">
                                                    <?php echo $lang['Document title label'] ?>
                                                </label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="document_title_<?php echo $language['name'] ?>"
                                                       id="document_title_<?php echo $language['name'] ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="document_content_<?php echo $language['name'] ?>">
                                                    <?php echo $lang['Document content label'] ?>
                                                </label>
                                                <textarea 
                                                    class="form-control"
                                                    name="document_content_<?php echo $language['name'] ?>"
                                                    id="document_content_<?php echo $language['name'] ?>"
                                                ></textarea>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                       
                                                <?php foreach ($checkboxConfigurations as $config) {  
                                                    $checkboxTypeName = $config['checkbox_type_name'];
                                                ?>
                                                    <label for="checkbox_<?php echo $checkboxTypeName . '_' . $language['name']; ?>" class="mt2">
                                                        <?php echo $lang[ucfirst(str_replace('_', ' ', $checkboxTypeName))] ?>
                                                    </label>
                                                    <input type="text"
                                                        class="form-control"
                                                        name="checkbox_<?php echo $checkboxTypeName . '_' . $language['name']; ?>"
                                                        id="checkbox_<?php echo $checkboxTypeName . '_' . $language['name']; ?>"
                                                        >
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                         
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div>
                </div>
                <!-- Save and delete buttons -->
                <div class="row" style="padding-left: 30px; padding-right: 30px">
                    <div class="col-lg-3 mt2 mb2">
                        <input type="submit" class="btn btn-success btn-lg btn-block"
                               value="<?php echo $lang['Save changes'] ?>">
                    </div>
                    <?php if ($action == 'update') { ?>
                        <div class="col-lg-3 mt2 mb2">
                            <input type="button"
                                   class="btn btn-danger btn-lg btn-block"
                                   value="<?php echo $lang['Delete document'] ?>"
                                   data-toggle="modal"
                                   data-target="#delete_document">
                        </div>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete document modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="delete_document">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><?php echo $lang['Delete document modal title'] ?></h4>
            </div>
            <form method="POST" name="delete_document_form" id="delete_document_form">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <p><?php echo $lang['Delete document modal content'] ?></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="delete_document" value="true">
                    <input type="button" value="<?php echo $lang['Delete document'] ?>"
                           class="btn btn-danger"
                           onclick="submitForms(event, 'delete_document_form')"/>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jodit/3.1.39/jodit.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jodit/3.1.39/jodit.min.js"></script>
<script src="<?php echo DIR_JS ?>feedback-errors.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.css"></link>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/mode/xml/xml.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/mode/javascript/javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/mode/css/css.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/mode/htmlmixed/htmlmixed.min.js"></script>

<script>

$(document).ready(function() {
  loadDocumentData();

  $('#document_form').bootstrapValidator({
    feedbackIcons: {
      valid: 'glyphicon glyphicon-ok',
      invalid: 'glyphicon glyphicon-remove',
      validating: 'glyphicon glyphicon-refresh'
    },
    excluded: ':disabled',
    fields: {
      document_name: {
        validators: {
          stringLength: {
            min: 2,
          },
          notEmpty: {
            message: '<?php echo $lang['Document name validation message'] ?>'
          }
        }
      },
      document_title_en: {
        validators: {
          notEmpty: {
            message: '<?php echo $lang['Document title validation message'] ?>'
          }
        }
      },
      document_content_en: {
        trigger: 'change',
        validators: {

            stringLength: {
                message: '<?php echo $lang['Document content max lenght message'] ?>',
                max: 65535 // Max length for TEXT type in MySQL
            },
            notEmpty: {
                message: '<?php echo $lang['Document content validation message'] ?>'
            },
        }
      }
    }
  })

    function addCheckboxValidators() {
        const bv = $('#document_form').data('bootstrapValidator');
        const validationMessage = "<?php echo $lang['Checkbox english translation info']; ?>";

        const checkboxGroups = new Set()

        // Find checkbox types in current document
        $(':input[name^="checkbox_"]').each(function() {
            const name = $(this).attr('name');
            // Remove checkbox_ and language
            const groupName = name.slice(9, -3);
            checkboxGroups.add(groupName);
        });

        checkboxGroups.forEach(group => {
            const enFieldName = `checkbox_${group}_en`;

            // Dynamically add error container if it doesn't exist. This allows the error message to appear directly below the correct input
            if (!$(`#error_container_${enFieldName}`).length) {
                $(`#${enFieldName}`).after(`<div id="error_container_${enFieldName}"></div>`);
            }
            
            const otherFieldNames = [];
            $(`:input[name^="checkbox_${group}_"]:not([name$="_en"])`).filter(function(){
                const name = $(this).attr('name');
                // match only checkboxes in the exact group
                const regex = new RegExp(`^checkbox_${group}_[a-z]{2}$`);
                return regex.test(name);
            }).each(function() {
            otherFieldNames.push($(this).attr('name'));
            });
            
            bv.addField(enFieldName, {
                container: `#error_container_${enFieldName}`, 
                validators: {
                    callback: {
                    message: validationMessage,
                    callback: function(value, validator) {
                        let isAnyNonEnFilled = otherFieldNames.some(fieldName => {
                        const fieldValue = validator.getFieldElements(fieldName).val();
                        return fieldValue.trim() !== '';
                        });
                        if (isAnyNonEnFilled && value.trim() === '') {
                        return false;
                        }
                        return true;
                    }
                    }
                }
            });
        })

    }

    addCheckboxValidators();

    // Revalidate English checkboxes when any related field changes
    $('#document_form').on('input',':input[name^="checkbox_"]', function() {

        const bv = $('#document_form').data('bootstrapValidator');

        // Get the group name from the modified checkbox
        const name = $(this).attr('name');
        const groupName = name.slice(9, -3); // Extract group name from the changed checkbox

        // Revalidate only the English checkbox for this specific group
        const enFieldName = `checkbox_${groupName}_en`;
        bv.revalidateField(enFieldName);

    });

});



$(".default-tab").each(function () {
  $(this).addClass("active");
});

function loadDocumentData() {
    const document_name = document.getElementById("document_name");
    const active = document.getElementById("active");
    const active_state = '<?php echo $documentData['active'] ?? "0"?>';

    let options = {
        width: 800,
        height: 300,
        buttons: "bold,strikethrough,underline,italic,|,,ul,ol,,outdent,indent,,|,link,,align,undo,redo,\n",
    };

    document_name.value = '<?php echo $documentData['name'] ?? null ?>';
    active.checked = active_state === '1';

    const hasHandlebars = /\{\{\s*#?(if|unless|each|with|log|lookup)[^}]*\}\}/.test(`<?php echo array_get($documentTranslationsContents, 1) ?>`);
    var documentEditors =  {};

    <?php foreach ($languages as $language) { ?>
        const document_title_<?php echo $language['name'] ?> = document.getElementById("document_title_<?php echo $language['name'] ?>");
        document_title_<?php echo $language['name'] ?>.value = `<?php echo array_get($documentTranslationsTitles, $language['id']) ?>`;

        // generate translations for each checkbox configuration
        <?php foreach ($checkboxConfigurations as $config) {
            $checkboxTypeName = $config['checkbox_type_name'];
            $checkboxTranslation = '';
            foreach ($config['translations'] as $translation) {
                if ($translation['language_id'] == $language['id']) {
                $checkboxTranslation = $translation['text'];
                break;
                }
            }
            ?>
            const checkbox_<?php echo $checkboxTypeName . '_' . $language['name']; ?> = new Jodit('#checkbox_<?php echo $checkboxTypeName . '_' . $language['name']; ?>', {...options, height: 125});
            checkbox_<?php echo $checkboxTypeName . '_' . $language['name']; ?>.editor.id = 'jodit_checkbox_<?php echo $checkboxTypeName . '_' . $language['name']; ?>';
            checkbox_<?php echo $checkboxTypeName . '_' . $language['name']; ?>.value = `<?php echo $checkboxTranslation ?>`;
        <?php } ?>

        if (!hasHandlebars) {
            const document_content_<?php echo $language['name'] ?> = new Jodit('#document_content_<?php echo $language['name'] ?>', options);
            document_content_<?php echo $language['name'] ?>.editor.id = 'jodit_document_content_<?php echo $language['name'] ?>';
            document_content_<?php echo $language['name'] ?>.value = `<?php echo array_get($documentTranslationsContents, $language['id']) ?>`;
        } else {
            const document_content_<?php echo $language['name'] ?> = CodeMirror.fromTextArea(document.getElementById('document_content_<?php echo $language['name'] ?>'), {
                mode: "htmlmixed",
                lineNumbers: true
            });
            document_content_<?php echo $language['name'] ?>.setValue( `<?php echo array_get($documentTranslationsContents, $language['id']) ?>`);
            
            // Update textarea with CodeMirror content
            document_content_<?php echo $language['name'] ?>.save();
            document_content_<?php echo $language['name'] ?>.on('change', function(cm) {
                cm.save();
            });

            documentEditors['<?php echo $language['name'] ?>'] = document_content_<?php echo $language['name'] ?>;
        }  
    <?php } ?>


    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        const selectedLang = e.target.getAttribute("href").slice(-2);

        // Refresh the correct CodeMirror instance based on the selected language
        if (documentEditors[selectedLang]) {
            documentEditors[selectedLang].refresh();

        }
    });

    $(window).load(function() { 
        setTimeout(function() { 
            $(".jodit_workplace").removeAttr("style");
        }, 1000);
    });
}

function submitForms(event, formName) {
  event.preventDefault();
  $('#' + formName).submit();

  return true;
}
</script>
