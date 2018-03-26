$(function() {
	"use strict";
	//=====================================================
	//
	// process the contact form START
	//
	//=====================================================
	$('#contact-form').on('submit', function(event) {
		
		var main = $(this);
		$('#contact-form .form-control').removeClass('has-error'); // remove the error class
		$('#contact-form .alert').remove(); // remove the error text

		// get the form data
		// there are many ways to get this data using jQuery (you can use the class or id also)
		var formData = new FormData(this);
		formData = {
			'c_username' 		: $('#contact-form input[name=username]').val(),
			'c_email' 			: $('#contact-form input[name=email]').val(),
			'c_message' 		: $('#contact-form textarea[name=message]').val()
		};

		// process the form
		$.ajax({
			type 		: 'POST', // define the type of HTTP verb we want to use (POST for our form)
			url 		: 'email.php', // the url where we want to POST
			data 		: formData, // our data object
			dataType 	: 'json' // what type of data do we expect back from the server
		})
			// using the done promise callback
			.done(function(data) {

				// log data to the console so we can see
				console.log(data); 

				// here we will handle errors and validation messages
				if ( ! data.success) {
					
					// handle errors for spam ---------------
					if (data.errors.spam) {
						main.append('<div class="alert help-block">' + data.errors.spam + '</div>');
					}
					//IF ends for "data.errors.spam"
					
					else{
						
						// handle errors for email ---------------
						if (data.errors.username) {
							$('#contact-form input[name=username]').addClass('has-error'); // add the error class to show red input
							main.append('<div class="alert alert-danger">' + data.errors.username + '</div>'); // add the actual error message under our input
						}
						
						// handle errors for email ---------------
						if (data.errors.email) {
							$('#contact-form input[name=email]').addClass('has-error'); // add the error class to show red input
							main.append('<div class="alert alert-danger">' + data.errors.email + '</div>'); // add the actual error message under our input
						}
						
						// handle errors for textarea message ---------------
						if (data.errors.textarea) {
							$('#contact-form input[name=message]').addClass('has-error'); // add the error class to show red input
							main.append('<div class="alert alert-danger">' + data.errors.textarea + '</div>'); // add the actual error message under our input
						}
					}
					//ELSE ends for "data.errors.spam"



				} else {

					// ALL GOOD! just show the success message!
					main.append('<div class="alert alert-success">' + data.message + '</div>');
					main.trigger('reset');

					// usually after form submission, you'll want to redirect
					// window.location = '/thank-you'; // redirect a user to another page

				}
			})

			// using the fail promise callback
			.fail(function(data) {

				// show any errors
				// best to remove for production
				console.log(data);
				
				main.append('<div class="alert help-block">' + data.message + '</div>');
			});

		// stop the form from submitting the normal way and refreshing the page
		event.preventDefault();
	});
	//=====================================================
	//
	// process the contact form END
	//
	//=====================================================

});

