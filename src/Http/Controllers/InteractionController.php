<?php

namespace Field\Interaction\Http\Controllers;

use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;

class InteractionController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->header('Title')
            ->description('Description')
            ->body(view('field-interaction::index'));
    }
}