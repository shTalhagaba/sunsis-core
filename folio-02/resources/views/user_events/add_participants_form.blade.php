<div class="widget-box widget-color-green">
    <div class="widget-header">
        <h5 class="widget-title">Add Participants</h5>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            <div class="form-group row required">
                {!! Form::label('participant_name', 'Enter Name', ['class' => 'col-sm-12']) !!}
                <div class="col-sm-10">
                    {!! Form::text('participant_name', null, ['class' => 'form-control ', 'maxlength' => '70']) !!}
                </div>
                <div class="col-sm-2">
                    <button type="button" class="btn btn-sm btn-primary btn-round" title="Enter name and click this button" id="btnSearchParticipants">
                        <i class="ace-icon fa fa-search"></i>
                    </button>
                </div>
                <div class="col-sm-12">
                    <div class="space-4"></div>
                    <table class="table table-bordered" id="tblParticipants">
                        <tbody id="tblParticipantsBody">
                            <tr id="infoRow"><td colspan="2"><i>enter name of the user and click search button to bring users</i></td></tr>
                            <tr id="loadingRow" style="display: none;"><td colspan="2" class="text-info"><i class="fa fa-spin fa-refresh fa-2x"></i> fetching matching records ...</td></tr>
                            <tr id="noParticipantsRow" style="display: none;"><td colspan="2" class="text-warning"><i class="fa fa-warning"></i> No matching results.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
    <script>

        function addParticipant(btn, participantId, trId)
        {
            if(participantId === '')
            {
                return;
            }
            var btn = $(btn);
                        
            $.ajax({
                type: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    '_method': 'PATCH'
                },
                beforeSend: function() {
                    btn.attr('disabled', true);
                    btn.html('<i class="fa fa-spinner fa-spin"></i>');
                },
                url:'{{ route("user_events.add_participant", ['event' => $event]) }}',
                data: {
                    event_id: {{ $event->id }},
                    participant_id: participantId,
                    tr_id: trId,
                }
            }).done(function(data) {
                window.location.reload();
            }).fail(function(jqXHR, textStatus, errorThrown){
                var response = JSON.parse(jqXHR.responseText);
                var errors = response.message !== undefined ? response.message : response.errors;
                var errorString = '<ul>';
                $.each( errors, function( key, value) {
                    errorString += '<li>' + value + '</li>';
                });
                errorString += '</ul>';
                bootbox.alert({
                    title: "Error: " + errorThrown,
                    message: errorString
                });
            });
        }

        $("button#btnSearchParticipants").on("click", function(e){
            e.preventDefault();
            $("#tblParticipants").find("tr:gt(2)").remove();
            $("tr#infoRow").hide();
            $("tr#loadingRow").hide();
            $("tr#noParticipantsRow").hide();

            if($("input[name=participant_name]").val() === '')
            {
                $("tr#infoRow").show();
                return;
            }

            $("tr#infoRow").hide();
            $.ajax({
                beforeSend: function() {
                    $("tr#loadingRow").show();
                },
                url:'{{ route("searchParticipantsForEvent", ['event' => $event]) }}',
                type: 'get',
                data: {
                    event_id: {{ $event->id }},
                    participant_name: $("input[name=participant_name]").val()
                }
            }).done(function(data) {
                var participantsCount = 0;
                $.each(data.data, function(key, value){
                    let dynamicRowHTML = `
                    <tr> 
                        <td>
                            <button type="button" 
                                class="btn btn-xs btn-primary btn-round btnAddParticipant" 
                                title="Click to add this as participant." 
                                onclick="addParticipant(this, '${value.user_id}', '${value.tr_id}');">
                                <i class="ace-icon fa fa-plus"></i>
                            </button> 
                        </td> 
                        <td> 
                            ${value.firstnames} ${value.surname} [${value.user_type_description}] 
                        </td> 
                    </tr>`;
                    $('#tblParticipantsBody').append(dynamicRowHTML);
                    participantsCount++;
                });
                if(participantsCount === 0)
                {
                    $("tr#noParticipantsRow").show();
                }
            }).fail(function(jqXHR, textStatus, errorThrown){
                var response = JSON.parse(jqXHR.responseText);
                var errorString = '<ul>';
                $.each( response.errors, function( key, value) {
                    errorString += '<li>' + value + '</li>';
                });
                errorString += '</ul>';
                bootbox.alert({
                    title: "Error: " + errorThrown,
                    message: errorString
                });
            }).always(function(){
                $("tr#loadingRow").hide();
            });
        });
    </script>
@endpush