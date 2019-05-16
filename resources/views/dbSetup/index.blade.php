<!DOCTYPE html>
<html lang="en" class=" ">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{config('app.name')}}</title>
    <!-- Bootstrap -->
    <link href="{{asset('css/vendor/bootstrap.min.css')}}" rel="stylesheet">
</head>

<body class="nav-sm">
<div class="container body">
    <div class="jumbotron">
        <h1>{{trans('dbSetup.appName')}}</h1>
        <p>{{trans('dbSetup.introText')}}</p>
    </div>
    @if (count($errors) > 0)
        <div class="alert alert-danger col-xs-6">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="clearfix"></div>
    <form method="post" action="{{route('postDbCredentials')}}">
        <div class="form-group col-xs-6">
            <label for="username">{{trans('dbSetup.userName')}}:</label>
            <input name="username" type="text" class="form-control" id="username" placeholder="{{trans('dbSetup.userNamePlaceHolder')}}">
        </div>
        <div class="col-xs-12"></div>

        <div class="form-group col-xs-6">
            <label for="password">{{trans('dbSetup.password')}}:</label>
            <input type="text" class="form-control" id="password" name="password" placeholder="{{trans('dbSetup.passwordPlaceHolder')}}">
        </div>
        <div class="col-xs-12"></div>

        <div class="form-group col-xs-6">
            <label for="database">{{trans('dbSetup.database')}}:</label>
            <input type="text" class="form-control" id="database" name="database" placeholder="{{trans('dbSetup.databasePlaceHolder')}}">
        </div>
        <div class="col-xs-12"></div>

        <div class="form-group col-xs-6">
            <label for="host">{{trans('dbSetup.host')}}:</label>
            <input type="text" class="form-control" id="host" name="host" placeholder="{{trans('dbSetup.hostPlaceHolder')}}">
        </div>
        <div class="col-xs-12"></div>

        <div class="form-group col-xs-6">
            <label for="port">{{trans('dbSetup.port')}}:</label>
            <input type="text" class="form-control" id="port" name="port" placeholder="{{trans('dbSetup.portPlaceHolder')}}">
        </div>
        <div class="col-xs-12"></div>
        {{csrf_field()}}
        <div class="col-xs-6">
            <button type="submit" class="btn btn-default">{{trans('dbSetup.submit')}}</button>
        </div>
        <div class="col-xs-12"></div>
    </form>
    <div class="clearfix"></div>
    <br>

</div>

<!-- jQuery -->
<script src="{{asset('js/vendor/jquery.min.js')}}"></script>
<!-- Bootstrap -->
<script src="{{asset('js/vendor/bootstrap.min.js')}}"></script>
</body>
</html>