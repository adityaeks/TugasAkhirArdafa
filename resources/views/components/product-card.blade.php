<div class="col-xl-3 col-sm-6 col-lg-4 {{ @$key }}">
    <div class="wsus__product_item">
        <span class="">{{ productType($product->product_type) }}</span>
        {{-- @if (checkDiscount($product))
            <span class="wsus__minus">-{{ calculateDiscountPercent($product->price, $product->offer_price) }}%</span>
        @endif --}}
        <a class="wsus__pro_link" href="{{ route('product-detail', $product->slug) }}">
            <img src="{{ asset($product->thumb_image) }}" alt="product" class="img-fluid w-100 img_1" />
            <img src="{{ asset($product->thumb_image) }}" alt="product" class="img-fluid w-100 img_2" />
        </a>
        <ul class="wsus__single_pro_icon">
            <li><a href="" class="add_to_wishlist" data-id="{{ $product->id }}"><i class="far fa-heart"></i></a>
            </li>
        </ul>
        <div class="wsus__product_details">
            <a class="wsus__category" href="#">{{ $product->category->name }}</a>
            <a class="wsus__pro_name"
                href="{{ route('product-detail', $product->slug) }}">{{ limitText($product->name, 52) }}</a>
            @if (checkDiscount($product))
                <p class="wsus__price">Rp{{ number_format($product->offer_price, 0, ',', '.') }}
                    <del>Rp{{ number_format($product->price, 0, ',', '.') }}</del>
                </p>
            @else
                <p class="wsus__price">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
            @endif

            <form class="shopping-cart-form">
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="qty" min="1" max="100" value="1" />
                <button class="add_cart" type="submit">add to cart</button>
            </form>
        </div>
    </div>
</div>
