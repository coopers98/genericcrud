<?php
namespace coopers98\GenericCRUD;


use DB;
use Illuminate\Http\Request;
use View;


trait GenericCRUD {

	protected $dbConnection = 'mysql';
	protected $table_name = 'generic';
	protected $columns = [ ];
	protected $ignored_columns = [ 'created_at', 'updated_at', 'deleted_at' ];
	protected $readonly_columns = [ 'id', '_id' ];  //  default to id being readonly
	protected $date_columns = [ ];
	protected $resourceLink = 'generic';
	protected $masterTemplate = 'genericcrud::master';
	protected $showView = 'genericcrud::show';
	protected $indexView = 'genericcrud::index';
	protected $confirmDeleteView = 'genericcrud::confirm_delete';
	protected $editView = 'genericcrud::edit';
	protected $createView = 'genericcrud::create';
	protected $extraViewValues = [ ];
	protected $toolEntries = [ ];

	//
	//  Gives a 1 step easy way to do admin checks
	//
	protected $is_admin = false;

	/**
	 * Will translate MySQL types to internally known types
	 *
	 * @param $fullType
	 *
	 * @return string
	 */
	protected function translateType( $fullType ) {
		if ( stripos( $fullType, 'int' ) !== false ) {
			return 'int';
		}

		if ( stripos( $fullType, 'double' ) !== false ) {
			return 'double';
		}

		if ( stripos( $fullType, 'decimal' ) !== false ) {
			return 'decimal';
		}

		if ( stripos( $fullType, 'varchar' ) === 0 ) {
			return 'string';
		}

		if ( stripos( $fullType, 'enum' ) === 0 ) {
			return 'enum';
		}

		if ( stripos( $fullType, 'datetime' ) === 0 ) {
			return 'datetime';
		}

		return $fullType;
	}

	/**
	 * Populate our column data
	 */
	protected function populateColumns() {

		$columns = DB::connection( $this->dbConnection )->select( DB::raw( 'SHOW FIELDS FROM ' . $this->table_name ) );

		foreach ( $columns as $column ) {

			$this->columns[ $column->Field ] = [
				'Type'      => $column->Type,
				'Null'      => $column->Null,
				'Key'       => $column->Key,
				'Default'   => $column->Default,
				'Extra'     => $column->Extra,
				'ShortType' => $this->translateType( $column->Type )
			];

			if ( $this->columns[ $column->Field ]['ShortType'] == 'enum' ) {
				$vals = explode( ',', substr( $column->Type, 5, - 1 ) );
				foreach ( $vals as $val ) {
					$val                                                 = trim( $val, "'" );
					$this->columns[ $column->Field ]['enumVals'][ $val ] = $val;
				}
			}
		}

	}

	/**
	 * Display a listing of the resource.
	 * GET /genericupdate
	 *
	 * @return Response
	 */
	public function index( Request $request ) {
		$this->authorizeList();

		$limit = $request->input( 'limit', 25 );
		$limit = $limit > 1000 ? 1000 : $limit;

		$items = DB::connection( $this->dbConnection )->table( $this->table_name )->paginate( $limit );

		return View::make( $this->indexView )
		           ->with( 'masterTemplate', $this->masterTemplate )
		           ->with( 'items', $items )
		           ->with( 'columns', $this->columns )
		           ->with( 'ignored_columns', $this->ignored_columns )
		           ->with( 'table_name', $this->table_name )
		           ->with( 'resource_link', $this->resourceLink )
		           ->with( 'tool_entries', $this->toolEntries );
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /genericupdate/create
	 *
	 * @return Response
	 */
	public function create() {
		$this->authorizeCreate();

		return View::make( $this->createView )
		           ->with( 'masterTemplate', $this->masterTemplate )
		           ->with( 'columns', $this->columns )
		           ->with( 'ignored_columns', $this->ignored_columns )
		           ->with( 'table_name', $this->table_name )
		           ->with( 'readonly_columns', $this->readonly_columns )
		           ->with( 'date_columns', $this->date_columns )
		           ->with( 'is_admin', $this->is_admin )
		           ->with( 'resource_link', $this->resourceLink );

	}

	/**
	 * Store a newly created resource in storage.
	 * POST /genericupdate
	 *
	 * @return Response
	 */
	public function store( Request $request ) {
		$this->authorizeStore();

		$updates = array();
		foreach ( $this->columns as $column_name => $column_attributes ) {
			if ( ! in_array( $column_name, $this->ignored_columns ) && strlen( $request->{$column_name} ) > 0 ) {
				switch ( $column_attributes['ShortType'] ) {
					case 'date':
						$updates[ $column_name ] = date( 'Y-m-d', strtotime( $request->{$column_name} ) );
						break;
					default:
						$updates[ $column_name ] = $request->{$column_name};
				}
			}
		}

		DB::connection( $this->dbConnection )->table( $this->table_name )->insert( $updates );

		return redirect()->route( $this->resourceLink . '.index' )
		                 ->with( 'message', 'Entry Created' );
	}

	/**
	 * Display the specified resource.
	 * GET /genericupdate/{id}
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show( $id ) {
		$this->authorizeShow();

		$entry = DB::connection( $this->dbConnection )->table( $this->table_name )->find( $id );
		if ( is_array( $entry ) ) {
			$entry = json_decode( json_encode( $entry ), false );
		}

		return View::make( $this->showView )
		           ->with( 'id', $id )
		           ->with( 'masterTemplate', $this->masterTemplate )
		           ->with( 'data', $entry )
		           ->with( 'columns', $this->columns )
		           ->with( 'ignored_columns', $this->ignored_columns )
		           ->with( 'table_name', $this->table_name )
		           ->with( 'is_admin', $this->is_admin )
		           ->with( 'resource_link', $this->resourceLink );

	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /genericupdate/{id}/edit
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function edit( $id ) {
		$this->authorizeEdit();

		$entry = DB::connection( $this->dbConnection )->table( $this->table_name )->find( $id );
		if ( is_array( $entry ) ) {
			$entry = json_decode( json_encode( $entry ), false );
		}


		return View::make( $this->editView )
		           ->with( 'id', $id )
		           ->with( 'masterTemplate', $this->masterTemplate )
		           ->with( 'data', $entry )
		           ->with( 'columns', $this->columns )
		           ->with( 'ignored_columns', $this->ignored_columns )
		           ->with( 'table_name', $this->table_name )
		           ->with( 'readonly_columns', $this->readonly_columns )
		           ->with( 'date_columns', $this->date_columns )
		           ->with( 'is_admin', $this->is_admin )
		           ->with( 'resource_link', $this->resourceLink );

	}

	/**
	 * Update the specified resource in storage.
	 * PUT /genericupdate/{id}
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function update( $id, Request $request ) {
		$this->authorizeUpdate();

		$updates = array();
		foreach ( $this->columns as $column_name => $column_attributes ) {
			if ( ! in_array( $column_name, $this->ignored_columns ) && strlen( $request->{$column_name} ) > 0 ) {
				$updates[ $column_name ] = $request->{$column_name};
			}
		}

		DB::connection( $this->dbConnection )->table( $this->table_name )
		  ->where( 'id', $id )
		  ->update( $updates );

		return redirect()->route( $this->resourceLink . '.show', [ $id ] )
		                 ->with( 'message', 'Entry Updated' );
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /genericupdate/{id}
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy( Request $request, $id ) {
		$this->authorizeDelete();

		$confirmed = $request->input( 'confirmed', false );

		if ( ! $confirmed ) {

			$entry = DB::connection( $this->dbConnection )->table( $this->table_name )->find( $id );
			if ( is_array( $entry ) ) {
				$entry = json_decode( json_encode( $entry ), false );
			}


			return View::make( $this->confirmDeleteView )
			           ->with( 'masterTemplate', $this->masterTemplate )
			           ->with( 'data', $entry )
			           ->with( 'columns', $this->columns )
			           ->with( 'ignored_columns', $this->ignored_columns )
			           ->with( 'table_name', $this->table_name )
			           ->with( 'resource_link', $this->resourceLink );

		}


		if ( array_key_exists( 'deleted_at', $this->columns ) ) {

			//  Soft delete
			DB::connection( $this->dbConnection )
			  ->table( $this->table_name )
			  ->where( 'id', '=', $id )
			  ->update( 'deleted_at', time() );

		} else {
			DB::connection( $this->dbConnection )->table( $this->table_name )->where( 'id', '=', $id )->delete();
		}

		return redirect()->route( $this->resourceLink . '.index' )
		                 ->with( 'message', 'Entry deleted' );
	}

	public function authorizeList() {
	}

	public function authorizeShow() {
	}

	public function authorizeEdit() {
	}

	public function authorizeUpdate() {
	}

	public function authorizeCreate() {
	}

	public function authorizeStore() {
	}

	public function authorizeDelete() {
	}

	public function checkAdmin() {
		if ( ! $this->is_admin ) {
			return redirect()->route( $this->resourceLink . '.index' )
			                 ->with( 'error', 'Unauthorized to do that' );
		}
	}

}