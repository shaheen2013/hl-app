<?php if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
} ?>
<?php include LANG . $_SESSION['userLang'] . '/statistics/app_header.php'; ?>
<?php include LANG . $_SESSION['userLang'] . '/hotel-suggest.php'; ?>

<?php // header for computer and tablets ONLY ?>
<div class="ui top large fixed menu tablet or lower hidden" id="header">
    <div class="item">
        <img class="logo" src="<?php echo $this->asset('/public/images/hotelinking_iso_black.png') ?>" alt="hotelinking logo">
    </div>
    <div class="item">
        <h1 id="page_title"><i class="icon <?= $this->e($page_icon) ?>"></i> <?= $this->e($page_title) ?></h1>
    </div>
    <div class="right menu">
        <?php if (array_get($_SESSION, 'chain.brand_id') || (array_get($_SESSION, "accountAccess") && array_get($_SESSION, "brandsAccess"))) : ?>
            <div class="top-bar-hotels">
                <ul id="hotel-search">
                    <li>
                        <button class="hotel-search-hotel"><i class="search icon"></i></button>
                    </li>
                    <li>
                        <button class="hotel-list"><i class="list icon"></i></button>
                    </li>
                    <li>
                        <button class="hotel-map"><i class="map icon"></i></button>
                    </li>
                </ul>
            </div>
            <div id="show-hotel-search">
                    <input placeholder="Hotel Name..." type="text" name="hotel-suggest" id="hotel-suggest">
            </div>

            <div id="show-hotel-list" class="top-bar-hotel-list top-bar-hotel-ul"></div>

            <div id="show-hotel-map" class="top-bar-hotel-map top-bar-hotel-ul"></div>
        <?php endif; ?>
        <div class="item hotel_logo">
            <img class="ui image" src="<?= $this->e($hotel_logo) ?>" alt="hotelinking logo">
        </div>
    </div>
</div>

<?php // header for mobiles ONLY ?>
<div class="ui top compact fixed menu mobile tablet only">
    <div class="item">
        <img class="logo" src="<?php echo $this->asset('/public/images/hotelinking_iso_black.png') ?>" alt="hotelinking logo">
    </div>
    <h5 class="mobile_title"><i class="icon <?= $this->e($page_icon) ?>"></i> <?= $this->e($page_title) ?></h5>
    <div class="menu right">
        <div class="item">
            <i class="big sidebar icon primary-color btn_sidebar_toggle"></i>
        </div>
    </div>
</div>

<?php
    $apiEndPoint = "";
    $suggestBrandId = 'null';
    $staffId = 0;
    
    if (array_get($_SESSION, 'chain.brand_id') || (array_get($_SESSION, "accountAccess") && array_get($_SESSION, "brandsAccess"))) {
        $accountId = array_get($_SESSION, 'chain.brand_id', array_get($_SESSION, "accountAccess"));  
        $apiEndPoint = "/hotel-api-map/brands/{$accountId}/childs/hotel/?score=1&users=1";
        $suggestBrandId = $accountId ?? 'null';
        $staffId = $_SESSION['staff_logueado'] ?? 0;
    }
?>

<script src="/<?php echo DIR_JS ?>typeahead.bundle.min.js"></script>
<script src="/<?php echo DIR_JS ?>googlemarker/markerclusterer.js"></script>
<script src="/<?php echo DIR_JS ?>relogin.js?v=3"></script>
<script src="/<?php echo DIR_JS ?>hotel-suggest.js"></script>
<script>
    HotelSuggest.init(<?php echo $suggestBrandId; ?>, <?php echo $staffId; ?>, '<?php echo $apiEndPoint; ?>', '<?php echo $hotelSuggestLang['tooltipExplanation']?>');
</script>
