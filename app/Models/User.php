<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\CacheInvalidation;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use HasApiTokens;
    use CacheInvalidation;

    public const string CACHE_KEY = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $appends = [
        'mainRole',
        'registeredDiff'
    ];

    protected $with = [
        'roles',
        'books',
        'phone',
        'photo',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getMainRoleAttribute(): ?int
    {
        return $this->roles()->min('id');
    }

    public function getRegisteredDiffAttribute(): ?string
    {
        $diff = Carbon::now()->diff($this->created_at);

        return match (true) {
            $diff->y > 0 && $diff->y < 5 => $diff->y . ' г назад',
            $diff->y >= 5 => $diff->y . ' л назад',
            $diff->m > 0 => $diff->m . ' мес назад',
            $diff->d > 0 => $diff->d . ' д назад',
            $diff->h > 0 => $diff->h . ' ч назад',
            default => 'менее часа назад'
        };
    }

    public function isAdmin(): bool
    {
        return $this->mainRole == Role::ADMIN_ROLE_ID;
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public function phone(): HasOne
    {
        return $this->hasOne(Phone::class);
    }

    public function photo(): BelongsTo
    {
        return $this->belongsTo(Photo::class);
    }

    public function settings(): BelongsToMany
    {
        return $this->belongsToMany(Setting::class)->withPivot('value')->withTimestamps();
    }

    public function chats(): HasMany
    {
        $chatsAsFirst = $this->hasMany(Chat::class, 'first_user_id');
        $chatsAsSecond = $this->hasMany(Chat::class, 'second_user_id');
        return $chatsAsFirst->union($chatsAsSecond);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class)->where('seen', false);
    }

    public function unreadMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'to_id')->where('seen', false);
    }
}
