@extends('layouts.master')

@section('title', 'Top Reviewers')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Top 5 Reviewers</h3>
    
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-light">
                <tr>
                    <th>Reviewer</th>
                    <th>Average Rating</th>
                    <th>Total Reviews Given</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($topReviewers as $reviewer)
                    <tr>
                        <td>{{ $reviewer->name }}</td>
                        <td>{{ number_format($reviewer->average_rating, 2) }} / 5</td>
                        <td>{{ $reviewer->reviews_given_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @if ($topReviewers->isEmpty())
        <div class="alert alert-warning mt-4">
            No reviewers available at the moment.
        </div>
    @endif
</div>
@endsection