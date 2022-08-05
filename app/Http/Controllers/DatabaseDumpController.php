<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\DbDumper\Databases\MySql;

class DatabaseDumpController extends Controller
{
    public function dump()
    {
        MySql::create()
                ->setDbName(config('database.connections.mysql.database'))
                ->setUserName(config('database.connections.mysql.username'))
                ->setPassword(config('database.connections.mysql.password'))
                ->dumpToFile(storage_path('app/db-dump.sql'));

        if(file_exists(storage_path('app/db-dump.sql')))
        {
            return response()->download(storage_path('app/db-dump.sql'))->deleteFileAfterSend();
        }

        return response()->json(['error'=>'Something went wrong'],500);
    }
}
