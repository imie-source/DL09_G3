jQuery(function($) {

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
    url: '/users/ajaxProfileEditor',
    name: 'date_birth',
    pk: pkey,
    emptytext: 'Date de naissance non renseignée',
    viewformat: 'dd/mm/yyyy',
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

  $('#address').editable({
    type: 'text',
    url: '/users/ajaxProfileEditor',
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
  
  $('#zip_code').editable({
    type: 'text',
    url: '/users/ajaxProfileEditor',
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
  
  
  $('#city').editable({
    type: 'text',
    url: '/users/ajaxProfileEditor',
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
    url: '/users/ajaxProfileEditor',
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
    url: '/users/ajaxProfileEditor',
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
  
  $('#available').editable({
    type: 'select',
    value: $('#available').html(),
    source: [
    {
      value: 'y', 
      text: 'Vous êtes disponbiles pour les projets'
    },

    {
      value: 'n', 
      text: 'Vous n\'êtes pas disponibles pour les projets'
    }
    ],
    url: '/users/ajaxProfileEditor',
    name: 'available',
    pk: pkey,
    display: function(value) {
      if (value == 'y') {
        $(this).text('Vous êtes disponbiles pour les projets');
      }
      if (value == 'n') {
        $(this).text('Vous n\'êtes pas disponibles pour les projets');
      }
    },
    error: function(response, newValue) {
      $.gritter.add({
        title: 'Erreur',
        text: 'Une erreur s\'est produite lors de la mise à jour de votre paramètre de disponbilité. Veuillez recommencer.',
        class_name: 'gritter-error'
      });
    },
    success: function(response, newValue) {
      $.gritter.add({
        title: 'Confirmation',
        text: 'Votre paramètre de disponbilité a bien été mis à jour.',
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