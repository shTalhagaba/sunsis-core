@push('after-styles')
<style>
    input[type=radio], input[type=checkbox] {
        transform: scale(1.7);
    }
</style>
@endpush

@php
$participantEventDetail = App\Models\UserEvents\UserEventParticipant::where('event_id', $event->id)
    ->where('user_id', auth()->user()->id)
    ->first();
@endphp

<div class="widget-box widget-color-green">
    <div class="widget-header">
        <h5 class="widget-title">Event Invitation</h5>
    </div>
    <div class="widget-body">
        @if ($participantEventDetail->isAccepted())
            <h4 class="bolder text-success text-center">You accepted this event on {{ $participantEventDetail->updated_at->format('d/m/Y H:i:s') }}.</h4>
        @elseif($participantEventDetail->isDeclined())
            <h4 class="bolder text-danger text-center">You declined this event {{ $participantEventDetail->updated_at->format('d/m/Y H:i:s') }}.</h4>
        @endif

        @if(!$event->isPast())
        {!! Form::open([
            'url' => route('user_events.updateStatusByParticipant', $event),
            'class' => 'form-horizontal',
        ]) !!}
        {!! Form::hidden('event_id', $event->id) !!}
        {!! Form::hidden('user_id', auth()->user()->id) !!}
        <div class="widget-main">
            <div class="form-group row required">
                {!! Form::label('status', 'Select Status', ['class' => 'col-sm-12']) !!}
                <div class="col-sm-12 table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <td class="center success" style="width: 50%">
                                <p>Accept</p>
                                <input type="radio" value="2" name="status" required>
                            </td>
                            <td class="center warning" class="center" style="width: 50%">
                                <p>Decline</p>
                                <input type="radio" value="3" name="status" required>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-center">
                                <span class="text-info">
                                    <i class="fa fa-info-circle"></i> System will notify the organiser about your choice.
                                </span>
                            </td>
                        </tr>
                    </table>
                    {!! $errors->first('fs_targets_met', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>            
        </div>
        <div class="widget-toolbox padding-8 clearfix">
            <div class="center">
                <button class="btn btn-sm btn-round btn-success" type="submit">
                    <i class="ace-icon fa fa-save bigger-110"></i>
                    Save
                </button>
            </div>
        </div>
        {!! Form::close() !!}
        @endif
    </div>
</div>