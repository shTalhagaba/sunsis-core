@extends('layouts.master')

@section('title', 'ALS Review Form')

@section('page-content')
    <div class="page-header">
        <h1>
            ALS Review Form
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                complete details about this ALS review form
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

            <div class="hr hr-12 hr-dotted"></div>

            @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

            @include('partials.session_message')

            @include('partials.session_error')

            @include('trainings.als_reviews.partials.als_review_form')

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@push('after-scripts')
    <script>
        $("#btnAddAssessorSessions").on('click', function() {
            let sessionCount = $("table#tblAssessorSessions tbody tr").length;
            sessionCount++;
            const newRow = `
                <tr>
                    <td>
                        <input type="hidden" name="session_${sessionCount}_saved_id" >
                        <input type="date" name="session_${sessionCount}_date" class="form-control" >
                    </td>
                    <td>
                        <input type="text" name="session_${sessionCount}_topic" class="form-control" >
                    </td>
                    <td>
                        <textarea name="session_${sessionCount}_support_detail" class="form-control" rows="3" ></textarea>
                    </td>
                </tr>           
            `;

            $('table#tblAssessorSessions tbody').append(newRow);
        });

        $('input[name="adjustments[]"][value="99"]').on('change', function() {
            $('textarea[name=adjustment_other]').removeClass('required');
            if (this.checked) {
                $('textarea[name=adjustment_other]').addClass('required');
            }
        });
    </script>
@endpush
