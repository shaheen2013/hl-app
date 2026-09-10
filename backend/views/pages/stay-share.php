<?php if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
} ?>

<?php include_once LANG . $_SESSION['userLang'] . '/stay-share.php'; ?>
<style type="text/css">
    html {
        overflow-x: hidden;
    }

    body {
        background: url(<?php echo imageSize('large', $hotelInfo['fotoBg']) ?>);
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .stay-share-content {
        display: flex;
        flex-wrap: wrap;
        height: 100vh;
        align-content: space-between;
    }
</style>
<div class="stay-overlayer"></div>

<div class="connection-text">
    <h4><strong><?php echo $stayShareLang['Connecting to wifi.'] ?></strong></h4>
    <p><?php echo $stayShareLang['This can take a bit, please be patient.'] ?></p>
</div>
<div class="ripple"><img src="<?php echo DIR_IMG . 'ripple.svg' ?>" alt="loaded spinner" width="100" height="100"></div>
<div class="stay-share-content">
    <div class="col-xs-12"></div>

    <script>
        var cookieEnabled = navigator.cookieEnabled ? true : false;

        if (typeof navigator.cookieEnabled == "undefined" && !cookieEnabled) {
            document.cookie = "test";
            cookieEnabled = (document.cookie.indexOf("test") != -1) ? true : false;
        }
        if (!cookieEnabled) {
            var title = "<?php echo $cookiesLang['cookies_required_title'] ?>";
            var description = "<?php echo $cookiesLang['cookies_required_description'] ?>";
            var html = '<div class="alert alert-danger text-center" style="margin:0;position:absolute;" role="alert"><strong>' + title + '</strong><p>' + description + '</p></div>'
            document.write(html)

            //hide buttons to continue login portal since no cookies are activated
            $(document).ready(function() {
                $('.btn-facebook').hide();
                $('.btn-email').hide();
                $('.modal').remove();
            })
        }
    </script>


    <?php if ($ofertaWifiHotel) { ?>
        <img width="40" class="wifi-offer-gift-button wifi-offer-gift-button_deactivated" data-toggle="modal" data-target="#wifi-offer-modal" alt="Hotel Logo" src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCA1MDggNTA4IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA1MDggNTA4OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjUxMnB4IiBoZWlnaHQ9IjUxMnB4Ij4KPGNpcmNsZSBzdHlsZT0iZmlsbDojNTRDMEVCOyIgY3g9IjI1NCIgY3k9IjI1NCIgcj0iMjU0Ii8+CjxwYXRoIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBkPSJNMzE5LDkzYy0xMy43LTEzLjctMzUuOS0xMy43LTQ5LjYsMGMtNS42LDUuNi05LjMsMTcuNi0xMS43LDI5LjljLTIuNy0xNi40LTcuMy0zMy45LTE0LjktNDEuNSAgYy0xNi4xLTE2LjEtNDIuMy0xNi4xLTU4LjQsMHMtMTYuMSw0Mi4zLDAsNTguNGM5LjIsOS4yLDMyLjgsMTQsNTEuNSwxNi40YzAuNSw5LjksOC43LDE3LjksMTguOCwxNy45YzkuOSwwLDE3LjktNy42LDE4LjctMTcuMyAgYzE2LjItMiwzNy41LTYuMSw0NS42LTE0LjJDMzMyLjcsMTI4LjksMzMyLjcsMTA2LjcsMzE5LDkzeiBNMTk4LjEsOTVjMTEuNS0xMS41LDMwLjItMTEuNSw0MS43LDBjNy40LDcuNCwxMC44LDI3LjgsMTIuMyw0MS42ICBjLTcsMS0xMi43LDUuNy0xNSwxMi4xYy0xMy42LTEuNi0zMi4xLTUuMS0zOS0xMkMxODYuNiwxMjUuMiwxODYuNiwxMDYuNSwxOTguMSw5NXogTTMwNy40LDE0MGMtNi4xLDYuMS0yMi45LDktMzQuNiwxMC40ICBjLTEuNS01LjctNS42LTEwLjMtMTEtMTIuNWMxLjQtMTEuNiw0LjMtMjcuMywxMC4yLTMzLjJjOS44LTkuOCwyNS42LTkuOCwzNS40LDBDMzE3LjIsMTE0LjQsMzE3LjIsMTMwLjIsMzA3LjQsMTQweiIvPgo8cGF0aCBzdHlsZT0iZmlsbDojRkY3MDU4OyIgZD0iTTM5MS4zLDQxNS41SDExNi43Yy0xLDAtMS44LTAuOC0xLjgtMS44VjE4OS42YzAtMSwwLjgtMS44LDEuOC0xLjhoMjc0LjVjMSwwLDEuOCwwLjgsMS44LDEuOHYyMjQuMSAgQzM5Myw0MTQuNywzOTIuMiw0MTUuNSwzOTEuMyw0MTUuNXoiLz4KPHBhdGggc3R5bGU9ImZpbGw6I0YxNTQzRjsiIGQ9Ik0zOTMsMjEzLjV2LTE4LjdjMC0zLjktMy4yLTcuMS03LjEtNy4xSDEyMmMtMy45LDAtNy4xLDMuMi03LjEsNy4xdjE4LjdIMzkzeiIvPgo8cGF0aCBzdHlsZT0iZmlsbDojRkY3MDU4OyIgZD0iTTQwMS41LDE5OC4ydi0zNy42YzAtMy45LTMuMi03LjEtNy4xLTcuMUgxMTMuNmMtMy45LDAtNy4xLDMuMi03LjEsNy4xdjM3LjZjMCwzLjksMy4yLDcuMSw3LjEsNy4xICBoMjgwLjhDMzk4LjMsMjA1LjIsNDAxLjUsMjAyLjEsNDAxLjUsMTk4LjJ6Ii8+CjxyZWN0IHg9IjIzNC40IiB5PSIxNTMuNSIgc3R5bGU9ImZpbGw6I0U2RTlFRTsiIHdpZHRoPSIzOS4zIiBoZWlnaHQ9IjI2MiIvPgo8cmVjdCB4PSIyMzQuNCIgeT0iMjk0LjgiIHN0eWxlPSJmaWxsOiNDRUQ1RTA7IiB3aWR0aD0iMzkuMyIgaGVpZ2h0PSI1MCIvPgo8cmVjdCB4PSIxMTUiIHk9IjMwMC4yIiBzdHlsZT0iZmlsbDojRTZFOUVFOyIgd2lkdGg9IjI3OC4xIiBoZWlnaHQ9IjM5LjMiLz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==" />
    <?php } ?>

    <div class="col-xs-12 text-center">
        <?php if (isset($_GET['error']) && $_GET['error'] === 'form') : ?>
        <?php else : ?>
            <img src="<?php echo !empty($hotelInfo['logo']) ? imageSize('small', $hotelInfo['logo']) :  DIR_IMG . 'img-placeholder.jpg' ?>" alt="Hotel logo" class="img-thumbnail img-circle hotel-stay-share-logo" width="100" height="100">
            <?php if ($hotelInfo) : ?>
                <h3 class="animated-p"><?php echo $hotelInfo['hotelName'] ?></h3>
                <?php if ($hotelInfo['estrellas']) : ?>
                    <?php if ($hotelInfo['estrellas'] >= 3) : ?>
                        <?php for ($i = 0; $i < $hotelInfo['estrellas']; $i++) { ?>
                            <i class="animated-p hotel-stars"><img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB3aWR0aD0iMzBweCIgaGVpZ2h0PSIyN3B4IiB2aWV3Qm94PSIwIDAgMzAgMjciIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+CiAgICA8IS0tIEdlbmVyYXRvcjogU2tldGNoIDQ0LjEgKDQxNDU1KSAtIGh0dHA6Ly93d3cuYm9oZW1pYW5jb2RpbmcuY29tL3NrZXRjaCAtLT4KICAgIDx0aXRsZT5SZWN0YW5nbGUgODwvdGl0bGU+CiAgICA8ZGVzYz5DcmVhdGVkIHdpdGggU2tldGNoLjwvZGVzYz4KICAgIDxkZWZzPgogICAgICAgIDxwb2x5Z29uIGlkPSJwYXRoLTEiIHBvaW50cz0iMTQuOTcyODc1MiAwIDE5LjI1ODQ4NjQgOS4xOTA1IDI5LjU3MTQyODYgMTAuMzEyNTc1IDIxLjkwNjQzOTQgMTcuMTE2OTUgMjMuOTk0NDQ0MyAyNy4wMDAwNzUgMTQuOTcyNTM4MyAyMi4wMTUyIDUuOTUwNjMyMzcgMjcuMDAwMDc1IDguMDM4NjM3MjUgMTcuMTE2OTUgMC4zNzM2NDgxMDEgMTAuMzEyNTc1IDEwLjY4NjU5MDIgOS4xODkwMzc1Ij48L3BvbHlnb24+CiAgICA8L2RlZnM+CiAgICA8ZyBpZD0iUGFnZS0xIiBzdHJva2U9Im5vbmUiIHN0cm9rZS13aWR0aD0iMSIgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj4KICAgICAgICA8ZyBpZD0iQUMtUmVwYWlyIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtNDIxLjAwMDAwMCwgLTgyLjAwMDAwMCkiPgogICAgICAgICAgICA8ZyBpZD0iSGVhZGVyIj4KICAgICAgICAgICAgICAgIDxnIGlkPSJUb3AiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDEzNS4wMDAwMDAsIDI1LjAwMDAwMCkiPgogICAgICAgICAgICAgICAgICAgIDxnIGlkPSJMZWZ0Ij4KICAgICAgICAgICAgICAgICAgICAgICAgPGcgaWQ9IlJldmlld3MiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDIwNi4wMDAwMDAsIDI5LjAwMDAwMCkiPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGcgaWQ9IlN0YXJzIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgxNC4wMDAwMDAsIDI4LjAwMDAwMCkiPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxnIGlkPSJucF9zdGFyXzc1MDgzMF8wMDAwMDAtY29weS0zIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSg2NS43MTQyODYsIDAuMDAwMDAwKSI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxnIGlkPSJSZWN0YW5nbGUtOCI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bWFzayBpZD0ibWFzay0yIiBmaWxsPSJ3aGl0ZSI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPHVzZSB4bGluazpocmVmPSIjcGF0aC0xIj48L3VzZT4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvbWFzaz4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDx1c2UgaWQ9Ik1hc2siIGZpbGw9IiNGRkM3MDAiIGZpbGwtcnVsZT0ibm9uemVybyIgeGxpbms6aHJlZj0iI3BhdGgtMSI+PC91c2U+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8cmVjdCBmaWxsPSIjRkY3MTAwIiBvcGFjaXR5PSIwLjIwMDAwMDAwMyIgbWFzaz0idXJsKCNtYXNrLTIpIiB4PSIxNC40NTcxNDI5IiB5PSIwIiB3aWR0aD0iMTUuMTE0Mjg1NyIgaGVpZ2h0PSIyOC4zMTcwNzMyIj48L3JlY3Q+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZz4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2c+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2c+CiAgICAgICAgICAgICAgICAgICAgICAgIDwvZz4KICAgICAgICAgICAgICAgICAgICA8L2c+CiAgICAgICAgICAgICAgICA8L2c+CiAgICAgICAgICAgIDwvZz4KICAgICAgICA8L2c+CiAgICA8L2c+Cjwvc3ZnPg==" width="15" /></i>
                        <?php } ?>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (isset($_GET['error']) && $_GET['error'] === 'form') : ?>
            <div class="col-md-4 col-md-offset-4">
                <img class="animated zoomIn" src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMS4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDY1IDY1IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA2NSA2NTsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI2NHB4IiBoZWlnaHQ9IjY0cHgiPgo8Zz4KCTxnPgoJCTxwYXRoIGQ9Ik0zMi41LDY1QzUwLjQyLDY1LDY1LDUwLjQyMSw2NSwzMi41UzUwLjQyLDAsMzIuNSwwUzAsMTQuNTc5LDAsMzIuNVMxNC41OCw2NSwzMi41LDY1eiBNMzIuNSw0ICAgIEM0OC4yMTUsNCw2MSwxNi43ODUsNjEsMzIuNVM0OC4yMTUsNjEsMzIuNSw2MVM0LDQ4LjIxNSw0LDMyLjVTMTYuNzg1LDQsMzIuNSw0eiIgZmlsbD0iI0ZGRkZGRiIvPgoJCTxjaXJjbGUgY3g9IjMzLjAxOCIgY3k9IjQzLjY1NSIgcj0iMy4zNDUiIGZpbGw9IiNGRkZGRkYiLz4KCQk8cGF0aCBkPSJNMzIuMzMyLDM1LjM0MmMxLjEwNCwwLDItMC44OTYsMi0ydi0xN2MwLTEuMTA0LTAuODk2LTItMi0ycy0yLDAuODk2LTIsMnYxN0MzMC4zMzIsMzQuNDQ2LDMxLjIyOCwzNS4zNDIsMzIuMzMyLDM1LjM0MnogICAgIiBmaWxsPSIjRkZGRkZGIi8+Cgk8L2c+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==" />
                <h4><strong><?php echo $stayShareLang['ups'] ?></strong></h4>
                <p><?php echo $stayShareLang['fields not fill detected'] ?></p>
                <button class="btn btn-facebook <?php echo !$facebookIsActivated ? 'hide' : '' ?>" aria-label="Log in with Facebook">
                    <div class="flex-container">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 216 216" class="_5h0m" color="#ffffff">
                            <path fill="#ffffff" d=" M204.1 0H11.9C5.3 0 0 5.3 0 11.9v192.2c0 6.6 5.3 11.9 11.9 11.9h103.5v-83.6H87.2V99.8h28.1v-24c0-27.9 17-43.1 41.9-43.1 11.9 0 22.2.9 25.2 1.3v29.2h-17.3c-13.5 0-16.2 6.4-16.2 15.9v20.8h32.3l-4.2 32.6h-28V216h55c6.6 0 11.9-5.3 11.9-11.9V11.9C216 5.3 210.7 0 204.1 0z"></path>
                        </svg>
                        <strong><?php echo $stayShareLang['share facebook'] ?></strong>
                    </div>
                </button>
                <p class="pt2">
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#linkModal"><?php echo $stayShareLang['fill the form again'] ?> </button>
                </p>
            </div>
        <?php else : ?>
            <?php if (isset($_GET['error']) && $_GET['error'] === 'user_canceled') : ?>
                <h4 class="animated-p"><?php echo $stayShareLang['It seems you cancelled login'] ?></h4>
                <!-- TODO: remove comments when start HLK-2000 -->
                <?php /*else: ?>
                <h4 class="animated-p"><?php echo $stayShareLang['Share your experience so far in our hotel and get free WIFI!'] */ ?>
                <!--</h4> -->
            <?php endif; ?>
            <p class="animated-p" style="margin-top:1rem;"><?php echo $stayShareLang['Additionally, your friends will get a complimentary'] ?></p>

            <button class="btn btn-facebook <?php echo !$facebookIsActivated ? 'hide' : '' ?>" aria-label="Log in with Facebook">
                <div class="flex-container">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 216 216" class="_5h0m" color="#ffffff">
                        <path fill="#ffffff" d=" M204.1 0H11.9C5.3 0 0 5.3 0 11.9v192.2c0 6.6 5.3 11.9 11.9 11.9h103.5v-83.6H87.2V99.8h28.1v-24c0-27.9 17-43.1 41.9-43.1 11.9 0 22.2.9 25.2 1.3v29.2h-17.3c-13.5 0-16.2 6.4-16.2 15.9v20.8h32.3l-4.2 32.6h-28V216h55c6.6 0 11.9-5.3 11.9-11.9V11.9C216 5.3 210.7 0 204.1 0z"></path>
                    </svg>
                    <strong><?php echo $stayShareLang['share facebook'] ?></strong>
                </div>
            </button>
            <div class="clearfix"></div>
            <?php if ($googleIsActivated) : ?>
                <button class="btn btn-social google-button animated fadeInUp" style="margin-top:1rem; width:245px">
                    <div class="google-button-overlay"></div>
                    <div class="flex-container">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50">
                            <defs>
                                <path id="a" d="M44.5 20H24v8.5h11.8C34.7 33.9 30.1 37 24 37c-7.2 0-13-5.8-13-13s5.8-13 13-13c3.1 0 5.9 1.1 8.1 2.9l6.4-6.4C34.6 4.1 29.6 2 24 2 11.8 2 2 11.8 2 24s9.8 22 22 22c11 0 21-8 21-22 0-1.3-.2-2.7-.5-4z" />
                            </defs>
                            <clipPath id="b">
                                <use xlink:href="#a" overflow="visible" />
                            </clipPath>
                            <path clip-path="url(#b)" fill="#FBBC05" d="M0 37V11l17 13z" />
                            <path clip-path="url(#b)" fill="#EA4335" d="M0 11l17 13 7-6.1L48 14V0H0z" />
                            <path clip-path="url(#b)" fill="#34A853" d="M0 37l30-23 7.9 1L48 0v48H0z" />
                            <path clip-path="url(#b)" fill="#4285F4" d="M48 48L17 24l-4-3 35-10z" />
                        </svg>
                        <strong><?php echo $stayShareLang['google login'] ?></strong>
                    </div>
                </button>
                <div class="clearfix"></div>
            <?php endif; ?>
            <?php if ($formIsActivated) : ?>
                <button class="btn btn-warning animated fadeInUp btn-email" style="margin-top:1rem; width:245px" data-toggle="modal" data-target="#linkModal">
                    <img height="12" src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0ZWQgYnkgSWNvTW9vbi5pbyAtLT4KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjEiIHdpZHRoPSIxNnB4IiBoZWlnaHQ9IjE2cHgiIHZpZXdCb3g9IjAgMCAxNiAxNiI+CjxwYXRoIGZpbGw9IiNGRkZGRkYiIGQ9Ik0xNSAydjJoLTl2LTJoOXpNMTYgMWgtMTF2NGgxMXYtNHoiLz4KPHBhdGggZmlsbD0iI0ZGRkZGRiIgZD0iTTAgMWg0djRoLTR2LTR6Ii8+CjxwYXRoIGZpbGw9IiNGRkZGRkYiIGQ9Ik0xNSA3djJoLTl2LTJoOXpNMTYgNmgtMTF2NGgxMXYtNHoiLz4KPHBhdGggZmlsbD0iI0ZGRkZGRiIgZD0iTTAgNmg0djRoLTR2LTR6Ii8+CjxwYXRoIGZpbGw9IiNGRkZGRkYiIGQ9Ik0xNSAxMnYyaC05di0yaDl6TTE2IDExaC0xMXY0aDExdi00eiIvPgo8cGF0aCBmaWxsPSIjRkZGRkZGIiBkPSJNMCAxMWg0djRoLTR2LTR6Ii8+Cjwvc3ZnPgo=" />
                    <strong><?php echo $stayShareLang['access by form'] ?></strong>
                </button>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <?php include 'views/common/stepper.php'; ?>
    <?php if ($regularUser && !$showGDPR) : ?>
        <form id="bypass" method='POST'>
            <input type="hidden" name="UserRedirecting" value="true" />
            <input type="hidden" name="PHPSESSID" value="<?php echo session_id() ?>">
        </form>
    <?php endif; ?>

</div>

<?php require LIB . 'wifi_integrations' . DS . $wifi_provider['name'] . '.php' ?>
<?php include TEMPLATES . 'link-modal-template.php' ?>
<?php require TEMPLATES . 'gdpr.php' ?>

<?php if (!empty($_SESSION['fbErrors'])) {
    include TEMPLATES . 'stay_facebook_errors_modal.php';
} else {
    if (!empty($ofertaWifiHotel['id']) && !$regularUser)
        include TEMPLATES . 'wifi-offer-modal-template.php';
} ?>
<script src="<?php echo DIR_JS . 'stay-share.min.js' ?>"></script>
<script src="<?php echo DIR_JS ?>eventEmitter.js"></script>


<script>
    window.onpageshow = function(event) {
        if (event.persisted) {
            window.location.reload()
        }

    };

    function showFormAnimation() {
        $('#linkModal').modal('hide');
        $('#wifi-offer-modal').modal('hide');
        $(".img-circle, h3, .animated-p,.btn-facebook, .mail-link").addClass("animated zoomOut");
        $('.btn-email').addClass('zoomOut hidden');
        $('.google-button').addClass('zoomOut hidden');
        $(".ripple, .connection-text").addClass("animated dblock bounceIn");
        $('.wifi-offer-gift-button').addClass('zoomOut hidden');
    }

    function hideFormAnimation() {
        $(".img-circle, h3, .animated-p,.btn-facebook, .mail-link").removeClass("animated zoomOut");
        $('.btn-email').removeClass('zoomOut hidden');
        $('.google-button').removeClass('zoomOut hidden');
        $(".ripple, .connection-text").removeClass("animated dblock bounceIn");
    }

    $(document).ready(function() {
        // Open form modal automatically
        <?php if (!$_SESSION['showPortalPro'] || data_get($_SESSION, 'portal_pro_user')) { ?>
            setTimeout(function() {
                $('#linkModal').modal('show');
            }, 500);
        <?php } ?>
        
        <?php
        if (array_get($_SESSION, 'premium_code_active')) { ?>
            // publish the wifi-redirect event for the wifi integration to authenticate the user with the router
            HLevents.publish('wifi-redirect');
        <?php } ?>

        //bypass if it is a user already (we have it's mac address already and all info is OK)
        <?php if ($regularUser && !$showGDPR && !array_get($_SESSION, 'bypassInvalid')) : ?>
            document.getElementById("bypass").submit();
        <?php endif; ?>
        


        //if hotel has offer show the modal
        <?php if (!empty($ofertaWifiHotel['id']) && !$showGDPR) : ?>
            $('#wifi-offer-modal').on('shown.bs.modal', function(e) {
                $('.wifi-offer-gift-button').removeClass('wifi-offer-gift-button_activated').addClass('wifi-offer-gift-button_deactivated');
            });

            <?php if (!array_get($_SESSION, 'formIncomplete')) { ?>
                setTimeout(function() {
                    $('#wifi-offer-modal').modal('show');
                }, 1000);
            <?php } ?>

        <?php endif; ?>

        //show error modal if facebook returns with error code
        <?php if (!empty($_SESSION['fbErrors'])) { ?>
            $('#facebook_errors_modal').modal('show');
            $('.btn-toggle-form').click(function() {
                $('#facebook_errors_modal').modal('hide');
            });
            <?php unset($_SESSION['fbErrors']); ?>
        <?php } ?>



        //click on facebook button
        $('.btn-facebook').click(function() {
            showFormAnimation();
            setTimeout(function() {
                window.location.href = '<?php echo $loginUrl ?? '' ?>';
            }, 1000);
        })

        //click on google button
        $('.google-button').click(function() {
            showFormAnimation();
            setTimeout(function() {
                window.location.href = '<?php echo $googleAuthUrl ?? '' ?>';
            }, 1000);
        })

        //login form submit
        $('#logByEmail').submit(function() {
            showFormAnimation();
        })

        // Check if the form has been completed by pms data before show the modal
        <?php if ($isLoginFormComplete ?? false) { ?>
            $('[name="refShareStep2userData"]').trigger('click');
        <?php } ?>

        // Check if the form has been completed by pms data before show the modal
        <?php if (!array_get($_SESSION, 'is_pms_user_email_valid', true) || array_get($_SESSION, 'formIncomplete')) { ?>
            $('.wifi-offer-gift-button').removeClass('wifi-offer-gift-button_deactivated').addClass('wifi-offer-gift-button_activated');

            setTimeout(function() {
                $('#linkModal').modal('show');
            }, 1000);
        <?php } ?>
    });
</script>