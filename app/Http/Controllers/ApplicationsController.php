<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\LicenceUser;
use App\Models\Server;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class ApplicationsController extends Controller
{
    public function addApplication(Request $request){

        $data = $request->validate([
            'username' => 'required',
            'server_mac_address' => 'required',
            'application_name' => 'required',
            'application_version' => 'required'
        ]);


        $licence_user = LicenceUser::updateOrCreate(['username' => $data['username']],[
            'username' => $data['username'],
            'updated_at' => Carbon::now(),
        ]);

        $server = $licence_user->servers()->where([
            'server_mac_address' => $data['server_mac_address']
        ])->first();

        $active = !$licence_user->lock_new_devices;

        $server = $licence_user->servers()->updateOrCreate(['server_mac_address' => $data['server_mac_address']],[
            'server_mac_address' => $data['server_mac_address'],
            'server_ip' => $request->ip(),
            'updated_at' => Carbon::now(),
            'active' => $server ? $server->active : $active
        ]);

        $server->save();

        $application = Application::updateOrCreate([
            'application_name' => $data['application_name'],
            'application_version' => $data['application_version']
        ], [
            'application_name' => $data['application_name'],
            'application_version' => $data['application_version'],
            'updated_at' => Carbon::now()
        ]);

        $saved_application = $server->applications()->find($application->id);

        $server->applications()->sync([
            $application->id => [
                'licence_date' => $request->post('licence_date', Carbon::today()),
                'licence_user_id' => $server->user->id,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'active' => $saved_application ? $saved_application->pivot->active : $active
            ]
        ], false);

        $application = $server->applications()->find($application->id);

        return response()->json([
            'message' => $application->pivot->message,
            'show_message' => true
        ]);
    }

    public function checkApplicationLicence(Request $request){
        $request->validate([
            'username' => 'required|exists:licence_users,username',
            'application_name' => 'required|exists:applications,application_name',
            'application_version' => 'required|exists:applications,application_version',
            'server_mac_address' => 'required|string'
        ]);

        $licence_user = LicenceUser::where('username', $request->post('username'))->first();
        if (!$licence_user || !$licence_user->active)
            return response()->json(['message' => 404], 404);

        $server_mac_address =  $request->post('server_mac_address');
        if (!strpos($server_mac_address, ':')){
            $server_mac_address = dechex($server_mac_address);
            $server_mac_address = str_pad($server_mac_address, 12, '0', STR_PAD_LEFT);
            $server_mac_address = implode(':', str_split($server_mac_address, 2));
        }
        $server = $licence_user->servers()->where('server_mac_address', $server_mac_address)->first();

    	if (!$server || !$server->active)
            return response()->json(['message' => 404], 404);

        $application = Application::where('application_name', $request->post('application_name'))
            ->where('application_version', $request->post('application_version'))
            ->first();

    	if (!$application)
            return response()->json(['message' => 404], 404);
        $application = $server->applications()->find($application->id);

        if (!$application || !$application->pivot->active)
            return response()->json(['message' => 404], 404);

        $application_end_date   = Carbon::createFromFormat('Y-m-d H:i:s', $application->pivot->end_date);
        if ($application_end_date->diffInDays(Carbon::today()) <= 0) {
            $server->applications()->sync([
                $application->id => [
                    'active' => false
                ]
            ], false);
            return response()->json(['message' => 404], 404);
        }

        return response()->json(['message' => 200]);
    }
}
