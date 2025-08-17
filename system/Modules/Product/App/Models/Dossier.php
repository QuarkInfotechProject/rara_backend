<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dossier extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ["product_id", "content", "pdf_path"];

    public function products() {
        return $this->belongsTo(Product::class);
    }
}
