<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('cashier.index')->with('categories', $categories);
    }

    /**
     * Show the form for creating a new resource.
     */

    public function getTables()
    {

        $tables = Table::all();
        $html = '';
        foreach ($tables as $table) {

            $html .= '<div class="col-md-2 mb-2">';
            $html .= '<button class="btn btn-primary btn-table" data-id="' . $table->id . '" data-name="' . $table->name . '"><br>';
            if($table->status == 'available'){
                $html .= '<span class="badge rounded-pill text-bg-success">' . $table->name . '</span>⛩';
            }else {
                $html .= '<span class="badge rounded-pill text-bg-danger">' . $table->name . '</span>⛩';
            }
            $html .= '</button>';
            $html .= '</div>';
        }
        return $html;
    }

    public function getMenuByCategory($category_id)
    {

        $menus = Menu::where('category_id', $category_id)->get();
        $html = '';
        foreach ($menus as $menu) {
            $html .= '
                       <div class="col-md-3 text-center">
                        <a class="btn btn-outline-secondary btn-menu" data-id="' . $menu->id . '">
                            <img class="img-fluid" src="' . url('/menu_images/' . $menu->image) . '">
                            <br>
                            ' . $menu->name . '
                            <br>
                            $' . number_format($menu->price) . '
                        </a>
                        </div>
                    ';
        }

        return $html;

    }

    public function orderFood(Request $request)
    {

        $menu = Menu::find($request->menu_id);
        $table_id = $request->table_id;
        $table_name = $request->table_name;
        $sale = Sale::where('table_id', $table_id)->where('sale_status', 'unpaid')->first();
        if (!$sale) {
            $user = Auth::user();
            $sale = new Sale();
            $sale->table_id = $table_id;
            $sale->table_name = $table_name;
            $sale->user_id = $user->id;
            $sale->user_name = $user->name;
            $sale->save();
            $sale_id = $sale->id;

            $table = Table::find($table_id);
            $table->status = "unavailable";
            $table->save();
        } else {
            $sale_id = $sale->id;
        }

        // add ordered menu to the sale_details
        $saleDetail = new SaleDetail();
        $saleDetail->sale_id = $sale_id;
        $saleDetail->menu_id = $menu->id;
        $saleDetail->menu_name = $menu->name;
        $saleDetail->menu_price = $menu->price;
        $saleDetail->quantity = $request->quantity;
        $saleDetail->save();

//            //update total price
        $sale->total_price = $sale->total_price + ($request->quantity * $menu->price);
        $sale->save();

//            //list all saleDetail
        $html = $this->getSaleDetails($sale_id);
        return $html;

    }

    public function getSaleDetailsByTable($table_id){

        $sale = Sale::where('table_id', $table_id)->where('sale_status', 'unpaid')->first();
        $html = '';

        if($sale){
                $sale_id = $sale->id;
                $html .= $this->getSaleDetails($sale_id);
        } else {
            $html .= "Not Found Any Sale Details for the Selected Table";
        }
        return $html;

    }

    private function getSaleDetails($sale_id)
    {

        $html = '<p>Sale ID: ' . $sale_id . '</p>';
        $saleDetails = SaleDetail::where('sale_id', $sale_id)->get();
        $html .= '<div class="table-responsive-md" style="overflow-y: scroll; height: 400px; border: 1px solid #343A40">
            <table class="table table-striped table-dark">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Menu</th>
                <th scope="col">Quantity</th>
                <th scope="col">Price</th>
                <th scope="col">Total</th>
                <th scope="col">Status</th>
            </tr>
            </thead>
            <tbody>';
            $showBtnPayment = true;
        foreach ($saleDetails as $saleDetail) {

            $html .= '
                <tr>
                    <td>' . $saleDetail->menu_id . '</td>
                    <td>' . $saleDetail->menu_name . '</td>
                    <td>' . $saleDetail->quantity . '<button data-id="'.$saleDetail->id.'" class="btn btn-primary btn-sm btn-increase-quantity">+</button> </td>
                    <td>' . $saleDetail->menu_price . '</td>
                    <td>' . ($saleDetail->menu_price * $saleDetail->quantity) . '</td>';
                if ($saleDetail->status == "noConfirm") {
                    $showBtnPayment = false;
                    $html .= '<td><a data-id="'.$saleDetail->id.'" class="btn btn-danger btn-delete-saledetail">🗑️</a></td>';
                }else{
                    $html .= '<td>✅</td>';
                }

                    $html .= '</tr>';
        }

        $html .= '</tbody></table></div>';

        $sale = Sale::find($sale_id);
        $html .= '<hr>';
        $html .= '<h3>Total Amount: $'.number_format($sale->total_price).'</h3>';

        if($showBtnPayment){
            $html .='<button data-id="'.$sale_id.'" data-totalAmount="'.$sale->total_price.'" class="btn btn-success btn-block btn-payment" data-bs-toggle="modal" data-bs-target="#exampleModal">Payment</button>';
        }else{
            $html .='<button data-id="'.$sale_id.'" class="btn btn-warning btn-block btn-confirm-order">Confirm Order</button>';
        }

        return $html;

    }

    public function increaseQuantity(Request $request){

        $saleDetail_id = $request->saleDetail_id;
        $saleDetail = SaleDetail::where('id', $saleDetail_id)->first();
        $saleDetail->quantity = $saleDetail->quantity + 1;
        $saleDetail->save();

        //update total amount
        $sale = Sale::where('id', $saleDetail->sale_id)->first();
        $sale->total_price = $sale->total_price + $saleDetail->menu_price;
        $sale->save();

        $html = $this->getSaleDetails($saleDetail->sale_id);
        return $html;


    }

    public function confirmOrderStatus(Request $request){

        $sale_id = $request->sale_id;
        $saleDetails = SaleDetail::where('sale_id', $sale_id)->update(['status' => 'confirm']);
        $html = $this->getSaleDetails($sale_id);
        return $html;

    }

    public function deleteSaleDetail(Request $request){

        $saleDetail_id = $request->saleDetail_id;
        $saleDetail = SaleDetail::find($saleDetail_id);
        $sale_id = $saleDetail->sale_id;
        $menu_price = ($saleDetail->menu_price * $saleDetail->quantity);
        $saleDetail->delete();

        $sale = Sale::find($sale_id);
        $sale->total_price = $sale->total_price - $menu_price;
        $sale->save();

        $saleDetails = SaleDetail::where('sale_id', $sale_id)->first();
        if($saleDetail){
            $html = $this->getSaleDetails($sale_id);
        }else {
            $html = "Not Found Any Sale Details for the Selected Table";
        }
        return $html;
    }

    public function savePayment(Request $request){

            $saleID = $request->saleID;
            $recievedAmount = $request->recievedAmount;
            $paymentType = $request->paymentType;

            //update sale info in the sales DB by use sale model
            $sale = Sale::find($saleID);
            $sale->total_recieved = $recievedAmount;
            $sale->change = $recievedAmount - $sale->total_price;
            $sale->payment_type = $paymentType;
            $sale->sale_status = "paid";
            $sale->save();
            // update table to be available
            $table = Table::find($sale->table_id);
            $table->status = "available";
            $table->save();
            return "/cashier/showReceipt/".$saleID;
    }

    public function showReceipt($saleID){

        $sale = Sale::find($saleID);
        $saleDetails = SaleDetail::where('sale_id', $saleID)->get();
        return view('cashier.showReceipt')->with('sale', $sale)->with('saleDetails',$saleDetails);

    }

}
