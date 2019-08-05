<?php

namespace App\Http\Controllers;

use App\Repository\Record;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index(Record $record)
    {
        return view('index', ['record' => $record->find(1), 'records' => $record->getAll()]);
    }
}
