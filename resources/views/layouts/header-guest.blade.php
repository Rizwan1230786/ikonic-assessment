<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">Orders</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="/signin">Sign-in</a>
                        </li>
                    @endguest
                    @auth
                        @if (auth()->user()->type == App\Models\User::TYPE_MERCHANT)
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="/merchant/dashboard">Dashboard</a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="/logout">Logout</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
</div>
