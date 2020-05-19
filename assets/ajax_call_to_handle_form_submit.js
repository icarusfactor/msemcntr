jQuery( document ).ready(function() {
    // Handler for .ready() called.
    //
  jQuery(function(){
  jQuery("#form_111687").submit(function(event){
   event.preventDefault();
   event.stopPropagation();
 
            var formOk = true;
            // do js validation 
   jQuery.ajax({
    url:ajaxmsemcntr_object.ajaxmsemcntr_url,
                type:'POST',
                data: jQuery(this).serialize() + "&action=ajaxmsemcntr_do_something",
                cache: false,
    success:function(response){ 
     if(response=="true"){
                       //alert('success'); 
        jQuery("#display_rec").html("<div style='color: green;' class='success'><p>SAVED</p></div>")
                    }else{
                        jQuery("#display_rec").html("<div style='color: red' class='fail'>PLEASE INPUT ALL REQUIRED FILEDS.</div>")
                        //alert('Please input required fields.'); 
                    } 
                }
   });
  }); 
    });
});


