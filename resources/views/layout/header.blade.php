<nav class="navbar">
  <a href="#" class="sidebar-toggler">
    <i data-feather="menu"></i>
  </a>
  <div class="navbar-content">

    <ul class="navbar-nav">

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img class="wd-30 ht-30 rounded-circle" src="{{ url('https://via.placeholder.com/30x30') }}" alt="profile">
        </a>
        <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
          <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
            <div class="mb-3">
              <img class="wd-80 ht-80 rounded-circle" src="{{ url('https://via.placeholder.com/80x80') }}" alt="">
            </div>
            <div class="text-center">
              <p class="tx-16 fw-bolder">{{auth()->user()->name}}</p>
            </div>
          </div>
          <ul class="list-unstyled p-1">
            <li class="dropdown-item py-2">
              @if (auth()->user()->role == "admin")
              <a type="button" href="javascript:;" class="text-body ms-0"
              data-bs-toggle="modal" data-bs-target="#add_user" 
              data-bs-whatever="Add User"
              >
                <i class="me-2 icon-md" data-feather="user-plus"></i>
                <span>Add User</span>
              </a>
              @endif
            </li>
            <li class="dropdown-item py-2">
              <form id="logout" method="POST" action="{{url('/logout')}}">
                @csrf
              <a href="javascript:{}" onclick="document.getElementById('logout').submit();" class="text-body ms-0">
                <i class="me-2 icon-md" data-feather="log-out"></i>
                <span>Log Out</span>
              </a>
              </form>
            </li>
          </ul>
        </div>
      </li>
    </ul>
  </div>
</nav>