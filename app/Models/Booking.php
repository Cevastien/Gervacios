<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    // NO SoftDeletes — RA 10173 requires hard delete within 24 hours

    protected $fillable = [
        'booking_ref',
        'source',
        'device_type',
        'table_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'party_size',
        'priority_type',
        'status',
        'booked_at',
        'completed_at',
        'checked_in_at',
        'no_show_at',
        'reminder_24h_sent_at',
        'reminder_2h_sent_at',
        'late_checkin_sms_sent_at',
        'paymongo_link_id',
        'paymongo_payment_id',
        'payment_status',
        'payment_method',
        'paid_at',
        'payment_verified_by',
        'deposit_amount',
        'transaction_number',
        'account_number',
        'city_of_residence',
        'policy_acknowledged',
        'special_requests',
        'marketing_opt_in',
        'auto_assigned_at',
    ];

    protected $casts = [
        'booked_at' => 'datetime',
        'completed_at' => 'datetime',
        'checked_in_at' => 'datetime',
        'no_show_at' => 'datetime',
        'reminder_24h_sent_at' => 'datetime',
        'reminder_2h_sent_at' => 'datetime',
        'late_checkin_sms_sent_at' => 'datetime',
        'paid_at' => 'datetime',
        'auto_assigned_at' => 'datetime',
        'deposit_amount' => 'integer',
        'policy_acknowledged' => 'boolean',
        'marketing_opt_in' => 'boolean',
    ];

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }
}
