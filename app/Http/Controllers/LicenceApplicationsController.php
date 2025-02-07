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

class LicenceApplicationsController extends Controller
{
    public function create(LicenceUser $licence_user, Server $server)
    {
        $server = $licence_user->servers()->findOrFail($server->id);
        $applications = Application::all();
        foreach ($applications as $application) {
            if (Carbon::parse($application->pivot->end_date) < Carbon::today()) {
                $application->pivot->update(['active' => false]);
                $application->pivot->save();
                $application->refresh();
            }
        }
        return view('Applications.store', ['user' => $licence_user, 'server' => $server, 'applications' => $applications]);
    }

    public function store(Request $request, LicenceUser $licence_user, Server $server)
    {
        $server = $licence_user->servers()->findOrFail($server->id);

        if (!$server)
            return redirect(route('licence-users.servers.show', ['licence_user' => $licence_user->id]))
                ->with('message', ['message' => "Server doesn't belong to the user", "type" => "danger"]);

        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'licence_date' => 'required|date'
        ]);

        $data = $request->only(['licence_date']);
        $data['active'] = false;


        $server->applications()->sync([
            $request->post('application_id') => $data
        ], false);

        return redirect(route('licence-users.servers.show', ['licence_user' => $licence_user, 'server' => $server]))
            ->with('message', ['message' => 'Application has been added successfully',
                'title' => 'Application added Successfully', 'type' => 'success']);
    }

    public function edit(LicenceUser $licence_user, Server $server, Application $application){
        $server = $licence_user->servers()->findOrFail($server->id);
        $application = $server->applications()->findOrFail($application->id);

        return view('Applications.edit', [
            'user' => $licence_user,
            'server' => $server,
            'application' => $application,
            'packages' => Application::Packages()
        ]);
    }

    public function update(Request $request, LicenceUser $licence_user, Server $server, Application $application){
        $application = $licence_user->servers()->findOrFail($server->id)?->applications()->findOrFail($application->id);
        if (!$application)
            return redirect(route('licence-users.servers.show', ['licence_user' => $licence_user]))
                ->with('message', ['message' => "Application doesn't belong to the user", "type" => "danger"]);

        $data = $request->validate([
            'start_date' => 'required|date',
            'package_id' => 'required|in:0,1,3,6,9,12,24',
            'message' => 'required|string|max:255',
        ]);

        $data['end_date'] = Carbon::parse($data['start_date'])->addMonths(request('package_id'));
        if ($data['package_id'] == 0)
            $data['end_date'] = Carbon::parse($data['start_date'])->addDay();
        $data['show_message'] = (bool)request('show_message');
        $application->pivot->update($data);
        $application->pivot->save();

        return redirect(route('licence-users.servers.show', ['licence_user' => $licence_user, 'server' => $server]))
            ->with('message', ['message' => 'Application Message has been updated successfully',
                'title' => 'Application Message Updated Successfully', 'type' => 'success']);
    }


    public function destroy(LicenceUser $licence_user, Server $server, Application $application)
    {

        $application = $licence_user->servers()->findOrFail($server->id)?->applications()->findOrFail($application->id);

        if (!$application)
            return redirect(route('licence-users.servers.show', ['licence_user' => $licence_user]))
                ->with('message', ['message' => "Server doesn't belong to the user", "type" => "danger"]);

        $server->applications()->detach([
            $application->id
        ]);

        return redirect(route('licence-users.servers.show', ['licence_user' => $licence_user, 'server' => $server]))
            ->with('message', ['message' => 'Application has been deleted successfully',
                'title' => 'Application deleted Successfully', 'type' => 'success']);

    }

    public function toggle(LicenceUser $licence_user, Server $server, Application $application){
        $application = $licence_user->servers()->findOrFail($server->id)?->applications()->findOrFail($application->id);
        if (!$application)
            return redirect(route('licence-users.servers.show', ['licence_user' => $licence_user]))
                ->with('message', ['message' => "Application doesn't belong to the user", "type" => "danger"]);

        $application->pivot->active = !$application->pivot->active;
        $application->pivot->save();

        return redirect(route('licence-users.servers.show', ['licence_user' => $licence_user, 'server' => $server]))
            ->with('message', ['message' => 'Application has been updated successfully',
                'title' => 'Application Updated Successfully', 'type' => 'success']);
    }
}
