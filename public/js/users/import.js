$(document).ready(function() {
	$('#id-input-file-2').ace_file_input({
		no_file:'Aucun fichier choisi ...',
		btn_choose:'Choisir',
		btn_change:'Changer',
		droppable:true,
		onchange:null,
		thumbnail:false //| true | large
		//whitelist:'gif|png|jpg|jpeg'
		//blacklist:'exe|php'
		//onchange:''
		//
	});
	
	$('#id-input-file-3').ace_file_input({
		style:'well',
		btn_choose:'Glissez votre fichier ici ou cliquez pour le choisir',
		btn_change:null,
		no_icon:'icon-cloud-upload',
		droppable:true,
		thumbnail:'small'//large | fit
		//,icon_remove:null//set null, to hide remove/reset button
		/**,before_change:function(files, dropped) {
			//Check an example below
			//or examples/file-upload.html
			return true;
		}*/
		/**,before_remove : function() {
			return true;
		}*/
		,
		preview_error : function(filename, error_code) {
			//name of the file that failed
			//error_code values
			//1 = 'FILE_LOAD_FAILED',
			//2 = 'IMAGE_LOAD_FAILED',
			//3 = 'THUMBNAIL_FAILED'
			//alert(error_code);
		}

	}).on('change', function(){
		//console.log($(this).data('ace_input_files'));
		//console.log($(this).data('ace_input_method'));
	});

});