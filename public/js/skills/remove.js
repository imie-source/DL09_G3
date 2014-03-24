$(document).ready(function() {
	
    $(document).on('mouseover', '.tree-folder-header, .tree-item', function() {
        $(this).find('.btn-skill-remover').removeClass('hidden');
    });

    $(document).on('mouseout', '.tree-folder-header, .tree-item', function() {
        $(this).find('.btn-skill-remover').addClass('hidden');
    });
});