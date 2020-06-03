<?php

namespace drflvirtual\src\view\page;


use drflvirtual\src\model\Event;
use drflvirtual\src\model\Mod;

class EventPage extends Page {
    protected $event;

    public function __construct(string $title, Event $event) {
        parent::__construct($title, "event");
        $this->event = $event;
    }

    /**
     * @return Event
     */
    public function getEvent(): Event {
        return $this->event;
    }

    function renderBody() {
        //var_dump($this->event);
        // Calculate where all the mods are in the table.
        $mod_statuses = array();

        //var_dump($this->getEvent()->getMods());

        foreach($this->getEvent()->getMods() as $mod) {
            $status = $mod->calculateStatus();

            // If the spot doesn't exist, create it.
            if (!array_key_exists($status, $mod_statuses)) {
                $mod_statuses[$status] = array();
            }

            // Add the mod.
            $mod_statuses[$status][] = $mod;
        }

        // Sort by key.
        ksort($mod_statuses);

        //var_dump($mod_statuses);

        ?>
        <table data-type="event">
            <tr>
                <th>Brainstorming</th>
                <th>Polishing</th>
                <th>Mapped</th>
                <th>Statted</th>
                <th>Finishing Touches</th>
            </tr>
            <tr valign="top">
                <td data-status="BEING_WRITTEN">
                    <?php $this->renderModSetIfExists(Mod::STATUS['BEING_WRITTEN'], "Brainstorming", $mod_statuses); ?>
                    <?php $this->renderModSetIfExists(Mod::STATUS['NEEDS_DETAILS'], "Written", $mod_statuses); ?>
                </td>
                <td data-status="UNSCHEDULED">
                    <?php $this->renderModSetIfExists(Mod::STATUS['UNSCHEDULED'], "Detailed", $mod_statuses); ?>
                    <?php $this->renderModSetIfExists(Mod::STATUS['MAP_INCOMPLETE'], "Scheduled", $mod_statuses); ?>
                </td>
                <td data-status="NEEDS_STATS">      <?php $this->renderModSetIfExists(Mod::STATUS['NEEDS_STATS'], "Mapped", $mod_statuses); ?></td>
                <td data-status="TABLETOP_INCOMPLETE"><?php $this->renderModSetIfExists(Mod::STATUS['TABLETOP_INCOMPLETE'], "Statted", $mod_statuses); ?></td>
                <td data-status="VERIFICATION">
                    <?php $this->renderModSetIfExists(Mod::STATUS['VERIFICATION'], "Verification", $mod_statuses); ?>
                    <?php $this->renderModSetIfExists(Mod::STATUS['READY'], "Complete", $mod_statuses); ?>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * @param Mod[] $mods
     */
    function renderModSetIfExists(int $key, string $title, array $mod_statuses) {
        ?>
        <div data-status="<?=$key?>" data-type="mod_list">
        <header>
            <?=$title?>
        </header>
        <?php
        if (array_key_exists($key, $mod_statuses)) {
            foreach ($mod_statuses[$key] as $mod) {
                $this->renderMod($mod);
            }
        }
        ?></div><?php
    }

    function renderMod(Mod $mod) {
        ?>
        <div data-type="mod" data-style="card" data-fold="true" data-active="false" id="mod_<?=$mod->getId();?>">
            <header>
                <button data-ui="button" href="#" onclick="toggleById('mod_<?=$mod->getId();?>')">🔎</button>
                <span data-type="name"><b><a href="mod.php?id=<?=$mod->getId()?>"><?=$mod->getName()?></a></b></span>
                <br/>
                <?php foreach ($mod->getErrors() as $error) { ?>
                    <?=$error["icon"]?>
                <?php } ?>
            </header>
            <main>
                <ul>
                    <?php foreach ($mod->getErrors() as $error) { ?>
                        <li class="error"><?=$error["message"]?></li>
                    <?php } ?>
                </ul>
                <ul>
                    <li><b>Location:</b> <?=$mod->getLocation()?></li>
                    <li><b>Where: </b><?=$mod->getHost()?></li>
                    <li><b>When: </b><?=$mod->getStartString()?></li>
                    <li><b>Map Status:</b> <?=$mod->getMapStatus()?></li>
                    <li><b>Tabletop Status:</b> <?=$mod->getTabletopStatus()?></li>
                    <li><b>Ready:</b> <?=($mod->isReady() ? "Yes" : "No")?></li>
                    <li></li>
                    <li></li>
                </ul>
                <div>
                    <b>Characters:</b>
                    <?php if (is_array($mod->getCharacters())) {
                        $character_names = array();
                        foreach ($mod->getCharacters() as $character) {
                            //$character_names[] = "<a href='character.php?id=" . $character['id'] . "'>" . $character['name'] . "</a>";
                            $character_names[] = $character->getName();
                        }
                        echo implode(", ", $character_names);
                    } else {
                        echo "None.";
                    } ?>
                </div>

                <div><?=
                    strlen($mod->getDescription()) > 300 ?
                        nl2br(substr($mod->getDescription(), 0, 300)) . "..." :
                        nl2br($mod->getDescription())
                    ?></div>
            </main>
        </div>
        <?php
    }
}