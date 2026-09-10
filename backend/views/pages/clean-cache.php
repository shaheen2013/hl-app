<div class="col-lg-12">
    <h2>clean cache</h2>
    <?php if(isset($cleanCache)) : ?>
        <?php if($cleanCache) : ?>
            <div class="alert alert-success" role="alert">
                <strong>Cache is cleared</strong> Use it with caution...
            </div>
        <?php else : ?>
            <div class="alert alert-danger" role="alert">
                <strong>Upps...</strong> I have problems clearing cache...
            </div>
        <?php endif;?>
    <?php endif; ?>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <form METHOD="get" action="<?php echo $urlTree['clean-cache']?>/">
        <input type="hidden" name="cache" value="clean">
        <div class="g-recaptcha" data-sitekey="6LeQiiYTAAAAAO6cNKK2C0QgXGWNqJHiwemvC2rN"></div>
        <button type="submit" class="btn btn-danger mt2">Clean cache now</button>
    </form>
</div>