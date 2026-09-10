<?php
if(!empty($ok)){
	if($ok[0] === true){
		echo '<div class="feedback success-action">';
	}else{
		echo '<div class="feedback error-action">';
	}
}else{
	echo '<div class="feedback warning-action">';
}?>
<?php if(!empty($ok)){ ?>
<div class="text-center">
	<ul>
		<?php foreach ($ok as $message) {
			if (!is_bool($message) && !is_int($message)) {
				$msgCode = ${'msg' . $message};
				echo '<li>' . $msgCode . '</li>';
			} 

		} ?>
	</ul>
	<a href="#" class="close-feedback blanco"><img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTguMS4xLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDE3NC4yMzkgMTc0LjIzOSIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMTc0LjIzOSAxNzQuMjM5OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjI0cHgiIGhlaWdodD0iMjRweCI+CjxnPgoJPHBhdGggZD0iTTg3LjEyLDBDMzkuMDgyLDAsMCwzOS4wODIsMCw4Ny4xMnMzOS4wODIsODcuMTIsODcuMTIsODcuMTJzODcuMTItMzkuMDgyLDg3LjEyLTg3LjEyUzEzNS4xNTcsMCw4Ny4xMiwweiBNODcuMTIsMTU5LjMwNSAgIGMtMzkuODAyLDAtNzIuMTg1LTMyLjM4My03Mi4xODUtNzIuMTg1UzQ3LjMxOCwxNC45MzUsODcuMTIsMTQuOTM1czcyLjE4NSwzMi4zODMsNzIuMTg1LDcyLjE4NVMxMjYuOTIxLDE1OS4zMDUsODcuMTIsMTU5LjMwNXoiIGZpbGw9IiNGRkZGRkYiLz4KCTxwYXRoIGQ9Ik0xMjAuODMsNTMuNDE0Yy0yLjkxNy0yLjkxNy03LjY0Ny0yLjkxNy0xMC41NTksMEw4Ny4xMiw3Ni41NjhMNjMuOTY5LDUzLjQxNGMtMi45MTctMi45MTctNy42NDItMi45MTctMTAuNTU5LDAgICBzLTIuOTE3LDcuNjQyLDAsMTAuNTU5bDIzLjE1MSwyMy4xNTNMNTMuNDA5LDExMC4yOGMtMi45MTcsMi45MTctMi45MTcsNy42NDIsMCwxMC41NTljMS40NTgsMS40NTgsMy4zNjksMi4xODgsNS4yOCwyLjE4OCAgIGMxLjkxMSwwLDMuODI0LTAuNzI5LDUuMjgtMi4xODhMODcuMTIsOTcuNjg2bDIzLjE1MSwyMy4xNTNjMS40NTgsMS40NTgsMy4zNjksMi4xODgsNS4yOCwyLjE4OGMxLjkxMSwwLDMuODIxLTAuNzI5LDUuMjgtMi4xODggICBjMi45MTctMi45MTcsMi45MTctNy42NDIsMC0xMC41NTlMOTcuNjc5LDg3LjEyN2wyMy4xNTEtMjMuMTUzQzEyMy43NDcsNjEuMDU3LDEyMy43NDcsNTYuMzMxLDEyMC44Myw1My40MTR6IiBmaWxsPSIjRkZGRkZGIi8+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==" /></i></a>
</div>
</div>
<?php } ?>
<script>
	var msgError = function(timeShow){
        timeShow = typeof timeShow !== 'undefined' ? timeShow : 5000;
		$('.feedback').addClass('feedback-active');
			setTimeout(function() {
			$('.feedback').removeClass('feedback-active');

		<?php if(!empty($ok) && !empty($ok[2]) && is_int($ok[2])) {
			echo "},".$ok[2].");";
		} else {
			echo "}, timeShow);";
		} ?>		
		
		$('.close-feedback').click(function(e){
			e.preventDefault();
			$('.feedback').removeClass('feedback-active');
		})
	}
	

	$(document).ready(function(){
		<?php
		if(!empty($ok)){ ?>
			msgError();
		<?php } ?>
	});
</script>
