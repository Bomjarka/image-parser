@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Parsed websites</h5>
                    </div>
                    <div class="card-body">
                        <div class="card-deck">

                            <ul class="list-group list-group-numbered">
                                @foreach($websites as $website)
                                    <li class="list-group-item">
                                        <a href="{{ route('parsed.url', ['web' => $website]) }}">{{ $website->url }}</a>
                                    </li>
                                @endforeach
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
