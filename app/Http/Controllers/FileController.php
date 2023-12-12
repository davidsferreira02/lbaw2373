<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class FileController extends Controller
{   
    static $default = 'default.jpg';
    static $diskName = 'Tutorial02'; 

    static $systemTypes = [
        'profile' => ['png', 'jpg', 'jpeg', 'gif'],
      
    ];

    private static function getDefaultExtension(String $type) {
        return reset(self::$systemTypes[$type]);
    }

    private static function isValidExtension(String $type, String $extension) {
        $allowedExtensions = self::$systemTypes[$type];

        
        return in_array(strtolower($extension), $allowedExtensions);
    }

    private static function isValidType(String $type) {
        return array_key_exists($type, self::$systemTypes);
    }

    private static function defaultAsset(String $type) {
        return asset($type . '/' . self::$default);
    }

    private static function getFileName(String $type, int $id, String $extension = null) {

        $fileName = null;
        switch($type) {
            case 'profile':
                $fileName = User::find($id)->profile_image; 
                break;

            default:
                return null;
        }

        return $fileName;
    }

    private static function delete(String $type, int $id) {
        $existingFileName = self::getFileName($type, $id);
        if ($existingFileName) {
            Storage::disk(self::$diskName)->delete($type . '/' . $existingFileName);

            switch($type) {
                case 'profile':
                    User::find($id)->profile_image = null;
                    break;
                
            }
        }
    }

    function upload(Request $request) {

        
        if (!$request->hasFile('file')) {
            return redirect()->back()->with('error', 'Error: File not found');
        }

        
        if (!$this->isValidType($request->type)) {
            return redirect()->back()->with('error', 'Error: Unsupported upload type');
        }

      
        $file = $request->file('file');
        $type = $request->type;
        $extension = $file->extension();
        if (!$this->isValidExtension($type, $extension)) {
            return redirect()->back()->with('error', 'Error: Unsupported upload extension');
        }

    
        $this->delete($type, $request->id);

      
        $fileName = $file->hashName();

       
        $error = null;
        switch($request->type) {
            case 'profile':
                $user = User::findOrFail($request->id);
                if ($user) {
                    $user->profile_image = $fileName;
                    $user->save();
                } else {
                    $error = "unknown user";
                }
                break;

          
        
            default:
                redirect()->back()->with('error', 'Error: Unsupported upload object');
        }

        if ($error) {
            redirect()->back()->with('error', `Error: {$error}`);
        }

        $file->storeAs($type, $fileName, self::$diskName);
        return redirect()->back()->with('success', 'Success: upload completed!');
    }

    static function get(String $type, int $userId) {

        
        if (!self::isValidType($type)) {
            return self::defaultAsset($type);
        }

   
        $fileName = self::getFileName($type, $userId);
        if ($fileName) {
            return asset($type . '/' . $fileName);
        }

  
        return self::defaultAsset($type);
    }
}
