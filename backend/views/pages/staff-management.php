<?php //Miramos si esta definida la variable de control de index.php
use GuzzleHttp\Psr7\Query;
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/staff-management.php' ?>
<div id="wrapper">
	<?php include TEMPLATES . 'hotel-sidebar.php'; ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'staff-management-menu.php' ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar">
			<div class="col-lg-12">
				<h1 class="pull-left"><i class="fa fa-folder-open-o"></i> <?php echo $lang['Staff Management'] ?></h1>
				<div class="breadcrumbs pull-right">
					<ul>
						<?php include (TEMPLATES .'breadcrumbs.php'); ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="mainContent" id="fullContainer">
			<?php if(!empty(array_get($staffs, 'data'))) { ?>
				<div class="col-lg-12">
					<div class="table-responsive mt relative">
						<table class="table table-striped">
							<tr class="table-header">
								<td>
									<span class="pull-left"><?php echo $lang['Name'] ?></span> <a href="<?php echo $urlTree['staff-management'] ?>/?ord=nombre" title="sort" class="sortOption"><i class="fa fa-sort pull-left pl"></i></a>
								</td>
								<td>
									<span class="pull-left"><?php echo $lang['Email'] ?></span><a href="<?php echo $urlTree['staff-management'] ?>/?ord=email" title="sort" class="sortOption"><i class="fa fa-sort pull-left pl"></i></a>
								</td>
                                <td>
									<span class="pull-left"><?php echo $lang['Hotel'] ?></span><a href="<?php echo $urlTree['staff-management'] ?>/?ord=hotelName" title="sort" class="sortOption"><i class="fa fa-sort pull-left pl"></i></a>
								</td>
								<td>
									<span class="pull-left"><?php echo $lang['Role'] ?></span> <a href="<?php echo $urlTree['staff-management'] ?>/?ord=role" title="sort" class="sortOption"><i class="fa fa-sort pull-left pl"></i></a>
								</td>
								<td>
									<span class="pull-left"><?php echo $lang['Verified'] ?></span><a href="<?php echo $urlTree['staff-management'] ?>/?ord=verified" title="sort" class="sortOption"><i class="fa fa-sort pull-left pl"></i></a>
								</td>
								<td class="actionsTd">
									<span class="pull-right"><?php echo $lang['Actions'] ?></span></a>
								</td>
							</tr>
							<?php foreach (array_get($staffs, 'data') as $staff) { ?>
							<tr class="table-row">
								<td>
									<?php echo $staff['name'] ?>
								</td>
								<td>
									<?php echo $staff['email'] ?>
								</td>
                                <td>
									<?php echo $staff['role'] === "Account Admin" ? $lang['All'] : $staff['hotels'] ?>
								</td>
								<td>
									<?php echo $staff['role'] ?>
								</td>
								<td>
									<?php echo $staff['verified'] ?>
								</td>
								<td class="actionsTd">
									<div class="btn-group pull-right">
										<a href="<?php echo $urlTree['edit-staff'] ?>/?id=<?php echo $staff['id'] ?>" class="btn btn-default" title="<?php echo $lang['Edit hoover'] ?>"><i class="fa fa-pencil-square-o"></i></a>
										<a href="<?php echo $urlTree['edit-staff'] ?>" data-staff="<?php echo $staff['id'] ?>" data-staffEmail="<?php echo $staff['email'] ?>" class="btn btn-warning deleteStaffBtn" title="<?php echo $lang['Delete hoover'] ?>"><i class="fa fa-trash-o"></i></a>
									</div>
								</td>
							</tr>
							<?php } ?>
						</table>
						<div class="pull-left">
							<div style="display: inline-block;" data-toggle="buttons"> 
								<span class="btn-group"><?php echo $lang['Show']; ?> &nbsp</span>
								<select style="width:auto" id="per_page" name="per_page" class="btn-group form-control"> 
									<option <?php echo ($itemsPage || $itemsPage == "10") ? 'selected="selected"': '' ?> value="10">10</option>
									<option <?php echo $itemsPage == "50" ? 'selected="selected"': '' ?> value="50">50</option>
									<option <?php echo $itemsPage == "100" ? 'selected="selected"': '' ?> value="100">100</option>
								</select>
								<span class="btn-group">&nbsp <?php echo $lang['Results']; ?></span>
							</div>
						</div>	
						<ul class="pull-right mt0 pagination">
							<?php
								$queryString['pag'] = '%d';
								$currentUrl = $pathUrl . '?' . Query::build($queryString, false);
								echo (is_string($paginate['prev'])) ?
									'<li><span class="disabled disabledAnchor icon item paginateItem">&laquo;</span></li>' :
									'<li><a href="' . sprintf($currentUrl, $paginate['prev']) . '" class="icon item paginateItem">&laquo;</a></li>';
								foreach($paginate['pages'] as $curr_page) {
									echo (is_string($curr_page)) ?
										'<li><span class="active active-page item paginateItem">' . $curr_page . '</span></li>' :
										'<li><a href="' . sprintf($currentUrl, $curr_page) . '" class="item paginateItem">' . $curr_page . '</a></li>';
									}
								echo (is_string($paginate['next'])) ?
									'<li><span class="disabled disabledAnchor icon item paginateItem">&raquo;</span></li>' :
									'<li><a href="' . sprintf($currentUrl, $paginate['next']) . '" class="icon item paginateItem">&raquo;</a></li>';
							?>
						</ul>
					</div>
				</div>
			<?php } else { ?>
				<div class="text-center mt2 container no-data-msg">
					<i class="fa fa-folder-open-o grisClaro fa-5x"></i>
					<h2>There´re no staff asigned at this moment</h2>
					<h4>¿Are you planning to do everything by your own?</h4>
					<a href="<?php echo $urlTree['add-staff'] ?>" title="add staff" class="btn btn-lg btn-success mt2">Add staff to help you</a>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
<?php include TEMPLATES . 'staff-delete-modal.php' ?>
<script>
	$('.deleteStaffBtn').click(function(e){
		e.preventDefault();
		var staffId = $(this).data('staff');
		var staffEmail = $(this).data('staffemail');
		$('.offerDeleteModalBtn').attr('href', "<?php echo $urlTree['staff-management'] ?>/?del="+ staffId +"&staffEmail="+encodeURIComponent(staffEmail));
		$('#staff-delete-modal').modal('show');
	})

	$('#per_page').on('change', function(e) {
		var url = "<?php echo preg_replace('~(\?|&)per_page=[^&]*~', '', $currentPageUrl); ?>"
		var symbol = url.indexOf('?') !== -1 ? "&" : "?"

		location.href =  url + symbol + "per_page=" + this.value;
    });
	
</script>