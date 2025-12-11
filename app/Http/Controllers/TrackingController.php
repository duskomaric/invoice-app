<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function pixel($id)
    {
        $log = \App\Models\InvoiceEmailLog::find($id);
        if ($log && ! $log->opened_at) {
            $log->update(['opened_at' => now()]);
        }

        // Return 1x1 transparent GIF
        return response(base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'))
            ->header('Content-Type', 'image/gif')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function click($id)
    {
        $log = \App\Models\InvoiceEmailLog::find($id);
        if ($log) {
            if (! $log->clicked_at) {
                $log->update(['clicked_at' => now()]);
            }
            
            // Redirect to download PDF
            return response()->streamDownload(function () use ($log) {
                echo (new \App\Services\InvoiceService())->generatePdf($log->invoice);
            }, (new \App\Services\InvoiceService())->getPdfFilename($log->invoice), [
                'Content-Type' => 'application/pdf',
            ]);
        }

        abort(404);
    }
}
