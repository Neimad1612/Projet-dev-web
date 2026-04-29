<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['title', 'slug', 'excerpt', 'content', 'cover_image', 'category', 'tags', 'is_published', 'published_at', 'author_id'];
    protected function casts(): array { return ['tags' => 'array', 'is_published' => 'boolean', 'published_at' => 'datetime']; }
    public function author(): BelongsTo { return $this->belongsTo(User::class, 'author_id'); }
    public function scopePublished($query) { return $query->where('is_published', true)->whereNotNull('published_at')->where('published_at', '<=', now()); }
    public function scopeByCategory($query, string $category) { return $query->where('category', $category); }
    public function scopeSearch($query, string $term) { return $query->where('title', 'like', "%{$term}%")->orWhere('excerpt', 'like', "%{$term}%"); }
}