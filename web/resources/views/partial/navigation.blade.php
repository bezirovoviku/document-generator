<nav class="navbar navbar-default" role="navigation">
    <div class="container">
        <ul class="nav navbar-nav">
            @foreach (['Home' => action('HomeController@index')] as $text => $url)
                <li class="{{ Request::url() == $url ? 'active' : '' }}">
                    <a href="{{ $url }}">{{ $text }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</nav>
