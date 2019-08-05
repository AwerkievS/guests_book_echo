<?php

namespace App\Repository;

use App\Events\NewRecord;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Record extends Model
{

    protected $table = 'records';
    protected $fillable = ['content', 'user_id'];
    protected $dates = ['created_at'];
    public $timestamps = true;


    /**
     * @param $attributes
     */
    public function store(Array $attributes)
    {

        $this->fill($attributes);
        $this->save();
        $this->user;
        broadcast(new NewRecord($this));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        return $this->with('user')->orderBy('created_at', 'desc')->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    const AVAILABLE_UPDATE_HOURS_RECORD = 2;


    /**
     * @return bool
     */
    public function CheckAvailableChanges()
    {
        $now = Carbon::now();

        $createdDate = $this->created_at;
        return $now->diffInHours($createdDate) < self::AVAILABLE_UPDATE_HOURS_RECORD ? true : false;

    }

    /**
     * @return bool
     */
    public function checkEnableButtons()
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();

        if ($user->role_id == Role::ADMIN_ROLE_ID) {
            return true;
        }

        return $user->id == $this->user_id && $this->CheckAvailableChanges() ? true : false;

    }


}
