<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    protected $fillable = [
        'contract_type', 'title', 'description',
        'effective_date', 'expiration_date', 'contract_date',
        'assigned_to', 'internship_supervisor',
        'company_name', 'company_address', 'company_rcs', 'legal_representative',
        'contract_duration', 'salary', 'work_location',
        'hr_representative', 'hr_position', 'hr_contact',
        'signe', 'signature', 'signature_path', 'signature_mime', 'signature_date',
        'signe_rh', 'signature_rh', 'signature_rh_path', 'signature_rh_mime', 'signature_rh_date',
        'archived',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'expiration_date' => 'date',
        'contract_date' => 'date',
        'signature_date' => 'datetime',
        'signature_rh_date' => 'datetime',
        'signe' => 'boolean',
        'signe_rh' => 'boolean',
        'archived' => 'boolean',
    ];

    // Pour que Vue puisse lire contract.assigned_user.first_name
    // (Laravel convertit assignedUser -> assigned_user en JSON par défaut)
    protected $with = ['assignedUser'];

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function internshipSupervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'internship_supervisor');
    }

    public function articles(): HasMany
    {
        return $this->hasMany(ContractArticle::class)->orderBy('position');
    }
}
