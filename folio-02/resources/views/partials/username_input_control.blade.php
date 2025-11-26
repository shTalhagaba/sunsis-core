{!! Form::text('username', null, [
    'class' => 'form-control ',
    'maxlength' => '50',
    'required',
    'onfocus' => $onfocus ? 'usernameOnfocus(this);' : '',
    'onkeypress' => $onkeypress ? 'validateUsername(event);' : '',
]) !!}
{!! $errors->first('username', '<p class="text-danger">:message</p>') !!}


@push('after-scripts')
    <script>
        function sanitizeForUsername(input) 
        {
            const sanitized = input.replace(/[^a-zA-Z0-9-.]/g, '');
            return sanitized;
        }

        function usernameOnfocus(username) 
        {
            var firstnames = username.form.elements['firstnames'].value.toLowerCase();
            var surname = username.form.elements['surname'].value.toLowerCase();
            firstnames = sanitizeForUsername(firstnames);
            surname = sanitizeForUsername(surname);

            if (username.value == '') 
            {
                var tmp = firstnames.substring(0, 1) + surname;
                username.value = tmp.substring(0, 21);
            }
            if (username.value.length < 8) 
            {
                var i = 1;
                do {
                    username.value += i++;
                } while (username.value.length < 8);
            }
        }

        function validateUsername(event) 
        {
            const char = String.fromCharCode(event.which);
            const pattern = /^[a-z0-9-.]$/;

            if (!pattern.test(char)) {
                event.preventDefault();
            }
        }
    </script>
@endpush
