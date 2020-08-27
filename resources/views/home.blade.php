@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <home :wallets="{{$wallets}}"></home>
        </div>
    </div>
</div>
@endsection
