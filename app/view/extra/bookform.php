<div class="card">
    <div class="card-body">

        <!-- Multi Columns Form -->
        <form class="row g-3" method='get' enctype="multipart/form-data">
        <input type="hidden" name="lid" value="<?php echo $lid; ?>">
        <div class="col-md-12">
            <label for="inputName5" class="form-label">Lottery Name</label>
            <input type="text" class="form-control" name='name' value="<?php echo $lot['name']; ?>" readonly>
        </div>
        <div class="col-md-6">
            <label for="kser" class="form-label">K3 Serial</label>
            <input type="text" class="form-control" id="kser" name='kser' required>
        </div>
        <div class="col-md-6">
            <label for="bokno" class="form-label">Book No.</label>
            <input type="text" class="form-control" id="bokno" name='bokno' required>
        </div>
        <div class="col-md-6">
            <label for="beginSeries" class="form-label">Begin Series</label>
            <input type="text" class="form-control" id="beginSeries" name='begin' required>
        </div>
        <div class="col-md-6">
            <label for="endSeries" class="form-label">End Series</label>
            <input type="text" class="form-control" id="endSeries" name='end' required>
        </div>
        <div id="error-message" style="color: red; display: none;">Error: The difference between begin and end cannot exceed 50.</div>

        
        <div class="col-md-6">
            <label for="inputEmail5" class="form-label">Agent Whatsapp</label>
            <select class="form-control" name='wssp'>
                <option value='112244'>Select Agent</option>
                <?php Emp::makeAgentListp(); ?>
            </select>
        </div>
        <br>
        <div class="col-12">
            <div class="form-check">
            <input class="form-check-input" type="checkbox" id="gridCheck">
            <label class="form-check-label" for="gridCheck">
                I Have Re-checked Proceed
            </label>
            </div>
        </div><br>
        <div class="text-center" id='vis' style='display:none;'>
            <button type="submit" class="btn btn-primary" id='submit'>Submit</button>
        </div>
        </form><!-- End Multi Columns Form -->

    </div>
</div>

<script>
$(document).ready(function() {
    $('#kser, #bokno').on('input', function() {
        // Check if the input value is not entirely numeric
        if (!/^\d*$/.test($(this).val())) {
            alert("Please number dalo. number dalne bolai number dalo");
            $(this).val(''); // Reset the input value
        }
    });
});

$(document).ready(function() {
    $('#endSeries').on('keyup', function() {
        var begin = $('#beginSeries').val();
        var end = $('#endSeries').val();
        var beginNum = parseInt(begin);
        var endNum = parseInt(end);

        // Check if either input is not a digit or if they are empty
        if (!begin.match(/^\d+$/) || !end.match(/^\d+$/)) {
            alert('Error: Both fields must be numeric.');
            $('#beginSeries').val(''); // Reset the value of begin input
            $('#endSeries').val(''); // Reset the value of end input
        } else {
            $('#error-message').hide();

            // Check if the numerical values are entered and calculate the difference
            if (!isNaN(beginNum) && !isNaN(endNum)) {
                var diff = endNum - beginNum;
                if (diff > 50) {
                    alert('Error: The difference between begin and end cannot exceed 50.');
                    $('#beginSeries').val(''); // Reset the value of begin input
                    $('#endSeries').val(''); // Reset the value of end input
                } else {
                    $('#error-message').hide();
                }
            }
        }
    });
});
</script>