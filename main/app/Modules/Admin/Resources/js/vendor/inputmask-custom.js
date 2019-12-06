$(function(){
	// date
	$('.form-mask-1').inputmask("dd/mm/yyyy");  
	// time
	$('.form-mask-2').inputmask("hh:mm");  
	// phone
	$('.form-mask-3').inputmask("(999) 999-9999");  
	// currency
	$('.form-mask-4').inputmask({
		mask: '$ [9][9][9][9][9][9][9][9]9.99',
		numericInput: true,
		placeholder: '0',
		greedy: false
	});
	// email
	$('.form-mask-5').inputmask({
		mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[.*{2,6}][.*{1,2}]",
		greedy: false,
		onBeforePaste: function (pastedValue, opts) {
			pastedValue = pastedValue.toLowerCase();
			return pastedValue.replace("mailto:", "");
		},
		definitions: {
		'*': {
			validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~\-]",
			casing: "lower"
			}
		}
	});
	// percent
	$('.form-mask-6').inputmask({
		mask: '% 99.99',
		placeholder: '0',
		greedy: false
	});
	
	// credit card
	$('.form-mask-7').inputmask("9999-9999-9999-9999");
});