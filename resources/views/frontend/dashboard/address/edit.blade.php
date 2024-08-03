@extends('frontend.dashboard.layouts.master')

@section('content')
    <section id="wsus__dashboard">
        <div class="container-fluid">
            @include('frontend.dashboard.layouts.sidebar')

            <div class="row">
                <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
                    <div class="dashboard_content mt-2 mt-md-0">
                        <h3><i class="fal fa-gift-card"></i>Edit Address</h3>
                        <div class="wsus__dashboard_add wsus__add_address">
                            <form action="{{ route('user.address.update', $address->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-xl-6 col-md-6">
                                        <div class="wsus__add_address_single">
                                            <label>Name <b>*</b></label>
                                            <input type="text" placeholder="Name" name="name"
                                                value="{{ $address->name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6">
                                        <div class="wsus__add_address_single">
                                            <label>Email</label>
                                            <input type="email" placeholder="Email" name="email"
                                                value="{{ $address->email }}">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6">
                                        <div class="wsus__add_address_single">
                                            <label>Phone <b>*</b></label>
                                            <input type="text" placeholder="Phone" name="phone"
                                                value="{{ $address->phone }}" required>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6">
                                        <div class="wsus__add_address_single">
                                            <label>Province <b>*</b></label>
                                            <div class="wsus__topbar_select">
                                                <select id="province" class="select_2" name="province" required>
                                                    <option>Select</option>
                                                    @foreach ($provinces as $province_id => $province_name)
                                                        <option value="{{ $province_id }}"
                                                            {{ $province_id == $address->province ? 'selected' : '' }}>
                                                            {{ $province_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6">
                                        <div class="wsus__add_address_single">
                                            <label>City <b>*</b></label>
                                            <div class="wsus__topbar_select">
                                                <select id="city" class="select_2" name="city" required>
                                                    <option>Select</option>
                                                    @foreach ($cities as $city_id => $city_name)
                                                        <option value="{{ $city_id }}"
                                                            {{ $city_id == $address->city ? 'selected' : '' }}>
                                                            {{ $city_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6">
                                        <div class="wsus__add_address_single">
                                            <label>Zip Code <b>*</b></label>
                                            <input type="text" placeholder="Zip Code" name="zip"
                                                value="{{ $address->zip }}" required>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6">
                                        <div class="wsus__add_address_single">
                                            <label>Address <b>*</b></label>
                                            <input type="text" placeholder="Address" name="address"
                                                value="{{ $address->address }}" required>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <button type="submit" class="common_btn">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#province').change(function() {
                var provinceId = $(this).val();
                if (provinceId) {
                    $.ajax({
                        url: '{{ url('cities') }}/' + provinceId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#city').empty();
                            $('#city').append('<option value="">Select City</option>');
                            $.each(data, function(key, value) {
                                $('#city').append('<option value="' + value.city_id +
                                    '">' + value.city_name + '</option>');
                            });
                        },
                        error: function(xhr, status, error) {
                            console.log('Error: ' + error);
                        }
                    });
                } else {
                    $('#city').empty();
                    $('#city').append('<option value="">Select City</option>');
                }
            });
        });
    </script>
@endpush
