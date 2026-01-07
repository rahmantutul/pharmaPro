<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Category;
use App\Models\Leaf;
use App\Models\Supplier;
use App\Models\Vendor;
use App\Models\Type;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
class MedicineController extends Controller
{
    public function index($id=null)
    {
        $suppliers = Supplier::get();
        $categories = Category::get();
        $vendors = Vendor::get();
        $leaves = Leaf::get();
        $types = Type::get();
        $medicines = Medicine::latest()->paginate(15);
        if($id){
            $medicine = Medicine::findOrFail($id);
            return view('dashboard.medicine.medicine_index', compact('suppliers','categories','vendors','leaves','types','medicines','medicine'));
        }else{
            return view('dashboard.medicine.medicine_index', compact('suppliers','categories','vendors','leaves','types','medicines'));
        }
        
    }

    public function search(Request $request)
    {
        $query = Medicine::query();
    
        if ($request->medName) {
            $query->where('name', 'like', '%' . $request->medName . '%');
        }
    
        if ($request->medSupplier) {
            $query->where('supplierId', $request->medSupplier);
        }
    
        if ($request->medCategory) {
            $query->where('categoryId', $request->medCategory);
        }
    
        $medicines = $query->with('supplier')->paginate(10); // Paginate the results, 10 items per page
    
        if ($request->ajax()) {
            return view('dashboard.medicine.filter_medicine', compact('medicines'))->render();
        }
    
        return view('dashboard.medicine.filter_medicine', compact('medicines'));
    }
    
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qr_code' => 'required|string|max:255',
            'hns_code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'strength' => 'nullable|string|max:255',
            'generic_name' => 'required|string|max:255',
            'desc' => 'nullable|string|max:1000',
            'leafId' => 'required|integer',
            'categoryId' => 'required|integer',
            'vendorId' => 'required|integer',
            'supplierId' => 'required|integer',
            'typeId' => 'required|integer',
            'sell_price' => 'required',
            'purchase_price' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = public_path('uploads/images/medicine');
            $image->move($imagePath,$imageName);
            $logoManager = new ImageManager(new Driver());
            $file = $logoManager->read($imagePath.'/'.$imageName);
            $file->resize(400,400);
            $file->save();
        }else{
            $imageName='default.png';
        }

        Medicine::create([
            'qr_code' => $request->qr_code,
            'hns_code' => $request->hns_code,
            'name' => $request->name,
            'strength' => $request->strength,
            'generic_name' => $request->generic_name,
            'desc' => $request->desc,
            'leafId' => $request->leafId,
            'categoryId' => $request->categoryId,
            'vendorId' => $request->vendorId,
            'supplierId' => $request->supplierId,
            'typeId' => $request->typeId,
            'purchase_price' => $request->purchase_price,
            'sell_price' => $request->sell_price,
            'image' => $imageName,
        ]);

        return redirect()->back()->with('success', 'Medicine created successfully.');
    }

    public function update(Request $request, $id)
    {
        // Validate incoming data
        $request->validate([
            'qr_code' => 'required|string|max:255',
            'hns_code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'strength' => 'nullable|string|max:255',
            'generic_name' => 'required|string|max:255',
            'desc' => 'nullable|string|max:1000',
            'leafId' => 'required|integer',
            'categoryId' => 'required|integer',
            'vendorId' => 'required|integer',
            'supplierId' => 'required|integer',
            'typeId' => 'required|integer',
            'sell_price' => 'required',
            'purchase_price' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Find the existing medicine record
        $medicine = Medicine::findOrFail($id);

        // Handle file upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($medicine->image && file_exists(public_path('uploads/images/medicine/' . $medicine->image))) {
                unlink(public_path('uploads/images/medicine/' . $medicine->image));
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/images/medicine'), $imageName);
            $medicine->image = $imageName;
        }

        // Update medicine fields
        $medicine->update([
            'qr_code' => $request->qr_code,
            'hns_code' => $request->hns_code,
            'name' => $request->name,
            'strength' => $request->strength,
            'generic_name' => $request->generic_name,
            'desc' => $request->desc,
            'leafId' => $request->leafId,
            'purchase_price' => $request->purchase_price,
            'sell_price' => $request->sell_price,
            'categoryId' => $request->categoryId,
            'vendorId' => $request->vendorId,
            'supplierId' => $request->supplierId,
            'typeId' => $request->typeId,
        ]);

        return redirect()->back()->with('success', 'Medicine updated successfully.');
    }
    

    public function destroy($id)
    {
        $dataInfo=Medicine::findOrFail($id);
        if ($dataInfo->image && file_exists(public_path('uploads/images/medicine/' . $dataInfo->image))) {
            unlink(public_path('uploads/images/medicine/' . $dataInfo->image));
        }
        $dataInfo->delete();
        return redirect()->back()->with('success', 'Medicine deleted successfully.');
    }

    public function getMedicineDetails(Request $request){
        if($request->ajax()){
            $medId = $request->id;
            $dataInfo= Medicine::with('supplier')->find($medId);
            if($dataInfo){
                return response()->json([
                    'success'=>true,
                    'dataInfo'=>$dataInfo,
                ]);
            }else{
                return response()->json([
                    'error'=>true,
                    'message' => 'Medicine not found.'
                ],404);
            }
        }else{
            return response()->json([
                'error'=>true,
                'message'=>'Invalid Request',
            ],403);
        }
    }
}
