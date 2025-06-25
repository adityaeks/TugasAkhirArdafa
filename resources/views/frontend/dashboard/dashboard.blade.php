@extends('frontend.dashboard.layouts.master')

@section('title')
UMKM Lowayu || Dahsboard
@endsection

@section('content')
<section id="wsus__dashboard">

    <div class="container-fluid">
      @include('frontend.dashboard.layouts.sidebar')
      <div class="row">
        <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
            <h3>User Dashboard</h3>
            <br>
          <div class="dashboard_content">
            <div class="wsus__dashboard">
              <div class="row">
                <div class="col-xl-6 col-xxl-4 col-lg-6 col-sm-6">
                    <a class="wsus__dashboard_item blue" href="{{route('user.orders.index')}}">
                        <i class="fas fa-shopping-basket"></i>
                        <p>Pesanan</p>
                    </a>
                </div>
                <div class="col-xl-6 col-xxl-4 col-lg-6 col-sm-6">
                    <a class="wsus__dashboard_item purple" href="{{route('user.address.index')}}">
                        <i class="fas fa-map-marker-alt"></i>
                        <p>Alamat</p>
                    </a>
                </div>
                <div class="col-xl-6 col-xxl-4 col-lg-6 col-sm-6">
                    <a class="wsus__dashboard_item red" href="{{route('user.profile')}}">
                        <i class="fas fa-user"></i>
                        <p>Profil</p>
                    </a>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
