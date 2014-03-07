$(document).ready(function() {
	$("a#delete-contract").click(function(event) {
	    event.preventDefault();
	    var location = $(this).attr('href');
	    bootbox.confirm("Etes-vous sûr de vouloir supprimer ce contrat?", function(confirmed) {
	        if(confirmed) {
	        window.location.replace(location);
	        }
	    });
    });
});