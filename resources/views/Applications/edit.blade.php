<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{$application->application_name}} - {{$application->application_version}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="update_form" method="post" class="w-100" action="{{route('licence-users.servers.applications.update', ['licence_user' => $user, 'server' => $server->id, 'application' => $application])}}">
                    @csrf
                    @method('PUT')
                    <div class="d-flex flex-wrap justify-content-between">
                        <div class="form-group mb-3">
                            <label for="start-date">Start Date</label>
                            <input value="{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $application->pivot->end_date)->toDateString()}}" class="form-control" type="date" name="start_date" id="start-date">
                        </div>
                        <div class="form-group mb-3">
                            <label for="end-date">End Date</label>
                            <input value="{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $application->pivot->end_date)->toDateString()}}" class="form-control" type="date" name="end_date" id="end-date">
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
