<?php $this->load->view('executive/components/_head'); ?>


<h5 style="font-size: 20px;margin-left: 100px;margin-top: 50px;">OLC comprises of 3 Phases - Pre : D-Day : Post Order Processing</h5>

 <a class="btn-floating btn-large waves-effect waves-light red" style="margin-left:100px;margin-top:100px;"><i class="material-icons">important_devices</i></a>
 <a class="btn-floating btn-large waves-effect waves-light red" style="margin-left:100px;margin-top:100px;"><i class="material-icons">perm_data_setting</i></a>
 <a class="btn-floating btn-large waves-effect waves-light red" style="margin-left:100px;margin-top:100px;"><i class="material-icons">alarm_on</i></a>


<h5 style="font-size: 20px;margin-left: 100px;margin-top: 50px;">Phase 1 : Pre - Order Processing Stages</h5>

	<div class="row">
		<div class="col s3">
			<div class="row">
				<div class="col s2">
					<div style="background: #d7d7d7;width: 100%;height: 100%;border-radius: 50%;text-align: center;">1</div>
				</div>
				<div class="col s8">
					<div><a href="#!" onclick="Materialize.showStaggeredList('#staggered-test')"><b>In House Before Call</b></a></div>
				</div>
			</div>
		</div>  
		<div class="col s3">
			<div class="row">
				<div class="col s2">
					<div style="background: #d7d7d7;width: 100%;height: 100%;border-radius: 50%;text-align: center;">2</div>
				</div>
				<div class="col s8">
					<div><a href="#!" onclick="Materialize.showStaggeredList('#staggered-test1')"><b>Call with Customer</b></a></div>
				</div>
			</div>
		</div>
		<div class="col s3">
			<div class="row">
				<div class="col s2">
					<div style="background: #d7d7d7;width: 100%;height: 100%;border-radius: 50%;text-align: center;">3</div>
				</div>
				<div class="col s8">
					<div><a href="#!" onclick="Materialize.showStaggeredList('#staggered-test2')"><b>Call with Service Provider</b></a></div>
				</div>
			</div>
		</div>
		<div class="col s3">
			<div class="row">
				<div class="col s2">
					<div style="background: #d7d7d7;width: 100%;height: 100%;border-radius: 50%;text-align: center;">4</div>
				</div>
				<div class="col s8">
					<div><a href="#!" onclick="Materialize.showStaggeredList('#staggered-test3')"><b>Paper & Digital Processing</b></a></div>
				</div>
			</div>
		</div>     
	</div>
	<div class="row">
		<div class="col s3">
			<ul id="staggered-test" style="margin-left:20px">
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Successful Portal Booking </a></h4>
	            <p>An order successful if it has Valid bike Details, Valid Address, and successful Payment</p>
	          </li>
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Incomplete Portal Booking</a></h4>
	            <p>If any of the above details are Invalid it will be considered as Incomplete Order</p>
	          </li>
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Dummy Bookings</a></h4>
	            <p>If all the above details are Invalid it will be considered as Dummy Order</p>
	          </li>
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Market Place Lead</a></h4>
	            <p>A lead generated from different market places like Just Dail, housejoy, urbanclap etc is considered as Market Place Lead</p>
	          </li>
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">SP Lead</a></h4>
	            <p>Service centers will transfer the customers who are willing to avail g6 services to gear6.in, they are termed as SP leads</p>
	          </li>

	        </ul>	
		</div>
		
		<div class="col s3">
			<ul id="staggered-test1" style="margin-left:20px;">
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Fresh Call</a></h4>
	            <p>Customer care will make calls to the customers after analyzing the order within the span of 15-20 minutes in working hours for order confirmation</p>
	          </li>
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Follow Up Call</a></h4>
	            <p>Follow up call will be made if the customer doesn't answer or asks us to call later </p>
	          </li>
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Phone Bookings</a></h4>
	            <p>Phone Bookings are open to the customers who calls to the customer care for an enquiry or for the direct booking</p>
	          </li>

	        </ul>
	    </div>
	    <div class="col s3">
			<ul id="staggered-test2" style="margin-left:20px;">
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Fresh Call</a></h4>
	            <p>Service center will be notified as soon as the order has placed</p>
	          </li>
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Follow Up Call</a></h4>
	            <p>Follow up call will be made if the Service Manager doesn't answer</p>
	          </li>

	        </ul>
	    </div>
	    <div class="col s3">
			<ul id="staggered-test3" style="margin-left:20px;">
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Order Assignment</a></h4>
	            <p>All the orders will assigned digitally to the service executives on day prior to the appointments</p>
	          </li>
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Order Area Mapping</a></h4>
	            <p>Different Areas will be marked based on the order density</p>
	          </li>
	        </ul>
	    </div>
	</div>

<!-- Phase 2 Starts-->

<h5 style="font-size: 20px;margin-left: 100px;margin-top: 50px;">Phase 2 : D Day - Order Processing Stages</h5>
<div class="row">
		<div class="col s3">
			<div class="row">
				<div class="col s2">
					<div style="background: #d7d7d7;width: 100%;height: 100%;border-radius: 50%;text-align: center;">1</div>
				</div>
				<div class="col s8">
					<div><a href="#!" onclick="Materialize.showStaggeredList('#staggered-test21')"><b>Start of the Day</b></a></div>
				</div>
			</div>
		</div>  
		<div class="col s3">
			<div class="row">
				<div class="col s2">
					<div style="background: #d7d7d7;width: 100%;height: 100%;border-radius: 50%;text-align: center;">2</div>
				</div>
				<div class="col s8">
					<div><a href="#!" onclick="Materialize.showStaggeredList('#staggered-test22')"><b>Bike Receiving Time</b></a></div>
				</div>
			</div>
		</div>
		<div class="col s3">
			<div class="row">
				<div class="col s2">
					<div style="background: #d7d7d7;width: 100%;height: 100%;border-radius: 50%;text-align: center;">3</div>
				</div>
				<div class="col s8">
					<div><a href="#!" onclick="Materialize.showStaggeredList('#staggered-test23')"><b>At Service Center (Pre Servicing)</b></a></div>
				</div>
			</div>
		</div>
		<div class="col s3">
			<div class="row">
				<div class="col s2">
					<div style="background: #d7d7d7;width: 100%;height: 100%;border-radius: 50%;text-align: center;">4</div>
				</div>
				<div class="col s8">
					<div><a href="#!" onclick="Materialize.showStaggeredList('#staggered-test24')"><b>At Service Center (Post Servicing)</b></a></div>
				</div>
			</div>
		</div> 
		   
	</div>
	<div class="row">
		<div class="col s3">
			<ul id="staggered-test21" style="margin-left:20px">
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Start of the Day </a></h4>
	            <p>Customer will be remainded by service executive before he starts to customer's location</p>
	          </li>


	        </ul>	
		</div>
		
		<div class="col s3">
			<ul id="staggered-test22" style="margin-left:20px;">
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">G6 Job Card</a></h4>
	            <p>Job card will be filled at customer's place, which includes the complete bike details, repairs explained by customer, dents, documents. SE will have a test ride and update extra repairs if any.</p></p>
	          </li>
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Acknowledgement form</a></h4>
	            <p>SE will give a copy of stamped and duly signed acknowledgement form to the customer and take the bike </p>
	          </li>
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Dents & Scratches </a></h4>
	            <p>Snaps of all the scratches and dents will be taken by the SE </p>
	          </li>
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Accessories & Documents</a></h4>
	            <p>SE will take the snaps of all the documents and return them back to customers along with all the accessories including helmet </p>
	          </li>
	        </ul>
	    </div>
	    <div class="col s3">
			<ul id="staggered-test23" style="margin-left:20px;">
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">SC Job Card</a></h4>
	            <p>Service center's Job card will be filled during which SE will be explaining all the repairs and customer's requirements.</p>
	          </li>
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Estimates</a></h4>
	            <p> After getting the estimate from service center, SE will take the approval of customer about both estimated time(for delayed repairs) and cost </p>
	          </li>
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">In House Updation</a></h4>
	            <p> SE will update the estimates, distance between SC and customer, and comments of both Customer and SC </p>
	          </li>
	        </ul>
	    </div>
	    <div class="col s3">
			<ul id="staggered-test24" style="margin-left:20px;">
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Billing</a></h4>
	            <p>SE pays the bill and updates it to the in-house customer care</p>
	          </li>
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Test Ride</a></h4>
	            <p>SE will have test ride and check for all the problems, and he leaves SC only once after his own satisfaction</p>
	          </li>
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Bill Updation to Customer</a></h4>
	            <p>Final billing amount will be updated to customer after test ride and asks for the mode of payment before SE leaves the service center</p>
	          </li>
	        </ul>
	    </div>
	    
	</div>
	<div class="row">
		<div class="col s3">
			<div class="row">
				<div class="col s2">
					<div style="background: #d7d7d7;width: 100%;height: 100%;border-radius: 50%;text-align: center;">5</div>
				</div>
				<div class="col s8">
					<div><a href="#!" onclick="Materialize.showStaggeredList('#staggered-test25')"><b>Bike Delivery Time</b></a></div>
				</div>
			</div>
		</div> 
		<div class="col s3">
			<div class="row">
				<div class="col s2">
					<div style="background: #d7d7d7;width: 100%;height: 100%;border-radius: 50%;text-align: center;">6</div>
				</div>
				<div class="col s8">
					<div><a href="#!" onclick="Materialize.showStaggeredList('#staggered-test26')"><b>G6 Office</b></a></div>
				</div>
			</div>
		</div>  
	</div>
	<div class="row">
		<div class="col s3">
			<ul id="staggered-test25" style="margin-left:20px;">
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Bike Condition Showcase</a></h4>
	            <p>SE will showcase the bike condition, explains the bill and asks for the customer to have a test ride</p>
	          </li>
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Payment</a></h4>
	            <p>For offline payment, SE directly collects the cash and for online payments, Payment confirmation will be taken </p>
	          </li>
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Feedback</a></h4>
	            <p>Feedback will be taken at the end. And also snaps for testimonials </p>
	          </li>
	        </ul>
	    </div>
	    <div class="col s3">
			<ul id="staggered-test26" style="margin-left:20px;">
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Documents</a></h4>
	            <p>SE has to submit all the invoices, acknowledgement forms, payments if any and feedback forms</p>
	          </li>
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Order Feedback</a></h4>
	            <p>Feedback will be taken from SE of each and every order for further more improvements </p>
	          </li>
	        </ul>
	    </div>
	</div>


<!-- Phase 3 Starts-->

<h5 style="font-size: 20px;margin-left: 100px;margin-top: 50px;">Phase 3 : Post - Order Processing Stages</h5>
<div class="row">
		<div class="col s3">
			<div class="row">
				<div class="col s2">
					<div style="background: #d7d7d7;width: 100%;height: 100%;border-radius: 50%;text-align: center;">1</div>
				</div>
				<div class="col s8">
					<div><a href="#!" onclick="Materialize.showStaggeredList('#staggered-test31')"><b>Details Updation</b></a></div>
				</div>
			</div>
		</div>  
		<div class="col s3">
			<div class="row">
				<div class="col s2">
					<div style="background: #d7d7d7;width: 100%;height: 100%;border-radius: 50%;text-align: center;">2</div>
				</div>
				<div class="col s8">
					<div><a href="#!" onclick="Materialize.showStaggeredList('#staggered-test32')"><b>Documentation</b></a></div>
				</div>
			</div>
		</div>
		<div class="col s3">
			<div class="row">
				<div class="col s2">
					<div style="background: #d7d7d7;width: 100%;height: 100%;border-radius: 50%;text-align: center;">3</div>
				</div>
				<div class="col s8">
					<div><a href="#!" onclick="Materialize.showStaggeredList('#staggered-test33')"><b>Review Call</b></a></div>
				</div>
			</div>
		</div>
		<div class="col s3">
			<div class="row">
				<div class="col s2">
					<div style="background: #d7d7d7;width: 100%;height: 100%;border-radius: 50%;text-align: center;">4</div>
				</div>
				<div class="col s8">
					<div><a href="#!" onclick="Materialize.showStaggeredList('#staggered-test34')"><b>Schedulers</b></a></div>
				</div>
			</div>
		</div>     
	</div>
	<div class="row">
		<div class="col s3">
			<ul id="staggered-test31" style="margin-left:20px">
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Insurance</a></h4>
	            <p>Current insurance details like insurance company, expiry date will be taken</p>
	          </li>
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">PUC</a></h4>
	            <p>PUC expiry date will be taken</p>
	          </li>


	        </ul>	
		</div>
		
		<div class="col s3">
			<ul id="staggered-test32" style="margin-left:20px;">
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Acknowledgement form, Feedback form and Invoices</a></h4>
	            <p>All the documents will be digitialized and stored slong with hard copies for reference</p>
	          </li>
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">User Bike Documents</a></h4>
	            <p>Snaps(scanned copies) of User bike documents will be collected</p>
	          </li>

	        </ul>
	    </div>
	    <div class="col s3">
			<ul id="staggered-test33" style="margin-left:20px;">
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Post servicing Feedback</a></h4>
	            <p>Two days after servicing/repair, feedback will be taken from the customers about bike condition.</p>
	          </li>

	        </ul>
	    </div>
	    <div class="col s3">
			<ul id="staggered-test34" style="margin-left:20px;">
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Schedulers</a></h4>
	            <p>Schedulers for next servicing, Insurance Renewal, PUC will be triggered</p>
	          </li>
	          <li class="" style="opacity: 0;">
	            <h4><a href="#">Order Area Mapping</a></h4>
	            <p>Different Areas will be marked based on the order density</p>
	          </li>
	        </ul>
	    </div>
	</div>



























<?php $this->load->view('executive/components/_foot'); ?>
<script>


  $('.fixed-action-btn').closeFAB();
        



</script>