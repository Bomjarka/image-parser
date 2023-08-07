@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Parsed from: {{ $parsedWebsite }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="card-deck">
                            <table class="table table-striped">
                                <thead>
                                <tr>Total images found: {{ $foundImagesCount }}, Total images parsed: {{ $parsedImagesCount }} Total size: {{ $totalSize }}</tr>
                                </thead>
                                <tbody>
                                @foreach($parsedImages as $parsedImageGroup)
                                    <tr>
                                        @foreach($parsedImageGroup as $parsedImage)
                                            <td><img class="img-fluid" src="{{ asset('storage/' . $parsedImage) }}" alt="{{ $parsedImage }}" size="100x100"></td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
