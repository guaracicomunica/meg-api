<?php

namespace App\Models;

use App\Utils\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasFactory, Notifiable, softDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'pivot',
        'created_at',
        'updated_at',
        'deleted_at',
        'email_verified_at',
        'role_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function isTeacher()
    {
        return $this->role_id == 2;
    }

    public function isMemberOfClassroom($classroomId)
    {
        return DB::table('users_classrooms')
            ->where('user_id', $this->id)
            ->where('classroom_id', $classroomId)
            ->exists();
    }

    public function isTeacherOfClassroom($classroomId)
    {
        return $this->isTeacher() && $this->isMemberOfClassroom($classroomId);
    }

    public function isStudentOfClassroom($classroomId)
    {
        return $this->isStudent() && $this->isMemberOfClassroom($classroomId);
    }

    public function isStudent()
    {
        return $this->role_id == 3;
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function activities()
    {
        return $this->hasMany(UserActivity::class, 'user_id');
    }

    public function classes()
    {
        return $this->belongsToMany(Classroom::class, 'users_classrooms', 'user_id', 'classroom_id');
    }

    public function gamefication()
    {
        return $this->hasOne(UserStatusGamefication::class, 'user_id');
    }

    public function reportCards()
    {
        return $this->hasMany(ReportCard::class, 'user_id');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }

    public function getAvatarPathAttribute($value)
    {
        return is_null($value) ? $value : File::formatLink($value);
    }

    public function uploadAvatar($file)
    {
        $hash_file = Str::random($this->id);
        $path = File::saveAs(
            "public/users/{$this->id}",
            $file,
            "{$hash_file}"
        );

        if($path != null)
        {
            $this->avatar_path  = $path;

            $this->save();
        }
    }
}
