<nav class="navbar navbar-expand-md navbar-dark primary-color">
    <a class="navbar-brand" href="{{ route('index') }}">FuturoVTC</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        @if(auth()->user()->typeRole == "ADM")
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link waves-effect waves-light" href="{{ route('hotliner') }}">Hotliner</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link waves-effect waves-light" href="{{ route('listCourse') }}">Chauffeur</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link waves-effect waves-light" href="{{ route('listCar') }}">Garagiste</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link waves-effect waves-light" href="{{ route('getTransactions') }}">Comptable</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-light" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Ressources humaines</a>
                    <div class="dropdown-menu dropdown-info" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item waves-effect waves-light" href="{{ route('indexHR') }}">Liste Employés</a>
                        <a class="dropdown-item waves-effect waves-light" href="{{ route('createHREmploye') }}">Nouvel employé</a>
                    </div>
                </li>
            </ul>
        @endif
        <ul class="navbar-nav ml-auto nav-flex-icons">
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif
            @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }} {{ Auth::user()->prename }} - {{ Auth::user()->typeRole }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            @endguest
        </ul>
    </div>
</nav>
