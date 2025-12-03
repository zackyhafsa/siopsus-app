<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operasi extends Model
{
    protected $fillable = [
        'tanggal_operasi',
        'nama_penelusur',
        'nomor_polisi',
        'jenis_kendaraan',
        'jatuh_tempo_pajak',
        'pokok_pkb',
        'denda_pkb',
        'opsen_pkb',
        'denda_opsen_pkb',
        'pokok_swdkllj',
        'denda_swdkllj',
        'foto_kendaraan',
        'user_id',
        'lokasi',
        'latitude',
        'longitude',
        "status_pembayaran"
    ];

    protected $casts = [
        'tanggal_operasi' => 'datetime',
        'jatuh_tempo_pajak' => 'date',
        'pokok_pkb' => 'integer',
        'denda_pkb' => 'integer',
        'opsen_pkb' => 'integer',
        'denda_opsen_pkb' => 'integer',
        'pokok_swdkllj' => 'integer',
        'denda_swdkllj' => 'integer',
        'latitude' => 'float',
        'longitude' => 'float',
        'status_pembayaran' => "string",
        'user_id' => 'integer',
    ];

    public function getTotalTagihanAttribute(): int
    {
        return array_sum([
            (int) ($this->pokok_pkb ?? 0),
            (int) ($this->denda_pkb ?? 0),
            (int) ($this->opsen_pkb ?? 0),
            (int) ($this->denda_opsen_pkb ?? 0),
            (int) ($this->pokok_swdkllj ?? 0),
            (int) ($this->denda_swdkllj ?? 0),
        ]);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
