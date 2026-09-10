<script>
    $(document).ready(function () {
        HLevents.subscribe('wifi-redirect', function(obj){
            window.location.href = "https://google.com";
        })
    })
</script>