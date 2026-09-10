(function(){
    $("#recoverPasswordButton").on("click", function(){ $('#recoverPassModal').modal('show'); });
    $("#recoverPass").on("click", function(e) {
        e.preventDefault();
        var form = document.getElementById('recoverPassForm');

        if (form.checkValidity()) {
            form.submit(); 
        } else {
            form.reportValidity();
        } 
    });
    $("#challengeModalButton").on("click", function() {
        var form = document.getElementById('challengeForm');

        if (form.checkValidity()) {
            form.submit(); // Submit the form if it's valid
        } else {
            // If the form is invalid, trigger the browser's built-in validation display
            form.reportValidity();
        }
    });

    $('input[name="mfa_type"]').change(function() {
        var selectedValue = $('input[name="mfa_type"]:checked').val();
      
        // Hide both forms initially
        $('#smsForm, #authenticatorForm').hide();
      
        // Show the relevant form based on the selected radio button
        if (selectedValue === 'sms') {
          $('#smsForm').show();
        } else if (selectedValue === 'authenticator') {
          $('#authenticatorForm').show();
        }
    });

    $('#toggle-secret').click(function() {
        event.preventDefault(); // Prevent default anchor behavior

        $("#secretKey").show();
        $("#toggle-secret").hide();

    });
})();