<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Restaurant App - Receipt - SaleID: {{$sale->id}}</title>
    <link type="text/css" rel="stylesheet" href="{{asset('/css/receipt.css')}}" media="all">
    <link type="text/css" rel="stylesheet" href="{{asset('/css/no-print.css')}}" media="print">
</head>
<body>
<div id="wrapper">
    <div id="receipt-header">
        <h3 id="restaurant-name">Restaurant App Dev</h3>
        <p>Address: Belgium, 43\5 Clause</p>
        <p>MD 878</p>
        <p>Tel: 5544-65-6</p>
        <p>Reference Receipt: <strong>{{$sale->id}}</strong></p>
    </div>
    <div id="receipt-body">
        <table class="tb-sale-detail">
            <thead>
            <tr>
                <th>#</th>
                <th>Menu</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
                @foreach($saleDetails as $saleDetail)
                    <tr>
                        <td width="30">{{$saleDetail->menu_id}}</td>
                        <td width="180">{{$saleDetail->menu_name}}</td>
                        <td width="50">{{$saleDetail->quantity}}</td>
                        <td width="55">${{$saleDetail->menu_price}}</td>
                        <td width="65">${{$saleDetail->menu_price * $saleDetail->quantity}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table class="tb-sale-total">
            <tbody>
            <tr>
                <td>Total Quantity</td>
                <td>{{$saleDetails->count()}}</td>
                <td>Total</td>
                <td>${{number_format($sale->total_price, 2)}}</td>
            </tr>
            <tr>
                <td colspan="2">Payment Type</td>
                <td colspan="2">{{$sale->payment_type}}</td>
            </tr>
            <tr>
                <td colspan="2">Paid Amount</td>
                <td colspan="2">${{number_format($sale->total_recieved, 2)}}</td>
            </tr>
            <tr>
                <td colspan="2">Change</td>
                <td colspan="2">${{number_format($sale->change, 2)}}</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div id="receipt-footer">
        <p>Thank You</p>
    </div>
    <div id="buttons">
        <a href="/cashier">
            <button class="btn btn-back">
                Back to Cashier
            </button>
        </a>
        <button class="btn btn-print" type="button" onclick="window.print(); return false;">
            Print
        </button>
    </div>
</div>

</body>
</html>
