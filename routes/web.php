<?php

use Field\Interaction\Http\Controllers\InteractionController;

Route::get('field-interaction', InteractionController::class.'@index');