<?php

namespace App\Http\Controllers;

use App\File;
use App\Folder;
use App\Bidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function index()
    {
      $files = File::with('folder', 'bidang')->orderBy('name')->get();
      $bidangs = Bidang::all();
      return view('admin.file.index', compact('files', 'bidangs'));
    }

    public function detail($id)
    {
      $files = File::with('folder', 'bidang', 'user')->where('id', $id)->get();
      return view('admin.file.detail', compact('files'));
    }

    public function find(Request $request)
    {
      $id = $request->get('bidang_id');
      if ($id > 0) {
        $files = File::with('folder', 'bidang')->where('bidang_id', $id)->orderBy('name')->get();
        $bidangs = Bidang::all();
        return view('admin.file.index', compact('files', 'bidangs'));
      } else {
        return redirect()->route('file.index')->with('warning', 'Gagal pencarian, anda belum memilih bidang!');
      }
    }

    public function create(){
      $folders = Folder::all();
      $bidangs = Bidang::all();
      return view('admin.file.create', compact('folders', 'bidangs'));
    }

    public function store(Request $request)
    {
      $folder_id     = $request->get('folder_id');
      $getFolderName = Folder::where('id', $folder_id)->first();
      $getFolderName = $getFolderName->name;

      $bidang_id     = $request->get('bidang_id');
      $getBidangName = Bidang::where('id', $bidang_id)->first();
      $getBidangName = $getBidangName->name;

      if ($request->hasFile('file')) {
        foreach ($request->file as $file) {
          $fileName = $file->getClientOriginalName();
          $fileExt  = $file->getClientOriginalExtension();
          $fileSize = $file->getClientSize();

          //if file is not null @ db then it cannot be store
          $fixPath = "public/".$getBidangName."/".$getFolderName;
          $totPath = "app/".$fixPath."/".$fileName;

          if (file_exists(storage_path($totPath))) {
            return redirect()->route('file.index')->with('warning', 'Gagal upload file, terdapat file yang sama!');
          }
          else {
            //store to file
            $file->storeAs($fixPath, $fileName);

            //store to db
            $fileObject = new File;
            $fileObject->name = $fileName;
            $fileObject->ext = $fileExt;
            $fileObject->size = $fileSize;
            $fileObject->user_id = Auth::user()->id;
            $fileObject->bidang_id = $bidang_id;
            $fileObject->folder_id = $folder_id;
            $fileObject->save();
          }
        }
        return redirect()->route('file.index')->with('success', 'File berhasil diupload!');
      }
      else {
        return redirect()->route('file.index')->with('warning', 'Gagal upload file!');
      }
    }

    public function show($id){
      $bidangs = Bidang::all();
      $folders = Folder::all();
      $files = File::with('folder', 'bidang')->where('id', $id)->first();
      return view('admin.file.edit', compact('files', 'folders', 'bidangs'));
    }

    public function update(Request $request, $id)
    {
      $files = File::with('bidang', 'folder')->where('id', $id)->first();

      $oldBidang = $files->bidang->name;
      $oldFolder = $files->folder->name;
      $oldName = $files->name;

      $oldFolderPath = "public/".$oldBidang."/".$oldFolder."/".$oldName;

      //get new name
      $name = $request->get('name');

      //get folder_id & folder name
      $folder_id     = $request->get('folder_id');
      $getFolderName = Folder::where('id', $folder_id)->first();
      $getFolderName = $getFolderName->name;

      //get bidang_id & bidang name
      $bidang_id     = $request->get('bidang_id');
      $getBidangName = Bidang::where('id', $bidang_id)->first();
      $getBidangName = $getBidangName->name;

      //if file is not null @ db then it cannot be store
      $fixPath = "public/".$getBidangName."/".$getFolderName."/".$name;

      if ($fileName != NULL && $bidang_id != NULL && $folder_id != NULL) {
        return redirect()->route('file.index')->with('warning', 'Gagal edit file, terdapat file yang sama!');
      } else {
        //rename or change dir file
        Storage::move($oldFolderPath, $fixPath);

        //store to db
        $files->update([
          'name' => $name,
          'user_id' => Auth::user()->id,
          'bidang_id' => $bidang_id,
          'folder_id' => $folder_id
        ]);
        return redirect()->route('file.index')->with('success', 'File berhasil diupload!');
      }
    }

    public function destroy($id)
    {
      $files = File::with('bidang', 'folder')->where('id', $id)->first();
      $oldBidang = $files->bidang->name;
      $oldFolder = $files->folder->name;
      $oldName = $files->name;
      $path = "public/".$oldBidang."/".$oldFolder."/".$oldName;

      if (Storage::delete($path)) {
        $files->delete();
        return redirect()->back()->with('success', 'File berhasil dihapus!');
      }else {
        return redirect()->back()->with('warning', 'File gagal dihapus!');
      }
    }

    public function download($id)
    {
      $files = File::with('bidang', 'folder')->where('id', $id)->first();
      $oldBidang = $files->bidang->name;
      $oldFolder = $files->folder->name;
      $oldName = $files->name;
      $path = "public/".$oldBidang."/".$oldFolder."/".$oldName;
      return response()->download(storage_path("/app/".$path));
    }

}
