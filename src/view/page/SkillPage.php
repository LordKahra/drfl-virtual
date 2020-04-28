<?php

namespace boffer\src\view\page;

use boffer\src\model\Skill;

class SkillPage extends Page {
    protected $skill;

    public function __construct(string $title, Skill $skill) {
        parent::__construct($title);
        $this->skill = $skill;
    }

    /**
     * @return Skill
     */
    public function getSkill(): Skill {
        return $this->skill;
    }

    function renderBody() {
        ?>
<main>
    <header><?=$this->getSkill()->getName()?></header>

</main><?php
    }
}