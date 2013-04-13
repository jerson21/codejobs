$(document).ready(function() {

	window.loadCalendar = function() {
		$('.jdpicker').each(function(){
			if ($(this).parent('div').attr('class') != "jdpicker_w") {
	        	$(this).jdPicker();
	    	}
	    });
	}

	$('.editor').each(function(e){
        CKEDITOR.replace($(this).attr('id'), {
					toolbar: [
						{ name:'group1', items:['Bold','Italic','Underline','StrikeThrough','PasteFromWord'] },
						{ name:'group2', items:['Outdent','Indent','NumberedList','BulletedList','Blockquote'] },
					 	{ name:'group3', items:['Image','Link','Unlink','InsertPre'] }  
					]
		});
    });

	$('.show-section h3').toggle(
		function() {
			$(this).removeClass('inactive-section').addClass('active-section');
			$(this).next('div').show();
		}, function() {
			$(this).removeClass('active-section').addClass('inactive-section');
			$(this).next('div').hide();
	})

	/*$('#expand-collapse').toggle(
		function(e) {
			e.preventDefault();
			$('.show-section h3').removeClass('inactive-section').addClass('active-section');
			$('.show-section h3').next('div').show();
			//$(this).text('"__("Collapse All")"');
		}, function(e) {
			e.preventDefault();
			$('.show-section h3').removeClass('active-section').addClass('inactive-section');
			$('.show-section h3').next('div').hide();
			//$(this).text('Expand All');
		})*/
	$('#expand').click(function(e){
		e.preventDefault();
		$('.show-section h3').removeClass('inactive-section').addClass('active-section');
		$('.show-section h3').next('div').show();
	})

	$('#collapse').click(function(e) {
		e.preventDefault();
		$('.show-section h3').removeClass('active-section').addClass('inactive-section');
		$('.show-section h3').next('div').hide();
	})

    /*var listSkills = [ 'c++', 'java', 'php', 'jquery'];

    $('input[name=skills]').tagit({
        availableTags: listSkills,
        itemName: 'item',
        fieldName: 'skills'
    });

    $('ul.tagit').addClass("span10");*/
});