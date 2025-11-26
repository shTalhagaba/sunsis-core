@extends('layouts.master')

@section('title', 'Edit Todo Task')

@section('page-content')
<div class="page-header">
   <h1>
        Edit Todo Task
      <small>
         <i class="ace-icon fa fa-angle-double-right"></i>
         edit todo task
      </small>
   </h1>
</div><!-- /.page-header -->
<div class="row">
   <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <button class="btn btn-sm btn-white btn-default btn-round" type="button" onclick="window.location.href='{{ route('todo_tasks.show', ['todo_task' => $task]) }}'">
            <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
        </button>
        <div class="hr hr-12 hr-dotted"></div>

        @include('partials.session_message')

        @include('partials.session_error')

        <div id="row">
            <div class="col-xs-12">
               <div class="space"></div>

               {!! Form::model($task->getAttributes(), [
                'method' => 'PATCH',
                'url' => route('todo_tasks.update', $task),
                'class' => 'form-horizontal',
                'role' => 'form',
                'id' => 'frmTask']) !!}
               @include('todo.form')
               {!! Form::close() !!}

            </div><!-- /.span -->
         </div><!-- /.user-profile -->


      <!-- PAGE CONTENT ENDS -->
   </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
@endsection

@section('page-inline-scripts')

<script type="text/javascript">


</script>

@endsection

