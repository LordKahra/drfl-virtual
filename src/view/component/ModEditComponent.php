<?php


namespace drflvirtual\src\view\component;

use Component;
use drflvirtual\src\model\Mod;

class ModEditComponent extends Component {
    protected $mod;

    public function __construct(Mod $mod) {
        parent::__construct("mod_" . $mod->getId());
        $this->mod = $mod;
    }

    function render() {
        ?>
        <main data-type="mod" data-style="edit" id="mod_<?=$this->mod->getId();?>">
            <header>
                <input id="mod_name_<?=$this->mod->getId();?>" type="text" data-type="name" value="<?=$this->mod->getName()?>" />
            </header>
        </main>
        <?php
    }
}