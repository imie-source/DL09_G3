$(document).ready(function() {
	var offset = 220;
    var duration = 'slow';
    jQuery(window).scroll(function() {
        if (jQuery(this).scrollTop() > offset) {
            jQuery('#btn-scroll-up').fadeIn(duration);
        } else {
            jQuery('#btn-scroll-up').fadeOut(duration);
        }
    });
});