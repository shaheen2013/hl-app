<?php

if (array_get($_SESSION, 'chain.brand_id') || (array_get($_SESSION, "accountAccess") && array_get($_SESSION, "brandsAccess"))):
    include LANG . $_SESSION['userLang'] . '/hotel-suggest.php';
    $accountId = array_get($_SESSION, 'chain.brand_id', array_get($_SESSION, "accountAccess"));
    $apiEndPoint = "/hotel-api-map/brands/{$accountId}/childs/hotel/?score=1&users=1";
    $suggestBrandId = $accountId ?? 'null';
    $staffId = $_SESSION['staff_logueado'] ?? 0;
?>
<div class="top-bar-hotels">
    <ul id="hotel-search">
        <li>
            <button class="hotel-search-hotel">
                <svg style="display:none" id="loadingHotelInput" width='20'xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="uil-ring-alt"><rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect><circle cx="50" cy="50" r="40" stroke="#000000" fill="none" stroke-width="10" stroke-linecap="round"></circle><circle cx="50" cy="50" r="40" stroke="#ffffff" fill="none" stroke-width="6" stroke-linecap="round"><animate attributeName="stroke-dashoffset" dur="2s" repeatCount="indefinite" from="0" to="502"></animate><animate attributeName="stroke-dasharray" dur="2s" repeatCount="indefinite" values="150.6 100.4;1 250;150.6 100.4"></animate></circle></svg>
                <i id="searchIconHotelInput" class="fa fa-search"></i>
            </button>
        </li>
        <li>
            <button class="hotel-list"><i class="fa fa-list-ul"></i></button>
        </li>
        <li>
            <button class="hotel-map"><i class="fa fa-map-o"></i></button>
        </li>
    </ul>
</div>
    <div id="show-hotel-search">
        <li>
            <input placeholder="<?php echo $hotelSuggestLang['Hotel Name'] . ', ' . $hotelSuggestLang['place'] . ', ' . $hotelSuggestLang['country'] . '...'?>" type="text" name="hotel-suggest" id="hotel-suggest">
        </li>
    </div>
    <div id="show-hotel-list" class="top-bar-hotel-list top-bar-hotel-ul">
    </div>
    <div id="show-hotel-map" class="top-bar-hotel-map top-bar-hotel-ul">

    </div>
    <div id="hotelSuggestLoading" class="top-bar-hotel-ul">
        <div style="display: flex;justify-content: center;height:100%" class="svg-container "> <svg style="align-self: center;" width="100" height="100" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg"> <defs> <linearGradient x1="8.042%" y1="0%" x2="65.682%" y2="23.865%" id="a"> <stop stop-color="#65c3df" stop-opacity="0" offset="0%"></stop> <stop stop-color="#65c3df" stop-opacity=".631" offset="63.146%"></stop> <stop stop-color="#65c3df" offset="100%"></stop> </linearGradient> </defs> <g fill="none" fill-rule="evenodd"> <g transform="translate(1 1)"> <path d="M36 18c0-9.94-8.06-18-18-18" id="Oval-2" stroke="url(#a)" stroke-width="2" transform="rotate(145.839 18 18)"> <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform> </path> <circle fill="#65c3df" cx="36" cy="18" r="1" transform="rotate(145.839 18 18)"> <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"></animateTransform> </circle> </g> </g> </svg> </div>
    </div>
<script src="<?php echo DIR_JS ?>typeahead.bundle.min.js"></script>
<script src="<?php echo DIR_JS ?>googlemarker/markerclusterer.js"></script>
<script src="<?php echo DIR_JS ?>relogin.js?v=3"></script>
<script src="<?php echo DIR_JS ?>hotel-suggest.js"></script>

<script>
    $(document).ready(function () {

        //if we just created a new hotel in the chain management page
        //remove data from session storage to get new hotel just created
        if(window.location.pathname === '/chain-management/' && window.location.search === "?msg=2017"){
            sessionStorage.clear();
        }

        HotelSuggest.init(<?php echo $suggestBrandId; ?>, <?php echo $staffId; ?>, '<?php echo $apiEndPoint; ?>', '<?php echo $hotelSuggestLang['tooltipExplanation']?>');
    });
</script>
<?php
endif;


