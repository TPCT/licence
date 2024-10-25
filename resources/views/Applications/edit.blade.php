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
                    <div class="form-group mb-3">
                        <label for="exampleFormControlTextarea1">Application Message</label>
                        <textarea class="form-control" name="message" id="exampleFormControlTextarea1" rows="3">{{$application->pivot->message}}</textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" name="show_message" type="checkbox" value="1" id="defaultCheck1" {{$application->pivot->show_message ? "checked='checked'" : ""}}">
                        <label class="form-check-label" for="defaultCheck1">
                            Show Message
                        </label>
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
