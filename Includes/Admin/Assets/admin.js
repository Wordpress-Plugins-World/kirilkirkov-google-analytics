(function($) {
	"use strict";

	kirilkirkovGoogleAnalyticsInit();

	function kirilkirkovGoogleAnalyticsInit() {
		const infoBtns = document.querySelectorAll('.show-info');
		let infoBox = document.getElementsByClassName('google-analytics-info')[0];

		// listen click info modal
		infoBtns.forEach(el => el.addEventListener('click', event => {
			infoBox.style.visibility = 'visible';
			infoBox.style.opacity = 1;
			document.getElementsByClassName('info-box')[0].innerHTML = el.getAttribute("data-info");
		}));

		// close info modal
		jQuery('.ft-modal-close').on('click', function() {
			infoBox.style.visibility = 'hidden';
			infoBox.style.opacity = 0;
		});

		// shortcode select
		jQuery("#shortcode").on('mouseup', function() { 
			let sel, range;
			let el = jQuery(this)[0];
			if (window.getSelection && document.createRange) { //Browser compatibility
			sel = window.getSelection();
			if(sel.toString() === '') { //no text selection
				window.setTimeout(function(){
					range = document.createRange(); //range object
					range.selectNodeContents(el); //sets Range
					sel.removeAllRanges(); //remove all ranges from selection
					sel.addRange(range);//add Range to a Selection.
				},1);
			}
			}else if (document.selection) { //older ie
				sel = document.selection.createRange();
				if(sel.text === '') { //no text selection
					range = document.body.createTextRange();//Creates TextRange object
					range.moveToElementText(el);//sets Range
					range.select(); //make selection.
				}
			}
		});
	}

})(jQuery); 