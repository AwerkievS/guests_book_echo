<?php

namespace App\Mail;

use App\Repository\Record;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewRecordMail extends Mailable
{
    use Queueable, SerializesModels;
    private $recordId;

    /**
     * Create a new message instance.
     *
     * @param $recordId
     */
    public function __construct($recordId)
    {
        $this->recordId = $recordId;
    }

    /**
     * Build the message.
     *
     * @param Record $record
     * @return $this
     */
    public function build(Record $record)
    {
        $newRecord = $record->with('user')->findOrFail($this->recordId);
        return $this->view('mail.new_record_mail', ['record' => $newRecord]);
    }
}
