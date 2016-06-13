<!DOCTYPE html>
<html>
  <head>
    @include('includes.head')
  </head>
  <body>
    @include('includes.header')
    <div class="container">
      <div class="row column">
        <h1>@yield('pageTitle')</h1>
      </div>
      <div class="row column">
        @yield('content')
      </div>
      @include('includes.footer')
    </div>
  </body>
</html>
