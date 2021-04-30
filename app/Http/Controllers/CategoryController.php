<?php

namespace App\Http\Controllers;

use App\Http\Resources\Category as ResourcesCategory;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            return ResourcesCategory::collection(Category::where("status", 1)->paginate(10));
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
            'name' => 'required|max:255'
        ]);

        $exist_category = Category::where('name', $request->name)->first();
        if ($exist_category != null)
            return response()->json([
                'message' => 'Ya existe una categoría con el mismo nombre.'
            ], 400);
        try {
            DB::beginTransaction();

            $category = new Category();
            $category->name = strip_tags($request->name);
            $category->created_by = auth()->id();
            $category->updated_by = auth()->id();
            $category->save();


            DB::commit();
            return response()->json([
                'message' => 'Categoría registrada.',
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
            'name' => 'required|max:255'
        ]);
        try {
            DB::beginTransaction();

            $category = Category::findOrFail($id);
            $category->name = $request->name;
            $category->updated_by = auth()->id();
            $category->save();


            DB::commit();
            return response()->json([
                'message' => 'Categoría actualizada.',
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

        $category = Category::findOrFail($id);
        if ($category == null)
            return response()->json([
                'message' => 'id inválido.'
            ], 400);
        try {
            DB::beginTransaction();

            $category->status = 0;
            $category->save();

            DB::commit();
            return response()->json([
                'message' => 'Categoría eliminada.',
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
        $category = Category::findOrFail($id);
        if ($category == null)
            return response()->json([
                'message' => 'id inválido.'
            ], 400);

        try {

            return new ResourcesCategory($category);
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
            return ResourcesCategory::collection(Category::where("status", 1)->get());
        } catch (Exception $e) {

            DB::rollBack();
            return response()->json([
                'message' => KernelLogController::fnKernelLogMessage($e),
            ], 400);
        }
    }
}
