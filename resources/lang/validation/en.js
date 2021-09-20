(function( factory ) {
	if ( typeof define === "function" && define.amd ) {
		define( ["jquery", "../jquery.validate"], factory );
	} else if (typeof module === "object" && module.exports) {
		module.exports = factory( require( "jquery" ) );
	} else {
		factory( jQuery );
	}
}(function( $ ) {

/*
 * Translated default messages for the jQuery validation plugin.
 * Locale: EN (English)
 */
$.extend( $.validator.messages, {
	required: "This field is required.",
	remote: "Please correct this field.",
	email: "Please provide a valid email address.",
	url: "Please provide a valid URL.",
	date: "Please provide a valid date.",
	dateISO: "Please provide a valid date (ISO).",
	number: "Please provide a valid number.",
	digits: "Please provide only numbers.",
	creditcard: "Please provide a valid credit card number.",
	equalTo: "Please provide the same value again.",
	notEqualTo: "Please provide a different value, the values ​​must not be the same.",
	extension: "Please provide a value with a valid extension.",
	maxlength: $ .validator.format ("Please provide at most {0} characters."),
	minlength: $ .validator.format ("Please provide at least {0} characters."),
	rangelength: $ .validator.format ("Please provide a value that contains between {0} and {1} characters."),
	range: $ .validator.format ("Please provide a value between {0} and {1}."),
	max: $ .validator.format ("Please provide a value less than or equal to {0}."),
	min: $ .validator.format ("Please provide a value greater than or equal to {0}."),
	step: $ .validator.format ("Please provide a value multiple of {0}."),
	maxWords: $ .validator.format ("Please provide at most {0} words."),
	minWords: $ .validator.format ("Please provide at least {0} words."),
	rangeWords: $ .validator.format ("Please provide between {0} and {1} words."),
	letterswithbasicpunc: "Please provide only letters and punctuation marks.",
	alphanumeric: "Please provide only letters, numbers, spaces and underscores.",
	lettersonly: "Please provide letters only.",
	nowhitespace: "Please do not enter white space.",
	ziprange: "Please provide a postal code between 902xx-xxxx and 905-xx-xxxx.",
	integer: "Please provide a non-decimal number that is positive or negative.",
	vinUS: "Please provide a vehicle identification number (VIN).",
	dateITA: "Please provide a valid date.",
	time: "Please provide a valid time between 00:00 and 23:59.",
	phoneUS: "Please provide a valid phone number.",
	phoneUK: "Please provide a valid phone number.",
	mobileUK: "Please provide a valid mobile phone number.",
	strippedminlength: $ .validator.format ("Please provide at least {0} characters."),
	email2: "Please provide a valid email address.",
	url2: "Please provide a valid URL.",
	creditcardtypes: "Please provide a valid credit card number.",
	ipv4: "Please provide a valid v4 IP address.",
	ipv6: "Please provide a valid v6 IP address.",
	require_from_group: "Please provide at least {0} of these fields.",
	nifES: "Please provide a valid NIF number.",
	nieES: "Please provide a valid NIE number.",
	cifES: "Please provide a valid CIF number.",
	postalCodeCA: "Please provide a valid postal code."
});
return $;
}));