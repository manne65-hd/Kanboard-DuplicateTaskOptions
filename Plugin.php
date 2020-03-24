<?php

namespace Kanboard\Plugin\DuplicateTaskOptions;

use Kanboard\Core\Plugin\Base;



class Plugin extends Base
{
    public function initialize()
    {
        $this->template->hook->attach('template:task:dropdown', 'DuplicateTaskOptions:task/dropdown');
        $this->template->hook->attach('template:task:sidebar:actions', 'DuplicateTaskOptions:task/sidebar');
    }

    public function onStartup()
    {
    }

    public function getPluginName()
    {
        return 'DuplicateTaskOptions';
    }

    public function getPluginDescription()
    {
        return t('Shows a form with extended options when duplicating a task');
    }

    public function getPluginAuthor()
    {
        return 'Manfred Hoffmann';
    }

    public function getPluginVersion()
    {
        return '0.0.1';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/manne65-hd/DuplicateTaskOptions';
    }
}
