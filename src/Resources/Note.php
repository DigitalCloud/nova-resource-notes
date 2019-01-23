<?php

namespace DigitalCloud\NovaResourceNotes\Resources;


use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Text;

class Note extends Resource {

    public static $model = 'DigitalCloud\ModelNotes\Note';

    public static $displayInNavigation = false;

    public function fields(Request $request)
    {
        return [
            Text::make('note'),
            Text::make('creator'),
            Text::make('time_ago'),
        ];
    }

}
