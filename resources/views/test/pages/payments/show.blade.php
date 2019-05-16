@extends('test.layouts.app')
@section('content')
<div class="container">
  <br><br><br><br><br>
  <div class="row" style="margin-top: -10%">
      <img src="{{ asset('images/logo2.png') }}" alt="" class="site_logo img-responsive center-block" style="width: 292px;margin-top: 45px;">
      <br><br>
    <div class="col-sm-6 col-sm-offset-3" style="border: 1px solid orangered;padding: 2% ;border-radius: 34px;">

      @foreach($errors->all() as $e)
        <div class="alert alert-warning">
          {{ $e }}
        </div>
      @endforeach
      <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <div class="count"><b>Transaction Amount: </b><i class="fa fa-inr" style="color:#ea6c44"></i> {{ $transaction->order_amount }}</div>
            </div>
        </div>
        <div class="col-sm-3">
          {{--<div class="form-group">---}}
            {{--<div class="count" ><b>Order ID: </b><i class="fa fa-id-badge" aria-hidden="true"></i> {{ $transaction->order_id }}</div>--}}
          {{--</div>--}}
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <div class="count" ><b>Order ID: </b><i class="fa fa-id-badge" aria-hidden="true"></i> {{ $transaction->order_id }}</div>
            </div>
        </div>
      </div>
      <form action="{{ route('test.payments.process', encrypt($transaction->id)) }}" method="POST">
        {{ csrf_field() }}
        <div>
          <ul class="nav nav-tabs nav-justified" role="tablist" id="method--list">
             @if(count(\App\PaymentMethodRoutes::getRoutes('credit_card')))
            <li role="presentation" class="active"><a href="#credit_card" aria-controls="credit_card" role="tab" data-toggle="tab" style="font-weight: 800;font-size: 16px;">Credit Card</a></li>
            @endif
            @if(count(\App\PaymentMethodRoutes::getRoutes('debit_card')))
            <li role="presentation"><a href="#debit_card" aria-controls="debit_card" role="tab" data-toggle="tab" style="font-weight: 800;font-size: 16px;">Debit Card</a></li>
            @endif
            @if(count(\App\PaymentMethodRoutes::getRoutes('net_banking')))
            <li role="presentation"><a href="#netbanking" aria-controls="netbanking" role="tab" data-toggle="tab" style="font-weight: 800;font-size: 16px;">NetBanking</a></li>
            @endif
            @if(count(\App\PaymentMethodRoutes::getRoutes('wallet')))
            <li role="presentation"><a href="#wallet" aria-controls="wallet" role="tab" data-toggle="tab" style="font-weight: 800;font-size: 16px;">Wallet</a></li>
            @endif
            {{-- <li role="presentation"><a href="#emi" aria-controls="emi" role="tab" data-toggle="tab">EMI</a></li>
            <li role="presentation"><a href="#cash_card" aria-controls="cash_card" role="tab" data-toggle="tab">Cash</a></li> --}}
          </ul>
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="credit_card">
              <br>
              <fieldset>
                <input type="hidden" name="payment_option" value="OPTCRDC">
                <input type="hidden" name="payment_method" value="credit_card">
                <div class="row">
                  <div class="col-sm-3">
                    <div class="form-group">
                      <label for="card_name" style="margin-top: 10%;">Name on Card</label>
                    </div>
                  </div>
                  <div class="col-sm-9">
                    <div class="form-group">
                      <input type="text" name="card_name" class="form-control name input-lg form-control-kartpay" id="card_name" placeholder="Cardholder Name" style="border-color: orangered;" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-5">
                    <div class="form-group">
                      <label for="card_number">Card Number</label>
                      <input type="tel" required autocomplete="off" name="card_number" id="card_number" class="form-control input-lg form-control-kartpay card-number" placeholder="1234 5678 9101 1212" style="border-color: orangered;" required>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label>Expiration Date <font color="red" id="font_credit_card_expiration_date_info"></font></label>
                      <div class="input-group input-group-custom">
                        <input type="tel" id="credit_card_expiry_month" name="expiry_month" pattern="[0-9]{2}" maxlength="2" required class="number form-control input-lg form-control-kartpay" style="border-color: orangered; width: 50%" placeholder="MM">
                        <input type="tel" id="credit_card_expiry_year" name="expiry_year" pattern="[0-9]{2}" maxlength="2" required class="number form-control input-lg form-control-kartpay"  style="width: 50%;border-left: 0px; border-color: orangered;" placeholder="YY">
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="form-group">
                      <label for="card_cvc">CVV Code</label>
                      <input type="password" pattern="[0-9]{3,4}" size="4" maxlength="4"
                        autocomplete="off" name="card_cvc" id="credit_card_cvc" required class="form-control cvc input-lg form-control-kartpay" style="border-color: orangered;" placeholder="XXXX">
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            <div role="tabpanel" class="tab-pane" id="debit_card">
              <br>
              <fieldset disabled>
                <input type="hidden" name="payment_option" value="OPTDBCRD">
                <input type="hidden" name="payment_method" value="debit_card">
                <div class="row">
                  <div class="col-sm-3">
                    <div class="form-group">
                      <label for="card_name" style="margin-top: 10%;">Name on Card</label>
                    </div>
                  </div>
                  <div class="col-sm-9">
                    <div class="form-group">
                      <input type="text" name="card_name" class="form-control input-lg form-control-kartpay" id="card_name" placeholder="Cardholder Name" style="border-color: orangered;" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-5">
                   <div class="form-group">
                     <label for="card_number">Card Number</label>
                     <input type="tel" required autocomplete="off"
                       name="card_number" id="card_number" class="form-control input-lg form-control-kartpay card-number"
                        placeholder="1234 5678 9101 1212" style="border-color: orangered;" required>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label>Expiration Date <font color="red" id="font_debit_card_expiration_date_info"></font></label>
                      <div class="input-group input-group-custom">
                        <input type="tel" id="debit_card_expiry_month" name="expiry_month" pattern="[0-9]{2}" maxlength="2" required class="number form-control input-lg form-control-kartpay" style="width: 50%; border-color: orangered;" placeholder="MM">
                        <input type="tel" id="debit_card_expiry_year" name="expiry_year" pattern="[0-9]{2}" maxlength="2" required class="number form-control input-lg form-control-kartpay" style="width: 50%; border-color: orangered; border-left: 0px;" placeholder="YY">
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="form-group">
                      <label for="card_cvc">CVV Code</label>
                      <input type="password" pattern="[0-9]{3,4}" size="4" maxlength="4"
                        autocomplete="off" name="card_cvc" id="debit_card_cvc" required class="form-control input-lg form-control-kartpay" placeholder="XXXX" style="border-color: orangered;">
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="netbanking">
              <fieldset disabled>
                <input type="hidden" name="payment_option" value="OPTNBK">
                <input type="hidden" name="payment_method" value="net_banking">
                <br>
                <?php $banks = \App\PaymentMethods::activeMethods('net_banking'); ?>
                <div class="row">
                @for($x=0; $x<count($banks); $x++)
                    <div class="col-sm-4">
                      <label class="checkbox-inline">
                        <input type="radio" name="card_name" class="icheck" value="{{$banks[$x]['payment_type']}}" required><img id="" src="{{ asset($banks[$x]['logo']) }}" alt="{{$banks[$x]['payment_type']}}"  class="checkbox-inline" width="110" height="60">
                      </label>
                    </div>
                @endfor
                </div>
                <p class="text-center">
                  <a class="btn btn-default btn-sm text-muted collapsed" style="margin-right: 10px"
                    role="button" data-toggle="collapse" href="#netbank--list" aria-expanded="false"
                    aria-controls="netbank--list" id="more">MORE
                    <i class="fa fa-lg"></i>
                  </a>
                </p>
              </fieldset>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="wallet">
              <br>
              <fieldset disabled>
                <input type="hidden" name="payment_option" value="OPTWLT">
                <input type="hidden" name="payment_method" value="wallet">
                <?php $wallets = \App\PaymentMethods::activeMethods('wallet'); ?>
                <div class="row">
                @for($x=0; $x<count($wallets); $x++)
                    <div class="col-sm-4">
                      <label class="checkbox-inline">
                        <input type="radio" name="card_name" class="icheck" value="{{$wallets[$x]['payment_type']}}" required><img id="" src="{{ asset($wallets[$x]['logo']) }}" alt="{{$wallets[$x]['payment_type']}}"  class="checkbox-inline" width="110" height="60">
                      </label>
                    </div>
                 @endfor
                </div>
              </fieldset>
              <br>
            </div>
            {{-- <div role="tabpanel" class="tab-pane fade" id="emi">
              <fieldset disabled>
                <input type="hidden" name="payment_option" value="OPTEMI">
                emi
              </fieldset>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="cash_card">
              <fieldset disabled>
                <input type="hidden" name="payment_option" value="OPTCASHC">
                cash card
              </fieldset>
            </div> --}}
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <button class="btn btn-block btn-lg btn-kartpay" style="background: darkgreen;" data-loading-text="Processing...">Pay Now</button><br>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <button href="#" class="btn btn-block btn-lg btn-kartpay" style="background: orangered;" onclick="event.preventDefault();document.getElementById('cancel-form').submit();">Cancel</button><br>
              </div>
            </div>
          </div>
        </div>
      </form>
      <p class="text-center">
        <i><a class=" text-danger" onclick="event.preventDefault();document.getElementById('cancel-form').submit();">*On cancel you will return back to {{ parse_url($transaction->failed_url)['host'] }}</a></i>
      </p>
      <form id="cancel-form" action="{{ route('test.payments.cancel', encrypt($transaction->kartpay_id)) }}" method="POST" style="display: none;">
        {{ csrf_field() }}
      </form>
    </div>
  </div>
</div>
{{-- <iframe src="" id="hello" frameborder="0" class="hidden" sandbox></iframe> --}}
@stop
@push('scripts')
<script src="{{asset('plugins/mask/mask.min.js')}}"></script>
<script src="{{asset('js/creditcard.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>
<!-- Seamless Child -->
<script type="text/javascript" src="{{asset('js/vendor/seamless.child.min.js')}}"></script>
<script>
  $(".icheck").iCheck({
    checkboxClass: 'icheckbox_flat-red',
    radioClass: 'iradio_flat-red'
  });
  $("#method--list a[data-toggle='tab']").on('show.bs.tab', function (e) {
    $(e.target.hash).find('fieldset').prop('disabled', false)
    $(e.relatedTarget.hash).find('fieldset').prop('disabled', true)
  })
  {{--
  $("form").on('submit', function (e) {
    $btn = $("button").button('loading')
    e.preventDefault()
    $.ajax({
      url: '{{ route('test.payments.process', $transaction->kartpay_id) }}',
      method: 'POST',
      data: $(this).serialize(),
    }).success(function (data) {
      console.log(data)
      $("iframe").attr('src',data)
      $btn.button('reset')
    }).error(function (data) {
      console.log(data)
    })
  })
  --}}
  function cancelForm() {
    $.ajax({
      url:'',
      method: 'POST',
      data: {
        '_token': '{{ csrf_token() }}'
      }
    })
  }

//Check month year validation
function CheckMonthYear(month, year)
{
  if(month == '' && year == '') return true;

  if(month != '')
  {
    if(/0[1-9]|1[0-2]/.test(month))
    {
      if(year == '')
      {
        return true;
      }
      else
      {
        return CheckYear(month, year);
      }
    }
  }
  else if(year != '')
  {
    return CheckYear(month, year);
  }
  else
  {
    return false;
  }
}
//END Check month validation

//Check year validation
function CheckYear(month, year)
{
  if(/1[7-9]|2[0-9]|3[0]/.test(year))
  {
    //Check combination mm and yy is validation
    expired_month = month;
    expired_year = year;
    check_month_format = month.substr(0, 1);
    if(check_month_format == '0')
    {
      expired_month = month.substr(1, 1);
    }
    current_month = new Date().getMonth();
    current_month = current_month + 1;

    current_year = new Date().getFullYear();
    current_year = current_year.toString().substr(-2);

    //expired
    if(expired_year < current_year)
    {
      return false;
    }
    if(expired_month <= current_month && expired_year == current_year)
    {
      return false;
    }
    //END expired

    //valid
    return true;
    //END valid

    //END Check combination mm and yy is validation
  }
  else
  {
    return false;
  }
}
//END Check year validation

$(function() {

  //Disable Back Button, execute cancel-form when user click it
  if (window.history && window.history.pushState) {

      $(window).on('popstate', function() {
        var hashLocation = location.hash;
        var hashSplit = hashLocation.split("#!/");
        var hashName = hashSplit[1];

        if (hashName !== '')
        {
          var hash = window.location.hash;
          if (hash === '')
          {
            $('form#cancel-form').submit();
          }
        }
      });

      window.history.pushState('forward', null, '');
    }
    //END Disable Back Button, execute cancel-form when user click it

  //Expired month validation
  $("#credit_card_expiry_month").on('keyup blur', function() {
      if($("#credit_card_expiry_month").val() != '')
      {
        if(CheckMonthYear($("#credit_card_expiry_month").val(), $("#credit_card_expiry_year").val()))
        {
          $("#credit_card_expiry_year").focus();
        }
        else
        {
          $('#font_credit_card_expiration_date_info').html('<small>(Invalid)</small>');
          $('#font_credit_card_expiration_date_info').attr('color', 'red');
          $("#credit_card_expiry_month").focus();
        }
      }
  });
  //End Expired month validation

  //Expired year validation
  $("#credit_card_expiry_year").on('keyup blur', function() {
      if($("#credit_card_expiry_year").val() != '')
      {
        if(CheckMonthYear($("#credit_card_expiry_month").val(), $("#credit_card_expiry_year").val()))
        {
          $('#font_credit_card_expiration_date_info').html('<small>(Valid)</small>');
          $('#font_credit_card_expiration_date_info').attr('color', 'green');
          $("#credit_card_cvc").focus();
        }
        else
        {
          $('#font_credit_card_expiration_date_info').html('<small>(Invalid)</small>');
          $('#font_credit_card_expiration_date_info').attr('color', 'red');
          $("#credit_card_expiry_year").focus();
        }
      }
  });
  //End Expired year validation

  //Expired month validation
  $("#debit_card_expiry_month").on('keyup blur', function() {
      if($("#debit_card_expiry_month").val() != '')
      {
        if(CheckMonthYear($("#debit_card_expiry_month").val(), $("#debit_card_expiry_year").val()))
        {
          $("#debit_card_expiry_year").focus();
        }
        else
        {
          $('#font_debit_card_expiration_date_info').html('<small>(Invalid)</small>');
          $('#font_debit_card_expiration_date_info').attr('color', 'red');
          $("#debit_card_expiry_month").focus();
        }
      }
  });

  //End Expired month validation

  //Expired year validation
  $("#debit_card_expiry_year").on('keyup blur', function() {
      if($("#debit_card_expiry_year").val() != '')
      {
        if(CheckMonthYear($("#debit_card_expiry_month").val(), $("#debit_card_expiry_year").val()))
        {
          $('#font_debit_card_expiration_date_info').html('<small>(Valid)</small>');
          $('#font_debit_card_expiration_date_info').attr('color', 'green');
          $("#debit_card_cvc").focus();
        }
        else
        {
          $('#font_debit_card_expiration_date_info').html('<small>(Invalid)</small>');
          $('#font_debit_card_expiration_date_info').attr('color', 'red');
          $("#debit_card_expiry_year").focus();
        }
      }
  });
  //End Expired year validation
});
</script>
@endpush
@push('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/flat/_all.css">
  <style>
    #method--list li>a{
      white-space: nowrap;
      color: rgba(212,56,8,0.99);
    }
    #method--list li.active a{
      border-bottom: 5px solid rgba(212,56,8,0.99);
      border-radius: 5px 5px 0px 0px;
    }
    label.checkbox-inline{
      padding-left: 10px;

    }
    label.checkbox-inline>div:first-child{
      margin-right: 5px;
    }
    label.checkbox-inline{
      font-size: 13px;
    }
    #more.collapsed>i:before{
      content: "\f078" !important;
      color: #333;
    }
    #more>i:before{
      content: "\f077";
      color: #333;
    }
    fieldset .row{
      margin-bottom: 10px;
    }
    label.input{ /* HIDE RADIO */
      visibility: hidden; /* Makes input not-clickable */
      position: absolute; /* Remove input from document flow */
    }
    label.input + img{ /* IMAGE STYLES */
      cursor:pointer;
      border:2px solid transparent;
    }
    label.input:checked + img{ /* (RADIO CHECKED) IMAGE STYLES */
      border:2px solid #f00;
    }
  </style>
@endpush
