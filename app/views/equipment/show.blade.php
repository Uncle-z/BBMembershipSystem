@extends('layouts.main')

@section('page-title')
Tools &amp; Equipment > {{ $equipment->name }}
@stop

@section('meta-title')
Tools and Equipment
@stop

@section('main-tab-bar')

@stop


@section('content')

<div class="row">

    <div class="col-md-12 col-lg-12">
        <div class="row">
            <div class="col-md-12 col-lg-6">
                <div class="well">
                    @if ($equipment->requires_training)
                        To use this piece of equipment an access fee and an induction is required. The access fee goes towards equipment maintenance.<br />
                        Equipment access fee: &pound{{ $equipment->cost }}<br />
                        <br />
                        @if ($userInduction)
                            Induction to be completed
                        @else
                            @include('partials/payment-form', ['reason'=>'induction', 'displayReason'=>'Equipment Access Fee', 'returnPath'=>route('equipment.show', [$equipmentId], false), 'amount'=>25, 'buttonLabel'=>'Pay Now', 'methods'=>['balance'], 'ref'=>$equipmentId])
                        @endif

                    @else
                        No fee required
                    @endif
                    @if (!$equipment->working)
                        <span class="label label-danger">Out of action</span>
                    @endif
                </div>
            </div>

            @if ($equipment->requires_training)
                <div class="col-sm-12 col-md-6">
                    <h4>Trainers</h4>
                    <div class="list-group">
                        @foreach($trainers as $trainer)
                            <a href="{{ route('members.show', $trainer->user->id) }}" class="list-group-item">
                                {{ HTML::memberPhoto($trainer->user->profile, $trainer->user->hash, 25, '') }}
                                {{{ $trainer->user->name }}}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>


    <h3>Activity Log</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Used for</th>
                <th>Member</th>
                <th>Reason</th>
                @if (Auth::user()->isAdmin() || Auth::user()->hasRole($equipmentId))
                <th></th>
                @endif
            </tr>
        </thead>
        <tbody>
        @foreach($equipmentLog as $log)
            <tr>
                <td>{{ $log->present()->started }}</td>
                <td>{{ $log->present()->timeUsed }}</td>
                <td><a href="{{ route('members.show', $log->user->id) }}">{{{ $log->user->name }}}</a></td>
                <td>{{ $log->present()->reason }}</td>
                @if (Auth::user()->isAdmin() || Auth::user()->hasRole($equipmentId))
                <td>
                    @if (empty($log->reason))
                    {{ Form::open(['method'=>'POST', 'route'=>['equipment_log.update', $log->id], 'name'=>'equipmentLog']) }}
                    {{ Form::select('reason', ['testing'=>'Testing', 'training'=>'Training'], $log->reason, ['class'=>'']) }}
                    {{ Form::submit('Update', ['class'=>'btn btn-primary btn-xs']) }}
                    {{ Form::close() }}
                    @endif
                </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="panel-footer">
        <?php echo $equipmentLog->links(); ?>
    </div>


    <div class="row">
        @if ($equipment->requires_training)
            <div class="col-sm-12 col-md-6">
                <h4>Trained Users</h4>
                <ul>
                    @foreach($trainedUsers as $trainedUser)
                        <li>
                            <a href="{{ route('members.show', $trainedUser->user->id) }}">
                                {{{ $trainedUser->user->name }}}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-sm-12 col-md-6">
                <h4>Members waiting for an Induction</h4>
                <ul>
                    @foreach($usersPendingInduction as $trainedUser)
                        <li>
                            <a href="{{ route('members.show', $trainedUser->user->id) }}">
                                {{{ $trainedUser->user->name }}}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>

@stop

@section('footer-js')

@stop