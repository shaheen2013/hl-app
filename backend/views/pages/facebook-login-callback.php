<?php if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}
switch ($_SESSION['access_fb_from']){

    case 'stay-share':
        include_once TEMPLATES . 'stay-share-template.php';
        break;
}
?>


<script>
    window.onload = function(){

        document.getElementsByClassName("stay-share-content")[0].style.display = 'block';

    };
    $(document).ready(function(){

        $('.btn-facebook').click(function(){
            window.location.href = '<?php echo (isset($loginUrl) ? $loginUrl : '#') ?>';
        })
    });
</script>