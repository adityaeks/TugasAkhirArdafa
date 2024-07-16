<li class="list-group-item py-3 border-top fw-bold">
    <div class="row align-items-center">
        <div class="col-2 col-md-2 col-lg-2"></div>
        <div class="col-4 col-md-4 col-lg-5">Service</div>
        <div class="col-3 col-md-2 col-lg-2">Estimate</div>
        <div class="col-3 text-lg-end text-start text-md-end col-md-3">Cost</div>
    </div>
</li>
@forelse ($services as $service)
    <li class="list-group-item py-3">
        <div class="row align-items-center">
            <div class="col-2 col-md-2 col-lg-2">
                @php
                    $serviceName = $service['service'];
                    $courier = $service['courier'];
                    $addressID = $service['address_id'];
                @endphp
                <input class="form-check-input delivery-package" type="radio" name="delivery_package" id="inlineRadio2"
                    value="{{ $service['service'] }}"
                    onclick="setShippingFee('{{ $serviceName }}', '{{ $courier }}', '{{ $addressID }}')">
            </div>
            <div class="col-4 col-md-4 col-lg-5">{{ $service['service'] }} ({{ $service['description'] }})</div>
            <div class="col-3 col-md-2 col-lg-2">{{ $service['etd'] }}</div>
            <div class="col-3 text-lg-end text-start text-md-end col-md-3">
                <span class="fw-bold">IDR {{ number_format($service['cost'], 0, ',', '.') }}</span>
            </div>
        </div>
    </li>
@empty
    <li class="list-group-item py-3">
        <span class="text-danger">No delivery service found, try another courier!</span>
    </li>
@endforelse

<script type="text/javascript">
    $(document).ready(function() {
        // Reset value on load
        $('#delivery_package').val("");

        // Update value when a delivery package is selected
        $('.delivery-package').on('click', function() {
            $('#delivery_package').val($(this).val());
        });

        // Handle form submission
        // $('#submitCheckoutForm').on('click', function(e) {
        //     e.preventDefault();
        //     if ($('#shipping_address_id').val() == "") {
        //         toastr.error('Shipping address is required');
        //     } else if ($('#delivery_package').val() == "") {
        //         toastr.error('Delivery pacgkage is required');
        //     } else if (!$('.agree_term').prop('checked')) {
        //         toastr.error('You have to agree to the website terms and conditions');
        //     } else {
        //         $.ajax({
        //             url: "{{ route('user.checkout.form-submit') }}",
        //             method: 'POST',
        //             data: $('#checkOutForm').serialize(),
        //             beforeSend: function() {
        //                 $('#submitCheckoutForm').html(
        //                     '<i class="fas fa-spinner fa-spin fa-1x"></i>')
        //             },
        //             success: function(data) {
        //                 if (data.status === 'success') {
        //                     $('#submitCheckoutForm').text('Place Order');
        //                     window.location.href = data.redirect_url;
        //                 }
        //             },
        //             error: function(data) {
        //                 console.log(data);
        //             }
        //         });
        //     }
        // });
    });


    function setShippingFee(deliveryPackage, courier, addressID) {
        $.ajax({
            url: "checkout/choose-package",
            method: "POST",
            data: {
                delivery_package: deliveryPackage,
                courier: courier,
                address_id: addressID,
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function(result) {
                $('#shipping-fee').html("IDR " + result.shipping_fee);
                $('#total_amount').html("IDR " + result.total_amount);
            },
            error: function(e) {
                console.log(e);
            }
        })
    }
</script>
