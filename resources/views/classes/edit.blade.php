@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">
            <h4>Edit Class</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('classes.update', $class) }}"
                  method="POST">

                @csrf
                @method('PUT')

                @include('classes._form')

            </form>

        </div>

    </div>

</div>

@endsection