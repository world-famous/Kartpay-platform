@extends('panel.backend.layouts.app')
@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <br><br>

         @if(session('success'))
            <div class="row">
                <div class="alert alert-success alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
                        {{session('success')}}
                </div>
            </div>
        @endif

        <div class="panel panel-default">
            {{Form::open(['url' => 'payment_method_routes/update_status/' . $payment_method['id']])}}
            <div class="panel-heading"> 
                <h4>
                    @if($payment_method['payment_method'] == 'net_banking')
                        {{$payment_method['payment_type']}}
                    @else
                        {{ucwords(\App\PaymentMethods::$payment_method[$payment_method['payment_method']]) }} - {{ \App\PaymentMethods::$payment_type[$payment_method['payment_type']] }}
                    @endif
                </h4>
            </div>
            <div class="panel-body">
                <br>
                @if(count($routes))
                    @foreach($routes as $row)
                       <div class="checkbox">
                          <label>
                            {{Form::checkbox('routes['.$row['id'].']', 1, $row['status'])}}
                            <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                <i>{{$row['route']}}</i>
                          </label>
                        </div>
                    @endforeach
                @else
                    <h1>No Existing Routes Found.</h1>
                @endif
            </div>
            <div class="panel-footer text-center">
                @if(count($routes))
                    {{Form::submit('Save', ['class' => 'btn btn-primary'])}}
                @endif
                <a href="{{url('payment_methods/list/' . $payment_method['payment_method'])}}" class="btn btn-default">Cancel</a>
            </div>
            {{Form::close()}}
        </div>

    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/checkbox.css') }}">
@endpush