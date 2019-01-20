# Nova Resource Notes 
This package provides a `Notes` field, which allow you to add and view notes for nova resource.

## Installation

You can install the package via composer:

```bash
composer require digitalcloud/nova-resource-notes
```

Note that, this package depend on  `digitalcloud/laravel-model-notes` (https://github.com/DigitalCloud/laravel-model-notes), so you need to configure it before start using this package

## Usage

In your nova resource add the the `Notes` field:

```php
<?php

namespace App\Nova;

use DigitalCloud\NovaResourceNotes\Fields\Notes;
use DigitalCloud\NovaResourceNotes\Resources\Note;
use Illuminate\Http\Request;

class YourResource extends Resource {
    
    // ...
    
    public function fields(Request $request)
    {
        return [
            // ...
            Notes::make('Notes','notes', Note::class),
            // ...
        ];
    }
    
    // ...

}
```

## Images
![notes](https://user-images.githubusercontent.com/41853913/51436734-674b2f80-1c9b-11e9-8832-750efb6229de.PNG)
