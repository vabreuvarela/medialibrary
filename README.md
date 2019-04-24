# medialibrary
#1 Add this snipper to your composer.json

```php
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/vabreuvarela/medialibrary/"
    }
],
```

#2 composer require spotmarket/medialibrary

#3 Add the migration with
```php
	php artisan vendor:publish --provider="Spotmarket\MediaLibrary\MediaLibraryServiceProvider" --tag="migrations"
```

#4 Append this snippet to the Model that you want to use this package

```php
	use Spotmarket\MediaLibrary\Traits\HasMedia;

    use HasMedia;

    protected $appends = [
        'media'
    ];
```

#5 You can choose the name of the folder, to have multiple files associated or just one, you can also have multiple groups of files, example below for two groups and multiple file inside folder named 'folderName'
```php
	protected $mediaPackageSettings = [
		'folder' => 'folderName',
		'groups' => [
			'images',
			'pdfs'
		],
		'multipleFiles' => true
	];
```


#6 Activate simlink if you haven't yet
```php
	php artisan storage:link
```
