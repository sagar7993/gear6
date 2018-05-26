<!DOCTYPE html>
<html class="no-scroll"> 
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Booking Page</title>
	<?php $this->load->view('user/components/_ucss'); ?>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/datatables/dataTables.bootstrap.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('js/raty/jquery.raty.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('nhome/js/lib/swal/sweetalert.css'); ?>">
	<script src="<?php echo site_url('nhome/js/lib/swal/sweetalert.min.js'); ?>"></script>
	<style type="text/css">
		#ratingModal .modal-footer, #ratingModal .modal-header, #ratingModal .modal-content {
			background: white!important;
		}
	</style>
</head>
<body>
	<?php $this->load->view('user/components/__head'); ?>
	<main>
		<div class="row remove-margin-bottom loader-gif-container" id="loader-gif">
			<div class="col s12 m12 l12 loader-gif-cover">
				<img class="responsive-img center loader-gif-img" src="<?php echo site_url('nhome/images/Logo-loop.gif'); ?>" id="loading">
			</div>
		</div>
		<!-- Searched Options content -->
		<section class="searched-options">
			<div class="center width90">
				<div class="row hide-on-small-only">
					<div class="col s10 l10 m12 margin-top-2pc">
						<?php if(isset($serid) && $serid != 9 && $serid != 10 && $serid != 11) { ?>
						<div class="col s12 m6 l3 searched-results-block">
							<div class="col s4">
								<img src="<?php echo site_url('img/icons/bike.png'); ?>">
							</div>
							<div class="col s8">
								<div class="">
									<?php echo convert_to_camel_case($company . ' ' . $bikemodel); ?>
								</div><!--Bike name and Model comes here -->
							</div>
						</div>
						<div class="col s12 m6 l3 searched-results-block">
							<div class="col s4">
								<img src="<?php echo site_url('img/icons/dateTime.png'); ?>">
							</div>
							<div class="col s8">
								<div class=""><?php echo convert_to_camel_case($servicedate); ?></div>
							</div>
						</div>
						<div class="col s12 m6 l3 searched-results-block">
							<div class="col s4">
								<img src="<?php echo site_url('img/icons/periodicService.png'); ?>">
							</div>
							<div class="col s8">
								<div class=""><?php echo $servicetype; ?></div>
							</div>
						</div>
						<div class="col s12 m6 l3 searched-results-block">
							<div class="col s4">
								<img src="<?php echo site_url('img/icons/location.png'); ?>">
							</div>
							<div class="col s8">
								<div class=""><?php echo convert_to_camel_case($area); ?></div>
							</div>
						</div>
						<?php } else { ?>
						<div class="col s12 m6 l6 searched-results-block">
							<div class="col s4">
								<img src="<?php echo site_url('img/icons/periodicService.png'); ?>">
							</div>
							<div class="col s8">
								<div class=""><?php echo $servicetype; ?></div>
							</div>
						</div>
						<div class="col s12 m6 l6 searched-results-block">
							<div class="col s4">
								<img src="<?php echo site_url('img/icons/location.png'); ?>">
							</div>
							<div class="col s8">
								<div class=""><?php echo convert_to_camel_case($area); ?></div>
							</div>
						</div>
						<?php } ?>
					</div>
					<div class="col s2 modSearch hide-on-med-and-down" id="open"><!-- Media -->
						<span href="#" class="float-left">Modify Search</span>
					</div>
				</div>
				<div class="select-box hide-on-med-and-down" id="search-box" style="width:100%!important;display:none;">
					<br/>
					<div class="boxShadow-beta container">
					<!-- form start -->
						<form role="form" action ="/user/book" method="POST">
							<div class="row">
								<div class="col s12 m6 l4" >
									<input type="text" class="form-control area" data-error="Location" name="area" id="area" placeholder="Type your area eg. Domlur">
								</div>
								<div class="col s12 m6 l4" >
									<select class="form-control styled-select" data-error="Service" name="servicetype" id="stype">
										<option></option>
										<?php
											foreach ($services as $service) {
												echo '<option value="'.$service->ServiceId.'">'.$service->ServiceName.'</option>';
											}
										?>
									</select>
								</div>
								<div class="col s12 m6 l4 lowerSection" >
									<input type="text" id="datepicker" data-error="Date" class="dpDate form-control" name="date_" placeholder="Appointment Date">
									<input type="hidden" id="datepicker_query" class="form-control dpDate" name="date_query">
								</div>
							</div>
							<div class="row margin-bottom-0">
								<div class="col s12 m6 l4 lowerSection" >
									<select class="form-control styled-select" id="company" data-error="Company" name="company" onchange="bikelist(this.value);">
										<option></option>
										<?php
											foreach ($bikecompanies as $bikecompany) {
												echo '<option value="'.$bikecompany->BikeCompanyId.'">'.convert_to_camel_case($bikecompany->BikeCompanyName).'</option>';
											}
										?>
									</select>
								</div>
								<div class="col s12 m6 l4 lowerSection">
									<select class="form-control styled-select" data-error="Model" id="bikediv" name="model">
										<option></option>
									</select>
								</div>
								<div class="col s12 m6 l4">
									<input type="hidden" id="ulatitude" name="qlati">
									<input type="hidden" id="ulongitude" name="qlongi">
									<button type="submit" name="book" id="submit" class="col s8 btn waves-effect waves-light">
									Modify Search
									</button>
								</form>
								<button id="close" class="col s4 btn waves-effect waves-light">
									Cancel
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section class="filterSection hide-on-med-and-down">
			<div class="center filterBox">
				<div class="filterBy">
					<span>Filter By :</span>
				</div>
				<?php if($serid == 9) { ?>
				<div class="filterBtnC" data-filter="sProvider" id="spFilter">
					<div class="filterBtn" >
						<span>ServiceProviders</span>
						<div class="farrw">
							<i></i>
							<span></span>
						</div> 
						<div class="center-small fr-ch-up1" id="sProvider-ch" style="display:none">
							<span class="chevron-frame"></span>
							<i id="sp" class="up-chevron"></i>
						</div>
					</div>
					<ul class="filterList filterListULAm" data-filterOpt="vendor" id="sProvider" style="display:none">
						<?php if(isset($spcompanies) && count($spcompanies) > 0) { foreach($spcompanies as $spcompany) { ?>
						<li class="filterList filterListLIAm">
							<label>
								<input type="checkbox" class="ckb" id="<?php echo $data_filter_counter; ?>" data-val="<?php echo convert_to_camel_case($spcompany); ?>" data-item="splist" data-id="<?php echo $data_filter_counter; ?>"><span class="margin-left-5px"><?php echo $spcompany; ?></span>
							</label>
						</li>
						<?php $data_filter_counter += 1; } } ?>
					</ul>
				</div>
				<?php } else { ?>
				<div class="filterBtnC vendorFilter" data-filter="vendor" id="vendorFilter" style="margin-left:75px;">
					<div class="filterVBtn" >
						<span>Service Provider</span>
						<div class="farrw">
							<i></i>
							<span></span>
						</div>
						<div class="center-small fr-ch-up" id="vendor-ch" style="display:none">
							<span class="chevron-frame"></span>
							<i id="sp" class="up-chevron"></i>
						</div>
					</div>
					<ul class="filterList filterListULAm form-group vendorSearch" id="vendor" style="display:none">
						<div class="">
							<input type="text" class="form-control area vsearch" name="area" id="search_servicer" placeholder="Search Servicer .."><!-- Media -->
						</div>
					</ul>
				</div>
				<?php } ?>
				<?php if($serid != 10 && $serid != 11) { ?>
				<div class="filterBtnC" data-filter="amenityList" id="amFilter">
					<div class="filterBtn" >
						<span>Amenities</span>
						<div class="farrw">
							<i></i>
							<span></span>
						</div> 
						<div class="center-small fr-ch-up1" id="amenityList-ch" style="display:none">
							<span class="chevron-frame"></span>
							<i id="sp" class="up-chevron"></i>
						</div>
					</div>
					<ul class="filterList filterListULAm" data-filterOpt="vendor" id="amenityList" style="display:none">
						<?php if(isset($amenities) && count($amenities) > 0) { foreach($amenities as $amenity) { ?>
						<li class="filterList filterListLIAm">
							<label>
								<input type="checkbox" class="ckb" id="<?php echo $data_filter_counter; ?>" data-val="<?php echo $amenity->AmName; ?>" data-item="amenity" data-id="<?php echo $data_filter_counter; ?>"><span class="margin-left-5px"><?php echo $amenity->AmName; ?></span>
							</label>
						</li>
						<?php $data_filter_counter += 1; } } ?>
					</ul>
				</div>
				<?php } elseif($serid == 10) { ?>
				<div class="filterBtnC" data-filter="ftypeList" id="ftypeFilter">
					<div class="filterBtn" >
						<span>PUC / EC Type</span>
						<div class="farrw">
							<i></i>
							<span></span>
						</div>
						<div class="center-small fr-ch-up1" id="ftype-ch" style="display:none">
							<span class="chevron-frame"></span>
							<i id="sp" class="up-chevron"></i>
						</div>
					</div>
					<ul class="filterList filterListULAm" data-filterOpt="vendor" id="ftypeList" style="display:none">
						<li class="filterList filterListLIAm">
							<label>
								<input type="checkbox" class="ckb" id="<?php echo $data_filter_counter; ?>" data-val="Office" data-item="ftype" data-id="<?php echo $data_filter_counter++; ?>"><span class="margin-left-5px">Office PUC</span>
							</label>
						</li>
						<li class="filterList filterListLIAm">
							<label>
								<input type="checkbox" class="ckb" id="<?php echo $data_filter_counter; ?>" data-val="Mobile" data-item="ftype" data-id="<?php echo $data_filter_counter++; ?>"><span class="margin-left-5px">Mobile PUC</span>
							</label>
						</li>
						<li class="filterList filterListLIAm">
							<label>
								<input type="checkbox" class="ckb" id="<?php echo $data_filter_counter; ?>" data-val="Petrol Bunk" data-item="ftype" data-id="<?php echo $data_filter_counter++; ?>"><span class="margin-left-5px">PetrolBunk PUC</span>
							</label>
						</li>
					</ul>
				</div>
				<?php } ?>
				<?php if ($serid == 1 || $serid == 4) { ?>
				<div class="filterBtnC" data-filter="priceList" id="priceFilter">
					<div class="filterBtn" >
						<span>Price</span>
						<div class="farrw">
							<i></i>
							<span></span>
						</div>
						<div class="center-small fr-ch-up1" id="priceList-ch" style="display:none">
							<span class="chevron-frame"></span>
							<i id="sp" class="up-chevron"></i>
						</div>
					</div>
					<ul class="filterList filterListULAm" data-filterOpt="vendor" id="priceList" style="display:none">
						<li class="filterList filterListLIAm">
							<label class="price">
								<input type="checkbox" class="ckb fr_price" id="<?php echo $data_filter_counter; ?>" data-val="0 - 500" data-min="0" data-max="500" value="1" data-item="pr" data-id="<?php echo $data_filter_counter++; ?>"><span class="margin-left-5px">0 - 500</span>
							</label>
						</li>
						<li class="filterList filterListLIAm">
							<label class="price">
								<input type="checkbox" class="ckb fr_price" id="<?php echo $data_filter_counter; ?>" data-val="500 - 1000" data-min="501" data-max="1000" value="2" data-item="pr" data-id="<?php echo $data_filter_counter++; ?>"><span class="margin-left-5px">500 - 1000</span>
							</label>
						</li>
						<li class="filterList filterListLIAm">
							<label class="price">
								<input type="checkbox" class="ckb fr_price" id="<?php echo $data_filter_counter; ?>" data-val="1000 - 1500" data-min="1001" data-max="1500" value="3" data-item="pr" data-id="<?php echo $data_filter_counter++; ?>"><span class="margin-left-5px">1000 - 1500</span>
							</label>
						</li>
						<li class="filterList filterListLIAm">
							<label class="price">
								<input type="checkbox" class="ckb fr_price" id="<?php echo $data_filter_counter; ?>" data-val="> 1500" data-min="1501" data-max="10000" value="10" data-item="pr" data-id="<?php echo $data_filter_counter++; ?>"><span class="margin-left-5px">> &nbsp;1500 </span>
							</label>
						</li>
					</ul>
				</div>
				<?php } ?>
				<div class="switch">
					<label class="green-text">
						View All
						<input type="checkbox" id="switch" checked="true">
						<span class="lever"></span>
						Near By
					</label>
				</div>
				<div class="row facetContainer">
					<div class="resetFilters" id="resetFilters" style="display:none">Reset All</div>
				</div>
			</div>
				<?php if($serid == 1) { ?>
				<div class="free-terms-bp">
					For availing your bike's free servicing, you can opt in the next page(review). Ignore the below prices if N/A.
				</div>
				<?php } elseif($serid == 2) { ?>
				<div class="free-terms-bp">
					Your bike check up will start at the time you opt here. You can track further details ,pay online once the service provider takes the bike and update you.
				</div>
				<?php } elseif($serid == 4) { ?>
				<div class="free-terms-bp">
					Below prices are minimum amount to be paid for the insurance renewal confirmation. Extra charges will be conveyed to you by the service provider after the order confirmation.
				</div>
				<?php } elseif($serid == 3) { ?>
				<div class="free-terms-bp">
					Ask your query to a maximum of 3 service providers and get multiple opinions to clear your doubts.
				</div>
				<?php } ?>
		</section>
		<div class="row hide-on-large-only"></div>
		<?php if ($serid == 3) { ?>
		<div class="query-exclusive">
			<div class="center">
				<div class="row margin-bottom-0">
					<div class="col s12 m4">
						<select class="" onchange="queryCheck();" name="querytype" id="querytype">
							<option ></option>
							<option value="Repair Issues">Repair Issues</option>
							<option value="Insurance Related">Insurance Related</option>
							<option value="Pick Up or Drop">Pick Up or Drop</option>
						</select>
					</div>
					<?php if(isset($is_logged_in) && $is_logged_in == 0) { ?>
					<div class="col s12 m5">
						<input type="text" class="white-bg" maxlength="10" name="phone" id="phNum" placeholder="Mobile Number">
					</div>
					<?php } else { ?>
					<div class="col s12 m5">
						<input type="text" class="white-bg" value="<?php echo $this->session->userdata('phone'); ?>" disabled>
						<div><a href="/user/account/uprofile/3" style="color: #ffffff;">Change Account Phone Number</a></div>
					</div>
					<?php } ?>
					<div class="col s12 m3 margin-top-5px">
						<button class="btn waves-effect waves-light" id="query_proceed" disabled>Proceed</button>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
		<div class="divide-wrap">
		</div>
		<section class="center">
			<div class="box-body table-responsive" style="margin-top:-10px;">
				<div id="detail_tab">
				</div> <!-- Detail Tab -- >
			</div><!-- /.box-body -->
		</section>
		<div id="ratingModal" class="modal">
			<div class="modal-content" style="overflow-y:scroll!important;overflow-x:hidden;!important;height:500px;">
				<div class="row">
					<div class="center"><h5 id="rating_sc" style="color:#028cbc;margin-top: -5px;"></h5></div>
					<div class="col s12 m3 l3">
						<span id="rating_0" style="display: block;box-sizing: inherit;background-image: url('/img/rating_star.svg');background-position: center;background-repeat: no-repeat;width: 150px;height: 150px;text-align: center !important;padding-top: 57px;background-size: 100% 100%;color: white;font-size: 23px;"></span>
						<span id="rating_n" style="margin-left:10px;"></span>
					</div>
					<div class="col s12 m9 l9" style="font-family:roboto;">
						<div class="row" style="margin-bottom:0px!important;">
							<div class="col s12 m3 l2">5 Stars</div>
							<div class="col s12 m6 l8">
								<div class="progress" style="display:inline-block!important;height:12px!important;background-color:#ababab!important;">
									<div id="rating_5_width" class="determinate" style="background-color:#028cbc!important;"></div>
								</div>
							</div>
							<div class="col s12 m3 l2" id="rating_5"></div>
						</div>
						<div class="row" style="margin-bottom:0px!important;">
							<div class="col s12 m3 l2">4 Stars</div>
							<div class="col s12 m6 l8">
								<div class="progress" style="display:inline-block!important;height:12px!important;background-color:#ababab!important;">
									<div id="rating_4_width" class="determinate" style="background-color:#028cbc!important;"></div>
								</div>
							</div>
							<div class="col s12 m3 l2" id="rating_4"></div>
						</div>
						<div class="row" style="margin-bottom:0px!important;">
							<div class="col s12 m3 l2">3 Stars</div>
							<div class="col s12 m6 l8">
								<div class="progress" style="display:inline-block!important;height:12px!important;background-color:#ababab!important;">
									<div id="rating_3_width" class="determinate" style="background-color:#028cbc!important;"></div>
								</div>
							</div>
							<div class="col s12 m3 l2" id="rating_3"></div>
						</div>
						<div class="row" style="margin-bottom:0px!important;">
							<div class="col s12 m3 l2">2 Stars</div>
							<div class="col s12 m6 l8">
								<div class="progress" style="display:inline-block!important;height:12px!important;background-color:#ababab!important;">
									<div id="rating_2_width" class="determinate" style="background-color:#028cbc!important;"></div>
								</div>
							</div>
							<div class="col s12 m3 l2" id="rating_2"></div>
						</div>
						<div class="row" style="margin-bottom:0px!important;">
							<div class="col s12 m3 l2">1 Stars</div>
							<div class="col s12 m6 l8">
								<div class="progress" style="display:inline-block!important;height:12px!important;background-color:#ababab!important;">
									<div id="rating_1_width" class="determinate" style="background-color:#028cbc!important;"></div>
								</div>
							</div>
							<div class="col s12 m3 l2" id="rating_1"></div>
						</div>
						<div class="row" style="margin-bottom:0px!important;">
						</div>
					</div>
				</div>
				<div class="center"><h5 id="review_sc" style="display:none;color:#028cbc;margin-top: -20px;margin-bottom: 10px;">Reviews</h5></div>
				<div class="row" id="reviewContainer"></div>
			</div>
		</div>
	</main>
	<?php $this->load->view('user/components/_foot'); ?>
	<?php $this->load->view('user/components/_ujs'); ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery.dataTables.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/dataTables.bootstrap.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/raty/jquery.raty.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.ui.datepicker.validation.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/materialize.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/date.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/home.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/book.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=AIzaSyCZ126reFV784ZQTqw_JfD08mnS0jI7nWo&libraries=places"></script>
<script>
	<?php if(isset($city_row)) { ?>
		var swlati = <?php echo $city_row->SwLati; ?>;
		var swlongi = <?php echo $city_row->SwLongi; ?>;
		var nelati = <?php echo $city_row->NeLati; ?>;
		var nelongi = <?php echo $city_row->NeLongi; ?>;
	<?php } ?>
	<?php if(isset($is_logged_in) && $is_logged_in == 1) { echo 'var is_logged_in = true; var univ_user_phone = "' . $this->session->userdata('phone') . '";'; } else { echo 'var is_logged_in = false;'; } ?>
	$(function() {
		<?php if(!$this->input->cookie('CityId')) { echo "openCityModal();"; } ?>
		<?php if(isset($is_first_login) && $is_first_login == 1) { echo "openFirstTimeLoginModal();"; } ?>
		<?php
			if(isset($open_blklogin_modal) && $open_blklogin_modal == 1) {
				echo "openBlkLoginModal();";
			} elseif(isset($open_login_modal) && $open_login_modal == 1) {
				echo "openLoginModal();";
			}
		?>
		$(".button-collapse").sideNav();
		var datepicker = $('#datepicker').pickadate({
			min: <?php if (isset($adv_time)) { echo '+' . $adv_time; } else { echo '0'; } ?>,
			max: 45,
			format: 'dddd, dd mmm, yyyy',
			formatSubmit: 'dddd, dd mmm, yyyy',
			closeOnSelect: true,
			container: 'body',
			onOpen: function() {
				$('#datepicker').val('');
			},
			onSet: function() {
				if($('#datepicker').val() != "" ) {
					$(this).close();
				}
			}
		});
		var picker = datepicker.pickadate('picker');
		picker.on('clear', function() {
			if($.cookie('servicetype') == 3|| $.cookie('servicetype') == 7) {
				disableDate();
			}
		});
		picker.on('close', function() {
			if($.cookie('servicetype') == 3|| $.cookie('servicetype') == 7) {
				disableDate();
			}
		});
		$('#querytype').select2({
			placeholder: "Query Type",
			minimumResultsForSearch: 10,
			containerCssClass: "cityCombo"
		});
		$('.modal-trigger').leanModal({
			ready: function() { 
				$('html').css('overflow','hidden');
			},
			complete: function() { 
				$('html').css('overflow','auto');
			}
		});
	});
	function insert_loader() {
		var loader_path = "<?php echo site_url('nhome/images/Logo-loop.gif'); ?>";
		return '';
	}
</script>
</body>
</html>