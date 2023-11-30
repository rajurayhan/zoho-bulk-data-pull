@extends('layout')

@section('title', 'Module List')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>Field List</h2>
            {{-- <a href="{{ route('zoho.modules.sync') }}" class="btn btn-primary my-3">Sync Module</a> --}}
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Module</th>
                        <th>Name</th>
                        <th>API Name</th>
                        {{-- <th>Actions</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach($fields as $field)
                        <tr>
                            <td>{{ $field->id }}</td>
                            <td>{{ $field->module->name }}</td>
                            <td>{{ $field->name }}</td>
                            <td>{{ $field->api_name }}</td>
                            {{-- <td>
                                <a href="{{ route('zoho.fields.sync') }}" class="btn btn-info btn-sm">Sync Fields</a>
                            </td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
