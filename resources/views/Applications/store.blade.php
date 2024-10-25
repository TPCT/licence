<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Application</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="store_form" method="post" class="w-100" action="{{route('licence-users.servers.applications.store', ['licence_user' => $user->id, 'server' => $server->id])}}">
                    @csrf
                    <div class="row row-cols-2 p-2">
                        <select class="form-select" aria-label="Default select example" name="application_id">
                            @foreach($applications as $application)
                                <option value="{{$application->id}}">{{$application->application_name}}-{{$application->application_version}}</option>
                            @endforeach
                        </select>
                        <div class="input-group mt-3">
                            <span class="input-group-text" id="licence_date">Date</span>
                            <input type="date" name="licence_date" class="form-control"
                                   placeholder="Licence date" aria-label="licence_date"
                                   aria-describedby="licence_date" id="licence_date" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="store_form">Save changes</button>
            </div>
        </div>
    </div>
</div>
