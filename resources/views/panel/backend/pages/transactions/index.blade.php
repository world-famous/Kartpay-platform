@extends('panel.backend.layouts.app')
@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Transactions</h3>
                <hr>
            </div>
        </div>
    </div>
    <div>
        <div class="row">
            <div class="col-sm-12">
                @if($errors->count())
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3">
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <p>{{$error}}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group" id="size-group">
                            <div class="input-group">
                                <span class="input-group-addon">Show</span>
                                <select class="form-control">
                                    <option {{$pageSize == 10?'selected':''}}>10</option>
                                    <option {{$pageSize == 25?'selected':''}}>25</option>
                                    <option {{$pageSize == 50?'selected':''}}>50</option>
                                    <option {{$pageSize == 100?'selected':''}}>100</option>
                                </select>
                                <span class="input-group-addon">Entries</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-sm-offset-2" id="search-group">
                        <form>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-btn">
                                        <!-- Single button -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default dropdown-toggle"
                                                    data-toggle="dropdown" aria-expanded="false">
                                                <span class="title">{{$searchColumns[$currentColumn]}}</span> <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                @foreach($searchColumns as $key => $title)
                                                    <li><a data-column="{{$key}}" href="#">{{$title}}</a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <input class="form-control" placeholder="Search..." value="{{$searchString}}">
                                    <div class="input-group-btn">
                                        <button class="btn btn-default submit">
                                            <i class="glyphicon glyphicon-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive" style="text-align: center;">
                    <table class="table table table-bordered table-condensed" id="transactions" style="width: 75%;">
                        <thead>
                        <tr>
                            <th style="width: 5%;text-align: center;">Sr No.</th>
                            <th style="width: 8%;text-align: center;">Kartpay ID</th>
                            <th style="width: 7%;text-align: center;">Txn Type</th>
                            <th style="width: 10%;text-align: center;">Date - Time</th>
                            <th style="width: 7%;text-align: center;">Order ID</th>
                            <th style="width: 10%;text-align: center;">Order Amount</th>
                            <th style="width: 8%;text-align: center;">Txn Status</th>
                            <th style="width: 7%;text-align: center;">View More</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($transactions as $index => $transaction)
						@php
							$index++;
						@endphp
                            <tr>
                                <td>{{ $index}}</td>
                                <td>{{ $transaction->kartpay_id }}</td>
                                <td>
									@php
										if(isset($transaction->access_code->type)) echo $transaction->access_code->type;
									@endphp
								</td>
                                <td>{{ $transaction->created_at->format("d/M/Y H:i") }}</td>
                                <td>{{ $transaction->order_id }}</td>
                                <td>{{ $transaction->order_amount }}</td>
                                <td>{{ $transaction->status }}</td>
                                <td>
                                    <a href="{{ route('admins.transactions.show', $transaction->kartpay_id) }}"
                                       class="btn btn-link text-muted">
                                        View More
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-center">{{$transactions->render()}}</div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
<style>
    #transactions th, td {
        text-align: center;
    }

    #size-group {
        max-width: 200px;
    }

    #size-group .input-group-addon {
        border: none;
        background: none;
    }
</style>
@endpush
@push('scripts')
<script>
    (function ($) {
        var searchGroup = $('#search-group'),
            sizeGroup = $('#size-group');
        searchGroup.find('.dropdown-menu a').click(function (e) {
            e.preventDefault();
            searchGroup.find('.dropdown-toggle .title').html(this.innerHTML)
        });
        searchGroup.find('form').submit(updateUrl);
        sizeGroup.find('select').change(updateUrl);

        function updateUrl(e) {
            e && e.preventDefault();
            var pageSize = sizeGroup.find('select').val(),
                search = searchGroup.find('input').val(),
                column = null,
                title = searchGroup.find('.dropdown-toggle .title').html();

            searchGroup.find('.dropdown-menu a').each(function () {
                if (this.innerHTML === title) {
                    column = $(this).data('column');
                }
            });
            var url = window.location.pathname + '?size=' + pageSize;
            if (search.trim()) {
                url += '&column=' + column + '&search=' + search;
            }
            window.location.href = url;
        }
    }(jQuery))
</script>
@endpush
