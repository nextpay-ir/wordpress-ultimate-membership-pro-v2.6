(function (){
	////////////////////LOCKER
	tinymce.PluginManager.add('ihc_button_locker', function(ed, url) {		
        // Add a button that opens a window
        ed.addButton('ihc_button_locker', {
            icon: 'ihc_btn_locker',
			title : 'Membership Locker',
            //image: imageurl,
            type: "button",
            text: "",
            cmd: "ihc_locker_popup",
            menu: false,
        });

        ed.addCommand('ihc_insert_shortcode', function(param){
        	return_text = '[ihc-hide-content]'+ed.selection.getContent()+'[/ihc-hide-content]';
            ed.execCommand('mceInsertContent', 0, return_text);
        });        
        
        ///LOAD POPUP
       ed.addCommand('ihc_locker_popup', function() {
    	   	url = url.replace('assets/js', '');
    	   	jQuery.ajax({
    	         type : "post",
    	         url : decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
    	         data : {
    	                    action: "ihc_ajax_admin_popup",
    	                },
    	         success: function (data) { 
    	        	 			jQuery(data).hide().appendTo('body').fadeIn('normal'); 
    	         }
    	    });
       });

       ///POPUP FORM SUBMIT
       ed.addCommand('ihc_insert_locker_shortcode', function(){
           selected_text = ed.selection.getContent();
           return_text = '[ihc-hide-content ';//START SHORTCODE TAG

           return_text += 'ihc_mb_type="'+jQuery('#ihc_mb_type-shortcode').val()+'" ';
           return_text += 'ihc_mb_who="'+jQuery('#ihc_mb_who-shortcode').val()+'" ';
		   templ = '';
		   if( jQuery('#ihc_mb_template-shortcode').val() ){
				templ = jQuery('#ihc_mb_template-shortcode').val();
		   }           
		   return_text += 'ihc_mb_template="'+templ+'" ';

           return_text += ']'+selected_text+'[/ihc-hide-content]';//END SHORTCODE TAG
           ed.execCommand('mceInsertContent', 0, return_text);
           ihc_closePopup();
       });       
        
    });
})();


(function (){
	////////////// REGISTER, LOGIN, LOGOUT
	tinymce.PluginManager.add('ihc_button_forms', function(ed, url) {		
        // Add a button that opens a window
        ed.addButton('ihc_button_forms', {
            icon: 'ihc_btn_forms',
			title : 'Membership ShortCodes',
            type: "button",
            text : "",
            cmd : "ihc_forms_popup"
        });

        ///LOAD POPUP
        ed.addCommand('ihc_forms_popup', function() {
	         url = url.replace('assets/js', '');
	    	 jQuery.ajax({
	    	     type : "post",
	    	     url : decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
	    	     data : {
	    	                action: "ihc_ajax_admin_popup_the_forms",
	    	            },
	    	     success: function (data) { 
	    	        	 	jQuery(data).hide().appendTo('body').fadeIn('normal'); 
	    	     }
	    	 });
        });  
        
        ed.addCommand('ihc_return_text', function(text){
        	ed.execCommand('mceInsertContent', 0, text);
        	ihc_closePopup();
        });
        
    });
})();