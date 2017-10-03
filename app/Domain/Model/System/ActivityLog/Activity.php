<?php

namespace App\Domain\Model\System\ActivityLog;

use Illuminate\Database\Eloquent\Model;

use App\Domain\Model\Authentication\User\User;

class Activity extends Model
{
    protected $table = 'activity_log';

    protected $fillable = [
        'user_uuid',
        'action',
        'document_type',
        'document_uuid',
        'changes',
        'json_backup'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}