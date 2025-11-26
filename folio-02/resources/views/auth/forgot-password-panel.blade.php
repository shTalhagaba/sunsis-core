<div id="forgot-box" class="forgot-box widget-box no-border">
    <div class="widget-body">
        <div class="widget-main">
            <h4 class="header red lighter bigger">
                <i class="ace-icon fa fa-key"></i>
                Retrieve Password
            </h4>
            <div class="space-6"></div>
            <p>
                Provide your system username and registered email address. System will verify the details and send reset password email.
            </p>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form name="frmResetPassword" id="frmResetPassword" method="POST" action="{{ route('password.email') }}">
                @csrf
                <fieldset>
                    <label class="block clearfix">
                        <span class="block input-icon input-icon-right">
                            <input id="frm_reset_password_username" name="frm_reset_password_username" type="text"
                                placeholder="Enter your username"
                                class="form-control @error('frm_reset_password_username') is-invalid @enderror"
                                value="{{ old('frm_reset_password_username') }}" required maxlength="50"
                                autocomplete="frm_reset_password_username" autofocus />
                            <i class="ace-icon fa fa-envelope"></i>
                            @error('frm_reset_password_username')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </span>
                    </label>
                    <label class="block clearfix">
                        <span class="block input-icon input-icon-right">
                            <input id="frm_reset_password_email" name="frm_reset_password_email" type="email"
                                placeholder="Enter registered email address"
                                class="form-control @error('frm_reset_password_email') is-invalid @enderror"
                                value="{{ old('frm_reset_password_email') }}" required maxlength="255"
                                autocomplete="frm_reset_password_email" autofocus />
                            <i class="ace-icon fa fa-envelope"></i>
                            @error('frm_reset_password_email')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </span>
                    </label>
                    
                    <div class="clearfix">
                        <button type="submit" class="btn btn-sm btn-danger btn-round">
                            <i class="ace-icon fa fa-lightbulb-o"></i>
                            <span class="bigger-110">{{ __('Send Password Reset Link') }}</span>
                        </button>
                    </div>
                </fieldset>
            </form>
        </div>
        <!-- /.widget-main -->
        <div class="toolbar center">
            <a href="#" data-target="#login-box" class="back-to-login-link">
                Back to login
                <i class="ace-icon fa fa-arrow-right"></i>
            </a>
        </div>
    </div>
    <!-- /.widget-body -->
</div>
