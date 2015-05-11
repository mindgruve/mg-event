/*
 * Javascript for Mg Events admin
 *  by kchevalier@mindgruve.com
 *  06/2014
 */

jQuery(document).ready(function() {
	MgEventsAdmin.init();
    Geolocation.init();
});

var MgEventsAdmin = function(){

	var startDateJQ = null;
	var endDateJQ = null;
	var sponsorUploadButtonJQ = null;
	var sponsorUploadInputJQ = null;

	var init = function() {

        // init properties
        startDateJQ = jQuery('#start_date');
        endDateJQ = jQuery('#end_date');
        sponsorUploadButtonJQ = jQuery('#sponsor_upload_button');
        sponsorUploadInputJQ = jQuery('#sponsor_upload');

        // attach datepicker
		startDateJQ.datepicker({
            dateFormat : 'mm/dd/yy'
        });
		endDateJQ.datepicker({
            dateFormat : 'mm/dd/yy'
        });

        // show media uploader
        sponsorUploadButtonJQ.click(function() {
            window['SPONSOR_UPLOAD_MODE'] = true;
            formfield = sponsorUploadInputJQ.attr('name');
            tb_show( '', 'media-upload.php?TB_iframe=true' );
            return false;
        });

        //hook into the thickbox_remove to properly exit out of SPONSOR_UPLOAD_MODE from above
        var _tb_remove = window.tb_remove;
        window.tb_remove = function () {
            window['SPONSOR_UPLOAD_MODE'] = false;
            _tb_remove();
        };

        // insert media into event
        var _send_to_editor = window.send_to_editor;
        window.send_to_editor = function(html) {
            if (window['SPONSOR_UPLOAD_MODE']) {
                mediaurl = jQuery(html).attr('href');
                sponsorUploadInputJQ.val(mediaurl);
                tb_remove();
            } else {
                _send_to_editor( html );
            }
        };
	};

	return {
		init: init
	};
}();

var Geolocation = function(){

	var geocoder;

	var buttonJQ = null;
	var statusJQ = null;
	var address1JQ = null;
	var address2JQ = null;
	var cityJQ = null;
	var regionJQ = null;
	var postalCodeJQ = null;

	var init = function() {
		if ( jQuery('#latitude').length != 0 ) {

			// init properties
			buttonJQ = jQuery('#google-map-query');
			statusJQ = jQuery('#map-query-status');
			address1JQ = jQuery('#address1');
			address2JQ = jQuery('#address2');
			cityJQ = jQuery('#city');
			regionJQ = jQuery('#region');
			postalCodeJQ = jQuery('#postal_code');
			latitudeJQ = jQuery('#latitude');
			longitudeJQ = jQuery('#longitude');

			// event handlers
			buttonJQ.bind( 'click', submitFormHandler );
		}
	};

	function submitFormHandler( evt ) {
		evt.preventDefault();
		var self = this;
		statusJQ.text('Checking...');
		geocoder = new google.maps.Geocoder();
		var address = address1JQ.val() + ' ' + address2JQ.val() + ', ' + cityJQ.val() + ', ' + regionJQ.val() + ', ' + postalCodeJQ.val();
		geocoder.geocode( { 'address': address }, function( results, status ) {
			if ( status == google.maps.GeocoderStatus.OK ) {
				statusJQ.text('Updated.');
				latitudeJQ.val( results[0].geometry.location.lat() );
				longitudeJQ.val( results[0].geometry.location.lng() );
			}
			else {
				statusJQ.text('There was a problem updating the coordinates. Please try again.');
				return false;
			}
		} );
	};

	return {
		init: init
	};
}();

