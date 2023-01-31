<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors" />
        <meta name="generator" content="Hugo 0.101.0" />
        <title>{{$title}}</title>

        <link rel="canonical" href="https://getbootstrap.com/docs/4.6/examples/blog/" />

        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="{{asset('blog_asset/dist/css/bootstrap.min.css')}}">

        <style>
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
            }

            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                    font-size: 3.5rem;
                }
            }
        </style>

        <!-- Custom styles for this template -->
        <link href="https://fonts.googleapis.com/css?family=Playfair&#43;Display:700,900" rel="stylesheet" />
        <!-- Custom styles for this template -->
        <link href="{{asset('blog_asset/blog.css')}}" rel="stylesheet" />
    </head>
    <body>
        <div class="container">
            <header class="blog-header py-3">
                <div class="row flex-nowrap justify-content-between align-items-center">
                    <div class="col-4 pt-1">
                        
                    </div>
                    <div class="col-4 text-center">
                        <a class="blog-header-logo text-dark" href="#">{{config('app.name', 'Laravel')}}</a>
                    </div>
                    <div class="col-4 d-flex justify-content-end align-items-center">
                        @if (! auth()->check())
                        <a class="btn btn-sm btn-outline-secondary" href="{{route('login')}}">Masuk</a>
                        @else
                        <a class="btn btn-sm btn-outline-secondary" href="{{route('home')}}">Kembali</a>
                        @endif
                    </div>
                </div>
            </header>

            <div class="nav-scroller py-1 mb-2">
                <nav class="nav d-flex justify-content-between">
                    
                </nav>
            </div>

            @yield('list')
        </div>

        @yield('show')

        <footer class="blog-footer">
            <p>
                <a href="#">Back to top</a>
            </p>
        </footer>
    </body>
</html>