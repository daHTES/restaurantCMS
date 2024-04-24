@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row" id="table-detail"></div>
        <div class="row justify-content-center">
            <div class="col-md-5">
                <button class="btn btn-primary btn-block" id="btn-show-tables">View All Table</button>
                <div id="select-table"></div>
                <div id="order-detail"></div>
            </div>
            <div class="col-md-7">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        @foreach($categories as $category)
                            <a class="nav-item nav-link" data-id="{{$category->id}}" data-toggle="tab">
                                {{$category->name}}
                            </a>
                        @endforeach
                    </div>
                </nav>
                <div id="list-menu" class="row mt-2"></div>
            </div>
        </div>
    </div>
<!--Modal view boxes-->

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Payment</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h3 class="totalAmount"></h3>
                    <h3 class="changeAmount"></h3>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                        </div>
                        <input type="number" id="recieved-amount" class="form-control bg-white">
                    </div>
                    <div class="form-group">
                        <label for="payment">Payment Type</label>
                        <select class="form-control" id="payment-type">
                            <option value="cash">Cash</option>
                            <option value="credit card">Credit Card</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-save-payment" disabled>Save Payment</button>
                </div>
            </div>
        </div>
    </div>
    <!--End of Modal view boxes-->
    <script>
        $(document).ready(function () {
            //default state
            $("#table-detail").hide();

                //show tables when a client click
                $("#btn-show-tables").click(function () {
                    if($("#table-detail").is(":hidden")){
                        $.get("/cashier/getTable", function (data) {
                            $("#table-detail").html(data);
                            $("#table-detail").slideDown('fast');
                            $("#btn-show-tables").html('Hide Tables').removeClass('btn-primary').addClass('btn-danger');
                        })
                    }else{
                        $("#table-detail").slideUp('fast');
                        $("#btn-show-tables").html('View All Tables').removeClass('btn-danger').addClass('btn-primary');
                    }
                });

                //load menu by cat.
            $(".nav-link").click(function () {
                $.get("/cashier/getMenuByCategory/"+$(this).data("id"),function (data) {
                    $("#list-menu").hide();
                    $("#list-menu").html(data);
                    $("#list-menu").fadeIn('fast');
                });
            })
            var SELECTED_TABLE_ID = "";
            var SELECTED_TABLE_NAME = "";
            var SALE_ID = "";
            // detect button when we click on VIEW ALL TABLe and selected specific table
            $("#table-detail").on('click', '.btn-table', function () {
                SELECTED_TABLE_ID = $(this).data("id");
                SELECTED_TABLE_NAME = $(this).data("name");
                $("#select-table").html('<br><h3>Table: '+SELECTED_TABLE_NAME+'</h3><hr>')
                $.get("/cashier/getSaleDetailsByTable/"+SELECTED_TABLE_ID, function (data) {
                                $("#order-detail").html(data);
                })
            })

            $("#list-menu").on('click', '.btn-menu', function() {
                if(SELECTED_TABLE_ID == ""){
                    alert("You need yo select a table for the customer first");
                }else{
                    var menu_id = $(this).data("id");
                    $.ajax({
                        type: "POST",
                        data: {
                            "_token":$('meta[name="csrf-token"]').attr('content'),
                            "menu_id": menu_id,
                            "table_id": SELECTED_TABLE_ID,
                            "table_name":  SELECTED_TABLE_NAME,
                            "quantity": 1
                        },
                        url: "/cashier/orderFood",
                        success: function(data){
                            $("#order-detail").html(data);
                        }
                    });
                }
            })

            $("#order-detail").on('click', ".btn-confirm-order", function () {
                        var SaleID = $(this).data("id");
                        $.ajax({
                            type: "POST",
                            data: {
                                "_token":$('meta[name="csrf-token"]').attr('content'),
                                "sale_id": SaleID
                            },
                            url: "/cashier/confirmOrderStatus",
                            success: function (data) {
                                $("#order-detail").html(data);
                            }
                  });
            })

            $("#order-detail").on("click", ".btn-delete-saledetail", function () {
                    var saleDetailID = $(this).data("id");
                    $.ajax({
                        type: "POST",
                        data:{
                            "_token":$('meta[name="csrf-token"]').attr('content'),
                            "saleDetail_id": saleDetailID
                        },
                        url: "/cashier/deleteSaleDetail",
                        success: function (data) {
                                $("#order-detail").html(data);
                        }
                    })
            })

            //inc++quantity

            $("#order-detail").on("click", ".btn-increase-quantity", function () {
                var saleDetailID = $(this).data("id");
                $.ajax({
                    type: "POST",
                    data:{
                        "_token":$('meta[name="csrf-token"]').attr('content'),
                        "saleDetail_id": saleDetailID
                    },
                    url: "/cashier/increase-quantity",
                    success: function (data) {
                        $("#order-detail").html(data);
                    }
                })
            })

            //trigger when user click PAYMENT
            $("#order-detail").on("click", ".btn-payment", function () {
                    var totalAmount = $(this).attr('data-totalAmount');
                    $(".totalAmount").html("Total Amount "+ totalAmount);
                    $("#recived-amount").val('');
                    $(".changeAmount").html('');
                    SALE_ID = $(this).data("id");
            })

            //calculate total price in payment boxes
            $("#recieved-amount").keyup(function () {
                    var totalAmount = $(".btn-payment").attr('data-totalAmount');
                    var recievedAmount = $(this).val();
                    var changeAmount = recievedAmount - totalAmount;
                    $(".changeAmount").html("Total Change: $" +changeAmount);

                //check if cash enter the amount | enable|disabled payment btn
                if(changeAmount >= 0){
                    $(".btn-save-payment").prop('disabled', false);
                }else{
                    $(".btn-save-payment").prop('disabled', true);
                }
            });
            //save payment
            $(".btn-save-payment").click(function () {
                    var recievedAmount = $("#recieved-amount").val();
                    var paymentType = $("#payment-type").val();
                    var saleId = SALE_ID;
                    $.ajax({
                        type: "POST",
                        data: {
                            "_token":$('meta[name="csrf-token"]').attr('content'),
                            "saleID": saleId,
                            "recievedAmount": recievedAmount,
                            "paymentType": paymentType
                        },
                        url: "/cashier/savePayment",
                        success: function (data) {
                                window.location.href = data;
                        }

                    });

            });
        });
    </script>
@endsection

