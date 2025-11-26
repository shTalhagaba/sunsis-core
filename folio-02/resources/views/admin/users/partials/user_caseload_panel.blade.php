<div class="widget-box widget-color-green">
    <div class="widget-header">
        <h4 class="widget-title">Link/Unlink with Accounts for Caseloading</h4>
    </div>
    <div class="widget-body">
        <div class="widget-main table-responsive">
            @if (\Session::has('message_access'))
                <div class="alert {{ \Session::get('alert-class', 'alert-info') }}">
                    <button class="close" data-dismiss="alert">
                        <i class="ace-icon fa fa-times"></i>
                    </button>
                    <i class="ace-icon fa {{ \Session::get('alert-icon', 'fa fa-check') }}"></i>
                    {{ \Session::get('message_unlink') }}
                </div>
            @endif
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> Use this feature to link or unlink this account with assessors, tutors
                and verifiers for caseloading.<br>
            </div>
            <div class="space"></div>
            @if ($userLinkedAccounts->count() > 0)
                <p class="text-info">
                    This account ({{ $user->full_name }}) is currently linked with the following
                    {{ $userLinkedAccounts->count() }} {{ Str::plural('account', $userLinkedAccounts->count()) }} for
                    caseloading.
                </p>
                <table class="table table-bordered">
                    <tr>
                        <th>User</th>
                        <th>User Type</th>
                        <th>Action</th>
                    </tr>
                    @foreach ($userLinkedAccounts as $userLinkedAccount)
                        <tr>
                            <td>
                                <i class="fa fa-user"></i> {{ $userLinkedAccount->firstnames }}
                                {{ $userLinkedAccount->surname }}<br>
                                <i class="fa fa-envelope"></i> {{ $userLinkedAccount->primary_email }}<br>
                                <code> {{ $userLinkedAccount->username }}</code>
                            </td>
                            <td>
                                {{ App\Models\Lookups\UserTypeLookup::getDescription($userLinkedAccount->user_type) }}
                            </td>
                            <td>
                                {!! Form::open([
                                    'method' => 'POST',
                                    'url' => route('user.unlinkCaseloadAccount', ['user' => $user]),
                                    'class' => 'form-horizontal',
                                    'name' => 'frmUnlinkCaseloadAccount',
                                ]) !!}
                                {!! Form::hidden('user_id', $user->id) !!}
                                {!! Form::hidden('caseload_account_id', $userLinkedAccount->id) !!}
                                <button class="btn btn-xs btn-primary btn-round" type="submit"><i
                                        class="fa fa-unlink"></i> Unlink</button>
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                </table>
            @else
                <p class="text-info">
                    <i class="fa fa-info-circle"></i> This user account ({{ $user->full_name }}) is currently not
                    linked with any accounts for caseloading.
                </p>
                <div class="space"></div>
            @endif

            {!! Form::open([
                'method' => 'POST',
                'url' => route('user.linkCaseloadAccount', ['user' => $user]),
                'class' => 'form-horizontal',
                'name' => 'frmLinkCaseloadAccount',
            ]) !!}
            {!! Form::hidden('user_id', $user->id) !!}
            <div class="form-group row required {{ $errors->has('caseload_account_id') ? 'has-error' : '' }}">
                {!! Form::label('caseload_account_id', 'Select User', ['class' => 'col-sm-4 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::select('caseload_account_id', $atvList, null, [
                        'class' => 'form-control',
                        'required',
                        'placeholder' => '',
                    ]) !!}
                    {!! $errors->first('caseload_account_id', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="clearfix center">
                <button class="btn btn-sm btn-success btn-round" type="submit">
                    <i class="ace-icon fa fa-link bigger-110"></i>
                    Link
                </button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
