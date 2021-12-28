<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = [
        'name',
        'body',
        'deadline',
        'points',
        'coins',
        'xp',
        'disabled',
        'classroom_id',
        'post_type_id'
    ];


    public static function createAndAssignToClassroom(array $posts, int $classroomId)
    {
        if ($posts) {
            foreach ($posts as $post) {
                $post = array_merge($post, ['classroom_id' => $classroomId]);
                $match = ['name' => $post['name'], 'classroom_id' => $classroomId];
                self::updateOrCreate($match, $post);
            }
        }
    }


    public function classroom()
    {
        return $this->belongsTo(Post::class);
    }
}
