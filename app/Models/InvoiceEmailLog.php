<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice; // Added for the relationship

class InvoiceEmailLog extends Model
{
    protected $fillable = ['invoice_id', 'opened_at', 'clicked_at'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    //
}
