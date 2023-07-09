<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{    
    use HasFactory;
    
    protected $keyType = 'string';
    public $incrementing = false; 
    public $timestamps = false;

    protected $guarded = [];

    public function __construct()
    {
        parent::__construct();
        $this->table = Config::get('sessions.table', 'sessions')
    }

   
    /**
     * Get Unserialized Payload (base64 decoded too)
     *
     * @return array
     */
    public function getUnserializedPayloadAttribute() : array
    {
        return unserialize(base64_decode($this->payload));
    }
    
    public function setPayload(string $payload)
    {
        $this->payload = serialize(base64_encode($payload));
        $this->save();
    }

    /**
     * User Relationship
     *
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Last Activity Carbon instance
     *
     * @return Carbon
     */
    public function getLastActivityAtAttribute() : Carbon
    {
        return Carbon::createFromTimestamp($this->last_activity);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
