@php
    use App\Models\Store;
    $stores = Store::where('company_id', Auth::user()->company_id)->get();
@endphp
<aside class="sidebar">
    <ul class="sidebar-links">
        <h4>
            <span>Main Menu</span>
        </h4>
        <li>
            <a id="sidebar_store">
                <img src="{{ asset('/img/home_icon.png') }}" class="sidebar_icon">店舗変更</a>
            <ul class="change_store">
                @foreach ($stores as $store)
                    <li><a href="{{ route('store') }}?store_id={{ $store->id }}">・{{ $store->name }}</a></li>
                @endforeach
            </ul>
        </li>
        <li>
            <a class="sidebar_bill" href="{{ route('bill') }}">
                <img src="{{ asset('/img/bill_icon2.png') }}" class="sidebar_icon">売上集計</a>
        </li>
        <li>
            <a class="sidebar_supplier" href="{{ route('supplier') }}">
                <img src="{{ asset('/img/supplier_icon2.png') }}" class="sidebar_icon">提携業社</a>
        </li>
        <li>
            <a class="sidebar_staff" href="{{ route('staff') }}">
                <img src="{{ asset('/img/staff_icon2.png') }}" class="sidebar_icon">従業員別売上</a>
        </li>
        <li>
            <a class="sidebar_month" href="{{ route('month') }}">
                <img src="{{ asset('/img/month_icon2.png') }}" class="sidebar_icon">月次売上</a>
        </li>
        <li>
            <a class="sidebar_customer" href="{{ route('customer') }}">
                <img src="{{ asset('/img/customer_icon2.png') }}" class="sidebar_icon">顧客別売上</a>
        </li>

        <h4>
            <span>Sub Menu</span>
        </h4>
        <li>
            <a class="sidebar_kake" href="{{ route('kake') }}">
                <img src="{{ asset('/img/kake_icon.png') }}" class="sidebar_icon">売掛</a>
        </li>
        <li>
            <a class="sidebar_DailyPayment" href="{{ route('DailyPayment') }}">
                <img src="{{ asset('/img/DailyPayment_icon.png') }}" class="sidebar_icon">給引き</a>
        </li>
        <li>
            <a class="sidebar_cashier" href="{{ route('cashier') }}">
                <img src="{{ asset('/img/cashier_icon.png') }}" class="sidebar_icon">レジ金</a>
        </li>
    </ul>
</aside>


<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
    }

    .sidebar {
        position: sticky;
        top: 0;
        left: 0;
        height: 100vh;
        width: 210px;
        display: flex;
        overflow-x: hidden;
        flex-direction: column;
        background: #161a2d;
        padding: 25px 20px;
        transition: all 0.4s ease;
    }

    .sidebar_icon {
        width: 15%;
    }

    .sidebar-links h4 {
        color: #fff;
        font-weight: 500;
        white-space: nowrap;
        margin: 10px 0;
        position: relative;
    }

    .sidebar:hover .sidebar-links .menu-separator {
        transition-delay: 0s;
        transform: scaleX(0);
    }

    .sidebar-links {
        list-style: none;
        margin-top: 20px;
        padding-left: 0 !important;
        height: 80%;
        overflow-y: auto;
        scrollbar-width: none;
    }

    .sidebar-links::-webkit-scrollbar {
        display: none;
    }

    .sidebar-links li a {
        display: flex;
        align-items: center;
        gap: 0 20px;
        color: #fff;
        font-weight: 500;
        white-space: nowrap;
        padding: 15px 10px;
        text-decoration: none;
        transition: 0.2s ease;
    }

    .sidebar-links li a:hover {
        color: #cfe937;
    }

    .user-account {
        margin-top: auto;
        padding: 12px 10px;
        margin-left: -10px;
    }

    .user-profile {
        display: flex;
        align-items: center;
        color: #161a2d;
    }

    .user-profile img {
        width: 42px;
        border-radius: 50%;
        border: 2px solid #fff;
    }

    .user-profile h3 {
        font-size: 1rem;
        font-weight: 600;
    }

    .user-profile span {
        font-size: 0.775rem;
        font-weight: 600;
    }

    .user-detail {
        margin-left: 23px;
        white-space: nowrap;
    }

    .sidebar:hover .user-account {
        background: #fff;
        border-radius: 4px;
    }

    .change_store{
      display: none;
    }

    .change_store li a {
        color: #fff;
        padding: 5px 0;
        font-size: 14px;
    }

    #sidebar_store{
      color: #fff;
      cursor: pointer;
    }
</style>
