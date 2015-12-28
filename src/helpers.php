<?php

if ( ! function_exists( 'crud_delete_form' ) ) {
	function crud_delete_form( $routeParams, $confirmed = false, $label = 'Delete' ) {
		$form = Form::open( [ 'method' => 'DELETE', 'route' => $routeParams ] );
		if ( $confirmed ) {
			$form .= Form::hidden( 'confirmed', true );
		}


		$form .= '<button type="submit" class="btn btn-danger" aria-label="Left Align">
	<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
	Delete
</button>';


		return $form .= Form::close();
	}
}
