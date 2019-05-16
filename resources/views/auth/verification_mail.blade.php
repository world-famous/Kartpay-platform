<!DOCTYPE html>
<html>
<head>
<title>Font Awesome Icons</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-md-6">
							<span class="help-block">
								<p>Hi {{ $name }},</p>
								<br>
								<p><i>{{ $body }} {{ $url }}</i></p>
							</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html> 
