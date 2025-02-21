<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class FileController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // Middleware pour les actions spécifiques
            new Middleware('permission:ajouter un fichier', only: ['create']),
            new Middleware('permission:liste des fichiers', only: ['index']),
            new Middleware('permission:modifier fichier', only: ['edit']),
            new Middleware('permission:supprimer fichier', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        
        $files = File::when($request->search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })->with('folder')
          ->paginate(10);
        return inertia('Files/index', ['files' => $files]);
    }

    public function create()
    {
        $folders = Folder::all();
        return inertia('Files/create', ['folders' => $folders]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'file_link' => 'required|file',
            'folder_id' => 'required|exists:folders,id',
            'name' => 'nullable|string',
            'size' => 'nullable|string',
            'extention' => 'nullable|string'
        ]);

    

        if($request->hasFile('file_link')){
            $validated['name'] = $validated['file_link']->getClientOriginalName();
            $validated['size'] = $validated['file_link']->getSize();
            $validated['extention'] = $validated['file_link']->getClientOriginalExtension();
            $validated['file_link'] = Storage::disk('public')->put("files",$request->file_link);
        }


        File::create($validated);

        return redirect()->route('files.index')->with('message', 'File created successfully.');
    }

    public function show(File $file)
    {
        return inertia('Files/show', ['file' => $file->load('folder')]);
    }

    public function edit(File $file)
    {
        $folders = Folder::all();
        return inertia('Files/edit', ['file' => $file, 'folders' => $folders]);
    }

    public function update(Request $request, File $file)
    {

        $validated = $request->validate([
            'file_link' => 'nullable|file',
            'folder_id' => 'required|exists:folders,id',
            'name' => 'nullable|string',
            'size' => 'nullable|string',
            'extention' => 'nullable|string'
        ]);

       
    
        if($request->hasFile('file_link')){
            if($file->file_link){
                Storage::delete($file->file_link);
            }
            $validated['name'] = $validated['file_link']->getClientOriginalName();
            $validated['size'] = $validated['file_link']->getSize();
            $validated['extention'] = $validated['file_link']->getClientOriginalExtension();
            $validated['file_link'] = Storage::disk('public')->put("files",$request->file_link);

        }

        $file->update($validated);

        return redirect()->route('files.index')->with('message', 'File updated successfully.');
    }

    public function destroy(File $file)
    {
        $file->delete();
        return redirect()->route('files.index')->with('message', 'File deleted successfully.');
    }
}
