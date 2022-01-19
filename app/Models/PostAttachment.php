<?php

namespace App\Models;

use App\Utils\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PostAttachment extends Model
{
    use HasFactory;

    protected $table = 'posts_attachments';

    protected $fillable = [
        'path',
        'post_id',
        'is_external_link'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function uploadAttachments($file, int $post_id)
    {
        $hash_file = Str::random($post_id);
        $path = File::saveAs(
            "public/posts/{$post_id}",
            $file,
            "attachment_{$hash_file}"
        );

        if($path != null)
        {
            $this->path = $path;

            $this->post_id  = $post_id;

            $this->save();
        }
    }

    public function getPathAttribute($value)
    {
        return is_null($value) ? $value : File::formatLink($value);
    }
}
