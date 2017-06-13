function initNavigation(sections)
{
	for (s in sections)
	{
		// $(sections[s]).sidebarButtons();
		$(sections[s]).tabSections();
	}	
}

/*======================================================================
 jQueury Plugin
 Static Navigation Bar for Spirit House
 ----------------------------------------------------------------------*/

(function( $ )
{
	$.fn.fixedNavigation = function( sections ) 
	{
		var _this = $(this);
		$(window).scroll(function (event)
		{ 
				var scrollTop = $(window).scrollTop();

				// Get the 'distance from top' of the first section
				try
				{
					var topOffset = $(sections[0]).position().top;
				}
				catch (e) 
				{
					// Sections wasn't found
					// This default should work, in fact maybe this 
					// is all we need? TODO: Consider remeving the above
					var topOffset = $('.section').eq(0).position().top;
				}

				if (scrollTop >= ( topOffset - _this.height() ))
				{
					_this.show();					
					_this.find("#nav").fadeIn("fast");
					_this.find("#logo").fadeIn("fast");

				}
				else
				{ 
					_this.find("#nav").fadeOut("fast");
					_this.find("#logo").fadeOut("fast");
					_this.hide();
				}
				
				/* Check to see what section we're in
				var paddingbuffer = 100;
				for (var i = sections.length - 1; i >= 0; i--)
				{
					var sectionOffset = $(sections[i]).position().top;
					if (scrollTop >= sectionOffset - paddingbuffer)
					{
						$("#fixedtop a").removeClass("current");
						$("#fixedtop a[href='" + sections[i] + "']").addClass("current");
						break;
					}
				}*/
		});
	};
	
})( jQuery );

/*======================================================================
 Basic Animated Page Scrolling
 ----------------------------------------------------------------------*/

function setupScroll()
{
	$("#mainnav a, #nav a").bind('click',function(event){
		var anch = $(this);
		var posFix = 0;

		if (anch.attr('href') == "#restaurant")
		{
			// Move a bit more for the first section, since it has extra padding
			//posFix = -50;
		}

		$('html, body').stop().animate({
			scrollTop: $(anch.attr('href')).offset().top - $("#fixedtop .container").height() + posFix
		}, 500,'swing');
		
		$("#fixedtop a").removeClass("current");
		$(this).addClass("current");

		event.preventDefault();
	});
	
	$("h3.nav a").bind('click',function(event){
		var anch = $(this);
		var posFix = 0;

		$('html, body').stop().animate({
			scrollTop: $(anch.attr('href')).offset().top - $("#fixedtop .container").height() + posFix
		}, 500,'swing');

		event.preventDefault();
	});
}

/*======================================================================
 jQueury Plugin
 Sidebar Buttons for Spirit House
 ----------------------------------------------------------------------*/

(function( $ )
{
	$.fn.sidebarButtons = function( ) 
	{
		var $me = $(this);
		var links = $me.find("a.navbutton");
		links.each(function()
		{
			$(this).bind('click',function(event)
			{
				event.preventDefault();
				
				// Hide other sub sections
				$me.find(".subsection").hide();
				
				// Show the right section
				var sectionID = $(this).attr('href');
				$me.find(sectionID).slideDown('fast');
				
				// Change the section header text
				$me.find("h4.title").text($(this).text() + ".");
				
				// Slide
				$('html, body').stop().animate({
					scrollTop: $(sectionID).parent().parent().offset().top - 50
				}, 500,'swing');
				
				// Adjust buttons appearence
				links.removeClass("current");
				$(this).addClass("current");
				
			});
		});
	};
	
})( jQuery );

/*======================================================================
 jQueury Plugin
 Tabs for Spirit House
 ----------------------------------------------------------------------*/

(function( $ )
{
	$.fn.tabSections = function( ) 
	{
		var $me = $(this);
		var links = $me.find(".tabs a");
		
		links.each(function()
		{
			$(this).bind('click',function(event)
			{
				event.preventDefault();
				
				// Hide other sub sections
				$me.find(".tabsection").hide();
				
				// Show the right section
				var sectionID = $(this).attr('href');
				$me.find(sectionID).show();
				
				// Adjust buttons appearence
				links.removeClass("current");
				$(this).addClass("current");
				
			});
		});
	};
	
})( jQuery );

/*======================================================================
 jQueury Plugin
 Class listing show/hide details
 ----------------------------------------------------------------------*/

function classInfoShowHide( index, th ) 
{
	var $me = $(th);
	var showhide = $me.find("a.toggle");
	var details = $me.find("div.details");
	
	$me.attr("state", "closed");
	
	showhide.bind('click',function(event)
	{
		event.preventDefault();		
		
		
		
		if ($me.attr("state") == "closed")
		{			
			$me.attr("state", "open");			
			showhide.html("Hide &uarr;");
		} else
		{
			$me.attr("state", "closed");
			showhide.html("Details &darr;");
		}		
		
		// Toggle
		details.slideToggle('fast');
							
	});
}