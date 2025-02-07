@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Applications') }}</div>

                    <div class="card-body">
{{--                        <div class="d-flex justify-content-end">--}}
{{--                            <a class="btn btn-warning me-2" href="{{route('licence-users.show', ['licence_user' => $user->id, 'server' => $server->id])}}">Back</a>--}}
{{--                            <button class="btn btn-success" id="add_application">Add New Application</button>--}}
{{--                        </div>--}}
                        <div class="d-flex flex-column p-2">
                            @foreach($applications as $application)
                                <div class="p-2 bg-light row shadow-sm rounded-3 border-bottom mb-2">
                                    <div class="col d-flex justify-content-center align-items-center flex-column">
                                        <span class="text-center">{{$application->application_name}} - {{$application->application_version}}</span>
                                    </div>
                                    <div class="d-flex flex-column col align-items-center justify-content-center">
                                        @if($user->active)
                                            <div class="d-flex justify-content-center align-items-center flex-column">
                                                <span class="badge bg-warning">{{$application->pivot->start_date}} - {{$application->pivot->end_date}}</span>
                                                @if (\Carbon\Carbon::parse($application->pivot->end_date) > \Carbon\Carbon::today())
                                                    @php
                                                        $application->pivot->update(['active' => 0]);
                                                        $application->pivot->save();
                                                    @endphp
                                                    <span class="alert text-center alert-success p-1 mt-1">Remaining: {{\Carbon\Carbon::parse($application->pivot->end_date)->diffInDays(\Carbon\Carbon::today())}} Days</span>
                                                @else
                                                    <span class="alert text-center bg-danger p-1 mt-1">Remaining: 0 Days</span>
                                                @endif
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center mb-2">
                                                <span class="badge bg-danger">
                                                    {{__('In-Active')}}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-sm-12 col-xl-6 d-flex justify-content-center align-items-center">
                                        <form id="toggle-status-{{$application->id}}" method="post" action="{{route('licence-users.server.applications.toggle', ['licence_user' => $user->id, 'server' => $server->id, 'application' => $application->id])}}">
                                            @csrf
                                        </form>
                                        <form id="delete-{{$application->id}}" method="post" action="{{route('licence-users.servers.applications.destroy', ['licence_user' => $user->id, 'server' => $server->id, 'application' => $application->id])}}">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        @if($user->active && $server->active)
                                            <div class="d-flex justify-content-end">
                                                <button
                                                    type="button"
                                                    class="edit btn btn-warning me-2"
                                                    data-application-id="{{$application->id}}"
                                                >Edit</button>
                                                @if (\Carbon\Carbon::parse($application->pivot->end_at) > \Carbon\Carbon::today())
                                                    <button type="submit" class="btn @if($application->pivot->active) btn-warning @else btn-success ms-1 @endif me-2 status" form="toggle-status-{{$application->id}}">
                                                        @if ($application->pivot->active)
                                                            Disable
                                                        @else
                                                            Enable
                                                        @endif
                                                    </button>
                                                @endif
                                                <button type="submit" class="btn btn-danger delete" form="delete-{{$application->id}}">
                                                    Delete
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            @endforeach
                        </div>
                        <div class="p-3">
                            {{ $applications->links() }}
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

            $('#add_application').on('click', function(){
                $.get("{{route('licence-users.servers.applications.create', ['licence_user' => $user->id, 'server' => $server->id])}}",
                    function(response, status){
                        validateResponse(status, response, function(response){
                            $(response).modal('show');
                        })
                    });
            });

            $('.edit').on('click', function(){
                $.get("{{route('licence-users.servers.applications.edit', ['licence_user' => $user->id, 'server' => $server->id, 'application' => "##application_id##"])}}".replace("##application_id##", $(this).data('application-id')),
                    function(response, status){
                        validateResponse(status, response, function(response){
                            $(response).modal('show');
                        })
                    });
            });
        })
    </script>
@endpush
