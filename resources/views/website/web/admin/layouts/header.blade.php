<!-- Top navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
    <div class="container-fluid d-flex justify-content-space-between align-items-center">

        <button class="btn btn-outline-primary menu-btn me-3" onclick="toggleDrawer()">
            <i class="bi bi-list"></i>
        </button>

        <div class="account-info">
            <div class="account-details">
                <div class="account-name" id="account-name">{{ auth()->user()->name }}</div>
                <div class="account-role" id="account-role">{{ auth()->user()->role }}</div>
            </div>
        </div>

    </div>
</nav>

@include('website.web.admin.layouts.sidebar')
