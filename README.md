# Nova Resource Notes 
This package provides a `Notes` field, which allow you to add and view notes for nova resource.

## Installation

You can install the package via composer:

```bash
composer require digitalcloud/nova-resource-notes
```

Note that, this package depend on  `digitalcloud/laravel-model-notes` (https://github.com/DigitalCloud/laravel-model-notes), so you need to configure it before start using this package.

You must add `HasNotes` trait to the resource model.

```php
use DigitalCloud\ModelNotes\HasNotes;

class YourEloquentModel extends Model
{
    use HasNotes;
}
```

## Usage

In your nova resource add the `Notes` field:

```php
<?php

namespace App\Nova;

use DigitalCloud\NovaResourceNotes\Fields\Notes;
use Illuminate\Http\Request;

class YourResource extends Resource {
    
    // ...
    
    public static $model = 'YourEloquentModel'; // model must use `HasNotes` trait`
    
    public function fields(Request $request)
    {
        return [
            // ...
            // This will appear in the resource detail view.
            Notes::make('Notes','notes'),
            // ...
        ];
    }
    
    // ...

}
```

Then, in resource detail page, you can add and delete notes for your resuorce as in the next image.

## Images
![notes](https://user-images.githubusercontent.com/41853913/51436776-328ba800-1c9c-11e9-975a-ac6f668d9664.PNG)
