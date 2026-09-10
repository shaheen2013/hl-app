<?php
    include TEMPLATES . 'feedback.php';
?>
<footer class="mt2">
    <div class="col-lg-12 text-center">
        <p>© 2014 All rights reserved. <a class="pl" href="<?php echo $urlTree['terms-and-conditions'] ?>"
                                          title="terms and coditions">Terms and conditions</a></p>
        <ul>
            <li><a href="//twitter.com/hotelinking" target="_blank" title="Hotelinking twitter" rel="noopener"><i
                            class="fa fa-twitter"></i></a></li>
            <li><a href="//www.facebook.com/hotelinking" target="_blank" title="Hotelinking facebook"
                   rel="noopener"><i class="fa fa-facebook"></i></a></li>
        </ul>
    </div>
</footer>
<!-- old brand footer-->
<?php if ($url['dir1'] != $urlTree['stay-share'] && $url['dir1'] != $urlTree['stay-wifi-redirect'] && $url['dir1'] != $urlTree['clients']): ?>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    <script src="<?php echo DIR_JS ?>bootstrapValidator.min.js"></script>
    <script src="<?php echo DIR_JS ?>jquery.ui.touch-punch.min.js"></script>
    <script src="<?php echo DIR_JS ?>logoutHL.min.js"></script>
<?php endif;?>
<script src="<?php echo DIR_JS ?>bootstrap.min.js" defer></script>
<script>
    $(document).ready(function () {

        $('.hasTooltip').tooltip({
            container: 'body'
        });

        $('.shop-user-menu').click(function (e) {
            e.preventDefault();
            $('.shop-user-submenu').slideToggle('fast').toggleClass('dnone');
            $(this).children('.fa').toggleClass('fa-chevron-up fa-chevron-down');
        });

    });
</script>
</div>
</body>
</html>
