@extends( $masterTemplate )
@section('title', $table_name )
@section( 'content' )
    <div class="col-lg-6">
        <div class="panel panel-green">
            <div class="panel-heading">View {{ $table_name }}</div>

            <div class="panel-body pan">

                <div class="form-body pal">

                    <table class="table table-bordered ">

                        <?php
                        foreach ( $columns as $col => $col_info ) {
                            if ( in_array( $col, $ignored_columns ) ) {
                                continue;
                            }


                            echo '<tr><td>', $col, '</td><td>';
                            switch ( $col_info['ShortType'] ) {
                                case '_id':
                                    echo $data->{'_id'}->{'$id'};
                                    break;
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

                    <?php if( $is_admin ) : ?>

                    <div class="col-lg-3">
                        <?=link_to_route( $resource_link . '.edit', 'Edit', [ $id ],
                                [ 'class' => "btn btn-info" ] )?>
                    </div>
                    <div class="col-lg-6">
                        <?php echo crud_delete_form( [ $resource_link . '.destroy', $id ] ); ?>
                    </div>
                    <div class="clearfix"></div>


                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>


@stop