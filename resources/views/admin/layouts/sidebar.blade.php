<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="dropdown active">
                <a href="{{ route('admin.dashbaord') }}" class="nav-link"><i
                        class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            <li class="menu-header">Ecommerce</li>
            <li class="{{ setActive(['home']) }}"><a class="nav-link"
                    href="{{ url('/') }}"><i class="fa fa-home"></i>
                    <span>Home</span></a>
            </li>
            <li
                class="dropdown {{ setActive(['admin.kategori.*', 'admin.sub-kategori.*', 'admin.child-kategori.*']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-list"></i>
                    <span>Kelola Kategori</span></a>
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
                    <span>Kelola Produk</span></a>
                <ul class="dropdown-menu">
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
                </ul>
            </li>

            <li class="{{ setActive(['admin.transaction']) }}"><a class="nav-link"
                    href="{{ route('admin.transaction') }}"><i class="fas fa-money-bill-alt"></i>
                    <span>Transactions</span></a>
            </li>
            <li
                class="dropdown {{ setActive([
                    'admin.slider.*',
                    'admin.about.index',
                    'admin.kupon.*',
                    'admin.home-page-setting.*',
                    'admin.terms-and-conditions.index',
                ]) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-cog"></i>
                    <span>Kelola Website</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.slider.*']) }}"><a class="nav-link"
                            href="{{ route('admin.slider.index') }}">Slider</a></li>
                    <li class="{{ setActive(['admin.kupon.*']) }}"><a class="nav-link"
                            href="{{ route('admin.kupon.index') }}">Kupon</a></li>
                    <li class="{{ setActive(['admin.home-page-setting.*']) }}"><a class="nav-link"
                            href="{{ route('admin.home-page-setting') }}">Home Page Setting</a></li>
                    {{-- <li class="{{ setActive(['admin.about.index']) }}"><a class="nav-link"
                            href="{{ route('admin.about.index') }}">About page</a></li> --}}
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
                            href="{{ route('admin.vendor-list.index') }}">Seller list</a></li>
                    <li class="{{ setActive(['admin.manage-user.index']) }}"><a class="nav-link"
                            href="{{ route('admin.manage-user.index') }}">Kelola user</a></li>

                </ul>
            </li>

        </ul>

    </aside>
</div>
