$(document).ready(function() {
	$(document).on("click", ".icon-remove, .icon-ok", function (event) {
        event.preventDefault();
		event.stopPropagation();
        console.log(event.target);
    });
    $(document).on("click", ".tree-folder-header", function (event) {
    	event.target = 'icon-plus';
        event.preventDefault();
		event.stopPropagation();
        console.log(event.target);
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