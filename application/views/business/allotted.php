<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Business Panel - Allotted Orders</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<?php $this->load->view('business/components/_vcss'); ?>
</head>
<body>
	<?php $this->load->view('business/components/_head'); ?>
	<?php if(isset($b_is_logged_in) && $b_is_logged_in == 1) { ?>
	<div class="wrapper row-offcanvas row-offcanvas-left">
		<?php $this->load->view('business/components/_sidebar'); ?>
		<aside class="right-side">
			<?php $this->load->view('business/components/_heading'); ?>
			<section class="">
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-solid">
							<div class="box-header">
								<i class="fa fa-bar-chart-o"></i>
								<h3 class="box-title">Order Split Details - Allotted</h3>
							</div><!-- /.box-header -->
							<div class="box-body">
								<div class="row">
									<div class="col-md-4 text-center">
										<input type="text" class="knob" readonly value="<?php if (isset($ps_count)) { echo $ps_count; } ?>" data-skin="tron" data-thickness="0.2" data-width="120" data-height="120" data-fgColor="#00a65a"/>
										<div class="knob-label">Periodic Servicing</div>
									</div><!-- ./col -->
									<div class="col-md-4 text-center">
										<input type="text" class="knob" readonly value="<?php if (isset($r_count)) { echo $r_count; } ?>" data-skin="tron" data-thickness="0.2" data-width="120" data-height="120" data-fgColor="#f56954"/>
										<div class="knob-label">Repair/Accidental</div>
									</div><!-- ./col -->
									<div class="col-md-4 text-center">
										<input type="text" class="knob" readonly value="<?php if (isset($ir_count)) { echo $ir_count; } ?>" data-skin="tron" data-thickness="0.2" data-width="120" data-height="120" data-fgColor="#932ab6"/>
										<div class="knob-label">Insurance Renewal</div>
									</div><!-- ./col -->
								</div><!-- /.row -->
							</div><!-- /.box-body -->
						</div><!-- /.box -->
					</div><!-- /.col -->
				</div>
			</section><!-- /.content -->
			<section>
				<div id="detail_tab" class="table-margin-top">
					<table id="example1" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
						<thead>
							<tr>
								<th class="first"><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Order ID</th>
								<th style="width:12%"><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Bike Model</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Order Date</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Order Type</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Contact</th>
								<th class="last"><i class="fa fa-user"></i> &nbsp;&nbsp;Customer Name</th>
							</tr>
						</thead>
						<tbody>
							<?php if(isset($rows) && count($rows) > 0) { $count = 0; foreach($rows as $row) { ?>
							<tr id="<?php echo $count; ?>">
								<td><a href="<?php echo site_url('business/odetail/show/' . $row['oid']); ?>" class="order-id-link"><?php echo $row['oid']; ?></a></td>
								<td><?php echo $row['bmodel']; ?></td>
								<td><?php echo $row['odate']; ?></td>
								<td><?php echo $row['otype']; ?></td>
								<td><?php echo $row['phone']; ?></td>
								<td><?php echo $row['username']; ?></td>
							</tr>
							<?php $count += 1; } } ?>
						</tbody>
					</table>
				</div>
			</section>
		</aside>
	</div>
	<?php $this->load->view('business/components/_foot'); ?>
	<?php } ?>
<?php $this->load->view('business/components/_vjs'); ?>
<script>
</script>
</body>
</html>