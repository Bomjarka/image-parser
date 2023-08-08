@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Parsed from: {{ $web->url }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="card-deck">
                            <table class="table table-striped">
                                <tbody>

                                @foreach($parsedImages as $parsedImageGroup)
                                    <tr>
                                        @foreach($parsedImageGroup as $parsedImage)
                                            <td><img class="img-fluid" src="{{ asset('storage/' . $web->host . '/' . $parsedImage->name) }}" alt="{{ $parsedImage->name }}" size="100x100"></td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="card-footer text-muted">
                        На странице обнаружено {{ $parsedImagesCount }} изображений на: {{ $totalSize }}
                        <form method="POST" action="{{ route('destroy.url', ['web' => $web]) }}">
                            @csrf
                            <button class="btn btn-danger" type="submit">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
