<?php

namespace Models\Options;

use Core\Bases\BaseGroupAcf;

class OpzioniGlobaliFields extends BaseGroupAcf
{
    protected $groupKey = 'group_671381737ffb8';

    public $logo;

    /** @var string|null URL opzionale (ACF); fallback in theme_social_urls(). */
    public $url_facebook;

    /** @var string|null */
    public $url_instagram;

    /** @var string|null */
    public $url_linkedin;

    public function __construct($postId = null) {
        parent::__construct($this->groupKey, $postId);
        $this->defineAttributes();
    }

    public function defineAttributes()
    {
        $this->addField('logo');
        $this->addField('title_blog');
        $this->addField('subtitle_blog');
        $this->addField('url_facebook');
        $this->addField('url_instagram');
        $this->addField('url_linkedin');
    }
}