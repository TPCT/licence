@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('User') }}</div>

                <div class="card-body">
{{--                    <div class="d-flex justify-content-end">--}}
{{--                        <button class="btn btn-success" id="add_user">Add New Licence</button>--}}
{{--                    </div>--}}
                    <div class="d-flex flex-column p-2">
                        @foreach($users as $user)
                            <div class="p-2 bg-light row shadow-sm rounded-3 border-bottom mb-2">
                                <div class="col d-flex justify-content-center align-items-center flex-column">
                                    <span class="text-center">{{$user->username}}</span>
                                    <span class="text-center">Logged: {{$user->updated_at->toDateString()}}</span>
                                </div>
                                <div class="d-flex flex-column col align-items-center justify-content-center">
                                    @if($user->active)
                                        <div class="d-flex align-items-center mb-2 flex-column">
                                            <span class="badge bg-success mb-2">
                                                {{$user->applications()->pluck("server_applications.application_id")->unique()->count() . " Active Applications"}}
                                            </span>
                                            <span class="badge bg-success mb-2">
                                                {{$user->servers->where('active', true)->count()}} Active Devices
                                            </span>
                                            <span class="badge bg-warning mb-2">
                                                {{$user->applications()->where('active', 1)->orderBy('server_applications.end_date')->first()->pivot->end_date ?? "No Subscriptions"}}
                                            </span>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center mb-2">
                                        <span class="badge bg-danger">
                                            {{__('In-Active')}}
                                        </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-sm-12 mt-3 col-xl-6 d-flex justify-content-center">
                                    <form id="lock-for-device-{{$user->id}}" method="post" action="{{route('licence-users.lock', ['licence_user' => $user->id])}}">
                                        @csrf
                                    </form>
                                    <form id="toggle-status-{{$user->id}}" method="post" action="{{route('licence-users.toggle', ['licence_user' => $user->id])}}">
                                        @csrf
                                    </form>
                                    <form id="delete-{{$user->id}}" method="post" action="{{route('licence-users.destroy', ['licence_user' => $user->id])}}">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <div class="d-flex align-items-center justify-content-end">
                                        <button type="submit"
                                                class="btn @if($user->active) btn-warning @else btn-success ms-1 @endif
                                             me-2 status" form="toggle-status-{{$user->id}}"
                                                data-id="{{$user->id}}">
                                            @if ($user->active)
                                                Disable
                                            @else
                                                Enable
                                            @endif
                                        </button>
                                        <button type="submit"
                                                class="btn @if($user->lock_new_devices) btn-danger @else btn-primary ms-1 @endif me-2 status"
                                                form="lock-for-device-{{$user->id}}"
                                                data-id="{{$user->id}}">
                                            @if ($user->lock_new_devices)
                                                Un-Lock
                                            @else
                                                Lock
                                            @endif
                                        </button>
                                        <a class="btn btn-primary me-2" href="{{route('licence-users.show', ['licence_user' => $user->id])}}">IPS</a>
                                        <span class="btn btn-warning me-2 edit" data-id="{{$user->id}}">Edit</span>
                                        <button type="submit" class="btn btn-danger delete" form="delete-{{$user->id}}">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    </div>
                    <div class="p-3">
                        {{ $users->links() }}
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

            $('.edit').on('click', function(){
                $.get("{{route('licence-users.edit', ['licence_user' => "licence_user"])}}".replaceAll('licence_user', $(this).data('id')),
                    function(response, status){
                        validateResponse(status, response, function(response){
                            $(response).modal('show');
                        })
                });
            });

            $('#add_user').on('click', function(){
                $.get("{{route('licence-users.create')}}",
                    function(response, status){
                        validateResponse(status, response, function(response){
                            $(response).modal('show');
                        })
                    });
            });
        })
    </script>
@endpush
