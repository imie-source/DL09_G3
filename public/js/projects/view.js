$(document).ready(function() {
	$('.easy-pie-chart.percentage').each(function(){
		var $box = $(this).closest('.infobox');
		var barColor = $(this).data('color') || (!$box.hasClass('infobox-dark') ? $box.css('color') : 'rgba(255,255,255,0.95)');
		var trackColor = barColor == 'rgba(255,255,255,0.95)' ? 'rgba(255,255,255,0.25)' : '#E2E2E2';
		var size = parseInt($(this).data('size')) || 50;
		$(this).easyPieChart({
			barColor: barColor,
			trackColor: trackColor,
			scaleColor: false,
			lineCap: 'butt',
			lineWidth: parseInt(size/10),
			animate: /msie\s*(8|7|6)/.test(navigator.userAgent.toLowerCase()) ? false : 1000,
			size: size
		});
	})

   $("#date-end, #date-start").mask("99/99/9999");

   $('#editor1').ace_wysiwyg({
		toolbar:
		[
			'font',
			null,
			'fontSize',
			null,
			{name:'bold', className:'btn-info'},
			{name:'italic', className:'btn-info'},
			{name:'strikethrough', className:'btn-info'},
			{name:'underline', className:'btn-info'},
			null,
			{name:'justifyleft', className:'btn-primary'},
			{name:'justifycenter', className:'btn-primary'},
			{name:'justifyright', className:'btn-primary'},
			{name:'justifyfull', className:'btn-inverse'},
			null,
			{name:'createLink', className:'btn-pink'},
			{name:'unlink', className:'btn-pink'},
			null,
			'foreColor',
			null,
			{name:'undo', className:'btn-grey'},
			{name:'redo', className:'btn-grey'}
		]
	});

   $("#bootbox-wiki").on(ace.click_event, function(event) {
   		event.preventDefault();
   		var loc = $(this).attr('href');
		bootbox.prompt("Ajoutez votre message au wiki!", function(result) {
			console.log(result);
			if (result !== null) {
				$.ajax({
					url: loc,
					type: 'post',
					data: {wiki: $('.wysiwyg-editor').html()},
				})
				.done(function() {
					$('.wiki').append('<p>' + $('.wysiwyg-editor').html() + '</p>');
				})
				.fail(function() {
					alert('Impossible d\'enregistrer votre wiki');
				})
				
			}
		});
	});

   $('.wiki').each(function () {
		var $this = $(this);
		$this.slimScroll({
			height: 'auto',
			railVisible:true
		});
	});
});