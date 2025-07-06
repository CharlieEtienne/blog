<?php

namespace App\Models;

use Filament\Panel;
use Filament\Facades\Filament;
use App\Support\AuthorizedDomains;
use Illuminate\Support\Facades\Storage;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int id
 * @property string name
 * @property string email
 * @property string avatar_url
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\Post> posts
 */
class User extends Authenticatable implements FilamentUser, HasAvatar
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin();
    }

    public function isAdmin(): bool
    {
        $emailDomain = explode('@', $this->email)[1] ?? false;

        if (!$emailDomain) {
            return false;
        }

        return in_array($emailDomain, AuthorizedDomains::get()) && $this->hasVerifiedEmail();
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getFilamentAvatarUrl(): ?string
    {
        if($this->avatar_url && Storage::disk('public')->exists($this->avatar_url)){
            return Storage::disk('public')->url($this->avatar_url);
        }
        return app(Filament::getDefaultAvatarProvider())->get($this);
    }

}
