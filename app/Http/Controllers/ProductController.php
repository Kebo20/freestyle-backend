<?php

namespace App\Http\Controllers;

use App\Http\Resources\Product as ResourcesProduct;
use App\Models\KernelLog;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            return ResourcesProduct::collection(Product::where("status", 1)->paginate(10));
        } catch (Exception $e) {

            DB::rollBack();
            return response()->json([
                'message' => KernelLogController::fnKernelLogMessage($e),
            ], 400);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric',

        ]);

        $exist_product = Product::where('name', $request->name)->first();
        if ($exist_product != null)
            return response()->json([
                'message' => 'Ya existe un producto con el mismo nombre.'
            ], 400);


        // $exist_product = Product::where('code', $request->code)->first();
        // if ($exist_product != null)
        //     return response()->json([
        //         'message' => 'Ya existe un producto con el mismo código.'
        //     ], 400);

        if ($request->price < 1) {
            return response()->json([
                'message' => 'El precio debe ser mayor a 0'
            ], 400);
        }
        try {
            DB::beginTransaction();

            $product = new Product();
            $product->code = strip_tags($request->code);
            $product->name = strip_tags($request->name);
            $product->price = strip_tags($request->price);
            $product->price_old = strip_tags($request->price_old);
            $product->image = ($request->image);
            $product->brand = strip_tags($request->brand);
            $product->description = strip_tags($request->description);
            $product->units = strip_tags($request->units);
            $product->active = 1;
            $product->novelty = $request->novelty == 1 ? '1' : '0';
            $product->recommended = $request->recommended == 1 ? '1' : '0';
            $product->idCategory = $request->idCategory ? strip_tags($request->idCategory) : null;
            $product->created_by = auth()->id();
            $product->updated_by = auth()->id();
            $product->save();


            DB::commit();
            return response()->json([
                'message' => 'Producto registrado.',
            ], 201);
        } catch (Exception $e) {

            DB::rollBack();
            return response()->json([
                'message' => KernelLogController::fnKernelLogMessage($e),
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric',
        ]);

        if ($request->price < 1) {
            return response()->json([
                'message' => 'El precio debe ser maypr a 0'
            ], 400);
        }

        try {

            DB::beginTransaction();

            $product = Product::findOrFail($id);
            $product->name = strip_tags($request->name);
            $product->code = strip_tags($request->code);
            $product->price = strip_tags($request->price);
            $product->price_old = strip_tags($request->price_old);
            $product->image = ($request->image);
            $product->brand = strip_tags($request->brand);
            $product->description = strip_tags($request->description);
            $product->units = "";
            $product->novelty = $request->novelty == 1 ? '1' : '0';
            $product->recommended = $request->recommended == 1 ? '1' : '0';
            $product->idCategory = $request->idCategory ? strip_tags($request->idCategory) : null;
            $product->updated_by = auth()->id();
            $product->save();


            DB::commit();
            return response()->json([
                'message' => 'Producto actualizado.',
            ], 200);
        } catch (Exception $e) {

            DB::rollBack();
            return response()->json([
                'message' => KernelLogController::fnKernelLogMessage($e),
            ], 400);
        }
    }

    public function destroy($id)
    {

        $product = Product::findOrFail($id);
        if ($product == null)
            return response()->json([
                'message' => 'id inválido.'
            ], 400);

        try {

            DB::beginTransaction();

            $product->status = 0;
            $product->save();

            DB::commit();
            return response()->json([
                'message' => 'Producto eliminado.',
            ], 200);
        } catch (Exception $e) {

            DB::rollBack();
            return response()->json([
                'message' => KernelLogController::fnKernelLogMessage($e),
            ], 400);
        }
    }


    public function active($id)
    {

        $product = Product::findOrFail($id);
        if ($product == null)
            return response()->json([
                'message' => 'id inválido.'
            ], 400);

        try {

            DB::beginTransaction();

            $product->active =  $product->active==0?1:0;
            $product->save();

            DB::commit();
            return response()->json([
                'message' => 'Producto actualizado.',
            ], 200);
        } catch (Exception $e) {

            DB::rollBack();
            return response()->json([
                'message' => KernelLogController::fnKernelLogMessage($e),
            ], 400);
        }
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        if ($product == null)
            return response()->json([
                'message' => 'id inválido.'
            ], 400);

        try {

            return new ResourcesProduct($product);
        } catch (Exception $e) {

            DB::rollBack();
            return response()->json([
                'message' => KernelLogController::fnKernelLogMessage($e),
            ], 400);
        }
    }

    public function list(Request $request)
    {
        try {
            return ResourcesProduct::collection(Product::where("name", "like", "%" . $request->search . "%")->get());
        } catch (Exception $e) {

            DB::rollBack();
            return response()->json([
                'message' => KernelLogController::fnKernelLogMessage($e),
            ], 400);
        }
    }

    public function novelties(Request $request)
    {
        try {
            return ResourcesProduct::collection(Product::where('novelty', 1)->where('status', 1)->get());
        } catch (Exception $e) {

            DB::rollBack();
            return response()->json([
                'message' => KernelLogController::fnKernelLogMessage($e),
            ], 400);
        }
    }

    public function category(Request $request)
    {
        try {
            if ($request->idCategory == 'all') {
                return ResourcesProduct::collection(Product::orderBy($request->order_name, $request->order)
                    ->where('name', 'like', '%' . $request->search . '%')
                    ->where('status', 1)
                    ->where('active', 1)
                    ->get());
            } else {
                return ResourcesProduct::collection(Product::where('idCategory', $request->idCategory)
                    ->orderBy($request->order_name, $request->order)
                    ->where('name', 'like', '%' . $request->search . '%')
                    ->where('status', 1)
                    ->where('active', 1)
                    ->get());
            }
        } catch (Exception $e) {

            DB::rollBack();
            return response()->json([
                'message' => KernelLogController::fnKernelLogMessage($e),
            ], 400);
        }
    }
}
