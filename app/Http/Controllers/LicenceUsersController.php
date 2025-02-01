<?php

namespace App\Http\Controllers;

use App\Models\LicenceUser;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;

class LicenceUsersController extends Controller
{
    public function index()
    {
        $users = LicenceUser::orderBy('updated_at', 'desc')->paginate(10);
        return view('Users.index', ['users' => $users]);
    }

    public function create()
    {
        return view('Users.store');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => ['unique:licence_users,username', 'required'],
        ]);

        LicenceUser::create($data);

        return redirect(route('licence-users.index'))
            ->with('message', ['message' => 'Licence has been added successfully',
                'title' => 'Licence added Successfully', 'type' => 'success']);
    }

    public function show(LicenceUser $licence_user)
    {
        return view('Servers.index', [
            'servers' => $licence_user->servers()->paginate(10),
            'user' => $licence_user
        ]);
    }

    public function edit(LicenceUser $licence_user)
    {
        return view('Users.edit', ['user' => $licence_user]);
    }

    public function update(Request $request, LicenceUser $licence_user)
    {
        $data = $request->validate([
            'username' => ['required', 'unique:licence_users,username,' . $licence_user->id]
        ]);
        $licence_user->update($data);
        return redirect(route('licence-users.index'))
                ->with('message', ['message' => 'Licence has been updated successfully',
                'title' => 'Licence updated Successfully', 'type' => 'success']);
    }

    public function destroy(LicenceUser $licence_user)
    {
    	$licence_user->applications()->detach();
    	$licence_user->servers()->delete();
        $licence_user->delete();
        return redirect(route('licence-users.index'))
            ->with('message', [
                'message' => 'Licence has been deleted successfully',
                'title' => 'Licence deleted Successfully',
                'type' => 'success'
            ]);
    }

    public function toggle(LicenceUser $licence_user){
        $licence_user->update([
            'active' => !$licence_user->active
        ]);
        return redirect(route('licence-users.index'))
            ->with('message', ['message' => 'Licence has been toggled successfully',
                'title' => 'Licence toggled Successfully', 'type' => 'success']);
    }

    public function lock(LicenceUser $licence_user){
        $licence_user->update([
            'lock_new_devices' => !$licence_user->lock_new_devices
        ]);

        return redirect(route('licence-users.index'))
            ->with('message', [
                'message' => 'Licence Lock has been updated successfully',
                'title' => 'Licence Lock updated Successfully',
                'type' => 'success'
            ]);
    }
}
