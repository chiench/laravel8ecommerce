<!DOCTYPE html>
<html>

<head>
    <title>Laravel 9 Generate PDF Example - ItSolutionStuff.com</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="s
        ha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
        crossorigin="anonymous">
    <style>
        .panel-body {
            width: 500px;

        }
    </style>

</head>

<body>
    <div class="content">

        <div class="container">
            <div class="row">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-12">

                        </div>
                        <div class="col-md-12">
                            <p style="padding-left: 12px">Export Day : <b> {{ $date }}</b></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div style="margin-bottom: 10px" class="panel-heading">
                            Latest Order
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 40px">OrderId</th>
                                        <th>Subtotal</th>
                                        <th>Discount</th>
                                        <th>Tax</th>
                                        <th>Total</th>
                                        <th>Full Name</th>

                                        <th>Mobile</th>



                                        <th>Order Date</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>${{ $order->subtotal }}</td>
                                            <td>${{ $order->discount }}</td>
                                            <td>${{ $order->tax }}</td>
                                            <td>${{ $order->total }}</td>
                                            <td>{{ $order->firstname }} {{ $order->lastname }}</td>

                                            <td>{{ $order->mobile }}</td>



                                            <td>{{ $order->created_at }}</td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>

</html>
