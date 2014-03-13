$(document).ready(function() {
	$('#tree1').ace_tree({
		dataSource: treeDataSource ,
		multiSelect:true,
		loadingHTML:'<div class="tree-loading"><i class="icon-refresh icon-spin blue"></i></div>',
		'open-icon' : 'icon-minus',
		'close-icon' : 'icon-plus',
		'selectable' : true,
		'selected-icon' : 'icon-ok',
		'unselected-icon' : 'icon-remove'
	});

	$('#tree2').ace_tree({
		dataSource: treeDataSource2 ,
		loadingHTML:'<div class="tree-loading"><i class="icon-refresh icon-spin blue"></i></div>',
		'open-icon' : 'icon-folder-open',
		'close-icon' : 'icon-folder-close',
		'selectable' : false,
		'selected-icon' : null,
		'unselected-icon' : null
	});
});