if (typeof jQuery != "undefined"){

	// On DOM Ready
	(function($){$(document).ready(function(){

		// Show closing "X"s
		$("div.notice-close").show();
		//$('div.notice').width($('div.notice').width());

		// Close a notice if the "X" is clicked
		$('div.notice-close a').live("click", function(){
			var notice = $(this).closest("div.notice");
			var persistent = notice.hasClass('notice-persistent');
			notice.hide("fast");

			if (persistent){
				var ajax_url = $(this).attr("href");
				$.ajax({
					url: ajax_url,
					cache: false,
					dataType: 'json',
					success: $.noop(),
					error: $.noop()
				});
			}

			return false;
		});

		// Extending the jQuery object to add notices
		// Example: $('div.notices-container').add_notice('success', 'You have succeeded!', true);
		jQuery.fn.add_notice = function(type, message, persist){

			var container = $(this);

			var ajax_url = '/notice/add';
			if (type != undefined){
				ajax_url += '/'+escape(type);
				if (message != undefined){
					ajax_url += '/'+escape(message);
					if (persist == true){
						ajax_url += '/TRUE';
					}
				}
			}

			$.ajax({
				url: ajax_url,
				cache: false,
				dataType: 'json',
				success: function(response){
					if (response.status == "success"){
						container.append(response.data);
						container.find("div.notice-close:last").show();
					}
				},
				error: $.noop()
			});
		};

	})})(jQuery); // Prevent conflicts with other js libraries
}