## Eloquent Files
[![Packagist License](https://poser.pugx.org/brysem/eloquent-files/license.png)](http://choosealicense.com/licenses/mit/)
[![Latest Stable Version](https://poser.pugx.org/brysem/eloquent-files/version.png)](https://packagist.org/packages/brysem/eloquent-files)
[![Total Downloads](https://poser.pugx.org/brysem/eloquent-files/d/total.png)](https://packagist.org/packages/brysem/eloquent-files)

This is a package to make uploading and attaching files to an eloquent model easy.
It includes a ServiceProvider to publish the migrations.
File uploads will be processed by your default filesystem storage. This can be changed in the `filesystem.php` config.
When deleting a model with files attached, the files will automatically also be deleted.

## Installation
Require this package with composer:

```shell
composer require brysem/eloquent-files
```

After updating composer, add the ServiceProvider to the providers array in `config/app.php`.

```php
Bryse\Eloquent\Files\FilesServiceProvider::class,
```

Copy the package migrations to your local migrations folder with the publish command:
```shell
php artisan vendor:publish --provider="Bryse\Eloquent\Files\FilesServiceProvider" --tag="migrations"
```

Add the `HasFiles` trait to the eloquent models you would like to save files to.
```php
use Bryse\Eloquent\Files\Traits\HasFiles;

class User extends Authenticatable
{
    use HasFiles;
}
```

## Usage
You can now easily process file uploads and save them to your eloquent models.
```php
// Returns an array of the files that have been uploaded.
// The second parameter is the path inside your storage_path().
$user->upload(request()->file(), 'users');
```

You also have access to some useful relationships.
```php
// Get a collection (array) of files that belong to the model.
$files = $user->files;

// Get a collection of image files that belong to the model.
$images = $user->files()->images()->get();

// Get a collection of video files that belong to the model.
$videos = $user->files()->videos()->get();

// You can go crazy and grab all text files created three days ago.
$files = $user->files()->where('created_at', '<=', Carbon\Carbon::now()->subDays(3))->where('type', 'like', 'text/%')->get();
```

Easily add a profile image for users.
```php
// UserController.php
public function uploadProfileImage(Request $request, User $user)
{
    // Remove all previous images.
    $user->files->each(function($file) {
        $file->delete();
    });

    $user->upload(request()->file(), 'users');
}

// User.php
public function getImageAttribute()
{
    return $this->files()->images()->first()->url;
}

// Usage
<img src="{{ $user->image }}">
```

Check if a file is an image or video with these easy helpers.
```php
$file->isVideo()
$file->isImage()
```
