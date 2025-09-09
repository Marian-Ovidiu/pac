<?php

namespace Models\Options;

use Core\Bases\BaseGroupAcf;

class OpzioniGlobaliFields extends BaseGroupAcf
{
    protected $groupKey = 'group_671381737ffb8';

    public $logo;
    public function __construct($postId = null) {
        parent::__construct($this->groupKey, $postId);
        $this->defineAttributes();
    }

    public function defineAttributes()
    {
        $this->addField('logo');
        $this->addField('title_blog');
        $this->addField('subtitle_blog');
    }
}