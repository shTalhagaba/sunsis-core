<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <col style="width: 50%;">
            <tbody>
                <tr>
                    <th>
                        <h4 class="text-bold">Contact and Marketing Information</h4>
                    </th>
                </tr>
                <tr>
                    <td>
                        <label for="hear_us">How did you hear about us?</label><br>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="hear_us[]" value="1">
                                Current Employer
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="hear_us[]" value="2">
                                Job Center / Work Coach / DWP
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="hear_us[]" value="3">
                                Social Media
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="hear_us[]" value="4">
                                Friends / Family
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="hear_us[]" value="5">
                                FE college / training provider
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="hear_us[]" value="6">
                                THE National Careers Service
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="hear_us[]" value="7">
                                Gov.uk website
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="hear_us[]" value="8">
                                Other (e.g. search engine, local media press)
                            </label>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <hr>
    </div>
</div>
<div class="row">
    <div class="col-sm-8">
        <span class="btn btn-info" onclick="getSignature();">
            <img id="img_learner_sign" src="do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=20" style="border: 2px solid;border-radius: 15px; width: 100%;" />
            <input type="hidden" name="learner_sign" id="learner_sign" value="" />
        </span>
    </div>
    <div class="col-sm-4">
        <h2 class="content-max-width"><?php echo date('d/m/Y'); ?></h2>
    </div>
</div>