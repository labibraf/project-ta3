<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'peserta_id',
        'role_id',
        'mentor_id',
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

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }

    public function isPeserta(): bool
    {
        if ($this->role) {
            return strtolower($this->role->role_name) === 'intern';
        }
        return false;
    }
    public function isAdmin(): bool
    {
        if ($this->role) {
            return strtolower($this->role->role_name) === 'admin';
        }
        return false;
    }

    public function mentor()
    {
        return $this->belongsTo(Mentor::class);
    }

    public function isMentor(): bool
    {
        if ($this->role) {
            return strtolower($this->role->role_name) === 'mentor';
        }
        return false;
    }

    /**
     * Sinkronisasi nama dan email dengan tabel terkait
     */
    public function syncProfileData()
    {
        // Sinkronisasi dengan tabel peserta
        if ($this->peserta) {
            $this->peserta->update([
                'nama_lengkap' => $this->name,
                'email' => $this->email,
            ]);
        }

        // Sinkronisasi dengan tabel mentor
        if ($this->mentor) {
            $this->mentor->update([
                'nama_mentor' => $this->name,
                'email' => $this->email,
            ]);
        }
    }

    /**
     * Get the actual name from related profile or user table
     */
    public function getActualNameAttribute()
    {
        if ($this->peserta && $this->peserta->nama_lengkap) {
            return $this->peserta->nama_lengkap;
        } elseif ($this->mentor && $this->mentor->nama_mentor) {
            return $this->mentor->nama_mentor;
        }
        return $this->name;
    }

    /**
     * Get status profile information
     */
    public function getProfileStatusAttribute()
    {
        if ($this->peserta) {
            return [
                'type' => 'peserta',
                'complete' => true,
                'name' => $this->peserta->nama_lengkap,
                'email' => $this->peserta->email,
            ];
        } elseif ($this->mentor) {
            return [
                'type' => 'mentor',
                'complete' => true,
                'name' => $this->mentor->nama_mentor,
                'email' => $this->mentor->email,
            ];
        }

        return [
            'type' => 'user',
            'complete' => false,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }

    /**
     * Get departemen/bagian name
     */
    public function getDepartemenNameAttribute()
    {
        if ($this->peserta && $this->peserta->bagian) {
            return $this->peserta->bagian->nama_bagian;
        } elseif ($this->mentor && $this->mentor->bagian) {
            return $this->mentor->bagian->nama_bagian;
        }
        return null;
    }

    /**
     * Get departemen info with role type
     */
    public function getDepartemenInfoAttribute()
    {
        $bagianName = $this->departemen_name;

        if ($this->peserta && $bagianName) {
            return [
                'bagian' => $bagianName,
                'type' => 'peserta',
                'icon' => 'fas fa-user-graduate',
                'color' => 'blue-800'
            ];
        } elseif ($this->mentor && $bagianName) {
            return [
                'bagian' => $bagianName,
                'type' => 'mentor',
                'icon' => 'fas fa-user-tie',
                'color' => 'red-700'
            ];
        }

        return [
            'bagian' => 'Belum Ada Departemen',
            'type' => 'user',
            'icon' => 'fas fa-user',
            'color' => 'secondary'
        ];
    }

}
