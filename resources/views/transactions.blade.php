@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <transactions :wallet="{{$wallet}}"></transactions>
            </div>
        </div>
    </div>
@endsection
