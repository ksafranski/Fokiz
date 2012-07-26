<hr />

<form id="add_rule" method="post" onsubmit="return false;">
    <label>Old Path:</label>
    <input type="text" name="path_old" />
    
    <label>New Path:</label>
    <input type="text" name="path_new" />
</form>

<button onclick="url_rewrite.save();" class="btn_left">Save Rewrite Rule</button><button onclick="url_rewrite.list();" class="btn_right">Return to Listing</button>

<hr />