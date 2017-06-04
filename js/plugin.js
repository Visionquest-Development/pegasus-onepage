jQuery(function($) {
	
	$.scrollify({
		section:".side",
		scrollbars:false,
		interstitialSection:"header, .pegasus-footer",
		before:function(i,panels) {

			var ref = panels[i].attr("data-section-name");

			$(".pagination .active").removeClass("active");

			$(".pagination").find("a[href=\"#" + ref + "\"]").addClass("active");
			
		},
		
		afterRender:function() {
			
			var pagination = "<ul class=\"pagination\">";
			var activeClass = "";
			$(".panel").each(function(i) {
				activeClass = "";
				if(i===0) {
					activeClass = "active";
				}
				pagination += "<li><a class=\"" + activeClass + "\" href=\"#" + $(this).attr("data-section-name") + "\"><span class=\"hover-text\">" + $(this).attr("data-section-name").charAt(0).toUpperCase() + $(this).attr("data-section-name").slice(1) + "</span></a></li>";
			});

			pagination += "</ul>";

			$(".home").append(pagination);
			/*
				Tip: The two click events below are the same:

				$(".pagination a").on("click",function() {
				$.scrollify.move($(this).attr("href"));
				});
			*/
			$(".pagination a").on("click",$.scrollify.move);
			
		}
		
	});
	
});







/* 
(function( $ ) {
	
	// Look in #page-wrap and find each section tag, then append a link to the section in the dot navigation 
		$('#page-wrap').find('section').each(function() {
            var tmp = $(this).attr('id');
            var anchorTitle = $(this).find('h2').text();
            var tooltipTitle = anchorTitle.substring(0,5);
            $('.dotnav').append('<li class="nav-tooltip " title="' + tmp + '" ><a href="#' + tmp + ' " ></a></li>');
        });
		
		//place tooltips with titles to left of dot nav
		// $('.nav-tooltip').tooltip({
			// placement: 'left'
		// });   
		
		//snapscroll for sections 
		//$('.side').snapscroll();
		
		// //this is for masonry / packery - it adds the item class to each image
		// $('#grid').find('img').each(function() {       
			// $(this).addClass('item');
        // });
		
})( jQuery );

jQuery(document).ready(function($) {
	
		//scrollspy for dot nav
		$('body').scrollspy({ target: '#dotnav' });
		
		
}); */