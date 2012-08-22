<?php

require_once('config.php');

// Pass in "To" parameter
$param = "";
if(!empty($_GET['param'])){ $param = $_GET['param']; }

// Set session verification (helps ensure no bots)
$_SESSION['contact_form'] = "Verified";

// Give form unique id (just in case there are multiple forms on the page...)
$id = rand(1,10000);

?>

<form id="contact_form_<?php echo($id); ?>" method="post" onsubmit="submitContactForm(<?php echo($id); ?>,'<?php echo($module->folder); ?>'); return false;">
    
    <div class="contact_form_thank_you" id="contact_form_thank_you_<?php echo($id); ?>"></div>
    
    <input type="hidden" name="To" value="<?php echo($param); ?>" />
    <input type="hidden" name="Form" value="<?php echo($module->name); ?>" />

    <label>Name*</label>
    <input type="text" name="Name" class="contact_form_required" />
    
    <label>Email*</label>
    <input type="text" name="Email" class="contact_form_required" />
    
    <label>Message*</label>
    <textarea name="Message" class="contact_form_required"></textarea>
    
    <input type="text" style="display: none;" name="Tester" />
    
    <button id="contact_form_submit">Submit</button>
      
</form>
