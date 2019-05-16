<!-- Start Session Modal -->
<div id="myModalSession" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
        <h4 class="modal-title" id="session_modal_title">Modal Header</h4>
      </div>
      <div class="modal-body" style="text-align:center;" id="session_modal_body">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer" id="session_modal_footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!-- End Session Modal -->
@push('scripts')
<script>
  var popup = 0
  var idleTime = 0;
  var idleInterval = '';
  var incrementTime = 1000 //30000; // 30 seconds
    $(document).ready(function () {
        //Increment the idle time counter every minute.
        idleInterval = setInterval(timerIncrement, incrementTime);

        //Zero the idle timer on mouse movement.
        $(this).mousemove(function (e) {
            if(idleTime < {{ config('session.timeout') * 60 }} && popup == 0)
      {
        clearInterval(idleInterval);
        idleTime = 0;
        idleInterval = setInterval(timerIncrement, incrementTime);
      }
        });
        $(this).keypress(function (e) {
            if(idleTime < {{ config('session.timeout') * 60 }} && popup == 0)
      {
        clearInterval(idleInterval);
        idleTime = 0;
        idleInterval = setInterval(timerIncrement, incrementTime);
      }
        });
    });

    function timerIncrement() {
        idleTime = idleTime + 1;
    if (idleTime >= {{ ((config('session.timeout') * 60) - config('session.timeout_delay')) }}) { // 30 seconds of idle
            ShowPopUpSession();
        }
        if (idleTime >= {{ (config('session.timeout') * 60) }}) { // 1 minute
            $("#logout-form").submit();
        }
    }

  function resetIdleTime()
  {
        popup = 0
    clearInterval(idleInterval);
    idleTime = 0;
    idleInterval = setInterval(timerIncrement, incrementTime);
  }

  function ShowPopUpSession()
  {
        popup = 1
    $('#session_modal_title').html('Notification Session');
    $('#session_modal_body').html('{{ config('session.timeout_message') }}');
    $('#session_modal_footer').html('<button type="button" class="btn btn-success" data-dismiss="modal" onclick="resetIdleTime()">Continue</button>');
    $('#myModalSession').modal('show');
  }
</script>
@endpush
