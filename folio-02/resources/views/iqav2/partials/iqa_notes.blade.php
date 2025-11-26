<h3>{{ $learnerName }} </h3>
<h4>{{ $portfolioUnit->unit_owner_ref }} [{{ $portfolioUnit->unique_ref_number }}]: {{ $portfolioUnit->title }}</h4>

@if ($notes->isEmpty())
    <p>No IQA notes available.</p>
@else
    @foreach ($notes as $note)
        <div class="itemdiv dialogdiv">
            <div class="body">
                <div class="time">
                    <i class="ace-icon fa fa-clock-o"></i>
                    <span class="green">{{ $note->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="name">
                    <h4>
                        {{ optional($note->creator)->full_name }}</h4>
                </div>
                <span class="label label-info">{{ $note->iqa_type }}</span>
                <div class="text">{!! nl2br(e($note->comments)) !!}</div>
            </div>
        </div>
    @endforeach

@endif
