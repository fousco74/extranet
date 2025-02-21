<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class OneDriveLink extends Model
{
    use HasFactory;

    protected $fillable = [ 'link', 'name'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'one_drive_link_user');
    }
}
