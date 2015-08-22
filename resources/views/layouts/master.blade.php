<!DOCTYPE html>
<html>
    <head>
        @include('includes.head')
        @yield('styles')
    </head>
    <body>
        <div class="container">
            @yield('body')
        </div>
        @include('includes.scripts')
    </body>
</html>
