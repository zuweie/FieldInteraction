<?php

namespace Field\Interaction;

use Encore\Admin\Extension;

class Interaction extends Extension
{
    public $name = 'field-interaction';

    public $views = __DIR__.'/../resources/views';

    public $assets = __DIR__.'/../resources/assets';

    public $menu = [
        'title' => 'Interaction',
        'path'  => 'field-interaction',
        'icon'  => 'fa-gears',
    ];
}