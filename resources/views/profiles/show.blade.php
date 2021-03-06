@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="page-header">
                    <h1>
                        {{ $profileUser->name }}
                        <small>since {{ $profileUser->created_at->diffForHumans() }}</small>
                    </h1>
                </div>

                @forelse($activities as $date => $activity)
                    <h3>{{ $date }}</h3>
                    @foreach($activity as $record)
                        @if ( view()->exists("profiles.activities.{$record->type}") )
                            @include("profiles.activities.{$record->type}", ['activity' => $record])
                        @endif
                    @endforeach
                @empty
                    <p>Activities none.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
