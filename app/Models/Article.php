<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';
    protected $fillable = ['title','content'];
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable')
        ->withoutGlobalScopes();   
    }
    
    public static function boot()
    {
        parent::boot();
        
        static::deleting(function ($article) {
            // Tùy chọn: Xóa attachments nếu muốn
            $article->attachments()->delete();
        });
    }
}
