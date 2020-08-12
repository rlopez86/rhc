<html>
<head>
    <style>
        #title{
            font-size: 50px;
            font-style: italic;
            color: #656565;
            line-height: 60px;
            border-bottom: 1px solid #d5d5d5;
        }
        #title span{
            width: 100%;
            padding-bottom: 5px;

        }
        .main{
            float: left;
            margin: 0 10px 10px 0;
            padding-top: 20px;
        }
        #footer{
            margin-top: 50px;
            padding-top:20px;
            border-top: 1px solid #d5d5d5;
            text-align: center;
        }
        #identifier{
            margin-top: 50px;
        }
        p{
            font-size: 15px;
            color: #3d3d3d;
            font-family: Helvetica, Arial, sans-serif;
        }

        .left{
            float: left;
            margin-right: 5px;
        }

        .right{
            float: right;
            margin-left: 5px;
        }

        .innerImage{
            padding-top: 70px;
            padding-bottom: 5px;
            padding-left: 5px;
            padding-right: 5px;
            border: 1px solid lightgrey;
            font-weight: bold;
        }

    </style>
</head>
<body>
    <h1 id="title"><span>{{$article->nombre}}</span></h1>
    @if($article->imagenFull())
        <div class="main">
            @if(str_contains($article->imagenFull()->location, 'articles'))
                <img src="{{asset($article->imagenFull()->location)}}">
            @else
                <img src="{{asset('articles/'.$article->imagenFull()->location)}}">
            @endif
        </div>
    @endif
    <div id="content">
        {!! $article->texto !!}
    </div>
    <div id="footer">
        <a href="{{$article->getFirstUrl()}}">{{$article->getFirstUrl()}}</a>
        <div id="identifier">
            <img src="{{asset('images/logo-color.png')}}">
            <h2>Radio Habana Cuba</h2>
        </div>
    </div>
</body>
</html>