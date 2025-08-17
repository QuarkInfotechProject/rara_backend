<html>
<head>
    <title>mail</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        .container{
            padding: 32px;
            background-color: white;
            /* width: 500px; */
            margin: auto;
            /* width: 100%; */
            max-width: 500px;
        }
        .link{
            color: blue;
        }
        .button{
            box-sizing: border-box;
            font-family: 'Segoe UI',Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';
            border-radius: 4px;
            color: #fff;
            display: inline-block;
            overflow: hidden;
            text-decoration: none;
            background-color: #52b8d8;
            border-bottom: 8px solid #52b8d8;
            border-left: 18px solid #52b8d8;
            border-right: 18px solid #52b8d8;
            border-top: 8px solid #52b8d8;
            /* width: 40%; */
            height: auto;
            line-height: 38px;
            font-size: 20px;
        }
        .w-100{
            width: 100%;
        }
        p{
            font-size: 14px;
        }
        h2{
            font-size: 24px;
        }
    </style>
</head>
<body style="background-color: #edf2f7;padding: 30px 0px;text-align:center;word-break: break-word;">
<div class="container">
    <h2>{{ $sendRegisterEmailDTO->title }}</h2>
    <h1>{!! $sendRegisterEmailDTO->description !!}</h1>
    <hr>
</div>
<p class="container" style="text-align: center;background: transparent">
    C {{ now()->year }} {{env('STORE_NAME', 'Community Homestay Network')}}.
</p>
</body>
</html>
