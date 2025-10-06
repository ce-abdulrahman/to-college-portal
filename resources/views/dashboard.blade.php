hello Student : {{ auth()->user()->name }} <br/>
<form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="drawer-btn logout-btn w-100" type="submit">
                <i class="bi bi-box-arrow-right"></i><span>دەرچوون</span>
            </button>
        </form>
