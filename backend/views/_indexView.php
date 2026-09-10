<?php include LANG . $_SESSION['userLang'] . '/cookies.php' ?>

<?php if ($url['dir1'] == 'private') { ?>

    <?php require(VIEW_COMMONS . 'head.php'); ?>
    <?php include(PAGES_PRIVATE . $contenido . '.php'); ?>
    <?php require(VIEW_COMMONS . 'footer.php'); ?>

<?php } else { ?>
    <?php require(VIEW_COMMONS . 'head.php'); ?>
    <div class="hlwrapper"><!--START HLWRAPPER-->
        <noscript>
            <div class="alert alert-danger text-center" style="margin:0;" role="alert">
                <strong><?php echo $cookiesLang['javascript_required_title'] ?></strong>
                <p><?php echo $cookiesLang['javascript_required_description'] ?></p></div>
        </noscript>

        <?php require(VIEW_PAGES . $contenido . '.php'); ?>
    </div><!--END HLWRAPPER-->
    <?php require(VIEW_COMMONS . 'footer.php'); ?>
<?php } ?>

<script>
    // We listen changes into the localStorage and if we see an attempt to change hotels, 
    // we close all tabs of other hotels to avoid saving data from one hotel over another.
    window.addEventListener('storage', function(e) {
        var preventedOrigins = ["private", "stay-share", "stay-wifi-redirect"];
        if (event.key === "Reloading Hotel" && !new RegExp(preventedOrigins.join("|")).test(window.location.href)) {
            window.close();
        }
    });
</script>