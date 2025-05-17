<?php

namespace Modules\CMS\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\CMS\App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::latest();
        
        // Lọc theo trạng thái nếu có và khác rỗng
        if ($request->has('status') && $request->status != '') {
            $query->where('is_published', $request->status);
        }
        
        $documents = $query->paginate(10);
        return view('cms::documents.index', compact('documents'));
    }

    public function create()
    {
        return view('cms::documents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240', // Max 10MB, allow PDF and Word files
            'is_published' => 'required|in:1,0', 
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents', $fileName, 'public');

        Document::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'is_published' => $request->is_published, // Thêm trường is_published
        ]);

        return redirect()->route('cms.documents.index')
            ->with('flash_message', 'Tài liệu đã được tải lên thành công.')
            ->with('flash_type', 'success');
    }

    public function show(Document $document)
    {
        return view('cms::documents.show', compact('document'));
    }

    public function edit(Document $document)
    {
        return view('cms::documents.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // Max 10MB, allow PDF and Word files
            'is_published' => 'required|in:1,0', 
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'is_published' => $request->is_published,
        ];

        if ($request->hasFile('file')) {
            // Delete old file
            Storage::disk('public')->delete($document->file_path);

            // Store new file
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $fileName, 'public');

            $data['file_path'] = $filePath;
            $data['file_name'] = $file->getClientOriginalName();
            $data['mime_type'] = $file->getMimeType();
            $data['file_size'] = $file->getSize();
        }

        $document->update($data);

        return redirect()->route('cms.documents.index')
            ->with('flash_message', 'Tài liệu đã được cập nhật thành công.')
            ->with('flash_type', 'success');
    }

    public function destroy(Document $document)
    {
        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return redirect()->route('cms.documents.index')
            ->with('flash_message', 'Tài liệu đã được xóa thành công.')
            ->with('flash_type', 'success');
    }

    public function download(Document $document)
    {
        $path = storage_path('app/public/' . $document->file_path);
        
        if (!file_exists($path)) {
            abort(404);
        }

        return Response::download($path, $document->file_name);
    }
}