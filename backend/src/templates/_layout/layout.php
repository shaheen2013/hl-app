<?php $this->layout('_layout::index')?>

<?php // Sidebar for mobile ONLY ?>
<div class="ui left vertical menu sidebar branded_mobile mobile tablet only">
    <?php echo $this->insert('common::app_mobile_sidebar', [
        'hotel_name' => $this->e($hotel_name),
        'hotel_logo' => $this->e($hotel_logo),
        'url' => $url
    ]) ?>
</div>
<?php // pusher for sidebar, moves content to right on sidebar slide ?>
<div class="pusher">

    <div class="ui grid">
        <?php echo $this->insert('common::app_header', [
            'page_title' => $this->e($page_title),
            'page_icon' => $this->e($page_icon),
            'hotel_name' => $this->e($hotel_name),
            'hotel_logo' => $this->e($hotel_logo),
            'url' => $url
        ]) ?>
    </div>

    <div class="ui grid" id="main_grid">
        <div class="row main_row" id="content">
            <div class="three wide column tablet or lower hidden" id="side">
                <?php echo $this->insert('common::app_sidebar', ['url' => $url, 'current_page' => $this->e($current_page), 'current_subPage' => $this->e($current_subPage)]) ?>
            </div>
            <div class="sixteen wide tablet thirteen wide computer column" id="main">
                <?php echo $this->section('content') ?>
                <footer>
                    <?php echo $this->insert('common::app_footer') ?>
                </footer>
            </div>
        </div>
    </div>
</div>
