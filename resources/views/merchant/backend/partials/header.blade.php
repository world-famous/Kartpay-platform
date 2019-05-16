<div class="top_nav">
    <div class="nav_menu" style="height: 70px;">

      @if(config('app_env')['app_env'] == 'staging')
      <span class="nav_menu" style="background:#26cc18;float:right;font-size:20px;text-align:center;color:white;">
        You are the Test Environment
      </span>
      @endif

			@if(Auth::guard('merchant')->user()->live_domain_active == '0')
			<span class="nav_menu" style="background:#26cc18;float:right;font-size:20px;text-align:center;height: 25px;">
				@if(Auth::guard('merchant')->user()->last_activation_step == 'step3' || Auth::guard('merchant')->user()->last_activation_step == 'step4')

					@php
						$document = Auth::guard('merchant')->user()->merchantDocument;
					@endphp
          
					@if($document)
						@php
							$total_doc = 0;
              $total_doc_goverment_issue = 0; //require = 2
              $total_doc_proprietor_address = 0; //require = 1
              $total_doc_business_related = 0; //require = 2
							$total_verified = 0;
              $total_reject = 0;
							$total_received = 0;
							$total_not_received = 0;

							if($document->proprietor_pan_card_file != null) $total_doc++;
							if($document->proprietor_pan_card_is_verified == 'APPROVE') $total_verified++;
              if($document->proprietor_pan_card_is_verified == 'REJECT') $total_reject++;
							if($document->gumasta_file != null)
              {
                $total_doc++;
                $total_doc_goverment_issue++;
              }
							if($document->gumasta_is_verified == 'APPROVE') $total_verified++;
              if($document->gumasta_is_verified == 'REJECT') $total_reject++;
							if($document->importer_exporter_code_file != null)
              {
                $total_doc++;
                $total_doc_goverment_issue++;
              }
							if($document->importer_exporter_code_is_verified == 'APPROVE') $total_verified++;
              if($document->importer_exporter_code_is_verified == 'REJECT') $total_reject++;
							if($document->gst_in_file != null)
              {
                $total_doc++;
                $total_doc_goverment_issue++;
              }
							if($document->gst_in_is_verified == 'APPROVE') $total_verified++;
              if($document->gst_in_is_verified == 'REJECT') $total_reject++;
							if($document->passport_file != null)
              {
                $total_doc++;
                $total_doc_proprietor_address++;
              }
							if($document->passport_is_verified == 'APPROVE') $total_verified++;
              if($document->passport_is_verified == 'REJECT') $total_reject++;
							if($document->aadhar_card_file != null)
              {
                $total_doc++;
                $total_doc_proprietor_address++;
              }
							if($document->aadhar_card_is_verified == 'APPROVE') $total_verified++;
              if($document->aadhar_card_is_verified == 'REJECT') $total_reject++;
							if($document->driving_license_file != null)
              {
                $total_doc++;
                $total_doc_proprietor_address++;
              }
							if($document->driving_license_is_verified == 'APPROVE') $total_verified++;
              if($document->driving_license_is_verified == 'REJECT') $total_reject++;
							if($document->voter_id_card_file != null)
              {
                $total_doc++;
                $total_doc_proprietor_address++;
              }
							if($document->voter_id_card_is_verified == 'APPROVE') $total_verified++;
              if($document->voter_id_card_is_verified == 'REJECT') $total_reject++;
							if($document->property_tax_receipt_file != null)
              {
                $total_doc++;
                $total_doc_proprietor_address++;
              }
							if($document->property_tax_receipt_is_verified == 'APPROVE') $total_verified++;
              if($document->property_tax_receipt_is_verified == 'REJECT') $total_reject++;
							if($document->bank_canceled_cheque_file != null)
              {
                $total_doc++;
                $total_doc_business_related++;
              }
							if($document->bank_canceled_cheque_is_verified == 'APPROVE') $total_verified++;
              if($document->bank_canceled_cheque_is_verified == 'REJECT') $total_reject++;
							if($document->audited_balance_sheet_file != null)
              {
                $total_doc++;
                $total_doc_business_related++;
              }
							if($document->audited_balance_sheet_is_verified == 'APPROVE') $total_verified++;
              if($document->audited_balance_sheet_is_verified == 'REJECT') $total_reject++;
							if($document->current_account_statement_file != null)
              {
                $total_doc++;
                $total_doc_business_related++;
              }
							if($document->current_account_statement_is_verified == 'APPROVE') $total_verified++;
              if($document->current_account_statement_is_verified == 'REJECT') $total_reject++;
							if($document->income_tax_return_file != null)
              {
                $total_doc++;
                $total_doc_business_related++;
              }
							if($document->income_tax_return_is_verified == 'APPROVE') $total_verified++;
							if($document->kartpay_merchant_agreement_file != null) $total_doc++;
							if($document->kartpay_merchant_agreement_is_verified == 'APPROVE') $total_verified++;
              if($document->kartpay_merchant_agreement_is_verified == 'REJECT') $total_reject++;

							if($document->proprietor_pan_card_is_received == 'RECEIVED') $total_received++;
							if($document->gumasta_is_received == 'RECEIVED') $total_received++;
							if($document->importer_exporter_code_is_received == 'RECEIVED') $total_received++;
							if($document->gst_in_is_received == 'RECEIVED') $total_received++;
							if($document->passport_is_received == 'RECEIVED') $total_received++;
							if($document->aadhar_card_is_received == 'RECEIVED') $total_received++;
							if($document->driving_license_is_received == 'RECEIVED') $total_received++;
							if($document->voter_id_card_is_received == 'RECEIVED') $total_received++;
							if($document->property_tax_receipt_is_received == 'RECEIVED') $total_received++;
							if($document->bank_canceled_cheque_is_received == 'RECEIVED') $total_received++;
							if($document->audited_balance_sheet_is_received == 'RECEIVED') $total_received++;
							if($document->current_account_statement_is_received == 'RECEIVED') $total_received++;
							if($document->income_tax_return_is_received == 'RECEIVED') $total_received++;
							if($document->kartpay_merchant_agreement_is_received == 'RECEIVED') $total_received++;

							if($document->proprietor_pan_card_is_received == 'NOT RECEIVED') $total_not_received++;
							if($document->gumasta_is_received == 'NOT RECEIVED') $total_not_received++;
							if($document->importer_exporter_code_is_received == 'NOT RECEIVED') $total_not_received++;
							if($document->gst_in_is_received == 'NOT RECEIVED') $total_not_received++;
							if($document->passport_is_received == 'NOT RECEIVED') $total_not_received++;
							if($document->aadhar_card_is_received == 'NOT RECEIVED') $total_not_received++;
							if($document->driving_license_is_received == 'NOT RECEIVED') $total_not_received++;
							if($document->voter_id_card_is_received == 'NOT RECEIVED') $total_not_received++;
							if($document->property_tax_receipt_is_received == 'NOT RECEIVED') $total_not_received++;
							if($document->bank_canceled_cheque_is_received == 'NOT RECEIVED') $total_not_received++;
							if($document->audited_balance_sheet_is_received == 'NOT RECEIVED') $total_not_received++;
							if($document->current_account_statement_is_received == 'NOT RECEIVED') $total_not_received++;
							if($document->income_tax_return_is_received == 'NOT RECEIVED') $total_not_received++;
							if($document->kartpay_merchant_agreement_is_received == 'NOT RECEIVED') $total_not_received++;

			        $documentCouriers = Auth::guard('merchant')->user()->merchantDocument->documentCouriers;
            @endphp

              <!-- if all documents are approved -->
							@if($total_doc == $total_verified && $total_received == 0 && $total_not_received == 0 && count($documentCouriers) == 0)
								<a href="{{ route('merchants.activation.settlement') }}" style="color:white;">Uploaded Document is successful, Please send the Physical documents by clicking here</a>

              <!-- if any document is rejected -->
							@elseif($total_reject > 0)
								<a href="{{ route('merchants.activation.not_approve_document') }}" style="color:white;">Some Uploaded documents are rejected. Please Upload again</a>

              <!-- if all documents are received -->
							@elseif($total_doc == $total_received && count($documentCouriers) == 0)
								<a style="color:white;">All Documents Received Successfully, Your Account is under Review</a>

              <!-- if any document is not receive -->
							@elseif($total_not_received > 0 && Auth::guard('merchant')->user()->merchantDocument->is_admin_approval == '0' && $document->courier_id == null)
								<a href="{{ route('merchants.activation.not_receive_document') }}" style="color:white;">Your Some Physical documents are missing, Please Send again by click here</a>

              <!-- if document is uploaded completely -->
              @elseif($total_doc_goverment_issue >= 2 && $total_doc_proprietor_address >= 1 && $total_doc_business_related >= 2 && count($documentCouriers) == 0)
                <a style="color:white;">Compliance Team is verifying your Uploaded document</a>

              <!-- if merchant just input courier id -->
							@elseif($document->courier_id != null && count($documentCouriers) > 0)
								<a style="color:white;">Your document is on the Way to our Office</a>

							@endif

          <!-- if merchant uploaded documents and waiting for admin to approve it -->
					@else
						<a href="{{ route('merchants.activation.status') }}" style="color:white;">Account is Under Review with Compliance Team</a>
					@endif

        <!-- if no document uploaded -->
				@else
					<a href="{{ route('merchants.activation.check_step') }}" style="color:white;">Activate your Account</a>
				@endif
			</span>
			@endif
        <nav>
            <div class="nav toggle" style="margin-top: -17px;">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="margin-top: -9%;">
                        <img src="{{ Auth::guard('merchant')->user()->avatar }}" alt="">{{ Auth::guard('merchant')->user()->first_name }} {{ Auth::guard('merchant')->user()->last_name }}
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li><a href="{{ url('/profile') }}"> Profile</a></li>
                        <!-- <li><a href="{{ url('/firm') }}"> Firm</a></li> -->

						@if(Auth::guard('merchant')->user()->type == 'merchant')
                        <li><a href="{{ url('/user_administration') }}"> Manage Users</a></li>
						@endif

                        <li>
                            <a href="{{ route('merchants.settings.index') }}">
                                <span>Settings</span>
                            </a>
                        </li>
                        <li><a href="{{ url('/log') }}"> Logs</a></li>
                        <li><a href="{{ url('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
													 <i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
						<form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
							{{ csrf_field() }}
						</form>
                    </ul>
                </li>

				<!--
                <li role="presentation" class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-envelope-o"></i>
                        <span class="badge bg-red">6</span>
                    </a>
                    <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                        <li>
                            <a>
                                <span class="image"><img src="{{asset('images/vendor/test.jpg')}}" alt="Profile Image"></span>
                                <span>
                                    <span>John Smith</span>
                                    <span class="time">3 mins ago</span>
                                </span>
                                <span class="message">
                                    Film festivals used to be do-or-die moments for movie makers. They were where...
                                </span>
                            </a>
                        </li>
                        <li>
                            <a>
                                <span class="image"><img src="{{asset('images/vendor/test.jpg')}}" alt="Profile Image"></span>
                                <span>
                                    <span>John Smith</span>
                                    <span class="time">3 mins ago</span>
                                </span>
                                <span class="message">
                                    Film festivals used to be do-or-die moments for movie makers. They were where...
                                </span>
                            </a>
                        </li>
                        <li>
                            <a>
                                <span class="image"><img src="{{asset('images/vendor/test.jpg')}}" alt="Profile Image"></span>
                                <span>
                                    <span>John Smith</span>
                                    <span class="time">3 mins ago</span>
                                </span>
                                <span class="message">
                                    Film festivals used to be do-or-die moments for movie makers. They were where...
                                </span>
                            </a>
                        </li>
                        <li>
                            <a>
                                <span class="image"><img src="{{asset('images/vendor/test.jpg')}}" alt="Profile Image"></span>
                                <span>
                                    <span>John Smith</span>
                                    <span class="time">3 mins ago</span>
                                </span>
                                <span class="message">
                                    Film festivals used to be do-or-die moments for movie makers. They were where...
                                </span>
                            </a>
                        </li>
                        <li>
                            <div class="text-center">
                                <a>
                                    <strong>See All Alerts</strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
				-->

            </ul>
        </nav>
    </div>
</div>
