@extends( $masterTemplate )
@section('title', 'Index')
@section( 'content' )


    <div id="table-action" class="row">
        <div class="col-lg-12">

            <?php echo $items->render(); ?>

            <div class="row">
                <div class="col-lg-12">
                    <div class="portlet portlet-white">
                        <div class="portlet-header pam mbn">
                            <div class="caption">{{ $table_name }} Listing</div>
                            <div class="actions">
                                <a href="{{ route( $resource_link . '.create' ) }}" class="btn btn-info btn-sm"><i
                                            class="fa fa-plus"></i>&nbsp;
                                    New {{ str_singular( $table_name ) }}</a>&nbsp;
                                <div class="btn-group dropdown"><a href="#"
                                                                   data-toggle="dropdown"
                                                                   class="btn btn-warning btn-sm dropdown-toggle"><i
                                                class="fa fa-wrench"></i>&nbsp;
                                        Tools</a>
                                    <ul class="dropdown-menu pull-right">
                                        <li>
                                            <a href="{{ route( 'coopers98.genericcrud.export', [ 't' => $table_name ] ) }}">Export
                                                to CSV</a></li>

                                        @foreach( $tool_entries as $entry )
                                            <li><a href="{{ $entry['url'] }}">{{ $entry['title'] }}</a></li>
                                            @endforeach
                                                    <!--
                                        <li><a href="#">Export to Excel</a></li>
                                        <li><a href="#">Export to XML</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#">Print Invoices</a></li>
-->
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body pan" style="overflow:scroll ">
                            <?php if( count( $items ) > 0 ) : ?>
                            <table class="table table-hover table-striped table-bordered table-advanced tablesorter mbn">
                                <thead>
                                <tr>
                                    <!--
                                                                            <th><input type="checkbox" class="checkall"/></th>
                                    -->
                                    <?php foreach( $columns as $col => $col_info ) :
                                    if ( in_array( $col, $ignored_columns ) ) {
                                        continue;
                                    }

                                    ?>
                                    <th><?php echo $col; ?></th>
                                    <?php endforeach; ?>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach( $items as $item ) : ?>
                                <tr>
                                    <!--
                                                                            <td><input type="checkbox"/></td>
                                    -->
                                    <?php
                                    foreach ( $columns as $col => $col_info ) {
                                        if ( in_array( $col, $ignored_columns ) ) {
                                            continue;
                                        }

                                        echo '<td>';
                                        switch ( $col_info['ShortType'] ) {
                                            case 'int':
                                            case 'string':
                                            case 'float':

                                                if ( $col == 'id' ) {
                                                    $route = route( $resource_link . '.show', [ $item->id ] );
                                                    echo "<a href='$route'>" . $item->id . '</a>';
                                                } else {
                                                    echo $item->{$col};
                                                }

                                                break;
                                            case 'boolean':
                                                echo $item->{$col} ? 'True' : 'False';
                                                break;
                                            default:
                                                echo $item->{$col};
                                                break;
                                        }
                                        echo '</td>';
                                    }


                                    ?>
                                    <td>
                                        <a href="<?php echo route( $resource_link . '.show', [ $item->id ] ); ?>"
                                           class="btn btn-default btn-xs">
                                            <i class="fa fa-edit"></i>&nbsp;
                                            View
                                        </a>

                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php else: ?>
                            <h2>No data</h2>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop