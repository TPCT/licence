<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Server</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="store_form" method="post" class="w-100" action="{{route('licence-users.servers.store', ['licence_user' => $user->id])}}">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <div class="input-group">
                                <span class="input-group-text" id="server_ip">IP</span>
                                <input type="text" name="server_ip" class="form-control"
                                       placeholder="Server IP" aria-label="server_ip"
                                       aria-describedby="server_ip" required>
                            </div>
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
