var submitWidget = {

    init: function(){

        submitWidget.onSubmit();

    },

    onSubmit: function(){

        jQuery('.submit_event').on('submit', function(evt){

            jQuery('.submit_event .form-wrap').fadeOut(400, function(){
                jQuery('.submitted-thanks').fadeIn();
            });

        });

    }

}

jQuery(document).ready(function(){
    submitWidget.init();
});
