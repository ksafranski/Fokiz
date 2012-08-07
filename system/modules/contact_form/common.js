function submitContactForm(id,folder){
    // Check required
    pass = true;
    $('.contact_form_required').each(function(){
        if($(this).val()==''){ pass = false; }   
    });
    // Check for pass
    if(pass==true){
        $.post('system/modules/'+folder+'/mailer.php',$('#contact_form_'+id).serialize(),function(data){
            if(data=='pass'){
                // No errors, show thank you message
                $('#contact_form_thank_you_'+id).html('Thank you. We will respond to your message shortly').fadeIn(300);
                $('#contact_form_'+id+' input, #contact_form_'+id+' textarea').each(function(){ $(this).val(''); });
            }else{
                // Error returned
                $('#contact_form_thank_you_'+id).html('There was an error sending the message.').fadeIn(300);
            }
        });
    }else{
        $('#contact_form_thank_you_'+id).html('Please fill out all required fields (*).').fadeIn(300);
    }
}
