<?php

namespace App\Http\Controllers\Import;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Containercy as DBContainerFcl;
use App\Models\Container as DBContainerLcl;
use App\Models\Manifest as DBManifest;

class PhotoController extends Controller
{
 
    public function lclPhotoContainerIndex()
    {
        if ( !$this->access->can('lcl.photo.container.index') ) {
            return view('errors.no-access');
        }
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Index LCL Photo Container', 'slug' => 'lcl.photo.container.index', 'description' => ''));
        
        $data['page_title'] = "LCL Photo Container";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'LCL Photo Container'
            ]
        ];        
        
        return view('import.lcl.photo.index-container')->with($data);
    }
    
    public function lclPhotoCargoIndex()
    {
        if ( !$this->access->can('lcl.photo.cargo.index') ) {
            return view('errors.no-access');
        }
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Index LCL Photo Container', 'slug' => 'lcl.photo.cargo.index', 'description' => ''));
        
        $data['page_title'] = "LCL Photo Cargo";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'LCL Photo Cargo'
            ]
        ];        
        
        return view('import.lcl.photo.index-cargo')->with($data);
    }
    
    public function fclPhotoContainerIndex()
    {
        if ( !$this->access->can('fcl.photo.container.index') ) {
            return view('errors.no-access');
        }
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Index LCL Photo Container', 'slug' => 'fcl.photo.container.index', 'description' => ''));
        
        $data['page_title'] = "FCL Photo Container";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'FCL Photo Container'
            ]
        ];        
        
        return view('import.fcl.photo.index-container')->with($data);
    }
    
    public function containerUploadPhoto(Request $request)
    {
        if($request->type == 'lcl'){
            $container = DBContainerLcl::find($request->id_cont);
        }else{
            $container = DBContainerFcl::find($request->id_cont);
        }

        $picture = array();
        if ($request->hasFile('photos')) {
            $files = $request->file('photos');
            $destinationPath = base_path() . '/public/uploads/photos/container/'.$request->type.'/'.$container->NOCONTAINER;
            
            if (!\File::isDirectory($destinationPath)) {
                \File::makeDirectory($destinationPath);
            }
            
            $i = 1;
            foreach($files as $file){
                if($file){
    //                $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    // create instance
                    $img = \Image::make($file)->orientate();

                    // resize the image to a width of 300 and constrain aspect ratio (auto height)
                    $img->resize(600, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    $filename = $request->kegiatan.'_'.date('dmyHis').'_'.str_slug($container->NOCONTAINER).'_'.$i.'.'.$extension;

                    $picture[] = $filename;
    //                $file->move($destinationPath, $filename);
                    $img->save($destinationPath.'/'.$filename);
                    $i++;
                }
            }
            // update to Database
            
            switch ($request->kegiatan) {
                case "gatein":
                    $oldJson = json_decode($container->photo_gatein_extra);
                    $newJson = array_collapse([$oldJson,$picture]);
                    if(isset($request->hapus)){ $newJson = $picture; }
                    $container->photo_gatein_extra = json_encode($newJson);
                    break;
                case "stripping":
                    $oldJson = json_decode($container->photo_stripping);
                    $newJson = array_collapse([$oldJson,$picture]);
                    if(isset($request->hapus)){ $newJson = $picture; }
                    $container->photo_stripping = json_encode($newJson);
                    break;
                case "behandle":
                    $oldJson = json_decode($container->photo_behandle);
                    $newJson = array_collapse([$oldJson,$picture]);
                    if(isset($request->hapus)){ $newJson = $picture; }
                    $container->photo_behandle = json_encode($newJson);
                    break;
                case "release":
                    $oldJson = json_decode($container->photo_release_extra);
                    $newJson = array_collapse([$oldJson,$picture]);
                    if(isset($request->hapus)){ $newJson = $picture; }
                    $container->photo_release_extra = json_encode($newJson);
                    break;
                case "empty":
                    $oldJson = json_decode($container->photo_empty);
                    $newJson = array_collapse([$oldJson,$picture]);
                    if(isset($request->hapus)){ $newJson = $picture; }
                    $container->photo_empty = json_encode($newJson);
                    break;
            }
            
            if($container->save()){
                return back()->with('success', 'Photo '.ucfirst($request->kegiatan).' for Container '. $container->NOCONTAINER .' has been uploaded.');
            }else{
                return back()->with('error', 'Photo uploaded, but not save in Database.');
            }
            
        } else {
            return back()->with('error', 'Something wrong!!! Can\'t upload photo, please try again.');
        }
    }
    
    public function cargoUploadPhoto(Request $request)
    {

        $manifest = DBManifest::find($request->id_hbl);
        
        $picture = array();
        if ($request->hasFile('photos')) {
            $files = $request->file('photos');
            $destinationPath = base_path() . '/public/uploads/photos/manifest';
            
            if (!\File::isDirectory($destinationPath)) {
                \File::makeDirectory($destinationPath);
            }
            
            $i = 1;
            foreach($files as $file){
                if($file){
    //                $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    // create instance
                    $img = \Image::make($file)->orientate();

                    // resize the image to a width of 300 and constrain aspect ratio (auto height)
                    $img->resize(600, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    $filename = $request->kegiatan.'_'.date('dmyHis').'_'.str_slug($manifest->NOHBL).'_'.$i.'.'.$extension;

                    $picture[] = $filename;
    //                $file->move($destinationPath, $filename);
                    $img->save($destinationPath.'/'.$filename);
                    $i++;
                }
            }
            
            // update to Database
            switch ($request->kegiatan) {
                case "stripping":
                    $oldJson = json_decode($manifest->photo_stripping);
                    $newJson = array_collapse([$oldJson,$picture]);
                    if(isset($request->hapus)){ $newJson = $picture; }
                    $manifest->photo_stripping = json_encode($newJson);
                    break;
                case "behandle":
                    $oldJson = json_decode($manifest->photo_behandle);
                    $newJson = array_collapse([$oldJson,$picture]);
                    if(isset($request->hapus)){ $newJson = $picture; }
                    $manifest->photo_behandle = json_encode($newJson);
                    break;
                case "release":
                    $oldJson = json_decode($manifest->photo_release);
                    $newJson = array_collapse([$oldJson,$picture]);
                    if(isset($request->hapus)){ $newJson = $picture; }
                    $manifest->photo_release = json_encode($newJson);
                    break;

            }
            
            if($manifest->save()){
                return back()->with('success', 'Photo '.ucfirst($request->kegiatan).' for HBL '. $manifest->NOHBL .' has been uploaded.');
            }else{
                return back()->with('error', 'Photo uploaded, but not save in Database.');
            }
            
        } else {
            return back()->with('error', 'Something wrong!!! Can\'t upload photo, please try again.');
        }
    }
    
}