/*
 * Image preview script 
 * powered by jQuery (http://www.jquery.com)
 * 
 * written by Alen Grakalic (http://cssglobe.com)
 * 
 * for more info visit http://cssglobe.com/post/1695/easiest-tooltip-and-image-preview-using-jquery
 *
 */
 
this.imageRollover = function(){	
	/* CONFIG */
		
		xOffset = -500;
		yOffset = 100;
		
		// these 2 variable determine popup's distance from the cursor
		// you might want to adjust to get the right result
	$(document).on(
	{
	  mouseenter: function(e)
	  {
  		this.t = this.title;
  		this.title = "";	
  		var c = (this.t != "") ? "<br/>" + this.t : "";
  		$("body").append("<p id='rollover_image'><img src='"+ this.href +"' alt='Image preview' />"+ c +"</p>");								 
  		$("#rollover_image")
  			.css("top",(e.pageY - yOffset) + "px")
  			.css("left",(e.pageX + xOffset) + "px")
  			.fadeIn("fast");						
	
	  },
	  mouseleave: function(e)
	  {
  		this.title = this.t;	
  		$("#rollover_image").remove();
	  }
	}, "a.rollover");  // descendant selector

	$(document).on('mousemove', "a.rollover", function(e)
	{
		$("#rollover_image")
			.css("top",(e.pageY - yOffset) + "px")
			.css("left",(e.pageX + xOffset) + "px");
	});
	
};


// starting the script on page load
$(document).ready(function(){
	imageRollover();
});