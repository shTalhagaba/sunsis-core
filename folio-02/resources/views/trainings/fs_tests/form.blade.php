{!! Form::hidden('tr_id', $training->id) !!}
{!! Form::hidden('next_action') !!}

<div class="row">
    <div class="col-sm-12 ">
        <div class="widget-box widget-color-green">
            <div class="widget-header">
                <h4 class="smaller">Details</h4>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <div class="row">
                        <div class="form-group row required {{ $errors->has('course_id') ? 'has-error' : '' }}">
                            {!! Form::label('course_id', 'Functional Skills Course', ['class' => 'col-sm-4 control-label']) !!}
                            <div class="col-sm-8">
                                {!! Form::select('course_id', $fsCoursesList, null, [
                                    'class' => 'form-control chosen-select',
                                    'required',
                                    'placeholder' => '',
                                ]) !!}
                                {!! $errors->first('course_id', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row required {{ $errors->has('complete_by') ? 'has-error' : '' }}">
                            {!! Form::label('complete_by', 'Complete By', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                            <div class="col-sm-8">
                                {!! Form::date('complete_by', null, ['class' => 'form-control', 'required']) !!}
                                {!! $errors->first('complete_by', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="widget-toolbox padding-8 clearfix">
                    <div class="center">
                        <button class="btn btn-sm btn-success btn-round" type="submit" onclick="setAction('add_more')">
                            Save and Add More
                        </button>
                        <button class="btn btn-sm btn-success btn-round" type="submit" onclick="setAction('no_add_more')">
                            Save and Go Back
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
    <script>

        function setAction(nextAction) {
            $("input[type=hidden][name=next_action]").val(nextAction);
        }

        if (!ace.vars['touch']) {
            $('.chosen-select').chosen({
                allow_single_deselect: true
            });
            //resize the chosen on window resize

            $(window)
                .off('resize.chosen')
                .on('resize.chosen', function() {
                    $('.chosen-select').each(function() {
                        var $this = $(this);
                        $this.next().css({
                            'width': $this.parent().width()
                        });
                    })
                }).trigger('resize.chosen');
            //resize chosen on sidebar collapse/expand
            $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
                if (event_name != 'sidebar_collapsed') return;
                $('.chosen-select').each(function() {
                    var $this = $(this);
                    $this.next().css({
                        'width': $this.parent().width()
                    });
                })
            });


            
        }

       

       

        
        
    </script>
@endpush
