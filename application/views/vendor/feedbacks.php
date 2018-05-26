<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Vendor Profile - Customer Feedbacks</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<?php $this->load->view('vendor/components/_vcss'); ?>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('js/raty/jquery.raty.css'); ?>">
</head>
<body>
	<?php $this->load->view('vendor/components/_head'); ?>
	<?php if(isset($v_is_logged_in) && $v_is_logged_in == 1) { ?>
		<div class="wrapper row-offcanvas row-offcanvas-left">
			<?php $this->load->view('vendor/components/_sidebar'); ?>
			<aside class="right-side">
				<section class="content-header">
					<h1>
						Feedback
						<small>Overview</small>
					</h1>
				</section>
				<section>
					<div id="detail_tab" class="table-margin-top">
						<table id="example1" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
							<thead>
								<tr>
									<th class="first"><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Order ID</th>
									<th style="width:12%"><i class="fa fa-user"></i> &nbsp;&nbsp;Customer</th>
									<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Contact</th>
									<th><i class="fa fa-edit"></i> &nbsp;&nbsp;Feedback</th>
									<th><i class="fa fa-star"></i> &nbsp;&nbsp;Rating</th>
								</tr>
							</thead>
							<tbody>
								<?php if(isset($rows) && count($rows) > 0) { $count = 0; foreach($rows as $row) { ?>
								<tr id="<?php echo $count; ?>">
									<td><a href="<?php echo site_url('vendor/feedback/feedview/' . $row['OId']); ?>" class="order-id-link"><?php echo $row['OId']; ?></a></td>
									<td><?php echo $row['UserName']; ?></td>
									<td><?php echo $row['Phone']; ?></td>
									<td><?php if(isset($row['Feedback'])) { echo $row['Feedback']; } else { echo "No remarks"; } ?></td>
									<td class="rate_it_i_say"><?php echo $row['Rating']; ?></td>
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
<script type="text/javascript" src="<?php echo site_url('js/raty/jquery.raty.js'); ?>"></script>
<script type="text/javascript">
</script>
</body>
</html>