<button class="btn btn-default" onclick="goBack()" style="margin-top:-7px;"><i class="fa fa-arrow-circle-o-left"></i> <?php echo ($_SESSION['userLang'] == 'en' ? 'Go Back' : 'Ir Atrás') ?> </button>
<script>
function goBack() {
    window.history.back();
}
</script>