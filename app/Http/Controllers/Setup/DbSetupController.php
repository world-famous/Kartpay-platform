<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateDbCredentialsRequest;
use Artisan;
use DB;

class DbSetupController extends Controller
{
    public function updateDbCredentials(UpdateDbCredentialsRequest $request)
    {

        Artisan::call('config:cache');
        sleep(3);

        setEnv('DB_HOST', $request->host);
        setEnv('DB_PORT', $request->port);
        setEnv('DB_DATABASE', encrypt($request->database));
        setEnv('DB_USERNAME', encrypt($request->username));
        setEnv('DB_PASSWORD', encrypt($request->password));

        try {
            DB::select(DB::raw('SELECT 1'));
        } catch (\Exception $e) {
            return back();
        }

        setEnv('hasToSetupDb', 0);

        Artisan::call('migrate', [
            '--seed' => true,
        ]);
        Artisan::call('config:cache');

        sleep(5);
        return redirect()->route('home');
    }
}
