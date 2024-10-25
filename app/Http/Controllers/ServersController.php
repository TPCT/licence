<?php

namespace App\Http\Controllers;

use App\Models\LicenceUser;
use App\Models\Server;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use phpDocumentor\Reflection\Types\True_;

class ServersController extends Controller
{
    public function create(Request $request, LicenceUser $licence_user)
    {
        return view('Servers.store', ['user' => $licence_user]);
    }

    public function store(Request $request, LicenceUser $licence_user)
    {
        $data = $request->validate([
            'server_ip' => 'required|unique:servers,server_ip|ipv4'
        ]);

        $licence_user->servers()->create($data);

        return redirect(route('licence-users.show', ['licence_user' => $licence_user]))
            ->with('message', ['message' => 'Server has been added successfully',
                'title' => 'Server added Successfully', 'type' => 'success']);
    }

    public function show(Request $request, LicenceUser $licence_user, Server $server)
    {
        $server = $licence_user->servers()->findOrFail($server->id);
        if (!$server)
            return redirect(route('licence-users.show', ['licence_user' => $licence_user]))
                ->with('message', ['message' => "Server doesn't belong to the user", "type" => "danger"]);

        $applications = $server->applications()->paginate(10);
        return view('Applications.index', [
            'user' => $licence_user,
            'server' => $server,
            'applications' => $applications
        ]);
    }

    public function toggle(Request $request, LicenceUser $licence_user, Server $server){
        $server = $licence_user->servers()->findOrFail($server->id);

        if (!$server)
            return redirect(route('licence-users.show', ['licence_user' => $licence_user]))
                ->with('message', ['message' => "Server doesn't belong to the user", "type" => "danger"]);

        $server->update([
            'active' => !$server->active
        ]);

        return redirect(route('licence-users.show', ['licence_user' => $licence_user]))
            ->with('message', ['message' => 'Server has been updated successfully',
                'title' => 'Server Updated Successfully', 'type' => 'success']);
    }

    public function destroy(LicenceUser $licence_user, Server $server)
    {
        $server = $licence_user->servers()->findOrFail($server->id);
        if (!$server)
            return redirect(route('licence-users.show', ['licence_user' => $licence_user]))
                ->with('message', ['message' => "Server doesn't belong to the user", "type" => "danger"]);

		$server->applications()->detach();
        $server->delete();

        return redirect(route('licence-users.show', ['licence_user' => $licence_user]))
            ->with('message', ['message' => 'Server has been deleted successfully',
                'title' => 'Server deleted Successfully', 'type' => 'success']);
    }
}
