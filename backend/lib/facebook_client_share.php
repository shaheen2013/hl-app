<?php if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
} ?>
<script>
    function fb_share(object, shareObject) {

        //Must be an object
        if (typeof object !== 'object') {
            console.log('hl shareActions - you need to pass an object shareActions to this function');
            return;
        }

        //Must be a share object
        if (typeof shareObject !== 'object') {
            console.log('hl shareObject - must be defined, it defines the share method and properties');
            return;
        }

        //must be methods shared and notShared inside this object
        //one method handles share action and the other cancel action
        if ((typeof object.shared !== 'function') || (typeof object.notShared !== 'function')) {
            console.log('hl - shareActions object needs to have two methods, shared and notShared');
            return;
        }

        FB.ui(
            shareObject,
            function(response) {
                console.log(response);
                if (response && response.post_id) {
                        object.shared();
                } else {
                        object.notShared();
                }

            });
    }
</script>
