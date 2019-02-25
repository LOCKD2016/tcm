<!DOCTYPE html>
<html>
<head>
    <title> The page cannot be found</title>


    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            color: #B0BEC5;
            display: table;
            font-weight: 100;
            font-family: 'Lato', sans-serif;
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 72px;
            margin-bottom: 40px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        @if(isset($message))
            <div class="title">404 {{$message}}</div>
        @else
            <div class="title">404 The page cannot be found</div>
        @endif
    </div>
</div>
</body>
</html>
