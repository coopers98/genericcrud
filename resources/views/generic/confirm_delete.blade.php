@extends( $masterTemplate )
@section('title', 'Index')
@section( 'content' )

    @include( 'layouts.partials.admin_nav' )


    <h2>Delete Entry</h2>
    <p>Are you sure you want to delete this entry?</p>
    <div class="alert alert-danger" role="alert">
        <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
        Warning: This is permanent, there is no going back!
    </div>

    <table>

        <?php
        foreach ($columns as $col => $col_info) {
            if (in_array( $col, $ignored_columns )) {
                continue;
            }


            echo '<tr><td>', $col, '</td><td>';
            switch ($col_info['ShortType']) {
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

    <?=link_to_route( $resource_link . '.show', 'Cancel', [ $data->id ], [ 'class' => "btn btn-info" ] )?>

    <?php echo delete_form( [ $resource_link . '.destroy', $data->id ], true ); ?>
@stop