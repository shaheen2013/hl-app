<?php if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<div class="mt2">
	<div class="col-lg-12 text-center">
		<h2>Acciones</h2>
		<div class="btn-group" role="group" aria-label="...">
			<button type="button" class="btn btn-success" data-toggle="modal" data-target="#addBookingEngineModal">Añadir Motor de Reserva</button>
            <a href="<?php echo SECURE_BASE_PATH . 'private/' . $urlTree['private-invitar-hotel']?>" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i> Volver</a>
            <a class="btn btn-warning" href="app/logout" title="logout">Logout</a>
		</div>
	</div>
    <div class="col-lg-12">
        <h2>Listado de Motores de Reserva</h2>
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-striped" style="overflow: auto">
                <tr>	
                    <td>ID</td>
                    <td>Booking Engine Name</td>
                    <td>GTM Container ID</td>
                    <td>GTM Workspace ID</td>
                    <td>GTM Variables Map</td>
                    <td>Actions</td>
                </tr>
                <?php foreach ($booking_engines as $booking_engine) { ?>
                    <tr>
                        <td><?php echo array_get($booking_engine, 'id') ?></td>
                        <td><?php echo array_get($booking_engine, "name") ?></td>
                        <td>
                            <form method="POST" class="form-inline">
                                <div class="form-group">
                                    <label>
                                        Edit Container ID
                                        <input type="number" name="gtm_booking_engine_container_id" value="<?php echo intval(array_get($booking_engine, "gtm_container_id"))?>">
                                    </label>
                                </div>
                                <input type="hidden" name="editContainerId" value="editContainerId">
                                <input type="hidden" name="booking_engine_id" value="<?php echo array_get($booking_engine, 'id') ?>">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </td>
                        <td>
                            <form method="POST" class="form-inline">
                                <div class="form-group">
                                    <label>
                                        Edit Workspace ID
                                        <input type="number" name="gtm_booking_engine_workspace_id" value="<?php echo intval(array_get($booking_engine, "gtm_workspace_id"))?>">
                                    </label>
                                </div>

                                <input type="hidden" name="editWorkspaceId" value="editWorkspaceId">
                                <input type="hidden" name="booking_engine_id" value="<?php echo array_get($booking_engine, 'id') ?>">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </td>
                        <td><?php echo array_get($booking_engine, "gtm_variable_map") ? json_encode(json_decode(array_get($booking_engine, "gtm_variable_map")), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : "" ?></td>
                        <td>
                            <form method="POST" class="form-inline">
                                <input type="hidden" name="startMappingVariables" value="startMappingVariables">
                                <input type="hidden" name="booking_engine_id" value="<?php echo array_get($booking_engine, 'id') ?>">
                                <input type="hidden" name="gtm_booking_engine_container_id" value="<?php echo array_get($booking_engine, 'gtm_container_id') ?>">
                                <input type="hidden" name="gtm_booking_engine_workspace_id" value="<?php echo array_get($booking_engine, 'gtm_workspace_id') ?>">
                                <button type="submit" class="btn btn-primary">Refresh Map Variables</button> 
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>
    </div>

<div class="modal fade" tabindex="-1" role="dialog" id="addBookingEngineModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Add Booking Engine</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-lg-12">
						<h2>Add New Booking Engine</h2>
						<form method="post">
							<div class="form-group">
								<input name="booking_engine_name" class="form-control" type="text" required  placeholder="Booking Engine Name" />
							</div>
							<div class="form-group">
								<input name="gtm_booking_engine_container_id" class="form-control" type="number" required placeholder="Container ID" />
							</div>
							<div class="form-group">
								<input name="gtm_booking_engine_workspace_id" class="form-control" type="number" placeholder="Workspace ID" />
							</div>
                            <input type="hidden" name="addNewBookingEngine" value="addNewBookingEngine">
                            <button type="submit" class="btn btn-primary">Send</button> 
						</form>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>


</div>
<center>
    <a href="<?php echo SECURE_BASE_PATH . 'private/' . $urlTree['private-invitar-hotel']?>" class="btn btn-default" style="margin-bottom: 2em;"><i class="fa fa-arrow-left" aria-hidden="true" ></i> Volver</a>
</center>
