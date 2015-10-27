<?php
/**
 * Laravel Generic CRUD Controller Trait Service Provider
 *
 * @author    Cooper Sellers <cooper@fifostudios.com>
 * @copyright 2015 Cooper Sellers / FIFO Studios, LLC (http://www.fifostudios.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      https://github.com/coopers98/genericcrud
 */

namespace coopers98\GenericCRUD;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Route;

class GenericCRUDServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot() {
		$viewPath = __DIR__ . '/../resources/views';
		$this->loadViewsFrom( $viewPath, 'genericcrud' );

		if ( ! $this->app->routesAreCached() ) {
			Route::get( '/coopers98/genericcrud/export',
				[
					'as' => 'coopers98.genericcrud.export',
					function ( Request $request ) {
						$table      = $request->input( 't' );
						$connection = $request->input( 'db', 'default' );
						\coopers98\GenericCRUD\Exporter::export( $table, $connection );
					}
				] );
		}
	}

	public function register() {

	}
}
