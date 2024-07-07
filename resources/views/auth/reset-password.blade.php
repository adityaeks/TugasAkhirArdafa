@extends('frontend.layouts.master')

@section('title')
UMKM Lowayu || Reset Password
@endsection

@section('content')
    <section id="wsus__login_register">
        <div class="container">
            <div class="row">
                <div class="col-xl-5 col-md-10 col-lg-7 m-auto">
                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf


                        <div class="wsus__change_password">
                            <h4>reset password</h4>
                                <!-- Password Reset Token -->
                                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <div class="wsus__single_pass">
                                <label>email</label>
                                <input id="email" type="email" name="email" value="{{old('email', $request->email)}}" placeholder="Email">
                            </div>

                            <div class="wsus__single_pass">
                                <label>new password</label>
                                <input id="password" type="password" name="password" placeholder="New Password">
                            </div>


                            <div class="wsus__single_pass">
                                <label>confirm password</label>
                                <input id="password_confirmation" type="password"
                                name="password_confirmation" type="text" placeholder="Confirm Password">
                            </div>


                            <button class="common_btn" type="submit">submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
