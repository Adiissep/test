<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterItem extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'master_items';
    protected $fillable = [
        // 'kode',
        'nama',
        'jenis',
        'harga_beli',
        'laba',
        'supplier',
        'foto'
    ];

    // Accessor untuk harga jual
    protected $appends = ['harga_jual'];

    public function getHargaJualAttribute()
    {
        return $this->harga_beli + ($this->harga_beli * $this->laba / 100);
    }

    /**
     * Relasi Many-to-Many dengan Category
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_master_item', 'master_item_id', 'category_id')
            ->withTimestamps();
    }
}
