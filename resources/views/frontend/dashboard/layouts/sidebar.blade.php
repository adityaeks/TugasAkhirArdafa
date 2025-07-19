
<div class="dashboard_sidebar">
    <span class="close_icon">
      <i class="far fa-bars dash_bar"></i>
      <i class="far fa-times dash_close"></i>
    </span>
    <div style="display: flex; justify-content: center; align-items: center; width: 100%; margin-top: 20px;">
        <a href="" style="width: 100%;">
            <p style="font-family: Arial, Helvetica, sans-serif; font-size: 1.5rem; color: #fff; letter-spacing: 2px; margin: 0 0 10px 0; text-align: center;">OurKitchen</p>
        </a>
    </div>
    <ul class="dashboard_link">
      <li><a class="{{setActive(['user.dashboard'])}}" href="{{route('user.dashboard')}}"><i class="fas fa-tachometer"></i>Dashboard</a></li>

      <li><a class="" href="{{url('/')}}"><i class="fas fa-home"></i>Go To Home Page</a></li>

      @if (auth()->user()->role === 'vendor')
      @endif


      <li><a class="{{setActive(['user.orders.*'])}}" href="{{route('user.orders.index')}}"><i class="fas fa-list-ul"></i> Orders</a></li>
      <li><a class="{{setActive(['user.profile'])}}" href="{{route('user.profile')}}"><i class="far fa-user"></i> My Profile</a></li>
      <li><a class="{{setActive(['user.address.*'])}}" href="{{route('user.address.index')}}"><i class="fal fa-gift-card"></i> Addresses</a></li>
      @if (auth()->user()->role !== 'vendor')
      @endif
      <li>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="{{route('logout')}}" onclick="event.preventDefault();
            this.closest('form').submit();"><i class="far fa-sign-out-alt"></i> Log out</a>
        </form>
        </li>

    </ul>
  </div>
