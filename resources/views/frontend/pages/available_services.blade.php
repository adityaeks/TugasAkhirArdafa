<li class="py-3 border-t border-b font-bold">
    <div class="flex items-center">
        <div class="w-1/6 md:w-1/6 lg:w-1/6"></div>
        <div class="w-1/3 md:w-1/3 lg:w-5/12">Service</div>
        <div class="w-1/4 md:w-1/6 lg:w-1/6">Estimate</div>
        <div class="w-1/4 text-left md:text-right lg:text-right">Cost</div>
    </div>
</li>
@forelse ($services as $service)
    <li class="py-3 border-b border-gray-200">
        <div class="flex items-center">
            <div class="w-1/6 md:w-1/6 lg:w-1/6">
                @php
                    $serviceName = $service['service'];
                    $courier = $service['courier'];
                    $addressID = $service['address_id'];
                    $totalWeight = $totalWeight ?? 0;
                @endphp
                <input class="form-radio h-4 w-4 text-indigo-600 focus:ring-indigo-500 delivery-package" type="radio" name="delivery_package" id="inlineRadio2"
                    value="{{ $service['service'] }}"
                    onclick="setShippingFee('{{ $serviceName }}', '{{ $courier }}', '{{ $addressID }}', '{{ $totalWeight }}')">
            </div>
            <div class="w-1/3 md:w-1/3 lg:w-5/12">{{ $service['service'] }} ({{ $service['description'] }})</div>
            <div class="w-1/4 md:w-1/6 lg:w-1/6">{{ $service['etd'] }}</div>
            <div class="w-1/4 text-left md:text-right lg:text-right">
                <span class="font-bold">IDR {{ number_format($service['cost'], 0, ',', '.') }}</span>
            </div>
        </div>
    </li>
@empty
    <li class="py-3 border-b border-gray-200">
        <span class="text-red-500">No delivery service found, try another courier!</span>
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
    });


    function setShippingFee(deliveryPackage, courier, addressID, totalWeight) {
        console.log('Setting shipping fee with:', {
            deliveryPackage,
            courier,
            addressID,
            totalWeight
        });
        $.ajax({
            url: "checkout/choose-package",
            method: "POST",
            data: {
                delivery_package: deliveryPackage,
                courier: courier,
                address_id: addressID,
                total_weight: totalWeight,
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function(result) {
                console.log('Shipping fee response:', result);
                if (result.shipping_fee !== undefined && result.total_amount !== undefined) {
                    $('#cost').html("Rp" + parseInt(result.shipping_fee).toLocaleString('id-ID'));
                    $('#total_amount').html("Rp" + parseInt(result.total_amount).toLocaleString('id-ID'));
                } else {
                    console.error('Invalid response:', result);
                }
            },
            error: function(e) {
                console.error('Ajax error:', e);
            }
        });
    }
</script>
