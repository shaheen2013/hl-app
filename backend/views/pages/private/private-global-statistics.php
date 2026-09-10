<div class="col-lg-12">
	<table class="table table-bordered table-striped">
		<tr>
			<td>ID</td>
			<td>Nombre</td>
			<td>Iframe Opens</td>
			<td>click Share</td>
			<td>Modal Opens</td>
			<td>Last clicks</td>
		</tr>
		
		<?php foreach ($hotelStatistics as $hotel) { ?>
		<tr>
			<td><?php echo $hotel['id_hotel'] ?></td>
			<td><?php echo $hotel['hotelName'] ?></td>
			<td><?php echo $hotel['iframe_opens'] ?></td>
			<td><?php echo $hotel['share_btn_clicks'] ?></td>
			<td><?php echo $hotel['iframe_modal_open'] ?></td>
			<td><?php echo $hotel['iframe_modal_share'] ?></td>
		</tr>
		<?php } ?>
		
	</table>
</div>