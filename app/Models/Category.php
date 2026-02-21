<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'categories';
    
    protected $fillable = [
        'kode',
        'nama',
    ];

    /**
     * Relasi Many-to-Many dengan MasterItem
     */
    public function masterItems()
    {
        return $this->belongsToMany(MasterItem::class, 'category_master_item', 'category_id', 'master_item_id')
            ->withTimestamps();
    }
}
