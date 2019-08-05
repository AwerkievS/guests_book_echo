<?php

namespace App\Services;
use App\Repository\Record;
use Maatwebsite\Excel\Concerns\FromCollection;

class RecordsExport implements FromCollection
{
    private $record;

    /**
     * RecordsExport constructor.
     * @param Record $record
     */
    public function __construct(Record $record)
    {
        $this->record = $record;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->record
            ->with('user')
            ->whereBetween('created_at', [$this->dateBegin, $this->dateEnd])->get();
    }

    private $dateBegin;
    private $dateEnd;

    /**
     * @param mixed $dateEnd
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;
    }

    /**
     * @param mixed $dateBegin
     */
    public function setDateBegin($dateBegin)
    {
        $this->dateBegin = $dateBegin;
    }



}