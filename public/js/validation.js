check = true;

/*change my profuile validation*/
$('#my-profile-update').on('click',function(){
	var first_name = $('#first_name').val().trim();
	var last_name = $('#last_name').val().trim();
	var add = $('#address').val().trim();
	var contact = $('#contact').val().trim();

	if(first_name == ''){
		check = false;
		$('#first_name_msg').html('<b>Enter your name.</b>');
	}else{
		check = true;
		$('#first_name_msg').html('');
	}

	if(last_name == ''){
		$('#last_name_msg').html('<b>Enter your last name.</b>');
	}else{
		check = true;
		$('#last_name_msg').html('');
	}

	if(contact != ''){
		if(isNaN(contact)==true){
	    	check = false;
	    	$("#contact_msg").html("<b>Please enter only the number.</b>");
	 	}else{
		    if(contact.length < 10){
		       check = false;
		       $("#contact_msg").html("<b>Enter 10 digit Mobile Number</b>");
		    }else{
		    	check = true;
		       $("#contact_msg").html("");
		    }
	 	}
	}
	return check;
});

/*validation liberary*/
/*login validation*/
$('#login-form').validate({
	rules :{
		email : {
			required : true,
			email 	 : true
		},
		password : {
			required : true
		}
	},
	messages : {
		email : {
			required : "Email address is required.",
			email 	 : "Please enter valid email address."
		},
		password : {
			required : "Password is required."
		}
	},
	submitHandler: function(form) {
        form.submit();
    }
});

/*pm register*/
$("#pm-register-form").validate({
	// debug : \true,
	rules :{

		first_name : {
			required : true,
			lettersonly : true,
			minlength : 3
		},
		last_name : {
			required : true,
			lettersonly : true,
			minlength : 3
		},
		company : {
			required : true
		},
		others_company : {
			required : true,
			remote: {
		        url: "/AAS-web-portal/check-company-name",
		        type: "post",
		        data: {
		          company_name: function() {
		            return $( "#other-company" ).val();
		          }
		        }
	      	}
		},
		email : {
			required : true,
			email 	 : true,
			remote 	 : {
				url: "/AAS-web-portal/check-email-address",
		        type: "post",
		        data: {
		          email: function() {
		            return $( "#email" ).val();
		          }
		        }
			}
		},
		phone : {
			required : true,
            phoneUS: true,
		 	minlength: 10
		},
		recaptcha :{
			required : function () {
                    if (grecaptcha.getResponse() == '') {
                        return true;
                    } else {
                        return false;
                    }
                }

		}
	},
	messages : {

		first_name :{
			required 	: "Enter first name.",
			lettersonly : "Enter valid first name.",
			minlength 	: "Enter minimum 3 characters."
		},
		last_name :{
			required 	: "Enter last name.",
			lettersonly : "Enter valid last name.",
			minlength 	: "Enter minimum 3 characters."
		},
		email : {
			required : "Enter your email address.",
			email 	 : "Enter valid email address.",
			remote 	 : "Email is already used."
		},
		company : {
			required : "Select company name."
		},
		others_company : {
			required : "Enter company name.",
			remote 	 : "Company already exixts."
		},
		phone : {
			phoneUS : "Enter valid phone number",
			required : "Enter phone number"
		}
	
	}
})

/*admin projects create validation*/
$('#create-projects-form').validate({
	rules:{
		project_name:{
			required : true
		},
		job_number:{
			required  : true,
			minlength : 4,
			number 	  : true,
			remote: {
		        url: "/AAS-web-portal/check-project-job-number",
		        type: "post",
		        data: {
		          job_number: function() {
		            return $( "#job-number" ).val();
		          }
		        }
	      	}
		},
		company_name:{
			required : true
		},
		/*pm_name : {

		}*/
		project_state : {
			required : true,
			number   : false
		},
		project_city : {
			required : true,
			number   : false
		}
	},
	messages:{
		project_name:{
			required : "Please enter your project name.",
		},
		job_number:{
			required: "Please enter your job number.",
			remote 	 : "Job number is already used."
		},
		company_name:{
			required : "Please enter your company name."
		},
		project_state : {
			required : "Please enter your state.",
		},
		project_city : {
			required : "Please enter your city.",
		}
	}
});

/*admin update projects details*/
$('#update-projects-form').validate({
	rules:{
		project_name:{
			required : true
		},
		job_number:{
			required  : true,
			minlength : 4,
			number 	  : true,
			/*remote: {
		        url: "/AAS-web-portal/check-project-job-number",
		        type: "post",
		        data: {
		          job_number: function() {
		            return $( "#job-number" ).val();
		          }
		        }
	      	}*/
		},
		company_name:{
			required : true
		},
		project_state : {
			required : true,
			number   : false
		},
		project_city : {
			required : true,
			number   : false
		}
	},
	messages:{
		project_name:{
			required : "Please enter your project name.",
		},
		job_number:{
			required: "Please enter your job number.",
		},
		company_name:{
			required : "Please enter your company name."
		},
		project_state : {
			required : "Please enter your state.",
		},
		project_city : {
			required : "Please enter your city.",
		}
	}
});

/*admin create customers*/
$('#create-company-form').validate({
	rules : {
		company_name : {
			required : true,
			remote: {
		        url: "/AAS-web-portal/check-company-name",
		        type: "post",
		        data: {
		          company_name: function() {
		            return $( "#company_name" ).val();
		          }
		        }
	      	}
		},
		name : {
			alphanumeric: true
		},
		email : {
			email    : true,
		},
		company_logo : {
			accept:"jpg,png,jpeg"
		},
		contact : {
            phoneUS: true,
		 	minlength: 10,
		 	alphanumeric: true
		}
	},
	messages : {
		company_name : {
			required : "Customer name is required.",
			remote 	 : "Company already exixts."
		},
		name : {
			alphanumeric : "Please do not use special characters."
		},
		contact: {
            required: "Please enter your phone number",
            phoneUS: "Please enter a valid phone number: (e.g. 19999999999 or 9999999999)"
        },
        email : {
			email    : "Enter valid email address.",
		},
		company_logo : {
			accept : "Only image type jpg/png/jpeg is allowed"
		},
	},
	submitHandler: function(form) {
        form.submit();
    }
})

/*admin update customer data*/
$('#update-company-form').validate({
	// debug : true,
	rules :{
		/*name :{
			alpha : true
		},*/
		email : {
			email    : true,
		},
		company_logo : {
			accept:"jpg,png,jpeg"
		},
		contact : {
            phoneUS: true,
		 	minlength: 10,
		 	number 	 : true,
		 	alphanumeric: true
		}
	},
	messages : {
		/*name : {
			alpha : "Enter valid name."
		},*/
        email : {
			email    : "Enter valid email address.",
		},
		company_logo : {
			accept : "Only image type jpg/png/jpeg is allowed"
		},
		contact : {
			phoneUS 	: "Enter valid phone number",
			minlength 	: "Minimum 10 digits phone number." 
		}
	}
});

/*admin create project manager*/
$('#create-project-manager').validate({
	rules :{
		first_name : {
			required : true,
			lettersonly : true,
			minlength : 3
		},
		last_name : {
			required : true,
			lettersonly : true,
			minlength : 3
		},
		company : {
			required : true
		},
		others_company : {
			required : true,
			remote: {
		        url: "/AAS-web-portal/check-company-name",
		        type: "post",
		        data: {
		          company_name: function() {
		            return $( "#other-company" ).val();
		          }
		        }
	      	}
		},
		email : {
			required : true,
			email 	 : true,
			remote 	 : {
				url: "/AAS-web-portal/check-email-address",
		        type: "post",
		        data: {
		          email: function() {
		            return $( "#email" ).val();
		          }
		        }
			}
		},
		contact : {
			required : true,
            phoneUS: true,
		 	minlength: 10
		}
	},
	messages : {
		first_name :{
			required 	: "Enter first name.",
			lettersonly : "Enter valid first name.",
			minlength 	: "Enter minimum 3 characters."
		},
		last_name :{
			required 	: "Enter last name.",
			lettersonly : "Enter valid last name.",
			minlength 	: "Enter minimum 3 characters."
		},
		email : {
			required : "Enter your email address.",
			email 	 : "Enter valid email address.",
			remote 	 : "This email address is already exixts."
		},
		company : {
			required : "Select company name."
		},
		others_company : {
			required : "Enter company name.",
			remote 	 : "Company already exixts."
		},
		contact : {
			phoneUS : "Enter valid phone number",
			required : "Enter phone number"
		}
	}
});

/*admin update company details*/
$('#update-project-manager').validate({
	rules :{
		first_name : {
			required : true,
			lettersonly : true,
			minlength : 3
		},
		last_name : {
			required : true,
			lettersonly : true,
			minlength : 3
		},
		company : {
			required : true
		},
		email : {
			required : true,
			email 	 : true,
			remote 	 : {
				url: "/AAS-web-portal/check-email-address",
		        type: "post",
		        data: {
		          email: function() {
		            return $( "#email" ).val();
		          }
		        }
			}
		},
		contact : {
			required : true,
            phoneUS: true,
		 	minlength: 10
		}
	},
	messages : {
		first_name :{
			required 	: "Enter first name.",
			lettersonly : "Enter valid first name.",
			minlength 	: "Enter minimum 3 characters."
		},
		last_name :{
			required 	: "Enter first name.",
			lettersonly : "Enter valid last name.",
			minlength 	: "Enter minimum 3 characters."
		},
		company : {
			required : "Select company name."
		},
		contact : {
			phoneUS : "Enter valid phone number",
			required : "Enter phone number"
		}
	}
});