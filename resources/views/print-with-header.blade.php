<html>
    <head>
        <title>@yield('title')</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <!--<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' media="all">-->
        <!--<link href="https://fonts.googleapis.com/css?family=Raleway+Dots" rel="stylesheet">-->
        <link href="{{asset('print/style.css')}}" rel="stylesheet" type='text/css' media="all">
    </head>
    <body>
        <div>
            <div style="font-size: 12px;">
                <h2 class="name">PT. WIRA MITRA PRIMA</h2>
                <div>Jl. Yos Sudarso No. 17 - 18 Jakarta Utara 14230 - Indonesia</div>
                <div>Tel. (021) 43932815 &nbsp;Fax. (021) 43932806</div>
                <div>Email : <a href="mailto:info@wira.co.id">info@wira.co.id</a></div>
                <hr style="border-style: dashed;" />
            </div>
        </div>
        <div class="clearfix"></div>
        <div id="main">
            
            @yield('content')

        </div>
    </body>
</html>