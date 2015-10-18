<!DOCTYPE html>
<html>
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        @include('includes.head')
        @yield('styles')
    </head>
    <body>
        <div class="container">
            @yield('body')
        </div>
        @include('includes.scripts')
        @yield('scripts')
    </body>
</html>
