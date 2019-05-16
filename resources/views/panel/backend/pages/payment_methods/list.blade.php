@extends('panel.backend.layouts.app')
@section('content')
<div class="row" ng-app="kartpay_app">
    <div ng-controller="PaymentMethodsController">
        <div class="col-md-12 col-sm-12 col-xs-12">
        <h3>{{ucwords(\App\PaymentMethods::$payment_method[$payment_method])}} Gateway Settings</h3>
        <br>
            <div class="panel panel-default">
                <div class="panel-body">
            	@if(count($list))
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <th width="10%">Sr. No.</th>
                                <th width="30%">Payment Type</th>
                                <th width="20%">Payment Method</th>
                                <th width="20%">Status</th>
                            </thead>
                            <tbody>
                                @foreach($list as $index => $row)
                                    <tr style="text-align:center;">
                                        <td class="valign">{{$index + 1}}</td>
                                        <td class="text-center valign">
                                            <div class="{{($row['payment_method'] == 'net_banking') ? 'col-sm-12' : 'col-sm-4'}}">
                                                <a href="{{url('/payment_method_routes/method/'.$row['id'])}}">
                                                @if($row['logo'])
                                                    <img style="width:100%; max-height:100px;"  src="{{asset($row['logo'])}}"/>
                                                @else
                                                    {{$row['payment_type']}}
                                                @endif
                                                </a>
                                            </div>
                                        </td>
                                        <td class="valign">{{ucwords(\App\PaymentMethods::$payment_method[$row['payment_method']])}}</td>
                                        <td class="valign">
                                            {{Form::select('status', \App\PaymentMethods::$status, $row['status'], ['class' => 'form-control', 'onchange' => 'setStatus(this)', 'data-id' => $row['id']])}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                   
                @else
                    <h1>No Results Found.</h1>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <style type="text/css">
        .valign{
            vertical-align:middle;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{asset('js/angular/payment_methods.js')}}"></script>
@endpush
