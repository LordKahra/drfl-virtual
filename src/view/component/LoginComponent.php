<?php


namespace drflvirtual\src\view\component;

use Component;

class LoginComponent extends Component {

    function render() {
        ?>
            <form data-action="login"
                  action="<?= SITE_HOST ?>/src/admin/admin_login.php"
                  method="POST">
                <input type="number" name="player_id"/>
                <input type="password" name="password"/>
                <input type="submit" value="Login"/>
            </form>
        <?php
    }
}