@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('IPS') }}</div>

                    <div class="card-body">
{{--                        <div class="d-flex justify-content-end">--}}
{{--                            <a class="btn btn-warning me-2" href="{{route('licence-users.index')}}">Back</a>--}}
{{--                            <button class="btn btn-success" id="add_ip">Add New IP</button>--}}
{{--                        </div>--}}
                        <div class="d-flex flex-column p-2">
                            @foreach($servers as $server)
                                <div class="p-2 bg-light row shadow-sm rounded-3 border-bottom mb-2">
                                    <div class="col d-flex justify-content-center align-items-center flex-column">
                                        <span class="text-center">{{$server->server_mac_address}}</span>
                                        <span class="text-center">{{$server->updated_at->toDateString()}}</span>
                                    </div>
                                    <div class="d-flex flex-column col align-items-center justify-content-center">
                                        @if($server->active)
                                            <div class="d-flex align-items-center flex-column">
                                                <span class="badge bg-warning mb-2">
                                                    {{$server->applications()->where('active', 1)->orderBy('pivot_end_date')->first()->pivot->end_date ?? "No Active Subscriptions"}}
                                                </span>
                                                    <span class="badge bg-success">
                                                    {{$server->applications()->where('active', true)->count()}} Active Applications
                                                </span>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center">
                                        <span class="badge bg-danger">
                                            {{__('In-Active')}}
                                        </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-sm-12 mt-3 col-xl-6 d-flex justify-content-center">
                                        <form id="toggle-status-{{$server->id}}" method="post" action="{{route('licence-users.servers.toggle', ['licence_user' => $user->id, 'server' => $server->id])}}">
                                            @csrf
                                        </form>
                                        <form id="delete-{{$server->id}}" method="post" action="{{route('licence-users.servers.destroy', ['licence_user' => $user->id, 'server' => $server->id])}}">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        @if($user->active)
                                            <div class="d-flex align-items-center justify-content-end">
                                                <button type="submit"
                                                        class="btn @if($server->active) btn-warning @else btn-success ms-1 @endif
                                                 me-2 status" form="toggle-status-{{$server->id}}">
                                                    @if ($server->active)
                                                        Disable
                                                    @else
                                                        Enable
                                                    @endif
                                                </button>
                                                <a class="btn btn-primary me-2" href="{{route('licence-users.servers.show', ['licence_user' => $user->id, 'server' => $server->id])}}">Applications</a>
                                                <button type="submit" class="btn btn-danger delete" form="delete-{{$server->id}}">
                                                    Delete
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            @endforeach
                        </div>
                        <div class="p-3">
                            {{ $servers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(function(){
            function validateResponse(status, response, callback){
                if (status === 'success')
                    return callback(response)
                let error_toast = $(`
                    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header d-flex justify-content-between">
                        <span class="text-danger text-center">
                            Request Error
                        </span>
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body text-danger">
                            request status: ${status}
                        </div>
                    </div>
            `);
                $('.toast-container').append(error_toast);
                let toastEl = new bootstrap.Toast(error_toast)
                toastEl.show();
            }

            $('#add_ip').on('click', function(){
                $.get("{{route('licence-users.servers.create', ['licence_user' => $user->id])}}",
                    function(response, status){
                        validateResponse(status, response, function(response){
                            $(response).modal('show');
                        })
                    });
            });
        })
    </script>
@endpush
