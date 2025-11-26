@extends('layouts.master')

@section('title', 'Learning Resource')

@section('page-content')
    <div class="page-header">
        <h1>View Learning Resource Details</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('learning_resources.index') }}'">
                <i class="ace-icon fa fa-times bigger-110"></i> Close
            </button>
            @if($learningResource->created_by == auth()->user()->id || auth()->user()->isAdmin() || auth()->user()->isQualityManager())
            <button class="btn btn-sm btn-primary btn-round" type="button"
                onclick="window.location.href='{{ route('learning_resources.edit', $learningResource) }}'">
                <i class="ace-icon fa fa-edit bigger-110"></i> Edit 
            </button>
            {!! Form::open([
                'method' => 'DELETE',
                'url' => route('learning_resources.destroy', [$learningResource]),
                'style' => 'display: inline;',
                'class' => 'form-inline frmDeleteResource',
            ]) !!}
                {!! Form::button('<i class="ace-icon fa fa-trash bigger-120"></i> Delete', [
                        'class' => 'btn btn-danger btn-sm btn-round btnDeleteResource',
                        'id' => 'btnDeleteResource' . $learningResource->id,
                        'type' => 'submit',
                        'style' => 'display: inline',
                ]) !!} &nbsp; 
            {!! Form::close() !!}
            @endif

            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_error')
            @include('partials.session_message')

            <div class="row">
                <div class="col-md-3">
                    @include('partials.tags_widget', [
                        '_entity' => $learningResource,
                        'tagTypeDesc' => 'LearningResource',
                    ])
                </div>
                <div class="col-md-9">
                    <span class="text-center">
                        <h3 class="text-info">
                            {{ $learningResource->resource_name }}
                            {!! $learningResource->is_featured ? '<i class="fa fa-star orange" title="Featured resource"></i>' : '' !!}
                        </h3>
                        <p>
                            {!! nl2br(e($learningResource->resource_short_description)) !!}
                        </p>
                    </span>
                    
                    @if ($learningResource->resource_type == App\Models\Lookups\LearningResourceTypeLookup::TYPE_FILE_UPLOAD)
                        <h4 class="text-info">
                            <a href="{{ route('files.download', encrypt($learningResource->media()->first()->id)) }}"
                                target="_blank" style="cursor: pointer;">
                                <i class="fa {{ $learningResource->icon() }} fa-3x"></i>  
                                {{ $learningResource->media()->first()->name }}.{{ $learningResource->media()->first()->extension }}<br>
                                {{ $learningResource->media()->first()->human_readable_size }}
                            </a>
                        </h4>                        
                    @elseif ($learningResource->resource_type == App\Models\Lookups\LearningResourceTypeLookup::TYPE_URL)
                        <h4 class="text-info">
                            <a href="{{ $learningResource->resource_url }}"
                                target="_blank" style="cursor: pointer;">
                                <i class="fa {{ $learningResource->icon() }} fa-2x"></i>  
                                {{ $learningResource->resource_url }}
                            </a>
                        </h4>                        
                    @elseif ($learningResource->resource_type == App\Models\Lookups\LearningResourceTypeLookup::TYPE_TEXT)
                        {!! $learningResource->resource_content !!}
                    @else
                        <!-- -->
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection


@section('page-inline-scripts')
    <script>
        $('.btnDeleteResource').on('click', function(e) {
            e.preventDefault();
            var form = this.closest('form');
            bootbox.confirm({
                title: 'Confirm Delete?',
                message: 'This action is irreversible, are you sure you want to continue?',
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: 'btn-sm btn-round'
                    },
                    confirm: {
                        label: '<i class="fa fa-trash-o"></i> Yes Delete',
                        className: 'btn-sm btn-danger btn-round'
                    }
                },
                callback: function(result) {
                    if (result) {
                        $('.loader').show();
                        $(form).find(':submit').attr("disabled", true);
                        $(form).find(':submit').html('<i class="fa fa-spinner fa-spin"></i>');
                        form.submit();
                    }
                }
            });
        });
    </script>

@endsection
