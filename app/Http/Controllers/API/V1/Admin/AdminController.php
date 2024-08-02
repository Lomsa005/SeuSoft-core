<?php
namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Models\API\V1\Admin\Admin;
use App\Services\AdminServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    protected $adminServices;

    public function __construct(AdminServices $adminServices)
    {
        $this->adminServices = $adminServices;
    }
    
    public function store(StoreAdminRequest $request)
    {
        try {
            $productData = [
                'title' => $request->title,
            ];

            if ($request->type == 1) {
                $productData['body'] = $request->body;
                $message = 'Regular product created successfully';
            } else {
                try {
                    $productData['titles'] = json_decode($request->titles, true);
                    $productData['images'] = json_decode($request->images, true);
                } catch (\JsonException $e) {
                    return response()->json([
                        'message' => 'Invalid JSON data for titles or images',
                        'error' => $e->getMessage()
                    ], 400);
                }
                $message = 'Ultra product created successfully';
            }

            $product = Admin::create($productData);

            return response()->json([
                'message' => $message,
                'data' => $product
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error in AdminController@store: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while creating the product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        $image = $request->file('image');
        $imageName = time().'.'.$image->getClientOriginalExtension();
        $imagePath = $image->storeAs('images', $imageName, 'public');
    
        Log::info('Image stored at: ' . $imagePath);
    
        return response()->json(['url' => Storage::url($imagePath)]);
    }

    public function delete($id)
    {
        try {
            $deleted = $this->adminServices->deleteProduct($id);
            
            if ($deleted) {
                return response()->json([
                    'message' => 'Product deleted successfully'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Product not found',
                ], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error in AdminController@delete: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while deleting the product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(UpdateAdminRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();
            
            if (isset($validatedData['titles'])) {
                try {
                    $validatedData['titles'] = json_decode($validatedData['titles'], true);
                } catch (\JsonException $e) {
                    return response()->json([
                        'message' => 'Invalid JSON data for titles',
                        'error' => $e->getMessage()
                    ], 400);
                }
            }
            
            if (isset($validatedData['images'])) {
                try {
                    $validatedData['images'] = json_decode($validatedData['images'], true);
                } catch (\JsonException $e) {
                    return response()->json([
                        'message' => 'Invalid JSON data for images',
                        'error' => $e->getMessage()
                    ], 400);
                }
            }

            $result = $this->adminServices->updateProduct($id, $validatedData);
            
            if ($result) {
                return response()->json([
                    'message' => 'Product updated successfully',
                    'data' => $result
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Product not found',
                ], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error in AdminController@update: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while updating the product',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}