<?php

namespace App\Http\Controllers;

use App\Models\EmailSetting;
use App\Models\GeneralSetting;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SettingController extends Controller
{
    public function setting_index(){
        $dataInfo = GeneralSetting::first();
        $timeZones=Helper::TimeZones();
        return view('dashboard.settings.index',compact('dataInfo','timeZones'));
    }

    public function setting_update(Request $request){
        $validatedData = $request->validate([
           'dataId' => 'required|integer',
            'appname' => 'required|string|max:255',
            'currency' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'expiryalert' => 'required|integer',
            'lowstockalert' => 'required|integer',
            'timezone' => 'required|string',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',  // Validate as image
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',     // Validate as image
            'mail_driver' => 'required|string',
            'mail_host' => 'required|string',
            'mail_port' => 'required|string',
            'mail_username' => 'required|string|max:255',
            'mail_password' => 'required|string|min:8',
            'mail_encryption' => 'required|string',
            'mail_from_address' => 'required|string|email',
            'mail_from_name' => 'required|string|max:255',
        ]);

        $dataInfo = GeneralSetting::findOrFail($request->dataId);
        $dataInfo->appname = $request->appname;
        $dataInfo->email = $request->email;
        $dataInfo->phone = $request->phone;
        $dataInfo->address = $request->address;
        $dataInfo->timezone = $request->timezone;
        $dataInfo->currency = $request->currency;
        $dataInfo->expiryalert = $request->expiryalert;
        $dataInfo->lowstockalert = $request->lowstockalert;
        $dataInfo->mail_driver = $request->mail_driver;
        $dataInfo->mail_host = $request->mail_host;
        $dataInfo->mail_port = $request->mail_port;
        $dataInfo->mail_username = $request->mail_username;
        $dataInfo->mail_password = $request->mail_password;
        $dataInfo->mail_encryption = $request->mail_encryption;
        $dataInfo->mail_from_address = $request->mail_from_address;
        $dataInfo->mail_from_name = $request->mail_from_name;
        if ($request->hasFile('logo')) {
            try {
                if ($dataInfo->logo && file_exists(public_path('uploads/images/settings/' . $dataInfo->logo))) {
                    unlink(public_path('uploads/images/settings/' . $dataInfo->logo));
                }
                $logo = $request->file('logo');
                $logoName = 'logo_' . uniqid() . '.' . $logo->getClientOriginalExtension();
                $logoPath = public_path('uploads/images/settings');
                
                // Ensure directory exists
                if (!file_exists($logoPath)) {
                    mkdir($logoPath, 0777, true);
                }

                $logo->move($logoPath, $logoName);
                
                $logoManager = new ImageManager(new Driver());
                $image = $logoManager->read($logoPath . '/' . $logoName);
                
                // Smart Scaling: Scale down to 300px width max, keep aspect ratio
                // This prevents stretching the logo while ensuring it's "compressed" for web
                $image->scale(width: 300); 
                
                // Save with 80% quality for best balance of compression and visual design
                $image->toJpeg(80)->save($logoPath . '/' . $logoName);
                
                $dataInfo->logo = $logoName;
            } catch (\Exception $e) {
                \Log::error('Logo Upload Error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Logo processing failed: ' . $e->getMessage());
            }
        }

        if ($request->hasFile('favicon')) {
            try {
                if ($dataInfo->favicon && file_exists(public_path('uploads/images/settings/' . $dataInfo->favicon))) {
                    unlink(public_path('uploads/images/settings/' . $dataInfo->favicon));
                }
                $favicon = $request->file('favicon');
                $favName = 'fav_' . uniqid() . '.' . $favicon->getClientOriginalExtension();
                $favPath = public_path('uploads/images/settings');

                if (!file_exists($favPath)) {
                    mkdir($favPath, 0777, true);
                }

                $favicon->move($favPath, $favName);
                
                $favManager = new ImageManager(new Driver());
                $image = $favManager->read($favPath . '/' . $favName);
                
                // Favicons are small, force to 64x64 square
                $image->cover(64, 64);
                $image->toPng()->save($favPath . '/' . $favName);
                
                $dataInfo->favicon = $favName;
            } catch (\Exception $e) {
                \Log::error('Favicon Upload Error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Favicon processing failed: ' . $e->getMessage());
            }
        }
        $dataInfo->save();
        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
