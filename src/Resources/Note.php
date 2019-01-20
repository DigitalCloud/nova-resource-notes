<?php

namespace DigitalCloud\NovaResourceNotes\Resources;


use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;

class Note extends Resource {

    public static $model = 'DigitalCloud\ModelNotes\Note';

    public static $displayInNavigation = true;

    public function fields(Request $request)
    {
        return [
            Text::make('note')
        ];
    }

}
