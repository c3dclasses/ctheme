//-----------------------------------------------
// file: ccontent.drv.js
// desc: defines the driver code for content
//-----------------------------------------------

//------------------------------------------
// name: function()
// desc: 
//------------------------------------------
(function($){
	$(document).ready(function($) { 
		$(".ccontent-add-btn").click(function(){
			var ccontent_type = $(this).closest(".ccontent-add-control").find("#ccontent-type").val();
			if(ccontent_type === "NULL") {
				alert("Please select a ccontent type before pressing \"Add Content\" button!");
				return;
			} // end if
			else alert(ccontent_type);
			var widget_id = $(this).closest("form").find(".widget-id").val();
			if(widget_id == null) {
				alert("Therse no widget id");
				return;
			} // end if
			else alert(widget_id);
			
			var data = {
				action: "ccontent_create",
				widget_id: widget_id,
				ccontent_type: ccontent_type
			}; // end data
			var spinner = $(this).siblings(".spinner");
			spinner.show();
			jQuery.post(ajaxurl, data, function(response){
				alert("Response: " + response);
				spinner.hide();
			}); // end jQuery.post();
		}); // end 
	}); // end 
})(jQuery); // end 