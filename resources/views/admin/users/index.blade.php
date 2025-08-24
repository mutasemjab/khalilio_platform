@extends('layouts.admin')

@section('title', __('messages.Users'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ __('messages.Users') }}</h4>
                </div>
                <div class="card-body">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.ID') }}</th>
                                        <th>{{ __('messages.Name') }}</th>
                                        <th>{{ __('messages.Phone') }}</th>
                                        <th>{{ __('messages.School Name') }}</th>
                                        <th>{{ __('messages.Arabic Grade') }}</th>
                                        <th>{{ __('messages.English Grade') }}</th>
                                        <th>{{ __('messages.Jordan History Grade') }}</th>
                                        <th>{{ __('messages.Islamic Education Grade') }}</th>
                                        <th>{{ __('messages.Average') }}</th>
                                        <th>{{ __('messages.Created At') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->phone }}</td>
                                            <td>{{ $user->school_name }}</td>
                                            <td>
                                                @if($user->arabic_grade)
                                                    <span class="badge badge-primary">{{ $user->arabic_grade }}</span>
                                                @else
                                                    <span class="text-muted">{{ __('messages.Not Set') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->english_grade)
                                                    <span class="badge badge-success">{{ $user->english_grade }}</span>
                                                @else
                                                    <span class="text-muted">{{ __('messages.Not Set') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->jordan_history_grade)
                                                    <span class="badge badge-info">{{ $user->jordan_history_grade }}</span>
                                                @else
                                                    <span class="text-muted">{{ __('messages.Not Set') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->islamic_education_grade)
                                                    <span class="badge badge-warning">{{ $user->islamic_education_grade }}</span>
                                                @else
                                                    <span class="text-muted">{{ __('messages.Not Set') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->average)
                                                    <strong class="text-primary">{{ number_format($user->average, 2) }}</strong>
                                                @else
                                                    <span class="text-muted">{{ __('messages.Not Calculated') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('messages.No Users Found') }}</h5>
                            <p class="text-muted">{{ __('messages.No users have been registered yet') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection