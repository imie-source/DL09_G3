jQuery(function($) {
  
  //Message remover
  $('.message-content').each(function(){
    $(this).on('click', '#message-deleter', function(event){
      event.preventDefault();
      var thisMessage = $(this);
      bootbox.confirm("Etes-vous sûr de vouloir supprimer cette conversation?", function(result) {
        if(result) {
          $('.message-container').append('<div class="message-loading-overlay"><i class="icon-spin icon-spinner orange2 bigger-160"></i></div>');
          idRemoved = thisMessage.attr('data-id');
          $.ajax({
            url: '/mailbox/ajaxMessageDeleter',
            type: 'POST',
            data: {
              id: idRemoved
            }
          })
          .done(function(data){
              var nbMail = ($('#nb-message-all').html() - 1);
              $('#nb-message-all').html(nbMail);
              if(nbMail <= 1){
                $('#nb-message-all-s').remove();
              }
              if(nbMail == 0){
                $('.message-list').append('<div class="no-message">Aucun message</div>');
              }
              $('.message-loading-overlay').remove();
              $('.message-item[data-id="' + idRemoved + '"]').remove();
              $.gritter.add({
                title: 'Confirmation',
                text: 'Le message a bien été supprimé.',
                class_name: 'gritter-success'
              });
              Inbox.show_list();
          })
          .fail(function(){
            $('.message-loading-overlay').remove();
            $.gritter.add({
              title: 'Erreur',
              text: 'Une erreur est survenue lors de la suppression du message. Merci de recommencer ultérieurement.',
              class_name: 'gritter-error'
            });
          });             
        }
      });
    });
  });

  //Users list for a new conversation
  var timer;
  $('#form-field-user').on('keydown', function() {
    if($('#form-field-user').val() != ''){
      $('#loading-users-results').removeClass('hidden');
      var valueToExclude = {
        "id":[{}]
      };
      $('input[type="hidden"]').each(function(){
        valueToExclude.id.push($(this).attr('data-id'));
      });
      clearTimeout(timer);
      timer = setTimeout(function() {
        if($('#form-field-user').val() != ''){
          $.ajax({
            url: '/users/ajaxUsersForNewMail',
            type: 'POST',
            data: {
              'q': $('#form-field-user').val(),
              'e': valueToExclude
            }
          })
          .done(function(data) {
            $('#loading-users-results').addClass('hidden');
            $('#user-find-results').removeClass('hidden');
            $('#user-find-results').html(data);
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
          $('#user-find-results').empty();
          $('#user-find-results').addClass('hidden');
        }
      }, 700);
    }
  });

  //Focusout recherche de destinataires mail
  $('#form-field-user').on('focusout', function() {
    setTimeout(function() {
      $('#user-find-results').addClass('hidden');
      $('#loading-users-results').addClass('hidden');
    }, 250);
  });

  //Focusin recherche de destinataires mail
  $('#form-field-user').on('focusin', function() {
    if (this.value) {
      $('#user-find-results').removeClass('hidden');
    }
  });

  //Multiple suppression de messages
  $('#multiple-remove').on('click', function(event){
    event.preventDefault();
    var datas = [];
    $('.message-item.selected').each(function(){
      datas.push($(this).attr('data-id'));
    });
    var question = '';
    if(datas.length > 1){
      question = "Etes-vous sûr de vouloir supprimer ces conversations?";
    }
    else{
      question = "Etes-vous sûr de vouloir supprimer cette conversation?";
    }

    bootbox.confirm(question, function(result) {
    if(result) {
      $('.message-container').append('<div class="message-loading-overlay"><i class="icon-spin icon-spinner orange2 bigger-160"></i></div>');
      $.ajax({
        url: '/mailbox/ajaxMutipleRemover',
        type: 'POST',
        data: {
          'conversations': datas
        }
      })
      .done(function(data){
        var i;
        for(key in datas){
          $('.message-item[data-id="' + datas[key] + '"]').remove();
        }
        var titre;
        var message;
        if(datas.length == 1){
          titre = 'Message supprimé';
          message = 'Votre message a bien été supprimé.';
        }
        else{
          titre = 'Messages supprimés';
          message = 'Vos messages ont bien été supprimés.';
        }
        var nbMail = ($('#nb-message-all').html() - datas.length);
        $('#nb-message-all').html(nbMail);
        $('#badge-nb-mail').html(nbMail);
        if(nbMail <= 1){
          $('#nb-message-all-s').remove();
        }
        if(nbMail == 0){
          $('.message-list').append('<div class="no-message">Aucun message</div>');
          $('#badge-nb-mail').addClass('hidden');
        }
        $('.message-loading-overlay').remove();
        $.gritter.add({
          title: titre,
          text: message,
          class_name: 'gritter-success'
        });
      })
      .fail(function(){
        var titre;
        var titre;
        var message;
        if(datas['conversations'].length == 1){
          titre = 'Message non supprimé';
          message = 'Votre message n\'a pas pu être supprimé.';
        }
        else{
          titre = 'Messages supprimés partiellement';
          message = 'Vos messages n\'ont pu être supprimés que partiellement.';
        }
        $('.message-loading-overlay').remove();
        $.gritter.add({
          title: titre,
          text: message,
          class_name: 'gritter-error'
        });        
      })
    }
  });
  });
  
  //l'ajout de destinataires est traité après jQuery();
  
  //Traitement d'envoi du message
  $('#send-new-mail').on('click', function(event){
    event.preventDefault();
    $('.message-container').append('<div class="message-loading-overlay"><i class="icon-spin icon-spinner orange2 bigger-160"></i></div>');
    var datas = {
      "to": [{}],
      "subject": [{}],
      "message": [{}]
    };
    $('input[type="hidden"]').each(function(){
      datas.to.push($(this).attr('value'));
    });
    datas.subject.push($('#form-field-subject').val());
    datas.message.push($('.wysiwyg-editor').html());
      
    $.ajax({
      url: '/mailbox/ajaxSendNewMail',
      type: 'POST',
      data: datas
    })
    .done(function(data) {
      var nbMail = parseInt($('#nb-message-all').html());
      if(nbMail == 0){
        $('.message-list').empty();
      }
      $('#nb-message-all').html( nbMail + 1);
      if(parseInt($('#nb-message-all').html()) > 1){
        $('#nb-message-all-s').html('s');
      }
      $('#message-list').prepend(data);
      $('.li-new-mail').removeClass('active');
      $('#conversations-tab').addClass('active');
      $('#chosen-choices, #hidden-users').empty();
      $('.message-loading-overlay').remove();
      $.gritter.add({
        title: 'Message envoyé',
        text: 'Votre message a bien été envoyé.',
        class_name: 'gritter-success'
      });
      
      Inbox.show_list();
    })
    .fail(function() {
      $('.message-loading-overlay').remove();
      $.gritter.add({
        title: 'Erreur',
        text: 'Une erreur est survenue lors de l\'envoi de votre message. Merci de recommencer ultérieurement.',
        class_name: 'gritter-error'
      });
    })
  });

  //handling tabs and loading/displaying relevant messages and forms
  //not needed if using the alternative view, as described in docs
  var prevTab = 'inbox';
  $('#inbox-tabs a[data-toggle="tab"]').on('show.bs.tab', function(e) {
    var currentTab = $(e.target).data('target');
    if (currentTab == 'write') {
      Inbox.show_form();
    }
    else {
      if (prevTab == 'write')
        Inbox.show_list();

    //load and display the relevant messages 
    }
    prevTab = currentTab;
  });



  //basic initializations
  $('.message-list .message-item input[type=checkbox]').removeAttr('checked');
  $('.message-list').delegate('.message-item input[type=checkbox]', 'click', function() {
    $(this).closest('.message-item').toggleClass('selected');
    if (this.checked)
      Inbox.display_bar(1);//display action toolbar when a message is selected
    else {
      Inbox.display_bar($('.message-list input[type=checkbox]:checked').length);
    //determine number of selected messages and display/hide action toolbar accordingly
    }
  });


  //check/uncheck all messages
  $('#id-toggle-all').removeAttr('checked').on('click', function() {
    if (this.checked) {
      Inbox.select_all();
    } else
      Inbox.select_none();
  });

  //select all
  $('#id-select-message-all').on('click', function(e) {
    e.preventDefault();
    Inbox.select_all();
  });

  //select none
  $('#id-select-message-none').on('click', function(e) {
    e.preventDefault();
    Inbox.select_none();
  });

  //select read
  $('#id-select-message-read').on('click', function(e) {
    e.preventDefault();
    Inbox.select_read();
  });

  //select unread
  $('#id-select-message-unread').on('click', function(e) {
    e.preventDefault();
    Inbox.select_unread();
  });

  /////////



  //display second message right inside the message list
  /*$('.message-list .message-item:eq(1) .text').on('click', function() {
    var message = $(this).closest('.message-item');

    //if message is open, then close it
    if (message.hasClass('message-inline-open')) {
      message.removeClass('message-inline-open').find('.message-content').remove();
      return;
    }

    $('.message-container').append('<div class="message-loading-overlay"><i class="icon-spin icon-spinner orange2 bigger-160"></i></div>');
    setTimeout(function() {
      $('.message-container').find('.message-loading-overlay').remove();
      message
      .addClass('message-inline-open')
      .append('<div class="message-content" />')
      var content = message.find('.message-content:last').html($('#id-message-content').html());

      content.find('.message-body').slimScroll({
        height: 200,
        railVisible: true
      });

    }, 500 + parseInt(Math.random() * 500));

  });
*/


  //back to message list
  $('.btn-back-message-list').on('click', function(e) {
    e.preventDefault();
    Inbox.show_list();
    $('#inbox-tabs a[data-target="inbox"]').tab('show');
  });



  //hide message list and display new message form
  /**
     $('.btn-new-mail').on('click', function(e){
     e.preventDefault();
     Inbox.show_form();
     });
     */




  var Inbox = {
    //displays a toolbar according to the number of selected messages
    display_bar: function(count) {
      if (count == 0) {
        $('#id-toggle-all').removeAttr('checked');
        $('#id-message-list-navbar .message-toolbar').addClass('hide');
        $('#id-message-list-navbar .message-infobar').removeClass('hide');
      }
      else {
        $('#id-message-list-navbar .message-infobar').addClass('hide');
        $('#id-message-list-navbar .message-toolbar').removeClass('hide');
      }
    }
    ,
    select_all: function() {
      var count = 0;
      $('.message-item input[type=checkbox]').each(function() {
        this.checked = true;
        $(this).closest('.message-item').addClass('selected');
        count++;
      });

      $('#id-toggle-all').get(0).checked = true;

      Inbox.display_bar(count);
    }
    ,
    select_none: function() {
      $('.message-item input[type=checkbox]').removeAttr('checked').closest('.message-item').removeClass('selected');
      $('#id-toggle-all').get(0).checked = false;

      Inbox.display_bar(0);
    }
    ,
    select_read: function() {
      $('.message-unread input[type=checkbox]').removeAttr('checked').closest('.message-item').removeClass('selected');

      var count = 0;
      $('.message-item:not(.message-unread) input[type=checkbox]').each(function() {
        this.checked = true;
        $(this).closest('.message-item').addClass('selected');
        count++;
      });
      Inbox.display_bar(count);
    }
    ,
    select_unread: function() {
      $('.message-item:not(.message-unread) input[type=checkbox]').removeAttr('checked').closest('.message-item').removeClass('selected');

      var count = 0;
      $('.message-unread input[type=checkbox]').each(function() {
        this.checked = true;
        $(this).closest('.message-item').addClass('selected');
        count++;
      });

      Inbox.display_bar(count);
    }
  }

  //show message list (back from writing mail or reading a message)
  Inbox.show_list = function() {
    $('.message-navbar').addClass('hide');
    $('#id-message-list-navbar').removeClass('hide');

    $('.message-footer').addClass('hide');
    $('.message-footer:not(.message-footer-style2)').removeClass('hide');

    $('.message-list').removeClass('hide').next().addClass('hide');
  //hide the message item / new message window and go back to list
  }

  //show write mail form
  Inbox.show_form = function() {
    if ($('.message-form').is(':visible'))
      return;
    if (!form_initialized) {
      initialize_form();
    }


    var message = $('.message-list');
    $('.message-container').append('<div class="message-loading-overlay"><i class="icon-spin icon-spinner orange2 bigger-160"></i></div>');

    setTimeout(function() {
      message.next().addClass('hide');

      $('.message-container').find('.message-loading-overlay').remove();

      $('.message-list').addClass('hide');
      $('.message-footer').addClass('hide');
      $('.message-form').removeClass('hide').insertAfter('.message-list');

      $('.message-navbar').addClass('hide');
      $('#id-message-new-navbar').removeClass('hide');


      //reset form??
      $('.message-form .wysiwyg-editor').empty();

      $('.message-form .ace-file-input').closest('.file-input-container:not(:first-child)').remove();
      $('.message-form input[type=file]').ace_file_input('reset_input');

      $('.message-form').get(0).reset();

    }, 300 + parseInt(Math.random() * 300));
  }




  var form_initialized = false;
  function initialize_form() {
    if (form_initialized)
      return;
    form_initialized = true;

    //intialize wysiwyg editor
    $('.message-form .wysiwyg-editor').ace_wysiwyg({
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
    
    $('.wysiwyg-editor').slimScroll({
      height: '260px',
        railVisible: true
    });

  }//initialize_form


//turn the recipient field into a tag input field!
/*	
     var tag_input = $('#form-field-recipient');
     if(! ( /msie\s*(8|7|6)/.test(navigator.userAgent.toLowerCase())) ) 
     tag_input.tag({placeholder:tag_input.attr('placeholder')});
     
     
     //and add form reset functionality
     $('.message-form button[type=reset]').on('click', function(){
     $('.message-form .message-body').empty();
     
     $('.message-form .ace-file-input:not(:first-child)').remove();
     $('.message-form input[type=file]').ace_file_input('reset_input');
     
     
     var val = tag_input.data('value');
     tag_input.parent().find('.tag').remove();
     $(val.split(',')).each(function(k,v){
     tag_input.before('<span class="tag">'+v+'<button class="close" type="button">&times;</button></span>');
     });
     });*/

});

//Ajout de destinataires mail
$('#user-find-results').each(function() {
  $(this).on('click', '#user-result-span', function(event) {
    event.preventDefault();
    var id = $(this).attr('data-id');
    var name = $(this).attr('data-name');
    $(this).attr('id', 'user-result-span-selected');
    $(this).addClass('user-result-span-selected');
    $('#hidden-users').append('<input type="hidden" data-id="' + id + '" id="hidden-user-field-' + id + '" name="data[users]" value="' + id + '-' + name + '" />');
    $('#chosen-choices').append('<li id="search-choice" class="search-choice">' + name + '<a data-id="' + id + '" id="remove-user-mail" class="search-choice-close">x</a></li>');
  });
});

//Prevention du # sur user séléctionné précédement
$('#user-find-results').each(function() {
  $(this).on('click', '#user-result-span-selected', function(event) {
    event.preventDefault();
  });
});

//Suppression d'un destinataire mail
$('#chosen-choices').each(function(){
  $(this).on('click', '#remove-user-mail', function(){
    $('#user-result-span-selected[data-id="' + $(this).attr('data-id') + '"]').removeClass('user-result-span-selected');
    $('#user-result-span-selected[data-id="' + $(this).attr('data-id') + '"]').attr('id', 'user-result-span');
    $('#hidden-user-field-' + $(this).attr('data-id')).remove();
    $(this).parent().remove();
  });
});
$('.message-list').each(function(){
  $(this).on('click', '#label-emails', function(e){
    e.stopPropagation();
  });
});

//Affichage du message
$('.message-list').each(function(){
  $(this).on('click', '.message-item', function(){
    //show the loading icon
    $('.message-container').append('<div class="message-loading-overlay"><i class="icon-spin icon-spinner orange2 bigger-160"></i></div>');

    $('.message-inline-open').removeClass('message-inline-open').find('.message-content').remove();

    var message_list = $(this).closest('.message-list');

    //ajax request
    $.ajax({
      url: '/mailbox/ajaxMessageView',
      type: 'POST',
      data: {
        id: $(this).attr('data-id')
      }
    })
    .done(function(data){
      $('.message-content').empty();
      $('.message-content').html(data);
      //hide everything that is after .message-list (which is either .message-content or .message-form)
      message_list.next().addClass('hide');
      $('.message-container').find('.message-loading-overlay').remove();

      //close and remove the inline opened message if any!

      //hide all navbars
      $('.message-navbar').addClass('hide');
      //now show the navbar for single message item
      $('#id-message-item-navbar').removeClass('hide');

      //hide all footers
      $('.message-footer').addClass('hide');
      //now show the alternative footer
      $('.message-footer-style2').removeClass('hide');


      //move .message-content next to .message-list and hide .message-list
      message_list.addClass('hide').after($('.message-content')).next().removeClass('hide');

    //add scrollbars to .message-body
    /*$('.message-content .message-body').slimScroll({
        height: '50%',
        railVisible: true
      });*/

    })
    .fail(function(){
      $('.message-loading-overlay').remove();
      $.gritter.add({
        title: 'Erreur',
        text: 'Une erreur est survenue lors de la récupération du message. Merci de recommencer ultérieurement.',
        class_name: 'gritter-error'
      });
    })
  });
});

