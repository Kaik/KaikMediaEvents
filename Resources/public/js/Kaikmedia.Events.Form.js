/**
 *Events form 
 */

var KaikMedia = KaikMedia || {};
KaikMedia.Events = KaikMedia.Events || {};
KaikMedia.Events.Form = {};

( function($) {
    // content
    var form;
    var autosave = false;
    
    KaikMedia.Events.Form.init = function ()
    {
    	form = $('form[name=eventform]');
    	if ( form.length ) {    		   	   	  
   	   	 //enable autosave		
   	   	 KaikMedia.Events.Form.enableautosave();   	   	 
   	   	 //enable tinymce
   	   	 KaikMedia.Events.Form.textarea();   	   	 		
   	   	 //enable urltitle check on change
   	   	 $('#eventform_urltitle').on('change', function(e) {	           
   		            e.preventDefault();
   		            KaikMedia.Events.Form.urlcheck();
   	   	 }); 
    	}
    };
  
    KaikMedia.Events.Form.enableautosave = function ()
    {   
    	//initial state 
    	if( autosave == false){
    		//check if it's new event or modifyied one
    		id = $('#eventform_id').val();
            //console.log(id);
    		if (id){
                console.log('id present enable autosave');    			
        		window.setTimeout(KaikMedia.Events.Form.autosave, 120000);
        		autosave = true;
    		}else{
    	       	 // new event first save trigger autosave when title is added
    			 // generate urltitle from title
    	      	 $('#eventform_title').on('change', function(e) {	           
    	   	            e.preventDefault();
    	   	    		KaikMedia.Events.Form.autourl();
    	   	    		autosave = true;
    	   	    		$('#autosave-info').removeClass('list-group-item-warning');
    	   	    		$('#autosave-info-off').addClass('hide');
    	   	    		$('#autosave-info').addClass('list-group-item-success');    	   	    		
    	   	    		$('#autosave-info-on').removeClass('hide');	   	    		
    	   	            window.setTimeout(KaikMedia.Events.Form.autosave, 120000);    	   	    		

    	      	 });      			    			
    		}  		    		
    	}else{
    		//autosave enabled start autosave
            console.log(' autosave already on, start timer');
	        window.setTimeout(KaikMedia.Events.Form.autosave, 120000);
    	}
    };
    //generate url from title and check if is already used
    KaikMedia.Events.Form.autourl = function ()
    {   
    	url = $('#eventform_urltitle').val();  	
    	if ( url == '' ) {    	
    		$('#eventform_urltitle').val('test');
    		//enable url check on auto url
            KaikMedia.Events.Form.urlcheck();    		
    	}else{    		
    		console.log(' url is set');	
    	}
    }; 
    
    KaikMedia.Events.Form.autosave = function ()
    {   
		console.log(' autosave triggered. saving...');
		
		window.setTimeout(KaikMedia.Events.Form.autosave, 120000);
		//renew timeout
    };     
    
    KaikMedia.Events.Form.urlcheck = function ()
    {   
		console.log(' url check triggered');	
    };    
    
    
    KaikMedia.Events.Form.textarea = function ()
    {   
    	// you can change all the editor settings here so it will fit more to your needs
	    tinymce.init({
	        selector: "textarea#eventform_content",
	        theme: "modern",
	        height: 800,
	        plugins: [
	             "advlist autolink link image lists charmap print preview hr anchor eventbreak spellchecker",
	             "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
	             "save table contextmenu directionality emoticons template paste textcolor"
	       ],
	       menubar: false,
	       content_css: "/web/modules/kaikmediaevents/css/content.css, http://fonts.googleapis.com/css?family=Cambay:400|400italic|700|700italic, http://fonts.googleapis.com/css?family=Rajdhani:300|400|500|600|700",
	       toolbar1: "gallery | code print preview",      	       
	       toolbar2: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link charmap table",      
	       style_formats: [
	       	    {title: 'Headers'},           
   	            {title: 'H1', block: 'h1'},
	            {title: 'H2', block: 'h2'},
	    	    {title: 'H3', block: 'h3'},
	    	    {title: 'H4', block: 'h4'},	  
	    	    {title: 'H5', block: 'h5'},	  
	    	    {title: 'H6', block: 'h6'}
	        ]
	     });     
    };   
                
    $(document).ready(function() {
        KaikMedia.Events.Form.init();
    });
})(jQuery);