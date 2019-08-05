<?php

namespace App\Http\Middleware;

use App\Repository\Record;
use App\Repository\Role;
use Closure;
use Illuminate\Support\Facades\Auth;

class EditRecordGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'false',
                'message' => 'un authorise'
            ], 401);
        }
        $user = Auth::user();
        if ($user->role_id == Role::ADMIN_ROLE_ID) {
            return $next($request);
        }
        $recordId = $request->route('id');
        $recordModel = resolve(Record::class);
        $record = $recordModel->findOrFail($recordId);

        if ($user->id == $record->user_id && $record->CheckAvailableChanges()) {
            return $next($request);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'bad request'
            ], 400);
        }
    }
}
