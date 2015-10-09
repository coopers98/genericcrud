### GenericCRUD
A Laravel GenericCRUD Controller trait and corresponding views to give a lightweight way to scaffold database tables in Laravel.

This package evolved from what originally was a vanilla GenericUpdate package that was eventually written for Kohana and now finally
for Laravel.  So while there are likely much better ways of completing these tasks, this has been around a while.

### Installation

```sh
composer require coopers98/genericcrud
```

### Service Provider

In your app config, add the `GenericCRUD` to the providers array.

```php
'providers' => [
    coopers98\GenericCRUD\GenericCRUDServiceProvider::class,
    ];
```


Usage
-----

Be warned that this isn't really a turn key package right now.  You'll likely need to dive into the source a little to
understand how it is all going.  Fear not, it is pretty straight forward and shouldn't be too hard to follow.



This is a trait so use it on a Controller that you want to have the Generic CRUD functionality on.

In your routes.php file, add a resource

```
Route::resource( 'post', 'PostController' );
```

Create your Controller and use the trait

```

use coopers98\GenericCRUD\GenericCRUD;

class PostController extends Controller {
	use GenericCRUD;

	public function __construct() {
		parent::__construct();

        //
        //  The name of the database table
        //
		$this->table_name   = 'posts';

		//
		//  Name of the resource link from your routes file
		//
		$this->resourceLink = 'post';

		//
		//  Optional overrides/settings
		//
		//  Ignored columns will not be shown in the views
	    //  $this->ignored_columns = [ 'created_at', 'updated_at', 'deleted_at' ];
	    //
	    //  Readonly columns will not be allowed to be edited (such as id fields, set by default )
	    //  $this->readonly_columns = [ 'id' ];
	    //
        //  Override the master template
		//  $this->masterTemplate = 'some_layout';
        //
        //  You can override any of the blade views to customize the display of the dataset
        //  $this->showView = 'post.show';
	    //  $this->indexView = 'post.index';
		//  $this->confirmDeleteView = 'post.confirm_delete';
		//  $this->editView = 'post.edit';
		//  $this->createView = 'post.create';

        //
        //  Call the populate columns function to load column data
        //
		$this->populateColumns();
	}

}
```

Once you have done that, by default, you will have basic CRUD functionality

Index
Show
Create
Store
Edit
Update
Destroy

Additionally, you can override the resource actions to either gather additional data, provide additional data or otherwise
change functionality.

There are also authorization checks called for each action.

```
	public function authorizeDelete() {
		//  Override this function and redirect or otherwise to prevent the action from completing
		//  The return value is not checked, so you should just return a redirect response here
	}
```

----

This package also includes a generic table exporter that uses the League/CSV package to export the given
index view as a csv file.

### License

The GenericCRUD is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
