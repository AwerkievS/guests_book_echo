<?php

namespace App\Http\Controllers;

use App\Repository\Record;
use App\Services\RecordsExport;
use App\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class RecordController extends Controller
{

    private $record;

    /**
     * RecordController constructor.
     * @param Record $record
     */
    public function __construct(Record $record)
    {
        $this->record = $record;
    }

    public function delete($id)
    {
        $record = $this->record->findOrFail($id);
        $record->delete();
        return response()->json([
            'status' => true,
        ]);

    }

    const RECORD_VALIDATION = [
        'content' => 'required'
    ];

    public function new(Request $request, User $user)
    {
        $this->validate($request, self::RECORD_VALIDATION);

        $attributes = $request->all();
        $attributes['user_id'] = $user->getActiveUserId();
        $this->record->store($attributes);

        return response()->json(['status' => true]);

    }

    public function update($id, Request $request)
    {
        $this->validate($request, self::RECORD_VALIDATION);
        $record = $this->record->findOrFail($id);
        $record->store($request->all());

        return response()->json(['status' => true, 'record' => $record]);

    }

    public function csv(Request $request, RecordsExport $export)
    {
        $dates = $request->all();
        $export->setDateBegin(isset($dates['begin']) ? $dates['begin'] : '');
        $export->setDateEnd(isset($dates['end']) ? $dates['end'] : '');
        return Excel::download($export, 'records.xlsx');
    }


}
