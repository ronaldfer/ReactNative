/*$(function () {
  $('select').selectpicker();
});*/
$(document).ready(function() {
    // $('select').selectpicker();
    $('.js-example-basic-multiple').select2();
});

/*load toogle library*/
var formSubmit = 1;
/*let elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

elems.forEach(function(html) {
    let switchery = new Switchery(html,  { size: 'small' });
});*/

/*send csrf token*/
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

/*show loader*/
function show_loading(){
	$("#loading").show();
}

/*hide loader*/
function hide_loading(){
	$("#loading").hide();
}

$('document').ready(function(){
	hide_loading();
});

/*view project manager profile*/
$('.pm_data').on('click',function(e){
	e.preventDefault();
    show_loading();
	var userid = $(this).data('id');

	$.ajax({
	    url: '/AAS-web-portal/admin/view-project-manager/'+userid,
	    type: 'GET',
	    data: {userid: userid},
	    success: function(response){
	    	show_loading();
	        // Add response in Modal body
	        $('#pm_name').text(response.pm_data.first_name+' '+response.pm_data.last_name);
	     	var company_name = (response.company_data === null ? '--' : response.company_data.company_name);
	     	var pm_email 	 = ((response.pm_data.email == "") ? '--' : response.pm_data.email);
	     	/*var pm_address 	 = (response.pm_data.address === null ? '--' : response.pm_data.pm_address);*/
	     	var pm_contact 	 = (response.pm_data.contact === null ? '--' : response.pm_data.contact);

	        $('#pm_company_name').text(company_name);
	     	$('#pm_email').text(pm_email);
	      	/*$('#pm_address').text(pm_address);*/
	      	$('#pm_contact').text(pm_contact);

	      	hide_loading();
	      	// Display Modal
      		$('#myModal').modal({
      			backdrop: 'static',
        		keyboard: false
        	});

	    }
    });
});

/*view company profile*/
$('.company_data').on('click',function(e){
	e.preventDefault();
    show_loading();
	var userid = $(this).data('id');

	$.ajax({
	    url: '/AAS-web-portal/admin/view-company/'+userid,
	    type: 'GET',
	    data: {userid: userid},
	    success: function(response){
	    	/*check image url exists or not*/
	    	var check_image = response.company_data.image == null ? 'https://vkapsprojects.com/AAS-web-portal/public/images/company-not-found.png': base_path+"/public/assets/company_logo/"+response.company_data.image;
	    	/*check image exixts or not*/
	    	$.ajax({
			    url:check_image,
			    type:'HEAD',
			    error: function(){
			        //file not exists
			        $('#company_image').attr("src", 'https://vkapsprojects.com/AAS-web-portal/public/images/company-not-found.png');
			    },
			    success: function(){
			    	$('#company_image').attr("src", check_image);
			    }
			});
	        // Add response in Modal body
	        var company_name 	= (response.company_data.company_name === null ? '--' : response.company_data.company_name);
	        var owner_name 	 	= (response.company_data.owner_name === null ? '--' : response.company_data.owner_name);
	        var company_email 	= (response.company_data.email === null ? '--' : response.company_data.email);
	        var company_address = (response.company_data.address === null ? '--' : response.company_data.address);
	        var company_contact = (response.company_data.contact === null ? '--' : response.company_data.contact);

	       	$('#company_name').text(company_name);
	     	$('#owner_name').text(owner_name);
	     	$('#company_email').text(company_email);
	      	$('#company_address').text(company_address);
	      	$('#company_contact').text(company_contact);

	      	hide_loading();
	      	// Display Modal
      		$('#myModal').modal({
      			backdrop: 'static',
        		keyboard: false
        	});
	    }
    });
});

/*view projects details*/
$('.project_data').on('click',function(e){
	e.preventDefault();
    // show_loading();
	var userid = $(this).data('id');

	$.ajax({
	    url: '/AAS-web-portal/admin/view-projects/'+userid,
	    type: 'GET',
	    data: {userid: userid},
	    success: function(response){

	    	var pm_name = [];
	    	if(response.pm_data.length != 0){

	    		for (var i = 0; i < response.pm_data.length; i++) {
				  var pm_full_name = response.pm_data[i].first_name+" "+response.pm_data[i].last_name;
				  pm_name.push(pm_full_name);
				}
			}else{
				$('#pm_name').text("--");
			}
			// console.l/og("pm name",pm_name);
			console.log(pm_name);
	        var company_name = (response.company_data === null ? '--' : response.company_data.company_name);


	        $('#project_name').text(response.projects_data.job_name);
	        $('#company_name').text(company_name);
		  	$('#pm_name').text(pm_name);
	     	$('#project_state').text(response.projects_data.state);
	     	$('#project_city').text(response.projects_data.city);
	      	hide_loading();
	      	// Display Modal
	      	$('#myModal').modal({
      			backdrop: 'static',
        		keyboard: false
        	});
	    }
    });
});

/*delete user data*/
$('.delete-confirm').on('click', function (e) {
	e.preventDefault();
	const url = $(this).attr('href');
	var msg = $(this).attr('title');
	if(msg == "Project"){
		msg = "The project will be permanently deleted!";
	}else{
		msg = "Project Manager account will be permanently deleted!";
	}
	swal({
		html : true,
  		title:'Are you sure?',
  		text: msg,
  		icon: 'warning',
  		buttons: ["No", "Yes"],
	}).then(function(value) {
	  	if (value) {
	    	window.location.href = url;
	  	}
	});
});

/*delete-company data*/
$('.delete-company').on('click', function (e) {
	e.preventDefault();
	const url = $(this).attr('href');
	const msg = $(this).attr('title');
	swal({
		html : true,
  		title:'Are you sure?',
  		text: msg+' account will be permanently deleted!',
  		icon: 'warning',
  		buttons: ["No", "Yes"],
	}).then(function(value) {
	  	if (value) {
	    	window.location.href = url;
	  	}
	});
});

/*create company on others click*/
$('.othersCompany').on('change',function(e){
	const val = $(this).children("option:selected").val().trim();
	if(val == 0 && val != ''){
		e.preventDefault();
    	show_loading();
    	$('.others-company').removeClass('d-none');
    	hide_loading();
	}else{
		show_loading();
		$('.others-company').addClass('d-none');
		hide_loading();
	}
});


/*change project manager status*/
$('.change-status').change(function() {
	// show_loading();
    var status = $(this).prop('checked') == true ? 1 : 0;
    var user_id = $(this).data('id');
    var data = {'status': status, 'user_id': user_id};

    $.ajax({
        url: '/AAS-web-portal/change-status',
        type: "POST",
        data: {'status': status, 'user_id': user_id},
        success: function(data){
        	hide_loading();
        	swal({
				text: 	data.success,
				timer: 	2000,
				buttons: false,
				icon: "success",
				closeOnClickOutside: false
            });
        	show_loading();
            window.setTimeout(function(){
            	location.reload();
            } ,3000);
        },
        error:function(res){
        	console.log(res);
        }
    });
});

/*company create without login by pm*/
/*$('.pm-register-company').on('change',function(e){
	const val = $(this).children("option:selected").val();
	if(val == 0){
		$('.create-company-data-append').removeClass('d-none');
	}else{
		$('.create-company-data-append').addClass('d-none');
	}
	// alert(val);
})*/

/*email validation for company email by pm*/
/*$("form.project-manager").on("submit", function(){
	// if(!formSubmit){
		return false;
	}else{
		return true;
	}
})*/
// $('#company1').on('change',function(e){
// 	e.preventDefault();
//     // show_loading();
//     var data = $(this).val().trim();
//     var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
//     var check = true;

//     /*if(data ==""){
//     	check = false;
//     	$('#company_name_msg').text('Please fill email address');
//     }else if(regex.test(data) == false){
//     	check = false;
//     	$('#company_name_msg').text('Please fill valid email address');
//     }*/
//     if(check){
//     	// formSubmit = 1;
//     	$.ajax({
// 	    	url:'/AAS-web-portal/check-company-name',
// 	    	type: "POST",
// 	        data: {'data': data},
// 	        success: function(data){
// 	        	console.log(data);
// 	        	if(!data.length == 0){
// 	        		$('#company_name_msg').html('<b>This company  name is already exixts.</b>');
// 	        		// formSubmit = 0;
// 	        		return false;
// 	        	}else{
// 	        		// formSubmit = 1;
// 	        	}
// 	        	$('#company_name_msg').html('');
// 	        },
// 	        error:function(res){
// 	        	$('#emailMsg').text(res);
// 	        }
// 	    });
//     }

// });
/*create project release notes notes*/
$('#add-notes').on('click',function(e){
	e.preventDefault();
	var projects_release_id 		 = $('#projects_release_id').val();
	var projects_release_pm_id		 = $('#projects_release_pm_id').val();
	var projects_release_projects_id = $('#projects_release_projects_id').val();
	var projects_release_company_id  = $('#projects_release_company_id').val();
	var notes_data					 = $('#notes-data').val().trim();
	var check 						 = true;

	if(notes_data == ''){
		check = false;
		$('#notes_msg').html('<b> Enter project release notes.</b>');
	}
	if(check){
		$('#notes_msg').text('');
		show_loading();
		$.ajax({
			url:'/AAS-web-portal/pm/create-release-notes',
			type:'POST',
			data: {
				'projects_release_id' : projects_release_id,
				/*'projects_release_pm_id' : projects_release_pm_id,
				'projects_release_projects_id' : projects_release_projects_id,
				'projects_release_company_id' : projects_release_company_id,*/
				'notes_data' : notes_data
			},
			success:function(res){
				hide_loading();
	        	swal({
					text: 	res.message,
					timer: 	2000,
					buttons: false,
					icon: "success",
					closeOnClickOutside: false
	            });
	        	show_loading();
	            window.setTimeout(function(){
	            	location.reload();
	            } ,3000);
			}/*,
			error:function(err){
				console.log('error',err);
			}*/
		});
	}
});

/*append pm data with company value*/
$('#company_name-1').on('change',function(){
	var company_value = $(this).val().trim();
	if(company_value == ""){
		$("#pm_name").html('<b>Select Project Manager.</b>');
	}else{
		// show_loading();
		$.ajax({
			url	 : '/AAS-web-portal/get-pm/'+company_value,
			type : 'GET',
			data : {company_value: company_value},
			success: function(response){

				show_loading();
				$("#pm_name").empty();
				$("#pm_name").append('<option value="">Select Project Manager</option>');
				if(response.length != 0){
					$.each( response, function( key, value ) {
					  	/*console.log('value : ',value['id'] );*//*
					  	$('#pm_name').append($("<option></option>").attr("value", value['id']).text(value['first_name']+ ' '+value['last_name']));*/

					  	$('#pm_name').append('<option value="'+value['id']+'">'+value['first_name']+ ' '+value['last_name']+'</option>');
					  	// $('#pm_name').append('<option>'+key + ": " + value + '</option><br>');
					});
				}else{
					$("#pm_name").append('<option value="">No Projects manager available.</option>');
				}
				hide_loading();
				/*
				$(response).each( function(index) {
					// console.log(response['pm_data'][index]['id']);
				  $(this).append($("<option></option>").attr("value", response['pm_data'][index]['id']).text(response['pm_data'][index]['first_name']));
				})*/

				// $('#pm_name').append(response.first_name);
			}
		})
	}
});
/*append pm data with edit company value*/
$('#edit-company-name').on('change',function(){
	var company_value = $(this).val().trim();
	if(company_value == ""){
		$("#pm_name").html('<b>Select Project Manager.</b>');
		$("#pm_name").append('<option value="">Select Project Manager</option>');
	}else{
		show_loading();
		$.ajax({
			url	 : '/AAS-web-portal/get-pm/'+company_value,
			type : 'GET',
			data : {company_value: company_value},
			success: function(response){
				show_loading();
				 $("#pm_name").empty();
				 $("#pm_name").append('<option value="">Select Project Manager</option>');
				$.each( response, function( key, value ) {
				  	$('#pm_name').append('<option value="'+value['id']+'">'+value['first_name']+ ' '+value['last_name']+'</option>');
				});
				hide_loading();
			}
		})
	}
});

/*view project release*/
$('#project_release_data').on('click',function(){
	var id = $(this).data('id');
	$.ajax({
		url  : '/AAS-web-portal/view-project-release/'+id,
		type : 'GET',
		data : {id : id},
		success : function(res){
			console.log(res);
		},
		error : function(err){
			console.log(err)
		}
	});
});

/*show status summary */
$("body").on("click","#clickstatusSummary",function(){
	$(this).addClass("active");
	$(this).siblings().addClass("disable");
	$(this).siblings().removeClass("active");
	$(this).removeClass("disable");
	$("#statusSummary").removeClass("d-none");
	$("#palletsJobs").addClass("d-none");
	$("#projectPlans").addClass("d-none");
})

/*show pallets file*/
$("body").on("click","#clickJobPallets",function(){
	$(this).addClass("active");
	$(this).siblings().addClass("disable");
	$(this).siblings().removeClass("active");
	$(this).removeClass("disable");
	$("#statusSummary").addClass("d-none");
	$("#palletsJobs").removeClass("d-none");
	$("#projectPlans").addClass("d-none");
})

/*show projects plans*/
$("body").on("click","#clickJobPlans",function(){
	$(this).addClass("active");
	$(this).siblings().addClass("disable");
	$(this).siblings().removeClass("active");
	$(this).removeClass("disable");
	$("#statusSummary").addClass("d-none");
	$("#palletsJobs").addClass("d-none");
	$("#projectPlans").removeClass("d-none");
})


// table header fixed js start

$(window).on("load resize ", function() {
  var scrollWidth = $('.table-content').width() - $('.table-content table').width();
  $('.table-head').css({'padding-right':scrollWidth});
}).resize();

function moveScroller() {
    var $anchor = $("#scroll-div");
    var $scroller = $('#fixed-div');

    var move = function() {
        var st = $(window).scrollTop();
        var ot = $anchor.offset().top;
        if(st > ot) {
            $scroller.css({
                position: "fixed",
                top: "0px",
                width: "100%",
                maxWidth: "1050px"
            });
        } else {
            $scroller.css({
                position: "relative",
                top: ""
            });
        }
    };
    $(window).scroll(move);
    move();
}

// table header fixed js end