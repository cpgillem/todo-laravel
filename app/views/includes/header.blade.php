<div class="container">
  <div class="top-bar">
    <div class="top-bar-left">
      <ul class="menu">
        <li><a href="/"><strong>Todo App</strong></a></li>
      </ul>
    </div>
    <div class="top-bar-right">
      <ul class="menu">
        <li> {{ HTML::link('tasks', 'Tasks') }} </li>
        <li> {{ HTML::link('users/create', 'Register') }} </li>
        <li> 
          @if (Auth::check())
            {{ HTML::link('sessions/destroy', 'Logout') }}
          @else
            {{ HTML::link('sessions/create', 'Login') }} 
          @endif
        </li>
      </ul>
    </div>
  </div>
</div>
