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
                class="{{ setActive(['admin.produk.*']) }}">
                <a class="nav-link" href="{{ route('admin.produk.index') }}"><i class="fas fa-box"></i>
                    <span>Kelola Produk</span></a>
            </li>
            <li class="{{ setActive(['admin.order.*']) }}"><a class="nav-link"
                    href="{{ route('admin.order.index') }}"><i class="fas fa-cart-plus"></i>
                    <span>Orders</span></a>
            </li>
            <li class="{{ setActive(['admin.transaction']) }}"><a class="nav-link"
                    href="{{ route('admin.transaction') }}"><i class="fas fa-money-bill-alt"></i>
                    <span>Transactions</span></a>
            </li>
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
                    <li class="{{ setActive(['admin.manage-user.index']) }}"><a class="nav-link"
                            href="{{ route('admin.manage-user.index') }}">Kelola user</a></li>

                </ul>
            </li>

        </ul>

    </aside>
</div>
