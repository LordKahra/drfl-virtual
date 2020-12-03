<?php

use drflvirtual\src\admin\Authentication;
use drflvirtual\src\model\database\EventDatabase;

require_once 'src/config/app_config.php';
require_once 'src/config/global_config.php';

global /** @var EventDatabase $db */ $db;
global /** @var Authentication $auth */ $auth;

echo "\r\nHELLO<br/><br/>";

echo ($auth->isLoggedIn() ? "LOGGED IN" : "NOT LOGGED IN") . "<br/><br/>";

?>

<link rel="stylesheet" type="text/css" href="<?php echo SITE_HOST; ?>/css/forms.css"/>

<script>
    const SITE_HOST = "<?php echo SITE_HOST; ?>";
    const API_HOST = "<?php echo API_HOST; ?>";
    //const API_HOST = "https://drfl-virtual-api-staging.herokuapp.com";
    const TOKEN = "<?php echo $auth->getToken() ? $auth->getToken() : null; ?>";
</script>
<script src="<?php echo SITE_HOST; ?>/js/jquery-1.12.3.js"></script>
<script src="<?php echo SITE_HOST; ?>/js/config.js"></script>
<script src="<?php echo SITE_HOST; ?>/js/api.js"></script>
<script src="<?php echo SITE_HOST; ?>/js/view.js"></script>

<script>
    function onRequestReturn(json, element) {
        console.log("Request returned!");
        console.log(json);
        // TODO: Successful?
        setDoneIcon(element);
    }

    function sendUpdateRequest(element) {
        console.log("Entered.");

        // Get required elements.
        let form = document.getElementById($(element).data("form"));
        let icon = document.getElementById($(form).data("icon"));
        let button = document.getElementById($(form).data("button"));
        //console.log("form.id: " + form.id);

        // Get variables.
        let type = $(form).data("type") + "_id";
        //let form_values = document.forms[form.id];
        let id = form.id.value;
        let key = form.key.value;
        let value = form.value.value;

        // Disable the button.
        button.disabled = true;

        // Set the icon as loading.
        setLoadingIcon(icon);

        let data = {
            //type: id,
            "key": key,
            "value": value
        }
        data[type] = id;

        sendPutRequest(API_HOST + "/mod.php", data, onRequestReturn, icon);

        // Don't return by returning false.
        return false;
    }


</script>

<form
    id="mod_143_KEY_form"
    name="mod_143_KEY_form"
    data-id="143"
    data-form="mod_143_KEY_form"
    data-icon="mod_143_KEY_icon"
    data-button="mod_143_KEY_button"
    data-type="mod"
>
    <input type="hidden" name="id" value="143"/>
    <input type="hidden" name="key" value="description"/>
    <input type="text" name="value" value="someval"
           data-form="mod_143_KEY_form" onchange="onEdit(this)"  /> <button
                id="mod_143_KEY_button" data-form="mod_143_KEY_form" type="button"
                data-style="icon" onclick="sendUpdateRequest(this)" disabled
    ><img data-type="loading" id="mod_143_KEY_icon" data-form="mod_143_KEY_form"
        src="<?=SITE_HOST;?>/res/images/site/blank.png" /></button>
    <script>
        $('#mod_143_KEY_form').submit(function () {
            return sendUpdateRequest($('#mod_143_KEY_form'));
            //return false;
        });
    </script>
</form>




