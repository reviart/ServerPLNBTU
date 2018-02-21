<?php

namespace App\Http\Controllers;

use DB;
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

      $path = "public/".$getBidangName."/".$getFolderName;
      if ($request->hasFile('file')) {
        foreach ($request->file as $file) {
          $fileName = $file->getClientOriginalName();
          $fileExt  = $file->getClientOriginalExtension();
          $fileSize = $file->getClientSize();

          //if file is not null @ db then it cannot be store
          $fixPath = $path."/".$fileName;
          $fixPath = File::where('path', $fixPath)->first();
          if ($fixPath != NULL) {
            return redirect()->route('file.index')->with('warning', 'Gagal upload file, terdapat file yang sama!');
          } else {
            //store to file
            $file->storeAs($path, $fileName);

            //store to db
            $fileObject = new File;
            $fileObject->name = $fileName;
            $fileObject->ext = $fileExt;
            $fileObject->size = $fileSize;
            $fileObject->path = $path."/".$fileName;
            $fileObject->access_permission = 777;
            $fileObject->status = "not_edited";
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
      //$datas = File::find($id);
      return view('admin.file.edit', compact('datas'));
    }

    public function update(Request $request, $id){
      $datas = File::find($id);
      $tmpOldName = $datas->name;
      $tmpOldStatus = $datas->status;
      $tmpOldExt = $datas->ext;

      $tmpNewName = $request->get('name');
      $tmpNewStatus = $request->get('status');
      $getAssistant = Auth::user()->id;
      $getKodemk = $request->get('kode_mk');

      switch ($getKodemk) {
        case 'C31040315':
          $saveTo = "jarkom";
          break;
        case 'C31040311':
          $saveTo = "sbd";
          break;
        case 'C31040203':
          $saveTo = "pv";
          break;
        case 'C31040206':
          $saveTo = "pbo";
          break;
        case 'C31040216':
          $saveTo = "pc";
          break;
        case 'C31040309':
          $saveTo = "tekan";
          break;
        case 'C31040306':
          $saveTo = "simpel";
          break;
        case 'C31041403':
          $saveTo = "rpw";
          break;
        default:
          $saveTo = "";
          break;
      }

      if ($tmpOldStatus == "edited") {
        Storage::move(
          "public/".$saveTo."/".$tmpOldName.".".$tmpOldExt,
          "public/".$saveTo."/".$tmpNewName.".".$tmpOldExt
        );
        echo "File name changed from ".$tmpOldName.".".$tmpOldExt." to ".$tmpNewName.".".$tmpOldExt;
      }
      else {
        Storage::move(
          "public/".$saveTo."/".$tmpOldName,
          "public/".$saveTo."/".$tmpNewName.".".$tmpOldExt
        );
        echo "File name changed from ".$tmpOldName." to ".$tmpNewName.".".$tmpOldExt;
      }

      $datas->name = $tmpNewName;
      $datas->status = $tmpNewStatus;
      $datas->user_nim = $getAssistant;
      $datas->kode_mk = $getKodemk;
      $datas->save();
      return $this->red;
    }

    public function destroy($id)
    {
      $files = File::findOrFail($id);
      $tmpStatus = $files->status;
      $path = $files->path;

      if ($tmpStatus == "edited") {
        //Storage::delete("public/".$dir."/".$tmpName.".".$tmpExt);
        echo "gagal";
      }
      else{
        Storage::delete($path);
      }
      $files->delete();
      return redirect()->route('file.index')->with('success', 'File berhasil dihapus!');
    }

    public function download($id){
      $datas = File::find($id);
      $tmpKodemk = $datas->kode_mk;

      switch ($tmpKodemk) {
        case 'C31040315':
          $dir = "jarkom";
          break;
        case 'C31040311':
          $dir = "sbd";
          break;
        case 'C31040203':
          $dir = "pv";
          break;
        case 'C31040206':
          $dir = "pbo";
          break;
        case 'C31040216':
          $dir = "pc";
          break;
        case 'C31040309':
          $dir = "tekan";
          break;
        case 'C31040306':
          $dir = "simpel";
          break;
        case 'C31041403':
          $dir = "rpw";
          break;
        default:
          $dir = "";
          break;
      }

      if ($datas->status == "edited") {
        $msg = response()->download(storage_path("app/public/".$dir."/".$datas->name.".".$datas->ext));
      }
      else {
        $msg = response()->download(storage_path("app/public/".$dir."/".$datas->name));
      }
      return $msg;
    }

}
