<div>
    <form action="<?php echo URL; ?>dashboard/update" method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>" />
        <table style="position: relative;top: 100px" align="center">
            <tr>
                <td>Amount:</td>
                <td><input type="text" name="amount" class="inputbox" value="<?php echo htmlspecialchars($bill['amount'], ENT_QUOTES, 'UTF-8'); ?>" /></td>
            </tr>
            <tr>
                <td>Description:</td>
                <td><input type="text" name="description" class="inputbox" value="<?php echo htmlspecialchars($bill['description'], ENT_QUOTES, 'UTF-8'); ?>" /></td>
            </tr>
            <tr>
                <td>Date:</td>
                <td><input type="text" name="date" class="form-control" value="<?php echo htmlspecialchars($bill['date'], ENT_QUOTES, 'UTF-8'); ?>" /></td>
            </tr>
            <tr>
                <td><button onclick="window.location.replace('<?php echo URL; ?>dashboard')" class="btn btn-info"><span class="glyphicon glyphicon-arrow-left"></span> Back</button></td>
                <td><button type="submit" class="btn btn-success">Submit</button></td>
            </tr>
        </table>
    </form>
</div>

<script>
    $('.form-control').datepicker({
    endDate: "date()",
    autoclose: true,
    todayHighlight: true
});
</script>