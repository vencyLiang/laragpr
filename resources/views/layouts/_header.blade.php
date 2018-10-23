
    <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-static-top">
        <div class="container">
            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">全球公排系统</a>

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggler pull-right" data-toggle="collapse" data-target="#app-navbar-collapse" aria-controls="app-navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="sr-only">Toggle navigation</span>
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">

                </ul>

            <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav">
                <!-- Authentication Links -->
                 @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">登录</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">注册</a></li>
                 @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <span class="user-avatar float-left" style="margin-right:8px; margin-top:-5px;">
                                <img src="https://fsdhubcdn.phphub.org/uploads/images/201709/20/1/PtDKbASVcz.png?imageView2/1/w/60/h/60" class="img-fluid rounded-circle" width="30px" height="30px">
                            </span>
                                {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                         document.getElementById('logout-form').submit();">
                                    退出登录
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                 @endguest
                </ul>
            </div>
        </div>
    </nav>
