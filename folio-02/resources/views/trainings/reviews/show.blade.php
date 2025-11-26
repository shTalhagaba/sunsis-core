@extends('layouts.master')

@section('title', 'View Review Details')

@section('page-content')
   <div class="page-header">
        <h1>
            View Review Details
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                view details about this review record
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('trainings.show', $training) }}'">
                <i class="ace-icon fa fa-times bigger-110"></i> Close
            </button>
            @if (auth()->user()->isStaff() && auth()->user()->can('update-training-record') && !optional($review->form)->locked())
            <button class="btn btn-sm btn-primary btn-round" type="button"
                onclick="window.location.href='{{ route('trainings.reviews.edit', [$training, $review]) }}'">
                <i class="ace-icon fa fa-edit bigger-110"></i> Edit
            </button>
            {!! Form::open([
                'method' => 'DELETE',
                'url' => route('trainings.reviews.destroy', [$training, $review]),
                'id' => 'frmDeleteReview',
                'style' => 'display: inline;',
                'class' => 'form-inline',
            ]) !!}
            {!! Form::hidden('review_id_to_del', $review->id) !!}
            {!! Form::button('<i class="ace-icon fa fa-trash-o bigger-110"></i> Delete', [
                'data-rel' => 'tooltip',
                'class' => 'btn btn-danger btn-xs btn-round',
                'type' => 'click',
                'id' => 'btnDeleteReview',
            ]) !!}
            {!! Form::close() !!}
            @endif

            <span class="btn btn-{{ optional($review->form)->completed() ? 'success' : 'primary' }} btn-round btn-sm btn-white"
                onclick="window.location.href='{{ route('trainings.reviews.form.show', ['training' => $training, 'review' => $review]) }}'">
                <i class="fa fa-folder-open {{ optional($review->form)->completed() ? 'green' : 'blue' }}"></i> Review Form
            </span> &nbsp;    

            <div class="hr hr-12 hr-dotted"></div>
            
            @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-12">
                    <div class="widget-box transparent">
                        <div class="widget-header"><h5 class="widget-title">Review Entry Details</h5></div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="info-div info-div-striped">
                                    <div class="info-div-row">
                                        <div class="info-div-name">Title</div>
                                        <div class="info-div-value">{{ $review->title }}</div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name">Type of Review</div>
                                        <div class="info-div-value">
                                            @if ($review->type_of_review != '')
                                            {{ !is_null($review->type_of_review) ? App\Models\LookupManager::getTrainingReviewTypes($review->type_of_review) : '' }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name">Scheduled Date & Time</div>
                                        <div class="info-div-value">{{ $review->due_date->format('d/m/Y') }} @ {{ $review->start_time . ' - ' }}{{ $review->end_time }}</div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name">Assessor</div>
                                        <div class="info-div-value">{{ optional(App\Models\User::find($review->assessor))->full_name }}</div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name">Comments</div>
                                        <div class="info-div-value">{!! nl2br(e($review->comments)) !!}</div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> File/Resource </div>
                                        <div class="info-div-value">
                                            @if($review->media->count() > 0)
                                            <div class="col-xs-12">
                                                @include('partials.model_media_items', ['mediaFiles' => $review->media, 'model' => $review])
                                            </div>
                                            @endif
                                            
                                            <div class="col-xs-12">
                                                @include('partials.upload_file_form', [
                                                    'associatedModel' => $review, 
                                                    'sectionName' => ''
                                                    ])
                                            </div>
            
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@push('after-scripts')
<script>
    $("button#btnDeleteReview").on('click', function(e){
        e.preventDefault();

        var form = $(this).closest('form');

        bootbox.confirm({
            title: 'Sure to Remove?',
            message: 'This action is irreversible, are you sure you want to continue?',
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> Cancel',
                    className: 'btn-xs btn-round'
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> Yes Remove',
                    className: 'btn-danger btn-xs btn-round'
                }
            },
            callback: function(result) {
                if (result) {
                    form.submit();
                } 
            }
        });        
    });
</script>
@endpush