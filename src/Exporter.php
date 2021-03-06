<?php

namespace coopers98\GenericCRUD;

use DB;

class Exporter {

	public static function export( $table, $dbConnection = 'default' ) {
		$data = DB::connection( $dbConnection )->table( $table )->get();

		$csv = \League\Csv\Writer::createFromFileObject( new \SplTempFileObject() );
		$csv->insertOne( \Schema::getColumnListing( $table ) );

		foreach ( $data as $row ) {
			$csv->insertOne( (array) $row );
		}

		$csv->output( $table . '-' . date( 'YmdHi' ) . '.csv' );
	}

}