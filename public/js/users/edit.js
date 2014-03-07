jQuery(function($) {
  $(function() {
    var cookie = $.cookie('nannyInformationInfoPerso');
    if(cookie){
      $('#div-infos-informations-perso').addClass('hidden');
    }
  });
  
		
  //cookie acceptation des infos sur les informations personnelles
  $('#infos-informations-perso').click(function(event) {
    event.preventDefault();
    $.cookie('nannyInformationInfoPerso', 'OK', {
      expires: 365,
      path: '/'
    });
    $(this).parent().fadeOut('slow');
  });

  //editables on first profile page
  $.fn.editable.defaults.mode = 'popup';
  $.fn.editableform.buttons = '<button type="submit" class="btn btn-info editable-submit"><i class="icon-ok icon-white"></i></button>'+
  '<button type="button" class="btn editable-cancel"><i class="icon-remove"></i></button>';    
  $.fn.datepicker.dates['fr'] = 
  {
    days: ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"],
    daysShort: ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim"],
    daysMin: ["Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa", "Di"],
    months: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aoüt", "Septembre", "Octobre", "Novembre", "Decembre"],
    monthsShort: ["Jan", "Fev", "Mar", "Avr", "Mai", "Jun", "Jui", "Aou", "Sep", "Oct", "Nov", "Dec"],
    today: "Aujourd'hui",
    clear: "Effacer"
  };

  //editables 
  $('#dateBirth').editable({
    type: 'date',
    url: '/users/ajaxAdminProfileEditor',
    name: 'date_birth',
    pk: pkey,
    emptytext: 'Date de naissance non renseignée',
    viewformat: 'dd-mm-yyyy',
    lang: 'fr',
    error: function(data) {
      $.gritter.add({
        title: 'Erreur',
        text: data,
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Votre date de naissance a bien été mise à jour.',
        class_name: 'gritter-success'
      });
    },
    validate: function(newValue) {
      if(newValue == '') {
        $.gritter.add({
          title: 'Erreur',
          text: 'Votre date de naissance est obligatoire.',
          class_name: 'gritter-error'
        });
        return 'Champ obligatoire';
      }
    }
  });

  $('#cityBirth').editable({
    type: 'text',
    url: '/users/ajaxAdminProfileEditor',
    name: 'city_birth',
    pk: pkey,
    emptytext: 'Lieu de naissance non renseignée',
    error: function(data) {
      $.gritter.add({
        title: 'Erreur',
        text: data,
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Votre lieu de naissance a bien été mis à jour.',
        class_name: 'gritter-success'
      });
    },
    validate: function(newValue) {
      if(newValue == '') {
        $.gritter.add({
          title: 'Erreur',
          text: 'Votre lieu de naissance est obligatoire.',
          class_name: 'gritter-error'
        });
        return 'Champ obligatoire';
      }
    }
  });

  $('#rc_address').editable({
    type: 'text',
    url: '/users/ajaxAdminProfileEditor',
    name: 'rc_address',
    pk: pkey,
    emptytext: 'Adresse non renseignée',
    error: function(data) {
      $.gritter.add({
        title: 'Erreur',
        text: data,
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'L\'adresse a bien été mise à jour.',
        class_name: 'gritter-success'
      });
    },
    validate: function(newValue) {
      if(newValue == '') {
        $.gritter.add({
          title: 'Erreur',
          text: 'L\'adresse est obligatoire.',
          class_name: 'gritter-error'
        });
        return 'Champ obligatoire';
      }
    }
  });

  $('#rc_validity').editable({
    type: 'date',
    url: '/users/ajaxAdminProfileEditor',
    name: 'rc_validity',
    pk: pkey,
    emptytext: 'Date de validité non renseignée',
    viewformat: 'dd-mm-yyyy',
    lang: 'fr',
    error: function(data) {
      $.gritter.add({
        title: 'Erreur',
        text: data,
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'La date de validité a bien été mise à jour.',
        class_name: 'gritter-success'
      });
    },
    validate: function(newValue) {
      if(newValue == '') {
        $.gritter.add({
          title: 'Erreur',
          text: 'La date de validité est obligatoire.',
          class_name: 'gritter-error'
        });
        return 'Champ obligatoire';
      }
    }
  });

  $('#rc_no').editable({
    type: 'text',
    url: '/users/ajaxAdminProfileEditor',
    name: 'rc_no',
    pk: pkey,
    emptytext: 'N° de police non renseigné',
    error: function(data) {
      $.gritter.add({
        title: 'Erreur',
        text: data,
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Le n° de police a bien été mis à jour.',
        class_name: 'gritter-success'
      });
    },
    validate: function(newValue) {
      if(newValue == '') {
        $.gritter.add({
          title: 'Erreur',
          text: 'Le n° de police est obligatoire.',
          class_name: 'gritter-error'
        });
        return 'Champ obligatoire';
      }
    }
  });

  $('#rc_city').editable({
    type: 'text',
    url: '/users/ajaxAdminProfileEditor',
    name: 'rc_city',
    pk: pkey,
    emptytext: 'Ville non renseignée',
    error: function(data) {
      $.gritter.add({
        title: 'Erreur',
        text: data,
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'La ville a bien été mise à jour.',
        class_name: 'gritter-success'
      });
    },
    validate: function(newValue) {
      if(newValue == '') {
        $.gritter.add({
          title: 'Erreur',
          text: 'La ville est obligatoire.',
          class_name: 'gritter-error'
        });
        return 'Champ obligatoire';
      }
    }
  });

  $('#rc_zip_code').on('shown', function(e, editable) {
    editable.input.$input.mask('99999');
  });
  $('#rc_zip_code').editable({
    type: 'text',
    url: '/users/ajaxAdminProfileEditor',
    name: 'rc_zip',
    pk: pkey,
    emptytext: 'Code postal non renseigné',
    error: function(data) {
      $.gritter.add({
        title: 'Erreur',
        text: data,
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Le code postal a bien été mis à jour.',
        class_name: 'gritter-success'
      });
    },
    validate: function(newValue) {
      if(newValue == '') {
        $.gritter.add({
          title: 'Erreur',
          text: 'Le code postal est obligatoire.',
          class_name: 'gritter-error'
        });
        return 'Champ obligatoire';
      }
    }
  });

  $('#rc_compagny').editable({
    type: 'text',
    url: '/users/ajaxAdminProfileEditor',
    name: 'rc_compagny',
    pk: pkey,
    emptytext: 'Compagnie d\'assurance responsabilité civile non renseignée',
    error: function(data) {
      $.gritter.add({
        title: 'Erreur',
        text: data,
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Votre compagnie d\'assurance a bien été mise à jour.',
        class_name: 'gritter-success'
      });
    },
    validate: function(newValue) {
      if(newValue == '') {
        $.gritter.add({
          title: 'Erreur',
          text: 'Votre compagnie d\'assurance est obligatoire.',
          class_name: 'gritter-error'
        });
        return 'Champ obligatoire';
      }
    }
  });

  $('#auto_address').editable({
    type: 'text',
    url: '/users/ajaxAdminProfileEditor',
    name: 'auto_address',
    pk: pkey,
    emptytext: 'Adresse non renseignée',
    error: function(data) {
      $.gritter.add({
        title: 'Erreur',
        text: data,
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'L\'adresse a bien été mise à jour.',
        class_name: 'gritter-success'
      });
    },
    validate: function(newValue) {
      if(newValue == '') {
        $.gritter.add({
          title: 'Erreur',
          text: 'L\'adresse est obligatoire.',
          class_name: 'gritter-error'
        });
        return 'Champ obligatoire';
      }
    }
  });

  $('#auto_validity').editable({
    type: 'date',
    url: '/users/ajaxAdminProfileEditor',
    name: 'auto_validity',
    pk: pkey,
    emptytext: 'Date de validité non renseignée',
    viewformat: 'dd-mm-yyyy',
    lang: 'fr',
    error: function(data) {
      $.gritter.add({
        title: 'Erreur',
        text: data,
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'La date de validité a bien été mise à jour.',
        class_name: 'gritter-success'
      });
    },
    validate: function(newValue) {
      if(newValue == '') {
        $.gritter.add({
          title: 'Erreur',
          text: 'La date de validité est obligatoire.',
          class_name: 'gritter-error'
        });
        return 'Champ obligatoire';
      }
    }
  });

  $('#auto_no').editable({
    type: 'text',
    url: '/users/ajaxAdminProfileEditor',
    name: 'auto_no',
    pk: pkey,
    emptytext: 'N° de police non renseigné',
    error: function(data) {
      $.gritter.add({
        title: 'Erreur',
        text: data,
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Le n° de police a bien été mis à jour.',
        class_name: 'gritter-success'
      });
    },
    validate: function(newValue) {
      if(newValue == '') {
        $.gritter.add({
          title: 'Erreur',
          text: 'Le n° de police est obligatoire.',
          class_name: 'gritter-error'
        });
        return 'Champ obligatoire';
      }
    }
  });

  $('#auto_city').editable({
    type: 'text',
    url: '/users/ajaxAdminProfileEditor',
    name: 'auto_city',
    pk: pkey,
    emptytext: 'Ville non renseignée',
    error: function(data) {
      $.gritter.add({
        title: 'Erreur',
        text: data,
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'La ville a bien été mise à jour.',
        class_name: 'gritter-success'
      });
    },
    validate: function(newValue) {
      if(newValue == '') {
        $.gritter.add({
          title: 'Erreur',
          text: 'La ville est obligatoire.',
          class_name: 'gritter-error'
        });
        return 'Champ obligatoire';
      }
    }
  });

  $('#auto_zip_code').on('shown', function(e, editable) {
    editable.input.$input.mask('99999');
  });
  $('#auto_zip_code').editable({
    type: 'text',
    url: '/users/ajaxAdminProfileEditor',
    name: 'auto_zip',
    pk: pkey,
    emptytext: 'Code postal non renseigné',
    error: function(data) {
      $.gritter.add({
        title: 'Erreur',
        text: data,
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Le code postal a bien été mis à jour.',
        class_name: 'gritter-success'
      });
    },
    validate: function(newValue) {
      if(newValue == '') {
        $.gritter.add({
          title: 'Erreur',
          text: 'Le code postal est obligatoire.',
          class_name: 'gritter-error'
        });
        return 'Champ obligatoire';
      }
    }
  });

  $('#auto_compagny').editable({
    type: 'text',
    url: '/users/ajaxAdminProfileEditor',
    name: 'auto_compagny',
    pk: pkey,
    emptytext: 'Compagnie d\'assurance responsabilité civile non renseignée',
    error: function(data) {
      $.gritter.add({
        title: 'Erreur',
        text: data,
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Votre compagnie d\'assurance a bien été mise à jour.',
        class_name: 'gritter-success'
      });
    },
    validate: function(newValue) {
      if(newValue == '') {
        $.gritter.add({
          title: 'Erreur',
          text: 'Votre compagnie d\'assurance est obligatoire.',
          class_name: 'gritter-error'
        });
        return 'Champ obligatoire';
      }
    }
  });

  $('#address').editable({
    type: 'text',
    url: '/users/ajaxAdminProfileEditor',
    name: 'address',
    pk: pkey,
    emptytext: 'Adresse non renseignée',
    error: function(data) {
      $.gritter.add({
        title: 'Erreur',
        text: data,
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Votre adresse a bien été mise à jour.',
        class_name: 'gritter-success'
      });
    },
    validate: function(newValue) {
      if(newValue == '') {
        $.gritter.add({
          title: 'Erreur',
          text: 'Votre adresse est obligatoire.',
          class_name: 'gritter-error'
        });
        return 'Champ obligatoire';
      }
    }
  });

  $('#pajemploi').editable({
    type: 'text',
    url: '/users/ajaxAdminProfileEditor',
    name: 'nopajemploi',
    pk: pkey,
    emptytext: 'N° Pajemploi non renseigné',
    error: function(data) {
      $.gritter.add({
        title: 'Erreur',
        text: data,
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Votre N° Pajemploi a bien été mis à jour.',
        class_name: 'gritter-success'
      });
    },
    validate: function(newValue) {
      if(newValue == '') {
        $.gritter.add({
          title: 'Erreur',
          text: 'Votre N° Pajemploi est obligatoire.',
          class_name: 'gritter-error'
        });
        return 'Champ obligatoire';
      }
    }
  });

  $('#tauxHoraire').editable({
    type: 'text',
    url: '/users/ajaxAdminProfileEditor',
    name: 'taux_horaire',
    pk: pkey,
    emptytext: 'Taux horaire non renseigné',
    display: function(value) {
      if (value != '') {$(this).text(value + ' €');};
    },
    error: function(data) {
      $.gritter.add({
        title: 'Erreur',
        text: data,
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Votre taux horaire a bien été mis à jour.',
        class_name: 'gritter-success'
      });
    },
    validate: function(newValue) {
      if(newValue == '') {
        $.gritter.add({
          title: 'Erreur',
          text: 'Votre taux horaire est obligatoire.',
          class_name: 'gritter-error'
        });
        return 'Champ obligatoire';
      }
    }
  });

  $('#indemniteEntretien').editable({
    type: 'text',
    url: '/users/ajaxAdminProfileEditor',
    name: 'indemnite_entretien',
    pk: pkey,
    emptytext: 'Indemnité d\'entretien non renseignée',
    display: function(value) {
      if (value != '') {$(this).text(value + ' €');};
    },
    error: function(data) {
      $.gritter.add({
        title: 'Erreur',
        text: data,
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Votre indemnité d\'entretien a bien été mise à jour.',
        class_name: 'gritter-success'
      });
    },
    validate: function(newValue) {
      if(newValue == '') {
        $.gritter.add({
          title: 'Erreur',
          text: 'Votre indemnité d\'entretien est obligatoire.',
          class_name: 'gritter-error'
        });
        return 'Champ obligatoire';
      }
    }
  });

  $('#fraisPetitDejeuner').editable({
    type: 'text',
    url: '/users/ajaxAdminProfileEditor',
    name: 'frais_petit_dejeuner',
    pk: pkey,
    emptytext: 'Frais de petit déjeuner non renseignés',
    display: function(value) {
      if (value != '') {$(this).text(value + ' €');};
    },
    error: function(data) {
      $.gritter.add({
        title: 'Erreur',
        text: data,
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Vos frais de petit déjeuner ont bien été mis à jour.',
        class_name: 'gritter-success'
      });
    },
    validate: function(newValue) {
      if(newValue == '') {
        $.gritter.add({
          title: 'Erreur',
          text: 'Vos frais de petit déjeuner sont obligatoires.',
          class_name: 'gritter-error'
        });
        return 'Champ obligatoire';
      }
    }
  });

  $('#fraisDejeuner').editable({
    type: 'text',
    url: '/users/ajaxAdminProfileEditor',
    name: 'frais_dejeuner',
    pk: pkey,
    emptytext: 'Frais de déjeuner non renseignés',
    display: function(value) {
      if (value != '') {$(this).text(value + ' €');};
    },
    error: function(data) {
      $.gritter.add({
        title: 'Erreur',
        text: data,
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Vos frais de repas ont bien été mis à jour.',
        class_name: 'gritter-success'
      });
    },
    validate: function(newValue) {
      if(newValue == '') {
        $.gritter.add({
          title: 'Erreur',
          text: 'Vos frais de repas sont obligatoires.',
          class_name: 'gritter-error'
        });
        return 'Champ obligatoire';
      }
    }
  });

  $('#fraisGouter').editable({
    type: 'text',
    url: '/users/ajaxAdminProfileEditor',
    name: 'frais_gouter',
    pk: pkey,
    emptytext: 'Frais de gouter non renseignés',
    display: function(value) {
      if (value != '') {$(this).text(value + ' €');};
    },
    error: function(data) {
      $.gritter.add({
        title: 'Erreur',
        text: data,
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Vos frais de gouter ont bien été mis à jour.',
        class_name: 'gritter-success'
      });
    },
    validate: function(newValue) {
      if(newValue == '') {
        $.gritter.add({
          title: 'Erreur',
          text: 'Vos frais de gouter sont obligatoires.',
          class_name: 'gritter-error'
        });
        return 'Champ obligatoire';
      }
    }
  });

  $('#fraisDiner').editable({
    type: 'text',
    url: '/users/ajaxAdminProfileEditor',
    name: 'frais_diner',
    pk: pkey,
    emptytext: 'Frais de diner non renseignés',
    display: function(value) {
      if (value != '') {$(this).text(value + ' €');};
    },
    error: function(data) {
      $.gritter.add({
        title: 'Erreur',
        text: data,
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Vos frais de diner ont bien été mis à jour.',
        class_name: 'gritter-success'
      });
    },
    validate: function(newValue) {
      if(newValue == '') {
        $.gritter.add({
          title: 'Erreur',
          text: 'Vos frais de diner sont obligatoires.',
          class_name: 'gritter-error'
        });
        return 'Champ obligatoire';
      }
    }
  });
  
  $('#ssociale').on('shown', function(e, editable) {
    editable.input.$input.mask('9 99 99 99 999 999 99');
  });
  $('#ssociale').editable({
    type: 'text',
    url: '/users/ajaxAdminProfileEditor',
    name: 'nossociale',
    pk: pkey,
    emptytext: 'N° de sécurité sociale non renseigné',
    error: function(data) {
      $.gritter.add({
        title: 'Erreur',
        text: data,
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Votre N° de sécurité sociale a bien été mis à jour.',
        class_name: 'gritter-success'
      });
    },
    validate: function(newValue) {
      if(newValue == '') {
        $.gritter.add({
          title: 'Erreur',
          text: 'Votre N° de sécurité sociale est obligatoire.',
          class_name: 'gritter-error'
        });
        return 'Champ obligatoire';
      }
    }
  });
  
  $('#zip_code').editable({
    type: 'text',
    url: '/users/ajaxAdminProfileEditor',
    name: 'zipcode',
    pk: pkey,
    emptytext: 'Code postal non renseigné',
    error: function(response, newValue) {
      $.gritter.add({
        title: 'Erreur',
        text: 'Une erreur s\'est produite lors de la mise à jour de votre code postal. Veuillez recommencer.',
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Votre code postal a bien été mis à jour.',
        class_name: 'gritter-success'
      });
    },
    validate: function(newValue) {
      if(newValue == '') {
        $.gritter.add({
          title: 'Erreur',
          text: 'Votre code-postal est obligatoire.',
          class_name: 'gritter-error'
        });
        return 'Champ obligatoire';
      }
    }
  });
  
  $('#email').editable({
    type: 'text',
    url: '/users/ajaxAdminProfileEditor',
    name: 'email',
    pk: pkey,
    emptytext: 'Ville non renseignée',
    error: function(response, newValue) {
      $.gritter.add({
        title: 'Erreur',
        text: 'Une erreur s\'est produite lors de la mise à jour de votre email. Veuillez recommencer.',
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Votre email a bien été mise à jour.',
        class_name: 'gritter-success'
      });
    },
    validate: function(newValue) {
      if(newValue == '') {
        $.gritter.add({
          title: 'Erreur',
          text: 'Votre email est obligatoire.',
          class_name: 'gritter-error'
        });
        return 'Champ obligatoire';
      }
    }
  });
  
  $('#city').editable({
    type: 'text',
    url: '/users/ajaxAdminProfileEditor',
    name: 'city',
    pk: pkey,
    emptytext: 'Ville non renseignée',
    error: function(response, newValue) {
      $.gritter.add({
        title: 'Erreur',
        text: 'Une erreur s\'est produite lors de la mise à jour de votre ville. Veuillez recommencer.',
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Votre ville a bien été mise à jour.',
        class_name: 'gritter-success'
      });
    },
    validate: function(newValue) {
      if(newValue == '') {
        $.gritter.add({
          title: 'Erreur',
          text: 'Votre ville est obligatoire.',
          class_name: 'gritter-error'
        });
        return 'Champ obligatoire';
      }
    }
  });
  
  $('#mobile').on('shown', function(e, editable) {
    editable.input.$input.mask('99 99 99 99 99');
  });
  $('#mobile').editable({
    type: 'text',
    url: '/users/ajaxAdminProfileEditor',
    name: 'mobile',
    pk: pkey,
    emptytext: 'Mobile non renseigné',
    error: function(response, newValue) {
      $.gritter.add({
        title: 'Erreur',
        text: 'Une erreur s\'est produite lors de la mise à jour de votre numéro de mobile. Veuillez recommencer.',
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Votre numéro de mobile a bien été mis à jour.',
        class_name: 'gritter-success'
      });
    },
    validate: function(newValue) {
      if(newValue == '') {
        $.gritter.add({
          title: 'Erreur',
          text: 'Votre numéro de mobile est obligatoire.',
          class_name: 'gritter-error'
        });
        return 'Champ obligatoire';
      }
    }
  });
  
  $('#phone').on('shown', function(e, editable) {
    editable.input.$input.mask('99 99 99 99 99');
  });
  $('#phone').editable({
    type: 'text',
    url: '/users/ajaxAdminProfileEditor',
    name: 'phone',
    pk: pkey,
    emptytext: 'Téléphone non renseigné',
    error: function(response, newValue) {
      $.gritter.add({
        title: 'Erreur',
        text: 'Une erreur s\'est produite lors de la mise à jour de votre numéro de téléphone. Veuillez recommencer.',
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Votre numéro de téléphone a bien été mis à jour.',
        class_name: 'gritter-success'
      });
    },
    validate: function(newValue) {
      if(newValue == '') {
        $.gritter.add({
          title: 'Erreur',
          text: 'Votre numéro de téléphone est obligatoire.',
          class_name: 'gritter-error'
        });
        return 'Champ obligatoire';
      }
    }
  });
  
  $('#visibility').editable({
    type: 'select',
    value: $('#visibility').html(),
    source: [
    {
      value: 'Y', 
      text: 'Vos informations personnelles ne sont visibles que par vous'
    },

    {
      value: 'N', 
      text: 'Vos informations personnelles sont visibles par tous'
    }
    ],
    url: '/users/ajaxAdminProfileEditor',
    name: 'private_informations',
    pk: pkey,
    display: function(value) {
      if (value == 'Y') {
        $(this).text('Vos informations personnelles ne sont visibles que par vous');
      }
      if (value == 'N') {
        $(this).text('Vos informations personnelles sont visibles par tous');
      }
    },
    error: function(response, newValue) {
      $.gritter.add({
        title: 'Erreur',
        text: 'Une erreur s\'est produite lors de la mise à jour de vos paramètres de confidentialité. Veuillez recommencer.',
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Vos paramètres de confidentialité ont bien été mis à jour.',
        class_name: 'gritter-success'
      });
    }
  });

  $('#active').editable({
    type: 'select',
    value: $('#active').html(),
    source: [
    {
      value: 'Y', 
      text: 'Oui'
    },

    {
      value: 'N', 
      text: 'Non'
    }
    ],
    url: '/users/ajaxAdminProfileEditor',
    name: 'active',
    pk: pkey,
    display: function(value) {
      if (value == 'Y') {
        $(this).text('Oui');
      }
      if (value == 'N') {
        $(this).text('Non');
      }
    },
    error: function(response, newValue) {
      $.gritter.add({
        title: 'Erreur',
        text: 'Une erreur s\'est produite lors de la mise à jour des paramètres du compte. Veuillez recommencer.',
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Les paramètres de confidentialité ont bien été mis à jour.',
        class_name: 'gritter-success'
      });
    }
  });

  $('#suspended').editable({
    type: 'select',
    value: $('#suspended').html(),
    source: [
    {
      value: 'Y', 
      text: 'Oui'
    },

    {
      value: 'N', 
      text: 'Non'
    }
    ],
    url: '/users/ajaxAdminProfileEditor',
    name: 'suspended',
    pk: pkey,
    display: function(value) {
      if (value == 'Y') {
        $(this).text('Oui');
      }
      if (value == 'N') {
        $(this).text('Non');
      }
    },
    error: function(response, newValue) {
      $.gritter.add({
        title: 'Erreur',
        text: 'Une erreur s\'est produite lors de la mise à jour des paramètres du compte. Veuillez recommencer.',
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Les paramètres de confidentialité ont bien été mis à jour.',
        class_name: 'gritter-success'
      });
    }
  });

  $('#banned').editable({
    type: 'select',
    value: $('#banned').html(),
    source: [
    {
      value: 'Y', 
      text: 'Oui'
    },

    {
      value: 'N', 
      text: 'Non'
    }
    ],
    url: '/users/ajaxAdminProfileEditor',
    name: 'banned',
    pk: pkey,
    display: function(value) {
      if (value == 'Y') {
        $(this).text('Oui');
      }
      if (value == 'N') {
        $(this).text('Non');
      }
    },
    error: function(response, newValue) {
      $.gritter.add({
        title: 'Erreur',
        text: 'Une erreur s\'est produite lors de la mise à jour des paramètres du compte. Veuillez recommencer.',
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Les paramètres de confidentialité ont bien été mis à jour.',
        class_name: 'gritter-success'
      });
    }
  });
  
  $('#notifParam').editable({
    type: 'select',
    value: $('#notifParam').html(),
    source: [
    {
      value: 'Y', 
      text: 'Vous recevez toutes les notifications par email'
    },

    {
      value: 'N', 
      text: 'Vous ne recevez pas les notifications par email'
    }
    ],
    url: '/users/ajaxAdminProfileEditor',
    name: 'email_notification',
    pk: pkey,
    display: function(value) {
      if (value == 'Y') {
        $(this).text('Vous recevez toutes les notifications par email');
      }
      if (value == 'N') {
        $(this).text('Vous ne recevez pas les notifications par email');
      }
    },
    error: function(response, newValue) {
      $.gritter.add({
        title: 'Erreur',
        text: 'Une erreur s\'est produite lors de la mise à jour de vos paramètres de notification. Veuillez recommencer.',
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Vos paramètres de notification ont bien été mis à jour.',
        class_name: 'gritter-success'
      });
    }
  });
  
  /*
  $('#profile-feed-1').slimScroll({
    });
  $(document).on('click', '#deleteRecommandation', function(){
    var dataid = $(this).attr('data-id');
    $.ajax({
      type: 'POST',
      url: "/usersnannyrecommandations/ajaxCommentsRemover",
      data: {
        'id' : dataid
      }
    })
    .fail(function() {
      $.gritter.add({
        title: 'Erreur',
        text: 'Le commentaire n\'a pas pu être supprimé. Veuillez recommencer.',
        class_name: 'gritter-error'
      });
    })
    .done(function() {
      var count = $('#count-comments').html();
      $('#count-comments').html(count - 1);
      if($('#count-comments').html() == 1){
        $('#s-comments').remove();
      }
      $('#comment-id-'+dataid).slideUp();
      $.gritter.add({
        title: 'Confirmation',
        text: 'Le commentaire a bien été supprimé.',
        class_name: 'gritter-success'
      });
    });
  });
  */
  /*
  $(document).on('click', '#acceptRecommandation', function(){
    var dataid = $(this).attr('data-id');
    $.ajax({
      type: 'POST',
      url: "/usersnannyrecommandations/ajaxCommentsAccepter",
      data: {
        'id' : dataid
      }
    })
    .fail(function() {
      $.gritter.add({
        title: 'Erreur',
        text: 'Le commentaire n\'a pas pu être accepté. Veuillez recommencer.',
        class_name: 'gritter-error'
      });
    })
    .done(function() {
      $('#label-comment-'+dataid).removeClass('label-danger');
      $('#label-comment-'+dataid).addClass('label-success');
      $('#label-comment-'+dataid).html('Commentaire validé');
      $('.iconClass-accept-'+dataid).remove();
      $.gritter.add({
        title: 'Confirmation',
        text: 'Le commentaire a bien été accepté.',
        class_name: 'gritter-success'
      });
    });
  });
  */
  
  $('#agreementFirst').editable({
    type: 'date',
    format: 'yyyy-mm-dd',    
    viewformat: 'dd-mm-yyyy',    
    datepicker: {
      weekStart: 1
    },
    url: '/users/ajaxAdminProfileEditor',
    name: 'agreement_start_date',
    pk: pkey,
    emptytext: 'Date d\'agrément non renseignée',
    error: function(response, newValue) {
      $.gritter.add({
        title: 'Erreur',
        text: 'Une erreur s\'est produite lors de la mise à jour de votre agrément. Veuillez recommencer.',
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Votre agrément a bien été mis à jour.',
        class_name: 'gritter-success'
      });
    }
  });
  
  
  $('#agreementRenew').editable({
    type: 'date',
    format: 'yyyy-mm-dd',    
    viewformat: 'dd-mm-yyyy',    
    datepicker: {
      weekStart: 1
    },
    url: '/users/ajaxAdminProfileEditor',
    name: 'agreement_renew_date',
    pk: pkey,
    emptytext: 'Date d\'agrément non renseignée',
    error: function(response, newValue) {
      $.gritter.add({
        title: 'Erreur',
        text: 'Une erreur s\'est produite lors de la mise à jour de votre agrément. Veuillez recommencer.',
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Votre agrément a bien été mis à jour.',
        class_name: 'gritter-success'
      });
    }
  });
  
  
  $('#agreementNumber').editable({
    type: 'text',
    url: '/users/ajaxAdminProfileEditor',
    name: 'agreement_id_number',
    pk: pkey,
    emptytext: 'Numéro d\'agrément non renseigné',
    error: function(response, newValue) {
      $.gritter.add({
        title: 'Erreur',
        text: 'Une erreur s\'est produite lors de la mise à jour de votre numéro d\'agrément. Veuillez recommencer.',
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Votre numéro d\'agrément a bien été mis à jour.',
        class_name: 'gritter-success'
      });
    }
  });
  
  
  $('#agreementChildren').editable({
    type: 'textarea',
    escape: false,
    emptytext: 'Nombre d\'enfants autorisés non renseigné',
    url: '/users/ajaxAdminProfileEditor',
    name: 'agreement_children_number',
    pk: pkey,
    error: function(response, newValue) {
      $.gritter.add({
        title: 'Erreur',
        text: 'Une erreur s\'est produite lors de la mise à jour des paramètres de votre agrément. Veuillez recommencer.',
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Les paramètres de votre agrément ont bien été mis à jour.',
        class_name: 'gritter-success'
      });
    }
  });
  
  $('#annuaireVisibility').editable({
    type: 'select',
    value: $('#annuaireVisibility').html(),
    source: [
    {
      value: 'Y', 
      text: 'Je souhaite apparaître automatiquement dans l\'annuaire'
    },

    {
      value: 'N', 
      text: 'Je ne souhaite pas apparaître dans l\'annuaire'
    }
    ],
    url: '/users/ajaxAdminProfileEditor',
    name: 'directory_visible',
    pk: pkey,
    display: function(value) {
      if (value == 'Y') {
        $(this).text('Je souhaite apparaître automatiquement dans l\'annuaire');
      }
      if (value == 'N') {
        $(this).text('Je ne souhaite pas apparaître dans l\'annuaire');
      }
    },
    error: function(response, newValue) {
      $.gritter.add({
        title: 'Erreur',
        text: 'Une erreur s\'est produite lors de la mise à jour de vos paramètres de l\'annuaire Nannyster. Veuillez recommencer.',
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Vos paramètres de l\'annuaire Nannyster ont bien été mis à jour.',
        class_name: 'gritter-success'
      });
    }
  });
  
  $('#annuaireInfo').editable({
    type: 'select',
    value: $('#annuaireInfo').html(),
    source: [
    {
      value: 'Y', 
      text: 'Vos coordonnées sont visibles dans l\'annuaire'
    },

    {
      value: 'N', 
      text: 'Vos coordonnées ne sont pas visibles dans l\'annuaire'
    }
    ],
    url: '/users/ajaxAdminProfileEditor',
    name: 'directory_private_informations',
    pk: pkey,
    display: function(value) {
      if (value == 'Y') {
        $(this).text('Vos coordonnées sont visibles dans l\'annuaire');
      }
      if (value == 'N') {
        $(this).text('Vos coordonnées ne sont pas visibles dans l\'annuaire');
      }
    },
    error: function(response, newValue) {
      $.gritter.add({
        title: 'Erreur',
        text: 'Une erreur s\'est produite lors de la mise à jour de vos paramètres de l\'annuaire Nannyster. Veuillez recommencer.',
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Vos paramètres de l\'annuaire Nannyster ont bien été mis à jour.',
        class_name: 'gritter-success'
      });
    }
  });
    
  var uploader = new plupload.Uploader({
    runtimes : 'html5,flash',
    container : 'plupload',
    browse_button : 'browse',
    drop_element : 'droparea',
    url : '/users/ajaxImageEditor',
    flash_swf_url : 'js/plupload/plupload.flash.swf',
    multipart : true,
    urlstream_upload : true,
    max_file_size:'10mb',
    filters : [
    {
      title : 'Images', 
      extensions : 'jpg,png,gif,JPG,PNG,GIF,jpeg,JPEG'
    }
    ]
  });

  uploader.init();

  uploader.bind('FilesAdded',function(up,files){
    $('#progressbar').removeClass('hidden');
    uploader.start();
    uploader.refresh();
  });

  uploader.bind('Error',function(up,err){
    $.gritter.add({
      title: 'Erreur',
      text: 'Votre fichier ne doit pas excéder 5MB et doit être au format JPG, JPEG, PNG ou GIF.',
      class_name: 'gritter-error'
    });
    $('#progressbar').addClass('hidden');
    uploader.refresh();
    
  });

  uploader.bind('FileUploaded',function(up, file, response){
    data = $.parseJSON(response.response);
    if(data.error){
      $.gritter.add({
        title: 'Erreur',
        text: data.message,
        class_name: 'gritter-error'
      });
      $('#progressbar').addClass('hidden');      
      $('#droparea').empty();
      $('#droparea').html('<img src="/img/avatars/' + data.oldImage + '" class="img-responsive" id="avatar" alt="">');
    }else{
      $.gritter.add({
        title: 'Confirmation',
        text: data.message,
        class_name: 'gritter-success'
      });
      $('#progressbar').addClass('hidden');
      $('#droparea').empty();
      $('#droparea').html('<img src="/img/avatars/' + data.newImage + '" class="img-responsive" id="avatar" alt="">');
      $('.nav-user-photo').attr('src', '/img/avatars/thumbs/' + data.newImage);
    }
  });

  uploader.bind('UploadProgress',function(up, file){
    $('#progressbar-inner').css('width',file.percent+'%');
    $('#progressbar').attr('data-percent',file.percent+'%');
  });    
});