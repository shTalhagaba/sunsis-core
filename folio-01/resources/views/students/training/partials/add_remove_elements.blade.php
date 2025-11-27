<div class="row">
    <div class="col-sm-12">
        <div class="alert alert-info">
            <p><i class="fa fa-info-circle"></i> Use this panel to add or remove following elements into the learner's
                training record.</p>
            <ul class="list-unstyled">
                <li class="muted"><i class="ace-icon fa fa-angle-right bigger-110"></i> Qualifications (Portfolios)</li>
                <li class="muted"><i class="ace-icon fa fa-angle-right bigger-110"></i> Units</li>
                <li class="muted"><i class="ace-icon fa fa-angle-right bigger-110"></i> Performance Criteria (PCs)</li>
            </ul>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        @can('add-remove-tr-elements')
        <button type="button" class="btn btn-primary btn-sm btn-round" onclick="window.location.href='{{ route('students.training.portfolios.show', [$student, $training_record]) }}'">
            <i class="ace-icon fa fa-plus"></i><span class="bigger-110">Add Qualifications</span>
        </button>
        @endcan
    </div>
</div>

<div class="space-6"></div>

<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive">
            @foreach($training_record->portfolios AS $portfolio)
                @php
                    $qualification = \App\Models\Programmes\ProgrammeQualification::findOrFail($portfolio->tbl_qualification_id);
                @endphp
                @if(!is_null($qualification))
                <div class="widget-box ui-sortable-handle collapsed qualification-widget" id="addRemoveElementsQualBox{{ $portfolio->id }}">
                    <div class="widget-header">
                        <h4 class="widget-title"><i class="fa fa-graduation-cap fa-lg"></i> {{ $portfolio->qan }} {{ $portfolio->title }}</h4>
                        @if($portfolio->isSafeToDelete())
                        {!! Form::open(['method' => 'DELETE',
                            'url' => route('ajax.training.add_remove_elements.remove_portfolio'),
                            'style' => 'display: inline;',
                            'class' => 'form-inline' ]) !!}
                            {!! Form::hidden('student_id', $student->id) !!}
                            {!! Form::hidden('tr_id', $training_record->id) !!}
                            {!! Form::hidden('portfolio_id', $portfolio->id) !!}
                            {!! Form::hidden('qualification_id', $qualification->id) !!}
                            {!! Form::button('<i class="ace-icon fa fa-trash-o bigger-160"></i>', ['data-rel'=> 'tooltip',
                                'title' => 'Delete this portfolio',
                                'class' => 'btn btn-danger btn-sm btn-round btnDeletePortfolio',
                                'type' => 'button']) !!}
                        {!! Form::close() !!}
                        @endif
                        <div class="widget-toolbar">
                            <a href="#" data-action="collapse" id="inaam"><i class="ace-icon fa fa-chevron-down"></i></a>
                        </div>
                    </div>
                    <div class="widget-body" id="addRemoveElementsQualBody{{ $portfolio->id }}">
                        <div class="widget-main"></div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
