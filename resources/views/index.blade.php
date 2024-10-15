<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('/css/index.css') }}">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <title>Home</title>
</head>
<body>

    <div class="flex_container">

        <div class="bill_container" onclick="location.href='{{ route('bill') }}' ">
            <div class="bill_icon">
                <p>売上計上</p>
            </div>
        </div>
    
        <div class="vendor_container" onclick="location.href='{{ route('supplier') }}' ">
            <div class="vendor_icon">
                <p>提携業社</p>
            </div>
        </div>
    
    </div>

    <div class="flex_container">

        <div class="staff_container" onclick="location.href='{{ route('staff') }}' ">
            <div class="staff_icon">
                <p>従業員別売上</p>
            </div>
        </div>
        
        <div class="month_container" onclick="location.href='{{ route('month') }}' ">
            <div class="month_icon">
                <p>月次売上</p>
            </div>
        </div>

    </div>


    <div class="flex_container">

        <div class="customer_container" onclick="location.href='{{ route('customer') }}' ">
            <div class="customer_icon">
                <p>顧客別集計</p>
            </div>
        </div>

        <div class="fake_container">

        </div>

    </div>
    
    <script src="{{ asset('/js/index.js') }}"></script>    
    <script src="{{ asset('/js/common.js') }}"></script>
</body>
</html>