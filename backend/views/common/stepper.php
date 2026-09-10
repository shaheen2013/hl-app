<?php
    include_once LANG . $_SESSION['userLang'] . '/stepper.php';
    include_once RUTA_DIR . LIB . 'hotelinking_emails.php';
    
    function activeSteps($urlStepMap, $limit) {
        foreach($urlStepMap as $key => $data) {
            if( $key == $limit) {
                $urlStepMap[$key]["current"] = true;
                break;
            }
            $urlStepMap[$key]["active"] = true;
        }
        return $urlStepMap;
    }

    $brand_id = (int)array_get($_SESSION, 'brandID');
    $urlStepMap = [
        "gdpr" => ["active" => true, "current" => false ],
        "stay-share" => ["active" => false, "current" => false ], 
        "facebook" => ["active" => false, "current" => false], 
        "room_number" => ["active" => false, "current" => false],
        "stay-wifi-redirect" => ["active" => false, "current" => false]
    ];

    $requireRoomProduct = getBrandProductActive($_SESSION['brandProducts'], 'require_room_num');
    if(
        !$requireRoomProduct || ($requireRoomProduct && !$requireRoomProduct['active']) ||
        getBrandProductActive(array_get($_SESSION, 'brandProducts'), 'portal_pro') ||
        getBrandAccessCodes($brand_id) == null
    ) {
        array_forget($urlStepMap, 'room_number');
    }

    if (!FACEBOOK_ENABLE) {
        array_forget($urlStepMap, 'facebook');
    }
    
    $urlStepper = explode('/', $_SERVER['REQUEST_URI']);
    $urlStepper = $urlStepper[1];
    
    if($urlStepper == "stay-wifi-redirect") {
        if($identifiedUser && $BrandHasBookingUrl) {
            $urlStepMap = activeSteps($urlStepMap, "facebook");
        } else if(getBrandProductActive($_SESSION['brandProducts'], 'require_room_num') && $hotel_room_list != null) {
            $urlStepMap = activeSteps($urlStepMap ,"room_number");
        } else {
            $urlStepMap = activeSteps($urlStepMap ,"stay-wifi-redirect");
        }
    } else {
        $urlStepMap = activeSteps($urlStepMap ,"stay-share");
    }
    
    $totalSpeps = count($urlStepMap);
?>
<div id="portalStepper">
    <div class="container-in">
        <ul class="progressbar">
    <?php
        foreach ($urlStepMap as $key => $data) {
            $class = $data["current"] ? 'class="current"' : ($data["active"] ? 'class="active"' : '' ); 
            $stepList = '<li id="'.$key.'-step-id"'.$class.'">'.$stepperLang[$key].'</li>';
            echo $stepList;
        }
    ?>
        </ul>
    </div>
</div>

<style>
#portalStepper {
    width: 100%;
    padding-bottom: 1em;
}
.progressbar {
    padding: 0;
    opacity: 0.7;
}
.progressbar li {
    list-style-type: none;
    width: calc(100% / <?php echo $totalSpeps ?>);
    float: left;
    font-size: 9px;
    position: relative;
    text-align: center;
    text-transform: uppercase;
    color: white;
}
.progressbar li:before {
    width: 30px;
    height: 30px;
    content: "";
    line-height: 30px;
    border: 2px solid #7d7d7d;
    display: block;
    text-align: center;
    margin: 0 auto 10px auto;
    border-radius: 50%;
    background-color: #333333;
}
.progressbar li:after {
    width: 100%;
    height: 2px;
    content: '';
    position: absolute;
    background-color: #7d7d7d;
    top: 15px;
    left: -50%;
    z-index: -1;
    
}
.progressbar li:first-child:after {
    content: none;
}
.progressbar li.active {
    color: #45b7af;
}

.progressbar li.active:before {
    background-color: #45b7af;
}
.progressbar li.active:before {
    border-color: #45b7af;
    
}
.progressbar li.active + li:after {
    background-color: #45b7af;
    
}

.progressbar li.current {
    color: white;
}

.progressbar li.current:before {
    background-color: white;
}
.progressbar li.current:before {
    border-color: white;
    
}

</style>

<script>
    function activeRoomCodeStep(idActive, idCurrent) {
        var stepActive  = document.getElementById(idActive);
        var stepCurrent  = document.getElementById(idCurrent);
        stepActive.classList.add("active");
        stepActive.classList.remove("current");
        stepCurrent.classList.add("current");
    }

    function disableRoomCodeStep(idActive, idCurrent) {
        var stepActive  = document.getElementById(idActive);
        var stepCurrent  = document.getElementById(idCurrent);
        stepActive.classList.remove("active");
        stepActive.classList.add("current");
        stepCurrent.classList.remove("current");
    }
</script>