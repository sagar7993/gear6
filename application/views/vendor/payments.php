<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Vendor Profile - Payment Details</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<?php $this->load->view('vendor/components/_vcss'); ?>
</head>
<body>
	<?php $this->load->view('vendor/components/_head'); ?>
	<?php if(isset($v_is_logged_in) && $v_is_logged_in == 1) { ?>
		<div class="wrapper row-offcanvas row-offcanvas-left">
			<?php $this->load->view('vendor/components/_sidebar'); ?>
			<aside class="right-side">
				<section class="content-header">
					<h1>
						Payments
						<small>Payment Transaction Details</small>
					</h1>
				</section>
				<section>
					<div id="detail_tab" class="table-margin-top">
						<table id="example1" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
							<thead>
								<tr>
									<th class="first"><i class="fa fa-user"></i>&nbsp;&nbsp;User Name</th>
									<th style="width:12%"><i class="fa fa-bookmark"></i>&nbsp;&nbsp;OrderId</th>
									<th><i class="fa fa-bookmark"></i>&nbsp;&nbsp;Transaction Id</th>
									<th><i class="fa fa-edit"></i>&nbsp;&nbsp;Amount Paid INR</th>
									<th><i class="fa fa-edit"></i>&nbsp;&nbsp;Amount With</th>
									<th><i class="fa fa-bookmark"></i>&nbsp;&nbsp;Transaction TimeStamp</th>
								</tr>
							</thead>
							<tbody>
								<?php if(isset($rows) && count($rows) > 0) { $count = 0; foreach($rows as $row) { ?>
								<tr id="<?php echo $count; ?>">
									<td><?php echo $row['UserName']; ?></td>
									<td><a href="<?php echo site_url('vendor/odetail/show/' . $row['OId']); ?>" class="order-id-link"><?php echo $row['OId']; ?></a></td>
									<td><?php echo $row['TId']; ?></td>
									<td><?php echo $row['PaymtAmt']; ?></td>
									<td><?php echo $row['isWithVendor']; ?></td>
									<td><?php echo $row['TimeStamp']; ?></td>
								</tr>
								<?php $count += 1; } } ?>
							</tbody>
						</table>
					</div>
				</section>
			</aside>
		</div>
	<?php $this->load->view('vendor/components/_foot'); ?>
	<?php } ?>
<?php $this->load->view('vendor/components/_vjs'); ?>
<script type="text/javascript">
</script>
</body>
</html>