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

use Illuminate\Support\ServiceProvider;

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
	}

	public function register() {

	}
}
