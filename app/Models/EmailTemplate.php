<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'subject',
        'body',
        'type',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->is_default) {
                // If setting this to default, unset others of same type for this company
                static::where('company_id', $model->company_id)
                    ->where('type', $model->type)
                    ->where('id', '!=', $model->id) // In case of update
                    ->update(['is_default' => false]);
            }
        });
    }

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public static function seedDefaults(Company $company): void
    {
        // Invoice Template
        static::create([
            'company_id' => $company->id,
            'name' => 'Default Invoice',
            'subject' => 'Invoice #{{ number }}',
            'body' => "Dear {{ client }},\n\nPlease find attached invoice #{{ number }} for {{ amount }}, due on {{ due_date }}.\n\nThank you for your business.\n\nBest regards,",
            'type' => 'invoice',
            'is_default' => true,
        ]);

        // Quote Template (Optional, but good to have)
        static::create([
            'company_id' => $company->id,
            'name' => 'Default Quote',
            'subject' => 'Quote #{{ number }}',
            'body' => "Dear {{ client }},\n\nPlease find attached quote #{{ number }}.\n\nWe look forward to working with you.\n\nBest regards,",
            'type' => 'quote',
            'is_default' => true,
        ]);
    }
}
