@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Website to parse</h5>
                    </div>
                    <div class="card-body">
                        <div class="card-deck">
                            <form action="{{ route('parse.url') }}" method="POST">
                                @csrf
                                <input name="website" type="url" placeholder="http://example.com"
                                       required>
                                <button class="btn btn-info" type="submit">GO</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
