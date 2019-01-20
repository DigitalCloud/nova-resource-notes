<?php

namespace DigitalCloud\NovaResourceNotes\Fields;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\ResourceRelationshipGuesser;

class Notes extends Field {

    public $onlyOnDetail = true;
    public $showOnUpdate = false;
    public $showOnCreation = false;

    public $component = 'notes-field';

    public $resourceName;
    public $resourceClass;
    public $hasManyRelationship;

    public function __construct($name, $attribute = null, $resource = null)
    {
        parent::__construct($name, $attribute);

        $resource = $resource ?? ResourceRelationshipGuesser::guessResource($name);

        $this->resourceClass = $resource;
        $this->resourceName = $resource::uriKey();
        $this->hasManyRelationship = $this->attribute;
    }

    public function meta()
    {
        return array_merge([
            'resourceName' => $this->resourceName,
            'hasManyRelationship' => $this->hasManyRelationship,
            'listable' => true,
            'singularLabel' => $this->singularLabel ?? Str::singular($this->name),
        ], $this->meta);
    }

}
