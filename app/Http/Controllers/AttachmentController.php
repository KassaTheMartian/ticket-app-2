<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function destroy(Attachment $attachment)
    {
        Storage::delete($attachment->path); // Xóa file khỏi storage
        $attachment->delete(); // Xóa record trong database
        return back()->with('success', 'Attachment deleted successfully.');
    }
    public function show($id)
    {
        $attachment = Attachment::findOrFail($id);

        // Kiểm tra file tồn tại
        if (!Storage::exists($attachment->path)) {
            abort(404, 'File not found.');
        }

        // Trả file về để hiển thị hoặc tải xuống
        return Storage::response($attachment->path);
        //return Storage::download($attachment->path, $attachment->filename); //để dowlload file
    }
    public function upload(Request $request)
    {
        // Xác thực file upload
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,doc,docx,xls,xlsx|max:2048', // Giới hạn loại file và kích thước
        ]);

        $file = $request->file('file');

        // Lưu file vào thư mục "public/attachments"
        $path = $file->store('attachments', 'public');

        // Lưu thông tin file vào database
        $attachment = Attachment::create([
            'path' => $path,
            'filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(), // Lưu loại MIME của file
        ]);

        // Trả về URL của file với cấu trúc JSON hợp lệ
        return response()->json([
            'url' => asset('storage/' . $path) // URL hợp lệ cho TinyMCE
        ]);
    }
    public function getAttachment($id)
    {
        $attachment = Attachment::findOrFail($id);

        // Kiểm tra file tồn tại
        if (!Storage::exists($attachment->path)) {
            abort(404, 'File not found.');
        }

        // Trả về thông tin file dưới dạng JSON
        return response()->json([
            'id' => $attachment->id,
            'filename' => $attachment->filename,
            'mime_type' => $attachment->mime_type,
            'url' => asset('storage/' . $attachment->path),
        ]);
    }
}
