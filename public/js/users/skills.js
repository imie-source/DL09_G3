$(document).ready(function() {
    $(document).on('click', '.tree-item', function(event){

        if($(this).hasClass('tree-selected')){
            if($('#no-skills').length){
                $('#skills-tags').empty();
                $('#submit-skills').removeClass('hidden');
            }
            $('#skills-tags').append('<span class="tag" id="' + $(this).attr('data-id') + '">' + $(this).attr('data-name') + ' <span class="star" id="star-' + $(this).attr('data-id') + '"></span></span>');
            $('#skills-tags').append('<input type="hidden" name="skills[' + $(this).attr('data-id') + '][rate]" id="rate-' + $(this).attr('data-id') + '">');
            $('#skills-tags').append('<input type="hidden" id="input-' + $(this).attr('data-id') + '" name="skills[' + $(this).attr('data-id') + '][id]" value="' + $(this).attr('data-id') + '">');
            $('#skills-tags').append('<script>$("#star-' + $(this).attr('data-id') + '").raty({ path: "/images/", click: function(score, evt){ $("#rate-' + $(this).attr('data-id') + '").val(score); } });</script>');
            
        }
        else{
            $('#' + $(this).attr('data-id')).remove();
            $('#input-' + $(this).attr('data-id')).remove();
            $('#rate-' + $(this).attr('data-id')).remove();
            if(!$('.tag').length){
                $('#skills-tags').append('<span id="no-skills">Aucune compétence renseignée</span>');
                $('#submit-skills').addClass('hidden');
            }
        }
        
    });
	var DataSourceTree = function (options) {
                this.url = options.url;
            }

            DataSourceTree.prototype.data = function (options, callback) {
                var self = this;
                var $data = null;

                var param = null

                if (!("name" in options) && !("type" in options)) {
                    param = 0;//load the first level  
                }
                else{
                	param = options.id;
                }

                if (param != null) {                    
                    $.ajax({
                        url: this.url,
                        data: 'id=' + param,
                        type: 'POST',
                        dataType: 'json',
                        success: function (response) {
                            if (response.status == "OK") 
                                callback({ data: response.data })
                        },
                        error: function (response) {
                            //console.log(response);
                        }
                    })
                }
            };

     $('#tree1').ace_tree({
            dataSource: new DataSourceTree({ url: '/skills/ajaxTreeFinder' }),
            multiSelect: true,
            loadingHTML: '<div class="tree-loading"><i class="icon-refresh icon-spin blue"></i></div>',
            'open-icon': 'icon-minus',
            'close-icon': 'icon-plus',
            'selectable': true,
            'selected-icon': 'icon-ok',
            'unselected-icon': 'icon-remove'
        });
});