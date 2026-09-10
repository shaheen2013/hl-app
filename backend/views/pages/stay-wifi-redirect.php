<?php include LANG . $_SESSION['userLang'] . '/stay-wifi-redirect.php' ?>

<script src="<?php echo DIR_JS ?>eventEmitter.js"></script>

<style type="text/css">
    body {
        background: url(<?php echo imageSize('large', $datosHotel['fotoBg']) ?>);
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .stay-wifi-redirect-content {
        display: flex;
        flex-wrap: wrap;
        height: 100vh;
        align-content: space-between;
    }

    .skip-link {
        display: block;
        position: relative;
        padding-bottom: 0.5em;
    }
</style>
<div class="stay-overlayer"></div>

<div class="connection-text">
    <h4><strong><?php echo $stayWifiRedirect['Connecting to wifi.'] ?></strong></h4>
    <p><?php echo $stayWifiRedirect['This can take a bit, please be patient.'] ?></p>
</div>
<div class="ripple"><img src="<?php echo DIR_IMG . 'ripple.svg' ?>" alt="loaded spinner" width="100" height="100">
</div>

<div class="stay-wifi-redirect-content text-center">
    <div class="col-xs-12"></div>
    <div class="col-xs-12 text-center">
        <?php if ($identifiedUser && $BrandHasBookingUrl) { ?>
            <div class="col-lg-6 col-lg-offset-3">
                <img class="animated zoomIn" src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMS4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDQ3Ny41IDQ3Ny41IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA0NzcuNSA0NzcuNTsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI2NHB4IiBoZWlnaHQ9IjY0cHgiPgo8Zz4KCTxnPgoJCTxwYXRoIGQ9Ik00MDcuNTMzLDcwYy00NS4xLTQ1LjEtMTA1LTcwLTE2OC44LTcwcy0xMjMuNywyNC44LTE2OC44LDY5LjljLTg3LDg3LTkzLjMsMjI2LTE1LjgsMzIwLjIgICAgYy0xMC43LDIxLjktMjMuNCwzNi41LTM3LjYsNDMuNWMtOC43LDQuMy0xMy42LDEzLjctMTIuMiwyMy4zYzEuNSw5LjcsOC45LDE3LjIsMTguNiwxOC43YzUuMywwLjgsMTEsMS4zLDE2LjksMS4zbDAsMCAgICBjMjkuMywwLDYwLjEtMTAuMSw4NS44LTI3LjhjMzQuNiwxOC42LDczLjUsMjguNCwxMTMuMSwyOC40YzYzLjgsMCwxMjMuNy0yNC44LDE2OC44LTY5LjlzNjkuOS0xMDUuMSw2OS45LTE2OC44ICAgIFM0NTIuNjMzLDExNS4xLDQwNy41MzMsNzB6IE0zODguNDMzLDM4OC41Yy00MCw0MC05My4yLDYyLTE0OS43LDYyYy0zNy44LDAtNzQuOS0xMC4xLTEwNy4yLTI5LjFjLTIuMS0xLjItNC41LTEuOS02LjgtMS45ICAgIGMtMi45LDAtNS45LDEtOC4zLDIuOGMtMzAuNiwyMy43LTYxLjQsMjcuMi03NC45LDI3LjVjMTYuMS0xMiwyOS42LTMwLjYsNDAuOS01Ni41YzIuMS00LjgsMS4yLTEwLjQtMi4zLTE0LjQgICAgYy03NC04My42LTcwLjEtMjExLDguOS0yOTBjNDAtNDAsOTMuMi02MiwxNDkuNy02MmM1Ni42LDAsMTA5LjcsMjIsMTQ5LjcsNjJDNDcxLjAzMywxNzEuNiw0NzEuMDMzLDMwNiwzODguNDMzLDM4OC41eiIgZmlsbD0iI0ZGRkZGRiIvPgoJCTxwYXRoIGQ9Ik0yOTkuMzMzLDEyOS40Yy0yMS42LDAtNDEuOSw4LjQtNTcuMiwyMy43bC0zLjQsMy40bC0zLjQtMy40Yy0xNS4zLTE1LjMtMzUuNi0yMy43LTU3LjItMjMuN2MtMjEuNSwwLTQxLjgsOC40LTU3LDIzLjYgICAgYy0xNS4zLDE1LjMtMjMuNywzNS42LTIzLjYsNTcuMWMwLDIxLjYsOC41LDQxLjgsMjMuNyw1Ny4xbDEwNy45LDEwNy45YzIuNiwyLjYsNi4xLDQsOS41LDRzNi45LTEuMyw5LjUtMy45bDEwOC4yLTEwNy44ICAgIGMxNS4zLTE1LjMsMjMuNy0zNS42LDIzLjctNTcuMWMwLTIxLjYtOC40LTQxLjktMjMuNi01Ny4yQzM0MS4xMzMsMTM3LjgsMzIwLjgzMywxMjkuNCwyOTkuMzMzLDEyOS40eiBNMzM3LjIzMywyNDguM2wtOTguNiw5OC4yICAgIGwtOTguNC05OC40Yy0xMC4yLTEwLjItMTUuOC0yMy43LTE1LjgtMzguMWMwLTE0LjQsNS42LTI3LjksMTUuNy0zOGMxMC4xLTEwLjEsMjMuNi0xNS43LDM4LTE1LjdzMjcuOSw1LjYsMzguMSwxNS44bDEzLDEzICAgIGM1LjMsNS4zLDEzLjgsNS4zLDE5LjEsMGwxMi45LTEyLjljMTAuMi0xMC4yLDIzLjctMTUuOCwzOC4xLTE1LjhjMTQuMywwLDI3LjgsNS42LDM4LDE1LjhzMTUuOCwyMy43LDE1LjcsMzggICAgQzM1My4wMzMsMjI0LjYsMzQ3LjQzMywyMzguMSwzMzcuMjMzLDI0OC4zeiIgZmlsbD0iI0ZGRkZGRiIvPgoJPC9nPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" />
                <?php if (getBrandProductActive($_SESSION['brandProducts'], 'wifi_offers') && $ofertaWifiHotel['condition'] == 'facebook_share') { ?>
                    <h3 class="swr-title-1 animated zoomIn delay-1"><?php echo $stayWifiRedirect['Welcome!'] . ', ' . ucwords(strtolower(array_get($user, 'name', ''))) ?></h3>
                    <h4 class="swr-title-1 animated zoomIn delay-1"><?php echo $stayWifiRedirect['Let your friends know about us with share gift'] . '<br><strong>' . $ofertaWifiHotel['description']['name'] . '</strong>' ?></h4>
                <?php } else { ?>
                    <h3 class="swr-title-1 animated zoomIn delay-1"><?php echo $stayWifiRedirect['Welcome!'] . ', ' . ucwords(strtolower(array_get($user, 'name', ''))) ?></h3>
                    <h5 class="swr-title-1 animated zoomIn delay-1"><?php echo $stayWifiRedirect['Let your friends know about us!'] ?></h5>
                <?php } ?>
                <div class="btn-container animated zoomIn delay-3 mt2">
                    <a href="https://www.facebook.com/dialog/feed?<?php echo $facebookQuery ?>">
                        <button class="btn btn-facebook" aria-label="Log in with Facebook">
                            <div class="flex-container">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 216 216" class="_5h0m" color="#ffffff">
                                    <path fill="#ffffff" d=" M204.1 0H11.9C5.3 0 0 5.3 0 11.9v192.2c0 6.6 5.3 11.9 11.9 11.9h103.5v-83.6H87.2V99.8h28.1v-24c0-27.9 17-43.1 41.9-43.1 11.9 0 22.2.9 25.2 1.3v29.2h-17.3c-13.5 0-16.2 6.4-16.2 15.9v20.8h32.3l-4.2 32.6h-28V216h55c6.6 0 11.9-5.3 11.9-11.9V11.9C216 5.3 210.7 0 204.1 0z"></path>
                                </svg>
                                <strong><?php echo $stayWifiRedirect['Share on Facebook'] ?></strong>
                            </div>
                        </button>
                    </a>
                </div>

                <!-- <p class="swr-text-1 animated zoomIn delay-2"><?php echo $stayWifiRedirect['Click and edit your post before it is shared on Facebook.'] ?></p> -->

                <?php if (array_get($_SESSION, 'has_friends') && count(array_get($_SESSION, 'friends')) >= 1) : ?>
                    <div class="facebook-friends">
                        <?php foreach ($_SESSION['friends'] as $friend) { ?>
                            <p style="padding: 0.5rem">
                                <img class="img-circle " src="https://graph.facebook.com/<?php echo $friend['id'] ?>/picture?width=200" alt="user avatar" width="50" height="50">
                                <!-- <span><?php echo $friend['name'] ?></span> -->
                            </p>
                        <?php } ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php } elseif ($userCanceled) { ?>
            <div class="col-lg-6 col-lg-offset-3">
                <img class="animated zoomIn" src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMS4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDY1IDY1IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA2NSA2NTsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI2NHB4IiBoZWlnaHQ9IjY0cHgiPgo8Zz4KCTxnPgoJCTxwYXRoIGQ9Ik0zMi41LDY1QzUwLjQyLDY1LDY1LDUwLjQyMSw2NSwzMi41UzUwLjQyLDAsMzIuNSwwUzAsMTQuNTc5LDAsMzIuNVMxNC41OCw2NSwzMi41LDY1eiBNMzIuNSw0ICAgIEM0OC4yMTUsNCw2MSwxNi43ODUsNjEsMzIuNVM0OC4yMTUsNjEsMzIuNSw2MVM0LDQ4LjIxNSw0LDMyLjVTMTYuNzg1LDQsMzIuNSw0eiIgZmlsbD0iI0ZGRkZGRiIvPgoJCTxjaXJjbGUgY3g9IjMzLjAxOCIgY3k9IjQzLjY1NSIgcj0iMy4zNDUiIGZpbGw9IiNGRkZGRkYiLz4KCQk8cGF0aCBkPSJNMzIuMzMyLDM1LjM0MmMxLjEwNCwwLDItMC44OTYsMi0ydi0xN2MwLTEuMTA0LTAuODk2LTItMi0ycy0yLDAuODk2LTIsMnYxN0MzMC4zMzIsMzQuNDQ2LDMxLjIyOCwzNS4zNDIsMzIuMzMyLDM1LjM0MnogICAgIiBmaWxsPSIjRkZGRkZGIi8+Cgk8L2c+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==" />
                <h3 class="swr-title-1 animated zoomIn delay-1"><?php echo $stayWifiRedirect['You\'ve just canceled. Please try again.'] ?></h3>
                <p class="swr-text-1 animated zoomIn delay-2"><?php echo $stayWifiRedirect['Click and edit your post before it is shared on Facebook.'] ?></p>
                <div class="btn-container animated zoomIn delay-3 mt2">
                    <a href="https://www.facebook.com/dialog/feed?<?php echo $facebookQuery ?>">
                        <button class="btn btn-facebook" aria-label="Log in with Facebook">
                            <div class="flex-container">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 216 216" class="_5h0m" color="#ffffff">
                                    <path fill="#ffffff" d=" M204.1 0H11.9C5.3 0 0 5.3 0 11.9v192.2c0 6.6 5.3 11.9 11.9 11.9h103.5v-83.6H87.2V99.8h28.1v-24c0-27.9 17-43.1 41.9-43.1 11.9 0 22.2.9 25.2 1.3v29.2h-17.3c-13.5 0-16.2 6.4-16.2 15.9v20.8h32.3l-4.2 32.6h-28V216h55c6.6 0 11.9-5.3 11.9-11.9V11.9C216 5.3 210.7 0 204.1 0z"></path>
                                </svg>
                                <strong><?php echo $stayWifiRedirect['Share on Facebook'] ?></strong>
                            </div>
                        </button>
                    </a>
                </div>

                <?php if (array_get($_SESSION, 'has_friends') && count(array_get($_SESSION, 'friends')) >= 1) : ?>
                    <div class="facebook-friends">
                        <?php foreach ($_SESSION['friends'] as $friend) { ?>
                            <p style="padding: 0.5rem">
                                <img class="img-circle " src="https://graph.facebook.com/<?php echo $friend['id'] ?>/picture?width=200" alt="user avatar" width="50" height="50">
                                <!-- <span><?php echo $friend['name'] ?></span> -->
                            </p>
                        <?php } ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php } elseif (array_get($_SESSION, 'bypassStuck')) {
            global $log;
            $log->info("User stuck on bypass page shown");
            header('HTTP/1.0 403 Forbidden');
        ?>
            <img class="animated zoomIn" src="data:image/png;base64,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" />
            <h3 class="swr-title-1 animated zoomIn delay-1"><?php echo $stayWifiRedirect['device_blacklisted']  ?></h3>
            <form method="POST">
                <input type="hidden" name="roomNumber" />
                <input type="hidden" name="hotelAccessCodes" />
                <input type="hidden" name="stuckedBypassRetry" />
                <input type="hidden" name="PHPSESSID" value="<?php echo session_id() ?>">
                <button id="wifiSubmitBtn" type="submit" class="btn btn-success send-login-button animated zoomIn delay-3 mt">
                    <?php echo $stayWifiRedirect['try again'] ?>
                </button>
            </form>
            <!--TODO-->
        <?php } elseif ($allOk && !$unifiOk || $allOk && !$BrandHasBookingUrl) { ?>
            <img class="animated zoomIn" src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDQ4NC42IDQ4NC42IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA0ODQuNiA0ODQuNjsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI2NHB4IiBoZWlnaHQ9IjY0cHgiPgo8Zz4KCTxnPgoJCTxnPgoJCQk8cGF0aCBkPSJNNDg0LjYsMjQyLjNDNDg0LjYsMTA4LjcsMzc1LjksMCwyNDIuMywwUzAsMTA4LjcsMCwyNDIuM3MxMDguNywyNDIuMywyNDIuMywyNDIuM1M0ODQuNiwzNzUuOSw0ODQuNiwyNDIuM3ogICAgICBNMzQuMywyNDIuM2MwLTExNC43LDkzLjMtMjA4LDIwOC0yMDhzMjA4LDkzLjMsMjA4LDIwOHMtOTMuMywyMDgtMjA4LDIwOFMzNC4zLDM1NywzNC4zLDI0Mi4zeiIgZmlsbD0iI0ZGRkZGRiIvPgoJCQk8cGF0aCBkPSJNMzE5LjQsMTg0LjZjLTYuNy02LjctMTcuNi02LjctMjQuMywwTDIxNiwyNjMuN2wtMjYuNS0yNi41Yy02LjctNi43LTE3LjYtNi43LTI0LjMsMHMtNi43LDE3LjYsMCwyNC4zbDM4LjYsMzguNiAgICAgYzMuMywzLjMsNy43LDUsMTIuMSw1YzQuNCwwLDguOC0xLjcsMTIuMS01bDkxLjItOTEuMkMzMjYuMSwyMDIuMSwzMjYuMSwxOTEuMywzMTkuNCwxODQuNnoiIGZpbGw9IiNGRkZGRkYiLz4KCQk8L2c+Cgk8L2c+Cgk8Zz4KCTwvZz4KCTxnPgoJPC9nPgoJPGc+Cgk8L2c+Cgk8Zz4KCTwvZz4KCTxnPgoJPC9nPgoJPGc+Cgk8L2c+Cgk8Zz4KCTwvZz4KCTxnPgoJPC9nPgoJPGc+Cgk8L2c+Cgk8Zz4KCTwvZz4KCTxnPgoJPC9nPgoJPGc+Cgk8L2c+Cgk8Zz4KCTwvZz4KCTxnPgoJPC9nPgoJPGc+Cgk8L2c+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==" />
            <?php if ($regularUser) { ?>
                <h3 class="swr-title-1 animated zoomIn delay-1"><?php echo $stayWifiRedirect['Welcome!'] . ', ' . ucwords(strtolower(array_get($user, 'name', ''))) ?></h3>
            <?php } else { ?>
                <h3 class="swr-title-1 animated zoomIn delay-1"><?php echo $stayWifiRedirect['Welcome!'] . ', ' . ucwords(strtolower(array_get($user, 'name', ''))) ?></h3>
            <?php } ?>
            <?php if (getBrandProductActive(array_get($_SESSION, 'brandProducts'), 'require_room_num') && !getBrandProductActive(array_get($_SESSION, 'brandProducts'), 'portal_pro') && ((!$_SESSION['brand_is_not_hotel'] && $_SESSION['customer'] && $hotel_room_list != null) || ( (!$_SESSION['customer'] || $_SESSION['brand_is_not_hotel']) && $hotel_access_room_list != null))) { ?>
                <form id="roomForm" method="POST" class="col-lg-6 col-lg-offset-3 animated zoomIn delay-2">
                    <br />
                    <div><?php echo ($_SESSION['brand_is_not_hotel']) || empty($_SESSION['customer']) ? $stayWifiRedirect['Room Number Not Hotel Text'] : $stayWifiRedirect['Room Number Text'] ?></div>
                    <br />
                    <div class="form-group">
                        <input id="hotel-room-number" placeholder="<?php echo ($_SESSION['brand_is_not_hotel']) || empty($_SESSION['customer']) ? $stayWifiRedirect['Room Not Hotel Placeholder'] : $stayWifiRedirect['Room Placeholder'] ?>" autocomplete="off" required>
                        <div id="room-text" class="room-error animated zoomOut"><?php echo ($_SESSION['brand_is_not_hotel']) || empty($_SESSION['customer']) ? $stayWifiRedirect['Room Error Not Hotel Text'] : $stayWifiRedirect['Room Error Text'] ?> </div>

                    </div>
                    <input type="hidden" name="roomNumber">
                    <input type="hidden" name="hotelAccessCodes">
                    <input type="hidden" name="PHPSESSID" value="<?php echo session_id() ?>">
                    <button id="roomSubmitBtn" type="submit" class="btn btn-success send-login-button animated zoomOut mt" disabled="true" autocomplete="off"><?php echo $stayWifiRedirect['Room Wifi Now'] ?></button>
                </form>

            <?php } else { ?>
                <!-- TODO: remove comments when start HLK-2000 -->
                <!-- <p class="swr-text-1 animated zoomIn delay-2"><?php echo $stayWifiRedirect['Now you can access free wifi by clickings on the button below'] ?></p> -->
                <form method="POST">
                    <input type="hidden" name="roomNumber" />
                    <input type="hidden" name="hotelAccessCodes" />
                    <input type="hidden" name="PHPSESSID" value="<?php echo session_id() ?>">
                    <button id="wifiSubmitBtn" type="submit" class="btn btn-success send-login-button animated zoomIn delay-3 mt"><?php echo $stayWifiRedirect['Give me wifi now'] ?></button>
                </form>
            <?php } ?>
        <?php } ?>

    </div>
    <div class="col-xs-12">
        <?php if (!$allOk) { ?>
            <?php if (getBrandProductActive($_SESSION['brandProducts'], 'wifi_offers') && $ofertaWifiHotel['condition'] == 'facebook_share') { ?>
                <a class="skip-link" href="<?php echo '/stay-wifi-redirect/?i=' . $hotel_id . '&action=skip' . '&PHPSESSID=' . session_id() ?>">
                    <?php echo $stayWifiRedirect['I want to skip this step and get wifi now gift'] ?>
                </a>
            <?php } else { ?>
                <a class="skip-link" href="<?php echo '/stay-wifi-redirect/?i=' . $hotel_id . '&action=skip' . '&PHPSESSID=' . session_id() ?>">
                    <?php echo $stayWifiRedirect['I want to skip this step and get wifi now'] ?>
                </a>
            <?php } ?>
        <?php } ?>
        <?php include 'views/common/stepper.php'; ?>
    </div>


</div>
<?php if ($unifiOk) : ?>
    <script>
        //show spinner
        $(".ripple, .connection-text").addClass("animated dblock bounceIn");

        //create a connection history
        // createConnectionHistory();
    </script>
<?php endif; ?>

<script src="<?php echo DIR_JS ?>polyfill_array_some_method.js"></script>
<script>
    // window.onpageshow = function(event) {
    //     if (event.persisted) {
    //         window.location.reload()
    //     }
    // };
    $(document).ready(function() {
        //get list of rooms from php into a js array

        var hotelRooms = <?php echo json_encode($hotel_room_list); ?>;
        var hotelAccessCodes = <?php echo json_encode($hotel_access_room_list); ?>;

        var error_text = <?php echo ($_SESSION['brand_is_not_hotel']) || empty($_SESSION['customer'])  ?  json_encode($stayWifiRedirect['Room Error Not Hotel Text']) : json_encode($stayWifiRedirect['Room Error Text']); ?>;
        var success_text = <?php echo ($_SESSION['brand_is_not_hotel']) || empty($_SESSION['customer']) ? json_encode($stayWifiRedirect['Room Success Not Hotel Text']) : json_encode($stayWifiRedirect['Room Success Text']); ?>;

        var input_room = document.getElementById('hotel-room-number');
        var error_div = document.getElementById('room-text');
        var submit_button = $('#roomSubmitBtn');

        $("#roomForm").submit(function(e) {
            if (!isValidRoomNumber) {
                e.preventDefault();
            }
        });
        //by default set bool to false
        var isValidRoomNumber = false;

        //function to show valid state
        var roomIsValid = function() {
            //set html for success
            error_div.className = "room-success animated zoomIn";
            error_div.innerHTML = success_text;

            //put form in an success state
            isValidRoomNumber = true;

            submit_button.removeClass('zoomOut')
            submit_button.prop('disabled', false)
            submit_button.addClass('zoomIn')
            activeRoomCodeStep('room_number-step-id', 'stay-wifi-redirect-step-id')
        }

        //function to show invalid state
        var roomIsNotValid = function() {
            //set html for error
            error_div.className = "room-error animated zoomIn";
            error_div.innerHTML = error_text;

            //put form in an error state
            isValidRoomNumber = false
            submit_button.prop('disabled', true)
            submit_button.removeClass('zoomIn')
            submit_button.addClass('zoomOut')
            disableRoomCodeStep('room_number-step-id', 'stay-wifi-redirect-step-id')
        }

        //checks if value exists in array (lowercase and that arr.length != 0)
        var existsInArray = function(val, arr) {
            return arr.length != 0 && arr.some(function(element, i) {
                return val.toLowerCase() === element.toLowerCase();
            });
        }

        var checkRoomOnKeyUp = function() {

            var timer;

            //check if hotel room value is in the hotels rooms array
            return $('#hotel-room-number').on('keyup', function(event) {

                //clear timer
                window.clearTimeout(timer);

                //value of input
                var inputValue = event.target.value;

                //Remove all whitespaces
                inputValue = inputValue.replace(/\s/g, '');
                hotelRooms = hotelRooms.map(function(el) {
                    return el.replace(/\s/g, '');
                });

                //set a 500ms timeout before executing the validation callback
                timer = window.setTimeout(function() {

                    //input field has no value or room array > 1 and room number does not exist in room number array

                    var brandIsNotHotel = <?php echo json_encode($_SESSION['brand_is_not_hotel']); ?>;
                    var isCustomer = <?php echo json_encode(!empty($_SESSION['customer'])); ?>;

                    if (!brandIsNotHotel && isCustomer && existsInArray(inputValue, hotelRooms)) {
                        $('[ name="roomNumber"]').val(inputValue);
                        $('[ name="roomNumber"]').prop('disabled', false);
                        $('[ name="hotelAccessCodes"]').prop('disabled', true);
                        roomIsValid();

                    } else {
                        //check if inputValue exists in hotelAccessCodes
                        if (existsInArray(inputValue, hotelAccessCodes)) {
                            $('[ name="roomNumber"]').prop('disabled', true);
                            $('[ name="hotelAccessCodes"]').prop('disabled', false);
                            $('[ name="hotelAccessCodes"]').val(inputValue);
                            roomIsValid();

                        } else {
                            roomIsNotValid();
                        }
                    }
                }, 500)
            })
        };

        checkRoomOnKeyUp();
    });

    $(document).ready(function() {
        $('#wifiSubmitBtn, #roomSubmitBtn').on('click', function(e) {
            $('.stay-wifi-redirect-content').hide();
            $(".ripple, .connection-text").addClass("animated dblock bounceIn");
        })
    });

    $("input").focus(function() {
        $(window).on("resize.keyboard", function() {
            $("#portalStepper").toggle();
        });
    });
</script>

<?php require_once LIB . 'wifi_integrations' . DS . $stayData['wifi_name'] . '.php'; ?>


<?php if (($trigger_integration && !$ok) || ($unifiOk)) : ?>
    <script src="<?php echo DIR_JS ?>feedback-errors.js"></script>
    <script>
        $(document).ready(function() {
            $('.stay-wifi-redirect-content').hide();
            $(".ripple, .connection-text").addClass("animated dblock bounceIn");



            // publish the wifi-redirect event for the wifi integration to authenticate the user with the router
            HLevents.publish('wifi-redirect');

            var url = '<?php echo $stayData['url'] ?>';

            if (url && url.indexOf('Set url') === -1) {

                //put a timeout to be sure that authorization has been done in integrations that are not unifi
                setTimeout(function() {
                    window.location.href = url;
                }, 3000);
            }
        });
    </script>
<?php endif; ?>

<?php
if (array_get($_SESSION, 'bypassStuck')) {
    exit();
}
?>