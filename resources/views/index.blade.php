@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Student</div>

                <div class="card-body">
                <livewire:student-create>
                <livewire:student-index>
                </div>

            </div>
        </div>
    </div>
</div>
@stop
