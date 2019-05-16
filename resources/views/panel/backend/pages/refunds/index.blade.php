@extends('panel.backend.layouts.app')
@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Refunds</h3>
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
                <div class="table-responsive">
                    <table class="table table table-bordered table-condensed" id="refunds">
                        <thead>
                        <tr>
                            <th>Sr No.</th>
                            <th>Kartpay ID</th>
                            <th>Tnx Type</th>
                            <th>Date - Time</th>
                            <th>Merchant Amount</th>
                            <th>Request Status</th>
                            <th>Status</th>
                            <th>View More</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($refunds as $index => $refund)
                            <tr>
                                <td>{{ $index +=1 }}</td>
                                <td>{{ $refund->id }}</td>
                                <td>
									{{ $refund->access_code->type }}
								</td>
                                <td>{{ $refund->created_at->format("d-F-Y H:i") }}</td>
                                <td>{{ $refund->refund_amount }}</td>
                                <td>{{ $refund->status }}</td>
                                <td>{{ $refund->approved_at ? 'Success' : 'Pending' }}</td>
                                <td>
                                    <a href="{{ route('admins.refunds.show', $refund->id) }}"
                                       class="btn btn-link text-muted">
                                        View More
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-center">{{$refunds->render()}}</div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
<style>
    #refunds th, td {
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
