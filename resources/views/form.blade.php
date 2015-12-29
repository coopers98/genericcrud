<div class="form-body pal">
    @foreach( $columns as $col => $col_info )
        <?php
        if ( ! in_array( $col, $ignored_columns ) ) {

            echo '<div class="form-group">';
            echo Form::label( $col, $col, [ 'class' => 'col-md-3 control-label' ] );
            echo '<div class="col-md-9">';

            $readonly = '';
            if ( in_array( $col, $readonly_columns ) ) {
                $readonly = [ 'readonly' => 'readonly' ];
            }


            //
            //  Perform a couple of overrides
            //
            if ( in_array( $col, $date_columns ) ) {
                echo Form::text( $col, crud_old( $col ), [ 'class' => 'datepicker' ] );
            } else {
                //
                //  Need to determine the datatype of the column to know what kind of update
                //  field to put in place, we also need to know if it's a lookup
                //  into another table
                //
                switch ( $col_info['ShortType'] ) {
                    case '_id':
                            echo 'MongoDB ID';
                            break;
                    case 'int':
                    case 'float':
                    case 'double':
                    case 'decimal':
                            echo Form::text( $col, crud_old( $col ), $readonly, [ 'class' => 'form-control' ] );
                        break;
                    case 'string':
                        if ( isset( $col_info['length'] ) && ! is_numeric( $col_info['length'] ) ) {
                            $entries = explode( ',', $col_info['length'] );

                            //  Enum field
                            $vals = array();
                            foreach ( $entries as $option ) {
                                $option          = strtoupper( trim( $option, '\'' ) );
                                $vals[ $option ] = $option;
                            }
                            echo Form::select( $col, $vals, crud_old( $col ), [ 'class' => 'form-control' ] );
                        } else {
                            echo Form::text( $col, crud_old( $col ), $readonly, [ 'class' => 'form-control' ] );
                        }
                        break;
                    case 'text':
                    case 'mediumtext':
                    case 'longtext':
                            echo Form::textarea( $col, crud_old( $col ), $readonly, [ 'class' => 'form-control' ] );
                        break;
                    case 'boolean':
                        echo form::checkbox( $col, $col, crud_old( $col ), $readonly, [ 'class' => 'form-control' ] );
                        break;
                    case 'timestamp':
                    case 'datetime':
                    case 'date':
                        echo '<div class="input-group">';
                        echo Form::text( $col, crud_old( $col ), [
                                'class'            => 'datepicker-default form-control',
                                'data-date-format' => 'mm/dd/yyyy',
                                'placeholder'      => 'mm/dd/yyyy'
                        ] );
                        echo '<div class="input-group-addon"><i class="fa fa-calendar"></i></div></div>';
                        break;
                    case 'enum' :
                        echo Form::select( $col, $col_info['enumVals'], crud_old( $col ),
                                [ 'class' => 'form-control' ] );
                        break;
                    default:
                        echo $col_info['ShortType'] . ' NOT HANDLED *needs to be translated?*';
                        break;
                }
            }
            if ( $readonly != '' ) {
                echo ' * readonly';
            }

            echo '</div></div>';
        }
        ?>

    @endforeach
</div>
<div class="form-actions">
    <div class="col-md-offset-3 col-md-9">
        {!! Form::submit( "Submit", ['class' => 'btn btn-primary'] ) !!}
    </div>
</div>
