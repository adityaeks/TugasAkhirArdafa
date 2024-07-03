<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="dropdown active">
                <a href="{{ route('admin.dashbaord') }}" class="nav-link"><i
                        class="fas fa-fire"></i><span>Dashboard</span></a>

            </li>
            <li class="menu-header">Ecommerce</li>
            {{-- <li class="{{ setActive(['admin.vendor-profile.index']) }}"><a class="nav-link"
                    href="{{ route('admin.vendor-profile.index') }}"><i class="fas fa-columns"></i>
                    <span>Profil Admin Shop</span></a>
            </li> --}}

            <li
                class="dropdown {{ setActive(['admin.kategori.*', 'admin.sub-kategori.*', 'admin.child-kategori.*']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-list"></i>
                    <span>Manage Categories</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.kategori.*']) }}"><a class="nav-link"
                            href="{{ route('admin.kategori.index') }}">kategori</a></li>
                </ul>
            </li>

            <li
                class="dropdown {{ setActive([
                    'admin.produk.*',
                    'admin.produk-image-gallery.*',
                    'admin.seller-produk.*',
                    'admin.seller-pending-produk.*',
                ]) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-box"></i>
                    <span>Manage produk</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.produk.*', 'admin.produk-image-gallery.*']) }}">
                        <a class="nav-link" href="{{ route('admin.produk.index') }}">Admin produk</a>
                    </li>
                    <li class="{{ setActive(['admin.seller-produk.*']) }}"><a class="nav-link"
                            href="{{ route('admin.seller-produk.index') }}">Seller produk</a></li>
                    <li class="{{ setActive(['admin.seller-pending-produk.*']) }}"><a class="nav-link"
                            href="{{ route('admin.seller-pending-produk.index') }}">Seller Pending produk</a></li>

                </ul>
            </li>



            <li
                class="dropdown {{ setActive([
                    'admin.order.*',
                    'admin.pending-orders',
                    'admin.processed-orders',
                    'admin.dropped-off-orders',
                    'admin.shipped-orders',
                    'admin.out-for-delivery-orders',
                    'admin.delivered-orders',
                    'admin.canceled-orders',
                ]) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-cart-plus"></i>
                    <span>Orders</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.order.*']) }}"><a class="nav-link"
                            href="{{ route('admin.order.index') }}">All Orders</a></li>
                    <li class="{{ setActive(['admin.pending-orders']) }}"><a class="nav-link"
                            href="{{ route('admin.pending-orders') }}">All Pending Orders</a></li>
                    <li class="{{ setActive(['admin.processed-orders']) }}"><a class="nav-link"
                            href="{{ route('admin.processed-orders') }}">All processed Orders</a></li>
                    {{-- <li class="{{ setActive(['admin.dropped-off']) }}"><a class="nav-link"
                            href="{{ route('admin.dropped-off-orders') }}">All Dropped Off Orders</a></li>

                    <li class="{{ setActive(['admin.shipped-orders']) }}"><a class="nav-link"
                            href="{{ route('admin.shipped-orders') }}">All Shipped Orders</a></li>
                    <li class="{{ setActive(['admin.out-for-delivery-orders']) }}"><a class="nav-link"
                            href="{{ route('admin.out-for-delivery-orders') }}">All Out For Delivery Orders</a></li> --}}


                    {{-- <li class="{{ setActive(['admin.delivered-orders']) }}"><a class="nav-link"
                            href="{{ route('admin.delivered-orders') }}">All Delivered Orders</a></li> --}}

                    {{-- <li class="{{ setActive(['admin.canceled-orders']) }}"><a class="nav-link"
                            href="{{ route('admin.canceled-orders') }}">All Canceled Orders</a></li> --}}

                </ul>
            </li>

            <li class="{{ setActive(['admin.transaction']) }}"><a class="nav-link"
                    href="{{ route('admin.transaction') }}"><i class="fas fa-money-bill-alt"></i>
                    <span>Transactions</span></a>
            </li>
            <li
                class="dropdown {{ setActive([
                    'admin.slider.*',
                    'admin.vendor-condition.index',
                    'admin.about.index',
                    'admin.kupon.*',
                    'admin.terms-and-conditions.index',
                ]) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-cog"></i>
                    <span>Manage Website</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.slider.*']) }}"><a class="nav-link"
                            href="{{ route('admin.slider.index') }}">Slider</a></li>
                    <li class="{{ setActive(['admin.kupon.*']) }}"><a class="nav-link"
                            href="{{ route('admin.kupon.index') }}">Kupon</a></li>
                    <li class="{{ setActive(['admin.slider.*']) }}"><a class="nav-link"
                            href="{{ route('admin.home-page-setting') }}">Home Page Setting</a></li>

                    <li class="{{ setActive(['admin.about.index']) }}"><a class="nav-link"
                            href="{{ route('admin.about.index') }}">About page</a></li>


                </ul>
            </li>

            <li><a class="nav-link {{ setActive(['admin.advertisement.*']) }}"
                    href="{{ route('admin.iklan.index') }}"><i class="fas fa-ad"></i>
                    <span>Iklan Website</span></a></li>


            <li class="menu-header">Settings & More</li>
            <li
                class="dropdown {{ setActive([
                    'admin.vendor-requests.index',
                    'admin.customer.index',
                    'admin.vendor-list.index',
                    'admin.manage-user.index',
                    'admin-list.index',
                ]) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-users"></i>
                    <span>Users</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.customer.index']) }}"><a class="nav-link"
                            href="{{ route('admin.customer.index') }}">Customer list</a></li>
                    <li class="{{ setActive(['admin.vendor-list.index']) }}"><a class="nav-link"
                            href="{{ route('admin.vendor-list.index') }}">Vendor list</a></li>

                    <li class="{{ setActive(['admin.vendor-requests.index']) }}"><a class="nav-link"
                            href="{{ route('admin.vendor-requests.index') }}">Pending vendors</a></li>

                    <li class="{{ setActive(['admin.admin-list.index']) }}"><a class="nav-link"
                            href="{{ route('admin.admin-list.index') }}">Admin Lists</a></li>

                    <li class="{{ setActive(['admin.manage-user.index']) }}"><a class="nav-link"
                            href="{{ route('admin.manage-user.index') }}">Manage user</a></li>

                </ul>
            </li>

            <li><a class="nav-link" href="{{ route('admin.settings.index') }}"><i class="fas fa-wrench"></i>
                    <span>Settings</span></a></li>

        </ul>

    </aside>
</div>
