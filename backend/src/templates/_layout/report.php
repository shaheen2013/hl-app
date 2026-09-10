<?php 
    $this->layout('_layout::report-index');
    $reportLang =  include LANG . $hotel_lang . '/statistics/report.php';
?>

<div class="pusher">

    <div>
        <div class="small_jump" style="height: 130px;"></div>
        <div class="row hl_report" id="content">

            <div class="report-logo-container" style='background-image: url("https://images.hotelinking.com/login/hotelinking-transparent.png"); height: 145px; background-size: contain;'>
            </div>
            <div class="hl_report_titles">
            <h2 style="font-weight:bolder;"><?php echo $this->e($hotel_name) ?></h2>
            <h2><?php echo $reportLang['Report date'] . ':' ?></h2>

            <?php if($this->e($origin_date_title) != date('d/m/Y')){?>
                <h2 style="border-color:rgba(163, 230, 53, 1)"> <?php echo $this->e($origin_date_title)?>-<?php echo $this->e($finish_date_title)?></h2>
            <?php }else{ ?>
                <h2 style="border-color:rgba(163, 230, 53, 1)"> <?php echo $reportLang['Origin']?>-<?php echo $this->e($finish_date_title)?></h2>
            <?php } ?>
                <div class="report-logo-container" style='height:250px;background-image: url("../../../public/images/curve.svg") '></div>

            </div>
            <div class="sixteen wide tablet thirteen wide computer column">
                <?php echo $this->section('content') ?>
            </div>
        </div>
    </div>
</div>
