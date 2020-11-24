<?php


namespace drflvirtual\src\view\component;


use Component;
use drflvirtual\src\model\Event;
use drflvirtual\src\model\Player;

class ModCreateComponent extends Component {
    /**
     * @var Event[]
     */
    private array $events;
    private array $players;
    private string $redirect;

    /**
     * ModCreationComponent constructor.
     * @param Event[] $events
     * @param Player[] $players
     * @param string $redirect The page to send the player to.
     */
    public function __construct(array $events, array $players, string $redirect="admin_mod.php") {
        parent::__construct();

        $this->events = $events;
        $this->players = $players;
        $this->redirect = $redirect;
    }

    function render() {
        ?>
        <article data-type="mod">
            <header id="add_mod">Add Mod</header>
            <form data-type="mod" action="<?=SITE_HOST?>/src/admin/admin_mod_add.php" method="post">
                <input type="hidden" name="redirect" value="<?= $this->redirect; ?>">


                Name: <input size="30" type="text" name="name" placeholder="Name" required />

                <br/>Event: <select id="event_id" name="event_id" required>
                    <?php foreach ($this->events as $event) { ?>
                        <option value="<?=$event->getId();?>" <?=($event->getId() == CURRENT_EVENT ? "selected" : "")?>><?= $event->getName();?></option>
                    <?php } ?>
                </select>

                <br/>Author: <select id="author_id" name="author_id" required>
                    <?php foreach ($this->players as $player) { ?>
                        <option value="<?= $player->getId() ?>"><?= $player->getName() ?></option>
                    <?php } ?>
                </select>

                <br/>Description: <textarea name="description" placeholder="description" rows="3" cols="40" required></textarea>

                <input type="submit"/>
            </form>
        </article>
        <?php
    }
}