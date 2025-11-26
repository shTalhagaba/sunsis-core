@extends('layouts.master')

@section('title', 'Individual Learning Support Assessment and Plan')

@section('page-plugin-styles')
<style>
    input[type=checkbox] {
        transform: scale(1.4);
    }
    textarea {
        border: 1px solid #3366FF;
        border-radius: 5px;
        border-left: 5px solid #3366FF;
    }
</style>
@endsection

@section('page-content')
    <div class="page-header">
        <h1>
            Individual Learning Support Assessment and Plan
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('trainings.als_assessment.show', ['training' => $training, 'als_assessment' => $alsAssessment]) }}'">
                <i class="ace-icon fa fa-times bigger-110"></i> Close
            </button>

            <div class="hr hr-12 hr-dotted"></div>

            @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-12">
                    <div class="space"></div>
                    {!! Form::model($alsAssessment, [
                        'method' => 'PATCH',
                        'url' => route('trainings.als_assessment.save_form', [$training, $alsAssessment]),
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id' => 'frmAlsAssessment',
                    ]) !!}

                    @include('trainings.als_assessment.partials.form_contents_read', ['alsAssessment' => $alsAssessment])

                    <!-- Learner Confirmation -->
                    @if(auth()->user()->isStudent())
                    <div class="card" style="margin-bottom: 4px">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <col width="33%" />
                                    <col width="33%" />
                                    <col width="33%" />
                                    <thead>
                                        <tr>
                                            <th colspan="3" class="text-center">Learner Confirmation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th colspan="3" class="text-center">
                                                <i>
                                                    I agree that the above Learning Support Plan is necessary to support my
                                                    apprenticeship and will attend all agreed sessions and monthly
                                                    reviews.<br>
                                                    I am aware that if I do not attend sessions, I may be withdrawn from the
                                                    apprenticeship.<br>
                                                    I am aware that I have not received a formal diagnosis of any learning
                                                    difficulty.
                                                </i>
                                            </th>
                                        </tr>
                                        <tr>
                                            <td>Learner Signature</td>
                                            <td>
                                                <input type="checkbox" name="learner_sign" 
                                                    value="1" {{ auth()->user()->id !== $training->student_id ? 'disabled' : '' }}>
                                            </td>
                                            <td>
                                                {{ optional($alsAssessment->learner_sign_date)->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">
                                                <i>
                                                    Do you consent to sharing this support assessment and plan with my
                                                    employer?
                                                </i>
                                            </th>
                                            <td>
                                                {!! Form::select('share_with_employer', ['Yes' => 'Yes - share with employer', 'No' => 'No - not share with employer'], null, [
                                                    'class' => 'form-control',
                                                    'placeholder' => '',
                                                ]) !!}
                                                <label class="block" style="padding: 2%">
                                                    <input type="checkbox" name="learner_confirm_choice" class="ace input-lg"
                                                        value="1" {{ auth()->user()->id !== $training->student_id ? 'disabled' : '' }}>
                                                    <span class="lbl"> Tick to confirm your choice</span>
                                                </label>
                                            </td>
                                            <td>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    @else
                    @include('trainings.als_assessment.partials.learner_confirmation_read', ['alsAssessment' => $alsAssessment])
                    @endif

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-success btn-md btn-round">
                            <i class="fa fa-save"></i> Save Information
                        </button>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@push('after-scripts')
    <script></script>
@endpush
