<?php

namespace App\Http\Controllers;

use App\Actions\GenerateDownloadUrlAction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    /**
     * Endpoint: GET /api/products/{id}/download
     * Returns: JSON with the temporary download link.
     */
    public function getLink(Request $request, $id, GenerateDownloadUrlAction $action)
    {
        $product = Product::findOrFail($id);
        
        $url = $action->execute($request->user(), $product);

        return response()->json([
            'download_url' => $url
        ]);
    }

    /**
     * Endpoint: The Signed URL points here.
     * This actually delivers the file content.
     */
    public function serve(Request $request)
    {
        // Laravel automatically validates the signature before reaching here
        // because we will use the 'signed' middleware.
        
        $path = $request->query('path');

        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('local')->download($path);
    }
}