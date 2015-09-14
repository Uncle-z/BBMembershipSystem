@extends('layouts.main')

@section('meta-title')
Member Roles and Groups
@stop

@section('page-title')
Member Roles and Groups
@stop

@section('content')

<p>
    Update group names and descriptions.<br />
    Assign members to specific roles in order to control how much access they have and what they can do
</p>
<table class="table">
<thead>
    <tr>
        <th>Role</th>
        <th>Members</th>
    </tr>
</thead>
<tbody>
    @foreach($roles as $role)
        <tr>
            <td>
                {!! Form::open(array('method'=>'PUT', 'route' => ['roles.update', $role->id], 'class'=>'')) !!}
                <div class="form-group">
                    {!! Form::text('title', $role->title, ['class'=>'form-control input-lg', 'required']) !!}
                </div>
                <div class="form-group">
                {!! Form::textarea('description', $role->description, ['class'=>'form-control', 'rows'=>2, 'placeholder'=>'Short description']) !!}
                </div>
                {!! Form::submit('Save', array('class'=>'btn btn-default')) !!}
                {!! Form::close() !!}
                <small>{{ $role->name }}</small>
            </td>
            <td>
                <table class="table">
                @foreach($role->users as $user)
                    <tr>
                        <td width="50%">{{ $user->name }}</td>
                        <td>
                        {!! Form::open(array('method'=>'DELETE', 'route' => ['roles.users.destroy', $role->id, $user->id], 'class'=>'form-inline')) !!}
                        {!! Form::submit('Remove', array('class'=>'btn btn-default btn-xs')) !!}
                        {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
                    <tr>
                        {!! Form::open(array('method'=>'POST', 'route' => ['roles.users.store', $role->id], 'class'=>'form-inline')) !!}
                        <td>{!! Form::select('user_id', [''=>'Add a member']+$memberList, null, ['class'=>'form-control js-advanced-dropdown']) !!}</td>
                        <td>
                        {!! Form::submit('Add', array('class'=>'btn btn-default btn-sm')) !!}
                        </td>
                        {!! Form::close() !!}
                    </tr>
                </table>
            </td>
        </tr>
    @endforeach
</tbody>
</table>

@stop