$(document).ready(function() {
	insertAddressValues('addr_1');
	$("#cslide-slides").cslide();
	$('#addr-block-open').on('click', function() {
		$('#addr-select-container').slideToggle("slow", function() { $('html, body').animate({ scrollTop: $("#addr-select-container").offset().top }, 1000); });
	});
	$(".imgInp").on('change', function() {
		$id = $(this).attr('id');
		readURL(this, $id);
	});
	try {
		var select2 = $('#city').select2({
			placeholder: "Select City",
			minimumResultsForSearch: 10,
			containerCssClass: "cityCombo12"
		});
		select2.val(null).trigger("change");
	} catch(err) {
		//Do Nothing
	}
});
(function($) {
	$.fn.cslide = function() {
		this.each(function() {
			var slidesContainerId = "#"+($(this).attr("id"));
			var len = $(slidesContainerId+" .cslide-slide").size();
			var slidesContainerWidth = len*100+"%";
			var slideWidth = (100/len)+"%";
			$(slidesContainerId+" .cslide-slides-container").css({
				width : slidesContainerWidth,
				visibility : "visible"
			});
			$(".cslide-slide").css({
				width : slideWidth
			});
			$(slidesContainerId+" .cslide-slides-container .cslide-slide").last().addClass("cslide-last");
			$(slidesContainerId+" .cslide-slides-container .cslide-slide").first().addClass("cslide-first cslide-active");
			$(slidesContainerId+" .cslide-prev-ua").addClass("cslide-disabled");
			if (!$(slidesContainerId+" .cslide-slide.cslide-active.cslide-first").hasClass("cslide-last")) {           
				$(slidesContainerId+" .cslide-prev-ua-next").css({
					display : "block"
				});
			}
			$(slidesContainerId+" .cslide-next-ua").on('click', function() {
				var i = $(slidesContainerId+" .cslide-slide.cslide-active").index();
				var n = i+1;
				var slideLeft = "-"+n*100+"%";
				if (!$(slidesContainerId+" .cslide-slide.cslide-active").hasClass("cslide-last")) {
					$(slidesContainerId+" .cslide-slide.cslide-active").removeClass("cslide-active").next(".cslide-slide").addClass("cslide-active");
					$(slidesContainerId+" .cslide-slides-container").animate({
						marginLeft : slideLeft
					},250);
					if ($(slidesContainerId+" .cslide-slide.cslide-active").hasClass("cslide-last")) {
						$(slidesContainerId+" .cslide-next-ua").addClass("cslide-disabled");
					}
				}
				if ((!$(slidesContainerId+" .cslide-slide.cslide-active").hasClass("cslide-first")) && $(".cslide-prev-ua").hasClass("cslide-disabled")) {
					$(slidesContainerId+" .cslide-prev-ua").removeClass("cslide-disabled");
				}
			});
			$(slidesContainerId+" .cslide-prev-ua").on('click', function() {
				var i = $(slidesContainerId+" .cslide-slide.cslide-active").index();
				var n = i-1;
				var slideRight = "-"+n*100+"%";
				if (!$(slidesContainerId+" .cslide-slide.cslide-active").hasClass("cslide-first")) {
					$(slidesContainerId+" .cslide-slide.cslide-active").removeClass("cslide-active").prev(".cslide-slide").addClass("cslide-active");
					$(slidesContainerId+" .cslide-slides-container").animate({
						marginLeft : slideRight
					},250);
					if ($(slidesContainerId+" .cslide-slide.cslide-active").hasClass("cslide-first")) {
						$(slidesContainerId+" .cslide-prev-ua").addClass("cslide-disabled");
					}
				}
				if ((!$(slidesContainerId+" .cslide-slide.cslide-active").hasClass("cslide-last")) && $(".cslide-next-ua").hasClass("cslide-disabled")) {
					$(slidesContainerId+" .cslide-next-ua").removeClass("cslide-disabled");
				}
			});
		});
		return this;
	}
}(jQuery));
function readURL(input,$id) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('#'+$id+'Thumb').attr('src', e.target.result);
		}
		reader.readAsDataURL(input.files[0]);
	}
}
function insertAddressValues(id) {
	$('#addr1').val($('#addr_content_'+id.split("_")[1]+' :first-child').text());
	$('#addr2').val($('#addr_content_'+id.split("_")[1]+' :nth-child(2)').text());
	$('#addr_location').val($('#addr_content_'+id.split("_")[1]+' :nth-child(3)').text());
	$('#addr_landmark').val($('#addr_content_'+id.split("_")[1]+' :nth-child(4)').text());
	$('#city').val($('#addr_content_'+id.split("_")[1]+' :nth-child(5)').text());
}