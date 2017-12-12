@extends( $masterTemplate )
@section('title', 'Confirm Delete')
@section( 'content' )
    <div class="col-lg-6">
        <div class="panel panel-green">
            <div class="panel-heading">View {{ $table_name }}</div>

            <div class="panel-body pan">

                <div class="form-body pal">

                    <h2>Delete Entry</h2>

                    <p>Are you sure you want to delete this entry?</p>

                    <div class="alert alert-danger" role="alert">
                        <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
                        Warning: This is permanent, there is no going back!
                    </div>


                    <table class="table table-bordered ">


                        <?php
                        foreach ( $columns as $col => $col_info ) {
                            if ( in_array( $col, $ignored_columns ) ) {
                                continue;
                            }


                            echo '<tr><td>', $col, '</td><td>';
                            switch ( $col_info['ShortType'] ) {
                                case 'int':
                                case 'string':
                                case 'float':
                                    echo $data->{$col};
                                    break;
                                case 'boolean':
                                    echo $data->{$col} ? 'True' : 'False';
                                    break;
                                default:
                                    echo $data->{$col};
                                    break;
                            }
                            echo '</td></tr>';
                        }

                        ?>
                    </table>


                    <div class="col-lg-3">
                        <a href="<?=route( $resource_link . '.show', [ $data->id ] ) ?>" class="btn btn-info">
                            Cancel</a>
                    </div>
                    <div class="col-lg-6">
                        <?php echo crud_delete_form( [ $resource_link . '.destroy', $data->id ], true ); ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
@stop