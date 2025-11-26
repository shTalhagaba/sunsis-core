<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="LLDD" class="fieldLabel_compulsory">Do you consider yourself to have a learning difficulty, health problem or disability: *</label>
            <?php echo HTML::selectChosen('LLDD', $LLDD, $registration->LLDD, true, true); ?>
        </div>

        <div class="form-group" id="divLLDDCat">
            <label>Select categories:</label>
            <table class="table table-bordered table-striped" cellpadding="6">
                <tr>
                    <th>Click to select the Category</th>
                    <th>Select which one is the Primary Category</th>
                </tr>
                <?php
                foreach ($LLDDCats as $key => $value) 
                {
                    $checkedLldd = in_array($key, $selectedLlddcat) ? 'checked="checked"' : '';
                    $checkedPriLldd = $key == $registration->primary_lldd ? 'checked="checked"' : '';

                    echo '<tr>';

                    echo '<td>';
                    echo '<div class="checkbox">';
                    echo '<label>';
                    echo '<input type="checkbox" name="llddcat[]" ' . $checkedLldd . ' value="' . $key . '"> &nbsp; ' . $value;
                    echo '</label>';
                    echo '</div>';
                    echo '</td>';

                    echo '<td>';
                    echo '<p><input type="radio" name="primary_lldd" value="' . $key . '" ' . $checkedPriLldd . '></p>';
                    echo '</td>';
                    
                    echo '</tr>';
                    
                }
                ?>
            </table>
        </div>
        <div class="form-group">
            <label for="confidential_interview" class="fieldLabel_compulsory">Would you like to benefit from a confidential interview:</label>
            <?php echo HTML::selectChosen('confidential_interview', $YesNoList, $registration->confidential_interview, true, true); ?>
        </div>
    </div>
</div>