/**
	* @file
	* Custom scripts for theme.
*/
(function ($) {

	$(document).ready(function() {

		//trigger resize
		$(window).resize();

		//facets
		$("#sidebar-first .block.block-facetapi h2").live("click", function() {
			$(this).toggleClass('opened').next('.content').slideToggle('fast');
		});

		//documents list
		if($(".node-search-result:has(.panel-panel.left)").length>0) {
			$(".node-search-result:has(.panel-panel.left) .panel-panel.right", this).css("min-height", "100px");
		}


	});

	$(window).resize(function() {

		//position submenu
		var ww = $(window).width();
		var mw = $("#block-system-main-menu").width();
		var dif = (ww-mw)/2;
		if($(".container.header #navigation ul.menu li ul").length>0) {
			var offparent = $(".container.header #navigation ul.menu li ul").parent().offset().left - dif;
			var ulw = 0;

			$(".container.header #navigation ul.menu li ul li").each(function() {
				ulw+= $(this).width();
			});

			var ml = ulw/2 - $(".container.header #navigation ul.menu li ul").parent().width()/2;

			$(".container.header #navigation ul.menu li ul li:first").css("margin-left", offparent-ml+"px");
		}

	}).load(function() {

		//documents list
		if($(".node-search-result:has(.panel-panel.left)").length>0) {
			$(".node-search-result:has(.panel-panel.left)").each(function() {
				$(".panel-panel.right", this).css("min-height", 25+$(".panel-panel.left img", this).height()+"px");
			});
		}

	});

})(jQuery);
