@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">
            <h4>Create Class</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('classes.store') }}"
                  method="POST">

                @csrf

                @include('classes._form')

            </form>

        </div>

    </div>

</div>

@endsection