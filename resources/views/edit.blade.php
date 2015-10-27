@extends( $masterTemplate )
@section('title', 'Edit')

@section( 'content' )

    <div class="col-lg-6">
        <div class="panel panel-primary">
            <div class="panel-heading">Edit {{ $table_name }}</div>

            <div class="panel-body pan">


                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {!! Form::model( $data, ['route' => [ $resource_link . '.update', $id], 'method' => 'PATCH', 'class' =>
                'form-horizontal'
                ] ) !!}

                @include( 'genericcrud::form' )

                {!! Form::close() !!}

            </div>
        </div>
    </div>
    <div class="clearfix"></div>



@stop