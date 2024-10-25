<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{$user->username}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="update_form" method="post" class="w-100" action="{{route('licence-users.update', ['licence_user' => $user->id])}}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                            <div class="col">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="username">Name</span>
                                    <input type="text" name="username" class="form-control"
                                           placeholder="Username" aria-label="username"
                                           aria-describedby="username" value="{{$user->username}}">
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
