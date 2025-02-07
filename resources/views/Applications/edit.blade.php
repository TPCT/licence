<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="input-group mb-1">
                        <div class="text-white text-start badge {{$application->pivot->active ? "bg-success" : "bg-danger" }} w-100 p-2">Name: {{$application->application_name}} </div>
                    </div>
                    <div class="input-group mb-1">
                        <div class="text-white text-start badge {{$application->pivot->active ? "bg-success" : "bg-danger" }} w-100 p-2">Version: {{$application->application_version}} </div>
                    </div>
                    <div class="input-group mb-1">
                        <div class="text-white text-start badge {{$application->pivot->active ? "bg-success" : "bg-danger" }} w-100 p-2">Expiration:  {{$application->pivot->end_date}} [@if(\Carbon\Carbon::parse($application->pivot->end_date) < \Carbon\Carbon::today()) Expired @else Active @endif]</div>
                    </div>
                    <div class="input-group mb-3">
                        <div class="text-white text-start badge {{$application->pivot->active ? "bg-success" : "bg-danger" }} w-100 p-2">Status: {{$application->pivot->active ? "Active" : "Disabled"}} </div>
                    </div>
                </div>
                <form id="update_form" method="post" class="w-100" action="{{route('licence-users.servers.applications.update', ['licence_user' => $user, 'server' => $server->id, 'application' => $application])}}">
                    @csrf
                    @method('PUT')
                    <div class="d-flex flex-column flex-wrap">
                        <div class="form-group mb-3">
                            <label for="start-date">Start Date</label>
                            <input value="{{\Carbon\Carbon::parse($application->pivot->start_date)->toDateString()}}" class="form-control" type="date" name="start_date" id="start-date">
                        </div>

                        <div class="form-group mb-3" >
                            <label for="package">Package</label>
                            <select class="form-select" name="package_id" id="package">
                                @foreach($packages as $value => $name)
                                    <option @selected($application->pivot->package_id == $value) value="{{$value}}">{{$name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label>De-Activation Message</label>
                            <textarea class="form-control" name="message" rows="6">{{$application->pivot->message}}</textarea>
                        </div>
                        <div class="form-group mb-3">
                            <div class="form-check form-switch">
                                <input name="show_message" class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" @checked($application->pivot->show_message)>
                                <label class="form-check-label" for="flexSwitchCheckChecked">Show Message</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="update_form">Save changes</button>
            </div>
        </div>
    </div>
</div>
