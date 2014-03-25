$(document).ready(function() {
    $(document).on('click', 'a.btn-skill-remover', function(event) {
        event.preventDefault();
        var location = $(this).attr('href');
        bootbox.confirm("Etes-vous sûr de vouloir supprimer cette compétence?", function(confirmed) {
            if(confirmed) {
            window.location.replace(location);
            }
        });
    });

    $(document).on('click', '.tree-item', function(event){

        if($(this).hasClass('tree-selected')){
            $('#parent-skill-text').val($(this).attr('data-name'));
            $('#parent_id').val($(this).attr('data-id'));
        }
        else{
            $('#parent-skill-text').val('Pas de compétence parent');
            $('#parent_id').val('');
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
            multiSelect: false,
            loadingHTML: '<div class="tree-loading"><i class="icon-refresh icon-spin blue"></i></div>',
            'open-icon': 'icon-minus',
            'close-icon': 'icon-plus',
            'selectable': true,
            'selected-icon': 'icon-ok',
            'unselected-icon': 'icon-remove'
        });
});