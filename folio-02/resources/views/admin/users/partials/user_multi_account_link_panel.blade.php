<div class="widget-box widget-color-green">
    <div class="widget-header">
        <h4 class="widget-title">Manage Multiple Accounts</h4>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            @if(\Session::has('message_access'))
            <div class="alert {{ \Session::get('alert-class', 'alert-info') }}">
                <button class="close" data-dismiss="alert">
                    <i class="ace-icon fa fa-times"></i>
                </button>
                <i class="ace-icon fa {{ \Session::get('alert-icon', 'fa fa-check') }}"></i>
                {{ \Session::get('message_unlink') }}
            </div>
            @endif
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> User with multiple accounts can be linked.<br>
                <i class="fa fa-info-circle"></i> Use this feature to link or unlink this account with other accounts of the same user.<br>
            </div>
            <div class="space"></div>
            @if($linkedUserAccounts->count() > 0)
            <p class="text-info">This account is currently linked with the following accounts.</p>
            <table class="table table-bordered">
                <tr><th>Username</th><th>Name</th><th>Type</th><th>Action</th></tr>
                @foreach($linkedUserAccounts AS $linkedUserAccount)
                <tr>
                    <td>{{ $linkedUserAccount->username }}</td>
                    <td>{{ $linkedUserAccount->full_name }}</td>
                    <td>{{ optional($linkedUserAccount->systemUserType)->description }}</td>
                    <td>
                        {!! Form::open([
                            'method' => 'POST',
                            'url' => route('users.unlinkAccount', $user),
                            'class' => 'form-horizontal',
                            'name' => 'frmUnlinkAccount'
                            ]) !!}
                            {!! Form::hidden('account_id', $user->id) !!}
                            {!! Form::hidden('linked_account_id', $linkedUserAccount->id) !!}
                            <button class="btn btn-xs btn-primary btn-round" type="submit"><i class="fa fa-unlink"></i> Unlink</button>
                        {!! Form::close() !!}
                    </td>
                </tr>
                @endforeach
            </table>
            @else
            <p class="text-info"><i class="fa fa-info-circle"></i> This account is currently not linked with any other account.</p>
            <div class="space"></div>
            @endif
            
            {!! Form::open([
                'method' => 'POST',
                'url' => route('users.linkAccount', $user),
                'class' => 'form-horizontal',
                'name' => 'frmLinkAccount'
                ]) !!}
                {!! Form::hidden('account_id', $user->id) !!}
                <div class="form-group row required {{ $errors->has('linked_account_id') ? 'has-error' : '' }}">
                    {!! Form::label('linked_account_id', 'Select Account to Link', ['class' => 'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
                        {!! Form::select('linked_account_id', $usersList, null, [
                            'class' => 'form-control',
                            'required',
                            'placeholder' => '',
                        ]) !!}
                        {!! $errors->first('linked_account_id', '<p class="text-danger">:message</p>') !!}
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
