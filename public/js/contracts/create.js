jQuery(function($) {

	$('#difficulty_maj_value').val(($('#salaire_horaire_brut').val() * $('#difficulty_maj_rate').val() / 100).toFixed(2));

	$('#difficulty_maj_rate, #salaire_horaire_brut').keyup(function() {
		if($('#difficulty_maj_rate').val() != '' && $('#difficulty_maj_rate').val() != null && $('#difficulty_maj_rate').val() != undefined){
			$('#difficulty_maj_value').val(($('#salaire_horaire_brut').val() * $('#difficulty_maj_rate').val() / 100).toFixed(2));
		}
		else{
			$('#difficulty_maj_value').val('0.00');
		}
	});

	$('#salaire_horaire_brut').keyup(function(){
		var salaireBase = $('#salaire_horaire_brut').val();
		var salaireNet = salaireBase;
		$.each(cotisJson, function() {
		    salaireNet -= (salaireBase * this.base / 100) * this.taux / 100;
		});
		$('#salaire_horaire_net').val(salaireNet.toFixed(2));
	});

	$('#hours_maj_value').val(($('#salaire_horaire_brut').val() * $('#hours_maj_rate').val() / 100).toFixed(2));

	$('#hours_maj_rate, #salaire_horaire_brut').keyup(function() {
		if($('#hours_maj_rate').val() != '' && $('#hours_maj_rate').val() != null && $('#hours_maj_rate').val() != undefined){
			$('#hours_maj_value').val(($('#salaire_horaire_brut').val() * $('#hours_maj_rate').val() / 100).toFixed(2));
		}
		else{
			$('#hours_maj_value').val('0.00');
		}
	});

	$('input[name="accueil_type"]').click(function(){
		updateSalary(false);
	});

	$('#salaire_horaire_brut, #nb_hour_weekly').keyup(function(){
		updateSalary(false);
	});

	$('#nb_semaines_acc_incomplet, #nb_heures_acc_occasionnel').keyup(function(){
		updateSalary(true);
	});

	$('#addVacancyPeriod').click(function(event){
		event.preventDefault();
		$(this).before('<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="fractionnement_conges_du_2">Du:</label><div class="col-xs-12 col-sm-3"><input type="text" id="fractionnement_conges_du_2" name="fractionnement_conges_du_2"></div><label class="control-label col-xs-12 col-sm-1 no-padding-right" for="fractionnement_conges_au_2">Au:</label><div class="col-xs-12 col-sm-3"><input type="text" id="fractionnement_conges_au_2" name="fractionnement_conges_au_2"></div>');
		$(this).remove();
		$('#fractionnement_conges_du, #fractionnement_conges_au').each(function() {
			$(this).mask('99-99-9999');
		});
	});

	$('#repas-fournis-ass-mat').click(function(){
		$('input#switch-field-repas').each(function(){
			$(this).prop('disabled', false);
		});
	});

	$('#repas-fournis-parents').click(function(){
		$('input#switch-field-repas').each(function(){
			$(this).prop('checked', false);
			$(this).prop('disabled', true);
		});
		$('#cout-petit-dejeuner, #cout-dejeuner, #cout-gouter, #cout-diner').val('');
		$('#cout-petit-dejeuner, #cout-dejeuner, #cout-gouter, #cout-diner').prop('disabled', true);
	});

	$('input#switch-field-repas').click(function(){
		if($(this).is(':checked')){
			$('#' + $(this).attr('data-target')).prop('disabled', false).val($(this).attr('data-value-target'));
		}
		else{
			$('#' + $(this).attr('data-target')).prop('disabled', true);
			$('#' + $(this).attr('data-target')).val('');
		}
	});

	$('[data-rel=tooltip]').tooltip();

	var $validation = false;
	$('#fuelux-wizard').ace_wizard()
	.on('change' , function(e, info){

		$('html, body').animate({scrollTop:0}, 'slow');
		var datas = $('#contract-ajax-form').serializeArray();
		$('.wysiwyg-editor').each(function(){
			datas.push({name: $(this).attr('id'), value:$(this).html()});
			datas.push({name: 'validated', value: 'N'});
			datas.push({name: 'salaire_mensuel_brut', value: $('strong#salaire-mensu').html()});
		});
		$.ajax({
			url: '/contracts/ajaxCreate',
			type: 'post',
			data: datas,
		})
		.done(function(data) {
			$('#contract_id').val(data);
		})
		if(info.step == 1 && $validation) {
			//if(!$('#contract-ajax-form').valid()) return false;
		}
	})
	.on('finished', function(e) {
		bootbox.confirm("<strong>Félicitations</strong><br/>Votre nouveau contrat est terminé mais pas encore validé. Si vous voulez corriger une partie cliquez sur 'Annuler'. Sinon vous pouvez cliquer sur 'Valider' pour valider votre nouveau contrat.", function(confirmed) {
	        if(confirmed) {
	        	var datas = $('#contract-ajax-form').serializeArray();
				$('.wysiwyg-editor').each(function(){
					datas.push({name: $(this).attr('id'), value:$(this).html()});
					datas.push({name: 'valide', value: 'Y'});
			datas.push({name: 'salaire_mensuel_brut', value: $('strong#salaire-mensu').html()});
				});
				$.ajax({
					url: '/contracts/ajaxCreate',
					type: 'post',
					data: datas,
				})
				.done(function(data) {
	        		window.location.replace('/contracts/index');
				})
				.fail(function(){
					$.gritter.add({
				        title: 'Erreur',
				        text: 'Une erreur est survenue lors de la finalisation de votre contrat. Veuillez recommencer.',
				        class_name: 'gritter-error'
				      });
				});
	        }
	    });
	})
	.on('stepclick', function(e){
					//return false;//prevent clicking on steps
				});	


				//documentation : http://docs.jquery.com/Plugins/Validation/validate


				

				$('.wysiwyg-editor').each(function() {
					$(this).ace_wysiwyg({
						toolbar:
						[
						'bold',
						'italic',
						'strikethrough',
						'underline',
						null,
						'justifyleft',
						'justifycenter',
						'justifyright',
						null,
						'createLink',
						'unlink',
						null,
						'undo',
						'redo'
						]
					});					
				});

				$('.wysiwyg-editor').each(function() {
					$(this).slimScroll({
						height: '260px',
				   		railVisible: true
					});
				});

				//$('#nb_hour_weekly').ace_spinner({value:45,min:0,step:1, btn_up_class:'btn-info' , btn_down_class:'btn-info'});


				/*jQuery.validator.addMethod("phone", function (value, element) {
					return this.optional(element) || /^\(\d{3}\) \d{3}\-\d{4}( x\d{1,6})?$/.test(value);
				}, "Enter a valid phone number.");

				jQuery.validator.addMethod("datefr", function (value, element) {
					return this.optional(element) || /^(0[1-9]|[1-2][0-9]|3[01])-(0[1-9]|1[0-2])-([12][09][019][0-9])$/.test(value);
				}, "Veuillez entrer un date au format dd-mm-yyyy.");

				$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						child_surname: {
							required: true
						},
						child_firstname: {
							required: true
						},
						child_birth_date: {
							datefr: true
						},
						mother_surname: {
							required: true
						},
						mother_firstname: {
							required: true
						},
						mother_email: {
							email: true
						}
					},

					messages: {
						child_surname: "Le nom de l'enfant est obligatoire.",
						child_firstname: "Le prénom de l'enfant est obligatoire.",
						mother_surname: "Le nom de la mère est obligatoire.",
						mother_firstname: "Le prénom de la mère est obligatoire.",
						mother_email: {
							email: "Merci de renseigner un email valide."
						}
					},

					highlight: function (e) {
						$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
					},

					success: function (e) {
						$(e).closest('.form-group').removeClass('has-error').addClass('has-info');
						$(e).remove();
					},

					errorPlacement: function (error, element) {
						if(element.is(':checkbox') || element.is(':radio')) {
							var controls = element.closest('div[class*="col-"]');
							if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
							else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
						}
						else if(element.is('.select2')) {
							error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
						}
						else if(element.is('.chosen-select')) {
							error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
						}
						else error.insertAfter(element.parent());
					},

					submitHandler: function (form) {
					},
					invalidHandler: function (form) {
					}
				});*/

				$('#mother_phone_house, #mother_phone_work, #mother_phone_mobile, #father_phone_house, #father_phone_work, #father_phone_mobile').mask('99 99 99 99 99');
				$('#fractionnement_conges_du, #fractionnement_conges_au, #date_debut_contrat, #child_birth_date').mask('?99-99-9999');

			$("#import-mother").on(ace.click_event, function(event) {
				event.preventDefault();
				bootbox.dialog({
					title: 'Importer une fiche Nannyster',
					message: "<form class=\"form-horizontal\"><div class=\"form-group\"><label class=\"col-xs-12 col-sm-6 no-padding-right\" for=\"mother_search_surname\">Veuillez saisir le nom de la mère:</label><div class=\"col-xs-12 col-sm-5\"><div class=\"input-icon clearfix\"><input autocomplete=\"off\" type=\"text\" id=\"mother_search_surname\" name=\"mother_search_surname\" class=\"col-xs-12 col-sm-12\" /><i class=\"icon-user\"></i><div id=\"user-find-results-import\" class=\"hidden user-find-results-import\"></div></div><div class=\"hidden image-loading-mail\" id=\"loading-users-results\"><i class=\"icon-spin icon-spinner orange2 bigger-160\"></i></div></div></form><script type=\"text/javascript\">motherImportUser();motherFct();motherClick();</script>",
					buttons: 			
						{
							"success" :
							{
								"label" : "<i class='icon-ok'></i> Terminé!",
								"className" : "btn-sm btn-success",
								"callback": function() {
									//Example.show("great success");
								}
							}
						}
					});
		});

		$("#import-father").on(ace.click_event, function(event) {
			event.preventDefault();
				bootbox.dialog({
					title: 'Importer une fiche Nannyster',
					message: "<form class=\"form-horizontal\"><div class=\"form-group\"><label class=\"col-xs-12 col-sm-6 no-padding-right\" for=\"father_search_surname\">Veuillez saisir le nom du père:</label><div class=\"col-xs-12 col-sm-5\"><div class=\"input-icon clearfix\"><input autocomplete=\"off\" type=\"text\" id=\"father_search_surname\" name=\"father_search_surname\" class=\"col-xs-12 col-sm-12\" /><i class=\"icon-user\"></i><div id=\"user-find-results-import\" class=\"hidden user-find-results-import\"></div></div><div class=\"hidden image-loading-mail\" id=\"loading-users-results\"><i class=\"icon-spin icon-spinner orange2 bigger-160\"></i></div></div></form><script type=\"text/javascript\">fatherImportUser();fatherFct();fatherClick()</script>",
					buttons: 			
						{
							"success" :
							{
								"label" : "<i class='icon-ok'></i> Terminé!",
								"className" : "btn-sm btn-success",
								"callback": function() {
									//Example.show("great success");
								}
							}
						}
					});
		});
	
	$('#delete-mother').click(function(event){
		event.preventDefault();
		$('#mother_id').val('');
		$('#mother_surname').val('').prop('readonly', false);
	    $('#mother_firstname').val('').prop('readonly', false);
	    $('#mother_address').val('').prop('readonly', false);
	    $('#mother_zip_code').val('').prop('readonly', false);
	    $('#mother_city').val('').prop('readonly', false);
	    $('#mother_phone_house').val('').prop('readonly', false);
	    $('#mother_phone_mobile').val('').prop('readonly', false);
	    $('#mother_email').val('').prop('readonly', false);
	    ($('#delete-father').hasClass('hidden')) ? $('#urssaf_pajemploi_no').val('').prop('readonly', false) : '';
	    $(this).addClass('hidden');
	});

	$('#delete-father').click(function(event){
		event.preventDefault();
		$('#father_id').val('');
		$('#father_surname').val('').prop('readonly', false);
	    $('#father_firstname').val('').prop('readonly', false);
	    $('#father_address').val('').prop('readonly', false);
	    $('#father_zip_code').val('').prop('readonly', false);
	    $('#father_city').val('').prop('readonly', false);
	    $('#father_phone_house').val('').prop('readonly', false);
	    $('#father_phone_mobile').val('').prop('readonly', false);
	    $('#father_email').val('').prop('readonly', false);
	    ($('#delete-mother').hasClass('hidden')) ? $('#urssaf_pajemploi_no').val('').prop('readonly', false) : '';
	    $(this).addClass('hidden');
	});

	 var text = jQuery('#nanny_agreement_nb_children').val(),
    // look for any "\n" occurences
    matches = text.match(/\n/g),
    breaks = matches ? matches.length : 2;
    jQuery('#nanny_agreement_nb_children').attr('rows',breaks + 2);

    $('.wysiwyg-speech-input').each(function() {
		$(this).removeAttr('style');    	
    });
});

function motherFct(){
	//Focusout recherche import contrat
  $('#mother_search_surname').on('focusout', function() {
    setTimeout(function() {
      $('#user-find-results-import').addClass('hidden');
      $('#loading-users-results').addClass('hidden');
    }, 250);
  });

  //Focusin recherche import contrat
  $('#mother_search_surname').on('focusin', function() {
    if (this.value) {
      $('#user-find-results-import').removeClass('hidden');
    }
  });
}

function motherImportUser(){
	var timer;
  $('#mother_search_surname').on('keydown', function() {
    if($('#mother_search_surname').val() != ''){
      $('#loading-users-results').removeClass('hidden');
      var valueToExclude = {
        "id":[{}]
      };
      $('input[type="hidden"]').each(function(){
        valueToExclude.id.push($(this).attr('data-id'));
      });
      clearTimeout(timer);
      timer = setTimeout(function() {
        if($('#mother_search_surname').val() != ''){
          $.ajax({
            url: '/users/ajaxMotherUsersForNewContract',
            type: 'POST',
            data: {
              'q': $('#mother_search_surname').val(),
              'e': valueToExclude
            }
          })
          .done(function(data) {
            $('#loading-users-results').addClass('hidden');
            $('#user-find-results-import').removeClass('hidden');
            $('#user-find-results-import').html(data);
          })
          .fail(function() {
            $('#loading-users-results').addClass('hidden');
            $.gritter.add({
              title: 'Erreur',
              text: 'Une erreur est survenue. Merci de recommencer ultérieurement.',
              class_name: 'gritter-error'
            });
          })
          .always(function() {
            })
        }
        else{
          $('#loading-users-results').addClass('hidden');
          $('#user-find-results-import').empty();
          $('#user-find-results-import').addClass('hidden');
        }
      }, 700);
    }
  });
}

function motherClick(){
	//Ajout de destinataires mail
	$('#user-find-results-import').each(function() {
	  $(this).on('click', '#user-result-span', function(event) {
	    event.preventDefault();
	    var id = $(this).attr('data-id');
	    var firstname = $(this).attr('data-firstname');
	    var surname = $(this).attr('data-surname');
	    var address = $(this).attr('data-address');
	    var zip_code = $(this).attr('data-zip-code');
	    var city = $(this).attr('data-city');
	    var phone = $(this).attr('data-phone');
	    var mobile = $(this).attr('data-mobile');
	    var email = $(this).attr('data-email');
	    var pajemploi = $(this).attr('data-pajemploi');
	    (id != '') ? $('#mother_id').val(id) : $('#mother_id').val('');
	    (surname != '') ? $('#mother_surname').val(surname).prop('readonly', true) : $('#mother_surname').val('').prop('readonly', false);
	    (firstname != '') ? $('#mother_firstname').val(firstname).prop('readonly', true) : $('#mother_firstname').val('').prop('readonly', false);
	    (address != '') ? $('#mother_address').val(address).prop('readonly', true) : $('#mother_address').val('').prop('readonly', false);
	    (zip_code != '') ? $('#mother_zip_code').val(zip_code).prop('readonly', true) : $('#mother_zip_code').val('').prop('readonly', false);
	    (city != '') ? $('#mother_city').val(city).prop('readonly', true) : $('#mother_city').val('').prop('readonly', false);
	    (phone != '') ? $('#mother_phone_house').val(phone).prop('readonly', true) : $('#mother_phone_house').val('').prop('readonly', false);
	    (mobile != '') ? $('#mother_phone_mobile').val(mobile).prop('readonly', true) : $('#mother_phone_mobile').val('').prop('readonly', false);
	    (email != '') ? $('#mother_email').val(email).prop('readonly', true) : $('#mother_email').val('').prop('readonly', false);
	    (pajemploi != '') ? (($('#urssaf_pajemploi_no').val() == '') ? $('#urssaf_pajemploi_no').val(pajemploi).prop('readonly', true) : $('#urssaf_pajemploi_no').val(pajemploi).prop('readonly', true)) : ($('#urssaf_pajemploi_no').val() == null ? $('#urssaf_pajemploi_no').val('').prop('readonly', false) : '');
	    $('#delete-mother').removeClass('hidden');
		});
	});
}

function fatherFct(){
	$('#father_search_surname').focus();
	//Focusout recherche import contrat
  $('#father_search_surname').on('focusout', function() {
    setTimeout(function() {
      $('#user-find-results-import').addClass('hidden');
      $('#loading-users-results').addClass('hidden');
    }, 250);
  });

  //Focusin recherche import contrat
  $('#father_search_surname').on('focusin', function() {
    if (this.value) {
      $('#user-find-results-import').removeClass('hidden');
    }
  });
}

function fatherImportUser(){
	var timer;
  $('#father_search_surname').on('keydown', function() {
    if($('#father_search_surname').val() != ''){
      $('#loading-users-results').removeClass('hidden');
      var valueToExclude = {
        "id":[{}]
      };
      $('input[type="hidden"]').each(function(){
        valueToExclude.id.push($(this).attr('data-id'));
      });
      clearTimeout(timer);
      timer = setTimeout(function() {
        if($('#father_search_surname').val() != ''){
          $.ajax({
            url: '/users/ajaxFatherUsersForNewContract',
            type: 'POST',
            data: {
              'q': $('#father_search_surname').val(),
              'e': valueToExclude
            }
          })
          .done(function(data) {
            $('#loading-users-results').addClass('hidden');
            $('#user-find-results-import').removeClass('hidden');
            $('#user-find-results-import').html(data);
          })
          .fail(function() {
            $('#loading-users-results').addClass('hidden');
            $.gritter.add({
              title: 'Erreur',
              text: 'Une erreur est survenue. Merci de recommencer ultérieurement.',
              class_name: 'gritter-error'
            });
          })
          .always(function() {
            })
        }
        else{
          $('#loading-users-results').addClass('hidden');
          $('#user-find-results-import').empty();
          $('#user-find-results-import').addClass('hidden');
        }
      }, 700);
    }
  });
}

function fatherClick(){
	//Ajout de destinataires mail
	$('#user-find-results-import').each(function() {
	  $(this).on('click', '#user-result-span', function(event) {
	    event.preventDefault();
	    var id = $(this).attr('data-id');
	    var firstname = $(this).attr('data-firstname');
	    var surname = $(this).attr('data-surname');
	    var address = $(this).attr('data-address');
	    var zip_code = $(this).attr('data-zip-code');
	    var city = $(this).attr('data-city');
	    var phone = $(this).attr('data-phone');
	    var mobile = $(this).attr('data-mobile');
	    var email = $(this).attr('data-email');
	    var pajemploi = $(this).attr('data-pajemploi');
	    (id != '') ? $('#father_id').val(id) : $('#father_id').val('');
	    (surname != '') ? $('#father_surname').val(surname).prop('readonly', true) : $('#father_surname').val('').prop('readonly', false);
	    (firstname != '') ? $('#father_firstname').val(firstname).prop('readonly', true) : $('#father_firstname').val('').prop('readonly', false);
	    (address != '') ? $('#father_address').val(address).prop('readonly', true) : $('#father_address').val('').prop('readonly', false);
	    (zip_code != '') ? $('#father_zip_code').val(zip_code).prop('readonly', true) : $('#father_zip_code').val('').prop('readonly', false);
	    (city != '') ? $('#father_city').val(city).prop('readonly', true) : $('#father_city').val('').prop('readonly', false);
	    (phone != '') ? $('#father_phone_house').val(phone).prop('readonly', true) : $('#father_phone_house').val('').prop('readonly', false);
	    (mobile != '') ? $('#father_phone_mobile').val(mobile).prop('readonly', true) : $('#father_phone_mobile').val('').prop('readonly', false);
	    (email != '') ? $('#father_email').val(email).prop('readonly', true) : $('#father_email').val('').prop('readonly', false);
	    (pajemploi != '') ? (($('#urssaf_pajemploi_no').val() == '') ? $('#urssaf_pajemploi_no').val(pajemploi).prop('readonly', true) : $('#urssaf_pajemploi_no').val(pajemploi).prop('readonly', true)) : ($('#urssaf_pajemploi_no').val() == null ? $('#urssaf_pajemploi_no').val('').prop('readonly', false) : '');
	    $('#delete-father').removeClass('hidden');
		});
	});
}

function updateSalary(setFoc){
	var nbsVal = ($('#nb_semaines_acc_incomplet').val() != '' ? $('#nb_semaines_acc_incomplet').val() : '');

	var nbAMVal = ($('#nb_heures_acc_occasionnel').val() != '' ? $('#nb_heures_acc_occasionnel').val() : '');

	var salB = ($('#salaire_horaire_brut').val() != '' ? $('#salaire_horaire_brut').val() + '€' : '<span class="error">Taux horaire non renseigné</span>');

	var hW = ($('#nb_hour_weekly').val() != '' ? $('#nb_hour_weekly').val() + ' heures par semaine' : '<span class="error">Nombre d\'heures hebdomadaires non renseigné</span>');

	var nbS = ($('#nb_semaines_acc_incomplet').val() != '' ? '<input type="text" name="nb_semaines_acc_incomplet" id="nb_semaines_acc_incomplet" placeholder="Nb de semaines" title="Veuillez renseigner le nombre de semaine prévues dans l\'année." value="' + $('#nb_semaines_acc_incomplet').val() + '"/>' : '<input type="text" name="nb_semaines_acc_incomplet" id="nb_semaines_acc_incomplet" placeholder="Nb de semaines" title="Veuillez renseigner le nombre de semaine prévues dans l\'année."/>');

	var nbAM = ($('#nb_heures_acc_occasionnel').val() != '' ? '<input type="text" name="nb_heures_acc_occasionnel" id="nb_heures_acc_occasionnel" placeholder="Nb d\' heures" title="Veuillez renseigner le nombre d\'heures d\'accueil prévues dans le mois." value="' + $('#nb_heures_acc_occasionnel').val() + '"/>' : '<input type="text" name="nb_heures_acc_occasionnel" id="nb_heures_acc_occasionnel" placeholder="Nb d\' heures" title="Veuillez renseigner le nombre d\'heures d\'accueil prévues dans le mois."/>');


	var totAccCom = ($('#nb_hour_weekly').val() != '' && $('#salaire_horaire_brut').val() != '') ? (($('#salaire_horaire_brut').val() * $('#nb_hour_weekly').val() * 52 / 12).toFixed(2)) : '<span class="error" id="calcul-salaire-impossible">Calcul du salaire impossible</span>'; 

	var totAccIncom = ($('#nb_hour_weekly').val() != '' && $('#salaire_horaire_brut').val() != '' && $('#nb_semaines_acc_incomplet').val() != '' && $('#nb_semaines_acc_incomplet').val() != undefined) ? (($('#salaire_horaire_brut').val() * $('#nb_hour_weekly').val() * $('#nb_semaines_acc_incomplet').val() / 12).toFixed(2)) : '<span class="error" id="calcul-salaire-impossible">Calcul du salaire impossible</span>'; 

	var totAccOcc = ($('#salaire_horaire_brut').val() != '' && $('#nb_heures_acc_occasionnel').val() != '' && $('#nb_heures_acc_occasionnel').val() != undefined) ? (($('#salaire_horaire_brut').val() * $('#nb_heures_acc_occasionnel').val()).toFixed(2)) : '<span class="error" id="calcul-salaire-impossible">Calcul du salaire impossible</span>'; 

	var scr = '<script type="text/javascript">$(\'#nb_semaines_acc_incomplet, #nb_heures_acc_occasionnel\').keyup(function(){updateSalary(true);});';

	if(setFoc == true){
		var sF = '$(\'#nb_semaines_acc_incomplet\').focus().val(\'\').val(' + nbsVal + ');$(\'#nb_heures_acc_occasionnel\').focus().val(\'\').val(' + nbAMVal + ');</script>';
	}
	else{
		var sF = '$(\'#nb_semaines_acc_incomplet\').val(' + nbsVal + ');$(\'#nb_heures_acc_occasionnel\').val(' + nbAMVal + ');</script>';
	}

	var aC = salB + ' X ' + hW + ' X 52 / 12 = <strong id="salaire-mensu">' + totAccCom + '</strong>€';
	var aI = salB + ' X ' + hW + ' x ' + nbS + ' / 12 = <strong id="salaire-mensu">' + totAccIncom + '</strong>€' + scr + sF;
	var aO = salB + ' X ' + nbAM + ' = <strong id="salaire-mensu">' + totAccOcc + '</strong>€' + scr + sF;

	if($('input:radio[name="accueil_type"]:checked').val() == 'accueil_mens_complete'){
		$('#calcul-mensualisation').empty().append(aC);
		$('input[name="vacancy_payment_modality"').each(function(){
			$(this).prop('disabled', true);
			$(this).prop('checked', false);
		});
	}
	if($('input:radio[name="accueil_type"]:checked').val() == 'accueil_mens_incomplete'){
		$('#calcul-mensualisation').empty().append(aI);
		$('input[name="vacancy_payment_modality"').each(function(){
			$(this).prop('disabled', false);
		});
	}
	if($('input:radio[name="accueil_type"]:checked').val() == 'accueil_occasion'){
		$('#calcul-mensualisation').empty().append(aO);
		$('input[name="vacancy_payment_modality"').each(function(){
			$(this).prop('disabled', true);
			$(this).prop('checked', false);
		});
	}
}