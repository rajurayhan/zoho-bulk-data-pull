@extends('layout')

@section('title', 'Module List')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>Module List</h2>
            <a href="{{ route('zoho.modules.sync') }}" class="btn btn-primary my-3">Sync Module</a>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>API Name</th>
                        <th>Field Synec Status</th>
                        <th>Request Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($modules as $module)
                        <tr>
                            <td>{{ $module->id }}</td>
                            <td>{{ $module->name }}</td>
                            <td>{{ $module->api_name }}</td>
                            <td>@if(sizeof($module->fields) > 0) {{ 'Synced' }} @else {{ '-' }} @endif</td>
                            <td>@if($module->bulk_request) {{ 'Synced' }} @else {{ '-' }} @endif</td>
                            <td>
                                <a href="{{ route('zoho.fields.sync', $module->id) }}" class="btn btn-info btn-sm">Sync Fields</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
