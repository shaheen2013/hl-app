<?php include LANG . $_SESSION['userLang'] . '/statistics/dashboard_menu.php'  ?>
<div class="ui small form" id="dashboard_menu_form">
    <form method="POST" action="<?=e($this->uriFull())?>" class="dashboard_menu_form">
        <div class="four fields">
            <div class="field" id="rangestart">
                <div class="ui input left icon">
                    <i class="calendar icon"></i>
                    <input type="text" placeholder="<?php echo $lang['from']?>" name="rangeStart" class="range_start_field"
                           value="<?php echo(!empty($_SESSION['rangeStart']) ? $_SESSION['rangeStart'] : '') ?>" autocomplete="off">
                </div>
            </div>
            <div class="field" id="rangeend">
                <div class="ui input left icon">
                    <i class="calendar icon"></i>
                    <input type="text" placeholder="<?php echo $lang['to']?>" name="rangeEnd" class="range_end_field"
                           value="<?php echo(!empty($_SESSION['rangeEnd']) ? $_SESSION['rangeEnd'] : '') ?>" autocomplete="off">
                </div>
            </div>

            <div class="field" id="chain_hotel_toggle">
                <?php
                // Appears only if is chain and is not a staff client
                if (!empty($_SESSION['c_logueado']) && empty($no_chain_selector) && empty($_SESSION['staff_role'])): ?>
                    <div class="ui toggle checkbox">
                        <input type="checkbox" tabindex="0" class="hidden chain_search_field"
                               name="chainSearch" <?php echo(!empty($_SESSION['chainSearch']) ? 'checked' : '') ?>>
                        <label><?php echo $lang['hotel']?> <i class="icon resize horizontal"></i><?php echo $lang['chain']?></label>
                    </div>
                <?php endif; ?>
            </div>
            <div class="field" id="dashboard_menu_submit_btn">
                <button style="background-color: black !important;" class="primary-color bg ui button has-loader" type="submit">
                    <i class="icon search"></i> <?php echo $lang['search']?>
                </button>
                <button class="ui button icon dashboard_menu_reset_form_button" type="reset">
                    <i class="icon remove"></i>
                </button>
            </div>
        </div>
    </form>
</div>
