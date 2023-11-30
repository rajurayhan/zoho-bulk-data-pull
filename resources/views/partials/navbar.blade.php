<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">ZohoData</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item {{ request()->is('/') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('/') }}">Home</a>
            </li>
            <li class="nav-item {{ request()->is('modules') || request()->is('modules/*') ? 'active' : '' }}"> 
                <a class="nav-link" href="{{ url('/modules') }}">Modules</a>
            </li>
            <li class="nav-item {{ request()->is('fields') || request()->is('fields/*') ? 'active' : '' }}"> 
                <a class="nav-link" href="{{ url('/fields') }}">Fields</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('zoho.oauth') }}">Authenticate Zoho</a>
            </li>
            <!-- Add more navigation items here -->
        </ul>
    </div>
</nav>
