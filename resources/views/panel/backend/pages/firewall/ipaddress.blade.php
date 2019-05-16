@extends('panel.backend.layouts.app')
@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
    	@if (count($errors) > 0)
            <div class="alert alert-danger">
                <button class="close" data-close="alert"></button>
                @foreach($errors->all() as $error)
                    <span>{{$error}}</span><br/>
                @endforeach
            </div>
        @endif

        @if(session('success'))
            <div class="row">
                <div class="alert alert-success alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
                        {{session('success')}}
                </div>
            </div>
        @endif
        <a href="#whitelist" data-toggle="modal" class="btn btn-success">Add Whitelist IP</a>
        <a href="#blacklist" data-toggle="modal" class="btn btn-danger">Add Blacklist IP</a>
    </div>
    <div class="col-sm-12">
        <table class="table table-hovered table-bordered">
            <thead>
                <tr>
                    <th>IP</th>
                    <th>STATUS</th>
                    <th>LAST UPDATE</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ipaddress as $ip)
                    <tr>
                        <td>{{ $ip->ip_address }}</td>
                        <td>
                            @if($ip->whitelisted)
                                <span class="label label-info">ACTIVE</span>
                            @else
                                <span class="label label-danger">BLOCKED</span>
                            @endif
                        </td>
                        <td>{{ $ip->updated_at->format("M d, Y") }}</td>
                        <td class="col-sm-2">
                            <form method="POST">
                            @if($ip->whitelisted)
                                <button formaction="{{ route('admins.firewall.blacklist') }}" class="btn btn-sm btn-danger">BLACKLIST</button>
                            @else
                                <button formaction="{{ route('admins.firewall.whitelist') }}" class="btn btn-sm btn-success">WHITELIST</button>
                            @endif
                                <button formaction="{{ route('admins.firewall.remove') }}" class="btn btn-sm btn-primary">REMOVE</button>
                                {{ csrf_field() }}
                                <input type="hidden" name="ipaddress" value="{{ $ip->ip_address }}">
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="blacklist">
    <form action="{{ route('admins.firewall.blacklist') }}" method="POST">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Blacklist</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" name="ipaddress">
                    </div>
                    {{ csrf_field() }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-danger">Submit</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </form>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="whitelist">
    <form action="{{ route('admins.firewall.whitelist') }}" method="POST">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Whitelist</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" name="ipaddress">
                    </div>
                    {{ csrf_field() }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </form>
</div>
@endsection
@push('scripts')
<script>
    $(function() {
        $("table").DataTable()
    })
</script>
@endpush