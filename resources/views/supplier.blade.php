<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('/css/supplier.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>提携業者</title>
</head>


<body>

    <div style="display: flex;">

        <div>
            @include('layouts.sidebar')
        </div>

        <div style="width:100%; margin-left: 20px;">
            @if (count($suppliers) == 0)
            @include('supplier.NoTable')
            @else
            @include('supplier.table')
            @endif
        </div>

    </div>

    @if (count($suppliers) != 0)
    @include('modal.supplier.add')
    @include('modal.supplier.list')
    @include('modal.supplier.edit')
    @endif

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="{{ asset('/js/common.js') }}"></script>
    <script id="supplier" type="text/javascript" src="{{ asset('/js/supplier.js') }}" 
    data-allDays='<?php echo json_encode($days); ?>'
    data-suppliers='<?php echo json_encode($suppliers); ?>'
    data-url='{{route('supplier_cell_save')}}' ></script>
</body>
