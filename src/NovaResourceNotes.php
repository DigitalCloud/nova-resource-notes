<?php

namespace DigitalCloud\NovaResourceNotes;

use DigitalCloud\NovaResourceNotes\Resources\Note;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class NovaResourceNotes extends Tool
{

    /**
     * @var mixed
     */
    public $noteResource = Note::class;

    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('nova-eloquent-notes', __DIR__.'/../dist/js/tool.js');
        Nova::style('nova-eloquent-notes', __DIR__.'/../dist/css/tool.css');
        Nova::resources([
            $this->noteResource
        ]);
    }

    /**
     * @param  string  $noteResource
     * @return mixed
     */
    public function noteResource(string $noteResource)
    {
        $this->noteResource = $noteResource;

        return $this;
    }
}
