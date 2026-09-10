<?php include LANG . $_SESSION['userLang'] . '/statistics/widgetEngagement.php'?>

<?php $this->layout('_layout::layout', [
    'title' => $this->e($title),
    'page_title' => $this->e($page_title),
    'page_icon' => $this->e($page_icon),
    'hotel_name' => $this->e($hotel_name),
    'hotel_logo' => $this->e($hotel_logo),
    'url' => $url
]) ?>
<style>

    .itemBox.active{
        margin-bottom: 1px !important;
        border-bottom: 1px solid #d4d4d5 !important;
        border-radius: .28571429rem .28571429rem !important;
    }

    .itemBox-container{
        border-bottom: 0 !important;
    }

    .huebee {
        z-index: 1000;
    }

    .position-selector-field {
        margin-top: 13px !important;
    }

    .position-selector-field .checkbox {
        margin-right: 10px;
    }

    .red {
        color: red;
    }

    .green {
        color: #32bea6;
    }

    .orange {
        color: #ed8919;
    }

    .light {
        font-weight: 500;
    }

    .big-icon {
        font-size: 1.7em !important;
    }

    .top {
        vertical-align: top;
    }

    .messages-container {
        display:flex; 
        align-items: end;
        margin-top: .5em;
    }

    .global-info {
        color: #aaaaaa;
        display: inline-flex;
        align-items: center;
        flex-direction: row;
        vertical-align: middle;
    }

    @media (max-width: 425px) {
        .global-info {
            margin-top: .5em;
            padding-left: .5em;
            padding-right: .5em;
        }
    }

    .global-info-text {
        max-width: 30em;
        margin-left: 1em;
    }

    .lang-buttons-container {
        display: inline-flex;
        margin-bottom: .5em;
    }
    
    .lang-buttons {
        min-height: unset !important;
        margin: .5em 0 .5em 0 !important;
    }

    .lang-button {
        padding: .78571429em 1.5em .78571429em !important;
        border-radius: .5em !important;
    }

    @media (max-width: 768px) {
        .lang-buttons {
            flex-wrap: wrap;
        }

        .lang-button {
            flex-basis: 25% !important;
            margin-top: .5em !important;
            width: 1em !important:
            justify-content: center;
        }
  
    }
    
    .ui.tabular.menu .item {
        background-color: #eeeeee;
        margin-right: .5em;
    }

    .ui.tabular.menu .active.item {
        border-color: #7258f6 !important;
        border-radius: .5em !important;
        background-color: #eeeeee;
    }

    .ui.tabular.menu .item:hover {
        background-color: #ddd;
    }

    .correctMessage {
        border: 1px solid #9ffa5c !important;
    }
    
    .warningMessage {
        border: 1px solid #ed8919 !important;
    }
    
    .errorMessage {
        border: 1px solid red !important;
    }

    pre {
        white-space: pre-wrap; /* Since CSS 2.1 */
        white-space: -moz-pre-wrap; /* Mozilla, since 1999 */
        white-space: -pre-wrap; /* Opera 4-6 */
        white-space: -o-pre-wrap; /* Opera 7 */
        word-wrap: break-word; /* Internet Explorer 5.5+ */
    }

    xmp {
        font-family: unset;
        margin: 0;
        display: inline-block;
    }
</style>
<div class="ui four column grid" style="padding:2rem">
    <?php if (($widgetActive || $parentWidgetActive) && array_get($_SESSION, 'permisos.widget')) { ?>
        <?php if($defaultOffer){ ?>
            <div class="ui card">
                <div class="content">
                    <h3 class="top inline light"><?php echo $lang['timeManagement'] ?></h3>
                    <i class="ui icon question circle primary-color has-tooltip big-icon inline" data-variation="wide" data-title="<?php echo $lang['timeManagementTooltipTitle'] ?>" data-content="<?php echo $lang['timeManagementTooltipDescription'] ?>"></i>

                    <a href="#" class="red" style="float:right" data-toggle="time-management"><?php echo $lang['closeConfig']?></a>
                </div>
                <div data-config="time-management" class="content">
                    <div class="row">
                        <div class="sixteen wide column">
                            <form class="ui form" method="post">
                                <div class="fields">
                                    <div class="field">
                                        <p><?php echo $lang['timeShowMessages']?></p>
                                        <div class="ui right labeled input">
                                            <input type="number" name="message_time" placeholder="Recomendado: 30 segundos"
                                                    value="<?php echo $messagesTime ?>">
                                            <div class="ui basic label">
                                                <?php echo $lang['seconds']?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <p><?php echo $lang['timeBetweenMessages']?></p>
                                            <div class="ui right labeled input">
                                            <input type="number" name="time_between_messages" placeholder="Recomendado: 5 segundos"
                                                    value="<?php echo $timeBetweenMessages ?>">
                                            <div class="ui basic label">
                                                <?php echo $lang['seconds']?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="field">
                                    <input type="submit" class="primary-color bg ui button has-loader"
                                        value="Guardar configuración "/>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ui card">
                <div class="content">
                    <h3 class="inline light"><?php echo $lang['customizedMessages']?> <b><?php echo $lang['unknownUser']?></b></h3>
                    <i class="ui icon question circle primary-color has-tooltip big-icon inline" data-variation="wide" data-html="<?php echo $lang['unknownUserTooltip']?>"></i>
                    <a href="#" class="red" style="float:right" data-toggle="unknownUser"><?php echo $lang['closeConfig']?></a>
                </div>
                <div data-config="unknownUser" class="content">
                    <div class="row">
                        <div class="sixteen wide column">
                            <div class="field">
                                <div class="fields">
                                    <div class="field lang-buttons-container ">
                                        <div class="ui top tabular lang-buttons itemBox-container menu">
                                            <?php foreach ($langs as $messageLang) { ?>
                                                <div id="button_tab_unknown-user_<?php echo $messageLang ?>" class="item itemBox ui button lang-button <?php echo $messageLang == 'en' ? 'active' : '' ?>"
                                                    data-tab="unknown-user_texts_<?php echo $messageLang ?>"><?php echo $messageLang; //echo $messageLang == 'en' ? '*' : '' ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div id="globalMessages" class="global-info global_unknown-user">
                                    </div>
                                </div>
                            </div>
                            <div id="messagesContainer_unknown-user" class="field">
                                <?php $i=0; foreach ($unknownUserMessages as $message) { $i=$i+1;?>
                                    <div class="item">
                                        <form class="ui form" id="unknownUserMessagesForm" method="post" >
                                            <?php foreach ($langs as $messageLang) { ?>
                                                <div class="ui tab <?php echo $messageLang == 'en' ? 'active' : '' ?>"
                                                    style="border-top: none; width:100%" data-tab="unknown-user_texts_<?php echo $messageLang ?>">
                                                    
                                                    <b><?php echo $i . 'º ' . $lang['message']?></b>
                                                    <div class="ui messages-container" data-tab="unknown-users_messages_<?php echo $messageLang ?>">
                                                        <div style="margin-right:2em"class="ten wide field">
                                                            <input 
                                                                id="<?php echo $i ?>_unknown-user-layer-text_<?php echo $messageLang ?>" 
                                                                type="text" 
                                                                name="<?php echo $i ?>_message_<?php echo $messageLang ?>" 
                                                                placeholder="<?php echo $lang["assistantMessage"] ?>" 
                                                                value="<?php echo !empty($message[$messageLang]) ? 
                                                                    $message[$messageLang] : 
                                                                    '' ?>"
                                                            >
                                                        </div>
                                                        <div class="ui icon buttons">
                                                            <button name="messageAction" value="save" class="ui icon primary-color bg button <?php echo $i ?>_messagesSubmitButton_unknown-user">
                                                                <i class="save icon"></i>
                                                            </button>
                                                            <?php if ($i != 1) { ?>
                                                                <button name="messageAction" value="delete" class="ui icon red bg button">
                                                                    <i class="close icon"></i>
                                                                </button>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <input type="hidden" name="actualMessages" value='<?php echo htmlspecialchars(json_encode($unknownUserMessages));?>'/>
                                            <input type="hidden" name="messageType" value="unknown_user_messages" />
                                        </form>
                                    </div>
                                <?php } ?>     
                            </div>
                            <div class="field">
                                <button id="newMessageButton_unknown-user" <?php echo count($unknownUserMessages) > 2 ? 'disabled' : '' ?>  class="primary-color bg ui button" style="display:inline-block"><?php echo $lang['newMessage'] ?></button>
                                <form method="post" style="display:inline-block">
                                    <input type="submit" class="red bg ui button" value="Reset" />
                                    <input type="hidden" name="messageType" value="unknown_user_messages" />
                                    <input type="hidden" name="messageAction" value="reset" />
                                </form>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>

            <div class="ui card">
                <div class="content">
                    <h3 class="inline light"><?php echo $lang['customizedMessages']?> <b><?php echo $lang['knownUser']?></b></h3>
                    <i class="ui icon question circle primary-color has-tooltip big-icon inline" data-variation="wide" data-html="<?php echo $lang['knownUserTooltip']?>"></i>
                    <a href="#" class="red" style="float:right" data-toggle="knownUser"><?php echo $lang['closeConfig']?></a>
                </div>
                <div data-config="knownUser" class="content">
                    <div class="row">
                        <div class="sixteen wide column">
                            <div class="field">
                                <div class="fields">
                                    <div class="field lang-buttons-container ">
                                        <div class="ui top tabular lang-buttons itemBox-container menu">
                                            <?php foreach ($langs as $messageLang) { ?>
                                                <div id="button_tab_known-user_<?php echo $messageLang ?>" class="item itemBox ui button lang-button <?php echo $messageLang == 'en' ? 'active' : '' ?>"
                                                    data-tab="known-user_texts_<?php echo $messageLang ?>"><?php echo $messageLang; //echo $messageLang == 'en' ? '*' : '' ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div id="globalMessages" class="global-info global_known-user">
                                    </div>
                                </div>
                            </div>
                            <div id="messagesContainer_known-user" class="field">
                                <?php $i=0; foreach ($knownUserMessages as $message) { $i=$i+1;?>
                                    <div class="item">
                                        <form class="ui form" id="knownUserMessagesForm" method="post" >
                                            <?php foreach ($langs as $messageLang) { ?>
                                                <div class="ui tab <?php echo $messageLang == 'en' ? 'active' : '' ?>"
                                                    style="border-top: none; width:100%" data-tab="known-user_texts_<?php echo $messageLang ?>">
                                                    
                                                    <b><?php echo $i . 'º ' . $lang['message']?></b>
                                                    <div class="ui messages-container" data-tab="known-users_messages_<?php echo $messageLang ?>">
                                                        <div style="margin-right:2em"class="ten wide field">
                                                            <input 
                                                                id="<?php echo $i ?>_known-user-layer-text_<?php echo $messageLang ?>" 
                                                                type="text" 
                                                                name="<?php echo $i ?>_message_<?php echo $messageLang ?>" 
                                                                placeholder="<?php echo $lang["assistantMessage"] ?>" 
                                                                value="<?php echo !empty($message[$messageLang]) ? 
                                                                    $message[$messageLang] : 
                                                                    '' ?>"
                                                            >
                                                        </div>
                                                        <div class="ui icon buttons">
                                                            <button name="messageAction" value="save" class="ui icon primary-color bg button <?php echo $i ?>_messagesSubmitButton_known-user">
                                                                <i class="save icon"></i>
                                                            </button>
                                                            <?php if ($i != 1) { ?>
                                                                <button name="messageAction" value="delete" class="ui icon red bg button">
                                                                    <i class="close icon"></i>
                                                                </button>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <input type="hidden" name="actualMessages" value='<?php echo htmlspecialchars(json_encode($knownUserMessages));?>'/>
                                            <input type="hidden" name="messageType" value="known_user_messages" />
                                        </form>
                                    </div>
                                <?php } ?>     
                            </div>
                            <div class="field">
                                <button id="newMessageButton_known-user" <?php echo count($knownUserMessages) > 2 ? 'disabled' : '' ?>  class="primary-color bg ui button" style="display:inline-block"><?php echo $lang['newMessage'] ?></button>
                                <form method="post" style="display:inline-block">
                                    <input type="submit" class="red bg ui button" value="Reset" />
                                    <input type="hidden" name="messageType" value="known_user_messages" />
                                    <input type="hidden" name="messageAction" value="reset" />
                                </form>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>

            <div class="ui card">
                <div class="content">
                    <h3 class="inline light"><?php echo $lang['customizedMessages']?> <b><?php echo $lang['visitUser']?></b></h3>
                    <i class="ui icon question circle primary-color has-tooltip big-icon inline" data-variation="wide" data-html="<?php echo $lang['visitUserTooltip']?>"></i>
                    <a href="#" class="red" style="float:right" data-toggle="visitUser"><?php echo $lang['closeConfig']?></a>
                </div>
                <div data-config="visitUser" class="content">
                    <div class="row">
                        <div class="sixteen wide column">
                            <div class="field">
                                <div class="fields">
                                    <div class="field lang-buttons-container ">
                                        <div class="ui top tabular lang-buttons itemBox-container menu">
                                            <?php foreach ($langs as $messageLang) { ?>
                                                <div id="button_tab_visit-user_<?php echo $messageLang ?>" class="item itemBox ui button lang-button <?php echo $messageLang == 'en' ? 'active' : '' ?>"
                                                    data-tab="visit-user_texts_<?php echo $messageLang ?>"><?php echo $messageLang; //echo $messageLang == 'en' ? '*' : '' ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div id="globalMessages" class="global-info global_visit-user">
                                    </div>
                                </div>
                            </div>
                            <div id="messagesContainer_visit-user" class="field">
                                <?php $i=0; foreach ($visitUserMessages as $message) { $i=$i+1;?>
                                    <div class="item">
                                        <form class="ui form" id="visitUserMessagesForm" method="post" >
                                            <?php foreach ($langs as $messageLang) { ?>
                                                <div class="ui tab <?php echo $messageLang == 'en' ? 'active' : '' ?>"
                                                    style="border-top: none; width:100%" data-tab="visit-user_texts_<?php echo $messageLang ?>">
                                                    
                                                    <b><?php echo $i . 'º ' . $lang['message']?></b>
                                                    <div class="ui messages-container" data-tab="visit-users_messages_<?php echo $messageLang ?>">
                                                        <div style="margin-right:2em"class="ten wide field">
                                                            <input 
                                                                id="<?php echo $i ?>_visit-user-layer-text_<?php echo $messageLang ?>" 
                                                                type="text" 
                                                                name="<?php echo $i ?>_message_<?php echo $messageLang ?>" 
                                                                placeholder="<?php echo $lang["assistantMessage"] ?>" 
                                                                value="<?php echo !empty($message[$messageLang]) ? 
                                                                    $message[$messageLang] : 
                                                                    '' ?>"
                                                            >
                                                        </div>
                                                        <div class="ui icon buttons">
                                                            <button name="messageAction" value="save" class="ui icon primary-color bg button <?php echo $i ?>_messagesSubmitButton_visit-user">
                                                                <i class="save icon"></i>
                                                            </button>
                                                            <?php if ($i != 1) { ?>
                                                                <button name="messageAction" value="delete" class="ui icon red bg button">
                                                                    <i class="close icon"></i>
                                                                </button>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <input type="hidden" name="actualMessages" value='<?php echo htmlspecialchars(json_encode($visitUserMessages));?>'/>
                                            <input type="hidden" name="messageType" value="visit_user_messages" />
                                        </form>
                                    </div>
                                <?php } ?>     
                            </div>
                            <div class="field">
                                <button id="newMessageButton_visit-user" <?php echo count($visitUserMessages) > 2 ? 'disabled' : '' ?>  class="primary-color bg ui button" style="display:inline-block"><?php echo $lang['newMessage'] ?></button>
                                <form method="post" style="display:inline-block">
                                    <input type="submit" class="red bg ui button" value="Reset" />
                                    <input type="hidden" name="messageType" value="visit_user_messages" />
                                    <input type="hidden" name="messageAction" value="reset" />
                                </form>                            
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
        <?php } else {?>
            <div>
                <div>
                    <h3><?php echo $lang['widgetDisabled']?></h3>
                </div>
                <div>
                    <p><?php echo $lang['needOffer']?></p>
                </div>
            </div>

        <?php } ?>
    <?php } else {
        $this->insert('partials::widget/no-widget-message');
    } ?>

</div>

<script src="https://unpkg.com/huebee@1/dist/huebee.pkgd.min.js"></script>
<script src="<?php echo $this->asset('/public/javascript/jodit.min.js') ?>"></script>
<script>
    $(document).ready(function () {

        $('[class^=messagesSubmitButton]').click(function(e){
            e.preventDefault();
            $('#unknownUserMessagesForm').submit();
        });

        //toggle configurations
        $("[data-toggle]").click(function (e) {
            e.preventDefault();
            var link = $(this);
            var configuration = link.data("toggle");
            $(".row").find("[data-config='" + configuration + "']").slideToggle('fast', function () {
                link.html(link.text() == '<?php echo $lang['openConfig']?>' ? '<?php echo $lang['closeConfig']?>' : '<?php echo $lang['openConfig']?>');
                link.toggleClass('red');
            }).toggleClass('dnone');
            //Adjust the weight of sidebar
            adjustWidth();
            adjustHeight();
        });

        $('[id*=-layer-text_]').each(function() {
            var lang = this.id.slice(-2);
            var messageType = this.id.substring(this.id.indexOf("_") + 1, this.id.indexOf("-layer-text"));

            if (this.value) {
                $('#button_tab_' + messageType + '_' + lang).addClass('correctMessage');
            } else {
                $('#button_tab_' + messageType + '_' + lang).addClass('warningMessage');
            }
        });
        
        var globalInfoUnknownUser = [];
        var globalInfoKnownUser = [];
        var globalInfoVisitUser = [];
        $('[id*=-layer-text_]').each(function(element) {
            reactElements(this);
        });

        // React elements when insert character on input
        $("body").on("keyup", "[id*=-layer-text_]", function(element) {
            reactElements(element.currentTarget);  
        });  

        // Limit messages length
        $('body').on("keypress", "[id*=-layer-text_]", function(element) {
            if (this.value.replace(/<[^>]*>?/gm, '').length >= 100) {
                return false;
            }
        });  

        function reactElements(element) {
            var lang = element.id.slice(-2);
            var messageType = element.id.substring(element.id.indexOf("_") + 1, element.id.indexOf("-layer-text"));
            
            var globalInfo = [];
            if (messageType == "unknown-user") {
                globalInfo = globalInfoUnknownUser;
            } else if (messageType == "known-user") {
                globalInfo = globalInfoKnownUser;
            } else {
                globalInfo = globalInfoVisitUser;
            }

            if (lang == 'en' && !element.value) {
                globalInfo.unshift({element: element.id, type: "error"});
                $('.' + element.id[0] + '_messagesSubmitButton_' + messageType).attr("disabled", true);

                $('#button_tab_' + messageType + "_" + lang).removeClass('correctMessage');
                $('#button_tab_' + messageType + "_" + lang).removeClass('warningMessage');
                $('#button_tab_' + messageType + "_" + lang).addClass('errorMessage');
            } else if (!element.value) {
                globalInfo.push({element: element.id, type: "warning"});

                $('#button_tab_' + messageType + "_" + lang).removeClass('correctMessage');
                $('#button_tab_' + messageType + "_" + lang).removeClass('warningMessage');
                $('#button_tab_' + messageType + "_" + lang).addClass('warningMessage');
            } else if (elementIsDirty(globalInfo, element) && element.value) {
                globalInfo = globalInfo.filter(function(e){
                    return e.element != element.id;
                });
                
                if (messageType == "unknown-user") {
                    globalInfoUnknownUser = globalInfo;
                } else if (messageType == "known-user") {
                    globalInfoKnownUser = globalInfo;
                } else {
                    globalInfoVisitUser = globalInfo;
                }

                if (!languageIsDirty(globalInfo, lang)) {
                    $('#button_tab_' + messageType + "_" + lang).removeClass('errorMessage');
                    $('#button_tab_' + messageType + "_" + lang).removeClass('warningMessage');
                    $('#button_tab_' + messageType + "_" + lang).addClass('correctMessage');
                }

                if (lang == 'en') {
                    $('.' + element.id[0] + '_messagesSubmitButton_' + messageType).attr("disabled", false);
                }
            } 

            if (globalInfo[0] && globalInfo[0].type == "error") {
                $(".global_" + messageType).html('<i class="ui icon close red big inline"></i><p class="global-info-text inline red"><?php echo $lang['errorInfo']?></p>')
            }  else if (globalInfo[0] && globalInfo[0].type == "warning") {
                $(".global_" + messageType).html('<i class="ui icon exclamation triangle orange big inline"></i><p class="global-info-text inline orange"><?php echo $lang['warningInfo']?></p>')
            }  else {
                $(".global_" + messageType).html('<i class="ui icon check green big inline"></i><p class="global-info-text inline green"><?php echo $lang['successInfo']?></p>')
            }  
        }

        function elementIsDirty (globalInfo, element) {
            return globalInfo.filter(function(e) { 
                return e.element === element.id; 
            }).length > 0
        }
        
        function languageIsDirty (globalInfo, lang) {
            return globalInfo.filter(function(e) { 
                return e.element.slice(-2) === lang; 
            }).length > 0
        }

        $('[id*=newMessageButton]').click(function (e) {
            e.preventDefault();
            
            var messageType = this.id.split('_')[1]
            var numberMessages = $("#messagesContainer_" + messageType).find('.item').length + 1;

            var productConfig = null;
            var actualMessages = null;
            if (messageType == "unknown-user") {
                productConfig = "unknown_user_messages";
                actualMessages = "<?php echo htmlspecialchars(json_encode($unknownUserMessages));?>"
            } else if (messageType == "known-user") {
                productConfig = "known_user_messages";
                actualMessages = "<?php echo htmlspecialchars(json_encode($knownUserMessages));?>"
            } else {
                productConfig = "visit_user_messages";
                actualMessages = "<?php echo htmlspecialchars(json_encode($visitUserMessages));?>"
            }

            $("#messagesContainer_"+ messageType).append(
                '<div class="item">' +
                    '<form class="ui form" id="unknownUserMessagesForm" method="post" >' +
                        <?php foreach ($langs as $messageLang) { ?>
                            '<div id="'+numberMessages+'_tab_' + messageType + '_<?php echo $messageLang?>" class="ui tab"' +
                                'style="border-top: none; width:100%" data-tab="' + messageType + '_texts_<?php echo $messageLang ?>">' +
                                '<b>'+numberMessages+'<?php echo "º " . $lang["message"]?></b>' +
                                '<div class="ui messages-container" data-tab="' + messageType + '_messages_<?php echo $messageLang ?>">' +
                                    '<div style="margin-right:2em"class="ten wide field">' +
                                        '<input ' +
                                            'id="'+numberMessages+'_' + messageType + '-layer-text_<?php echo $messageLang ?>"' +
                                            'type="text" ' +
                                            'name="'+numberMessages+'_message_<?php echo $messageLang ?>" ' +
                                            'placeholder="<?php echo $lang["assistantMessage"] ?>" ' +
                                            'value="" ' +
                                        '/>' +
                                    '</div>' +
                                    '<div class="ui icon buttons">' +
                                        '<button name="messageAction" value="save" class="ui icon primary-color bg button '+numberMessages+'_messagesSubmitButton_'+messageType+'">' +
                                            '<i class="save icon"></i>' +
                                        '</button>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        <?php } ?>
                        "<input type='hidden' name='actualMessages' value='" + actualMessages + "'/>" +
                        '<input type="hidden" name="messageType" value="' + productConfig + '" />' +
                    '</form>' +
                '</div>'
            );

            if (numberMessages == 3) {
                $('#newMessageButton_' + messageType).attr("disabled", true);
            }

            $('[id*=_' + messageType + '-layer-text_]').each(function(element) {
                reactElements(this);
            });

            var activeLanguage = $('[id^=button_tab_].active')[0].id.slice(-2);
            $("#"+numberMessages+ "_tab_" + messageType + "_" + activeLanguage).addClass('active');

            $('.menu .item').tab();
        });
    });

    

    // initials on multiple elements with loop
    var elems = document.querySelectorAll('.color-input');
    for (var i = 0; i < elems.length; i++) {
        var elem = elems[i];
        var hueb = new Huebee(elem, {
            // options
        });
    }
</script>