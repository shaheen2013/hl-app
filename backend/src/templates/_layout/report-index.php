<!doctype html>
<html lang="en">
<head>
    <title><?php echo $this->e($title) ?></title>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="#ffffff">
    <link rel="apple-touch-icon" sizes="180x180"
          href="<?php echo $this->asset('/public/favicon/apple-touch-icon.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32"
          href="<?php echo $this->asset('/public/favicon/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16"
          href="<?php echo $this->asset('/public/favicon/favicon-16x16.png') ?>">
    <link rel="manifest" href="<?php echo $this->asset('/public/favicon/manifest.json') ?>">
    <link rel="mask-icon" href="<?php echo $this->asset('/public/favicon/safari-pinned-tab.svg') ?>" color="#5bbad5">
    <link rel="stylesheet" href="<?php echo $this->asset('/public/styles/semantic.min.css') ?>">
    <link rel="stylesheet" href="/public/css/pdf-report.css">
    <script src="<?php echo $this->asset('/public/javascript/jquery.3.7.1.min.js') ?>"></script>

</head>
<body onload="$('#loader').fadeOut('fast');">
<div id="loader">
    <img style="height: unset;position: absolute!important;top: 50%!important;left: 50%!important;transform: translate(-50%, -50%);" src="https://images.hotelinking.com/ui/Loader_black.gif" alt="loader" height="80" width="80">
</div>

<?php if ($flash): ?>
    <div class="ui <?php echo data_get($flash, 'errors') ? 'error' : 'success' ?> message top_fixed_message large fixed">
        <i class="close icon"></i>
        <div class="header"><?php echo data_get($flash, 'errors') ? 'Ups...' : 'Success' ?></div>
        <?php if (data_get($flash, 'errors')) : ?>
            <ul class="list">
                <?php foreach (data_get($flash, 'errors') as $error) { ?>
                    <li><?= e($error[0]) ?></li>
                <?php } ?>
            </ul>
        <?php else : ?>
            <p>Success</p>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php echo $this->section('content') ?>
<script src="<?php echo $this->asset('/public/styles/semantic.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/main.min.js') ?>"></script>
<?= $this->section('scripts') ?>

</body>
</html>
