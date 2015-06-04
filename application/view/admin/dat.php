<div>
    <table class="table table-striped">
        <tr>
            <th style="width: 10%">Name</th><th style="width: 15%">Receive</th><th style="width: 15%">Send</th><th style="width: 15%">Delete</th>
        </tr>
        <?php foreach ($transactions as $transaction) { ?>
            <tr>
                <td><?php echo htmlspecialchars($transaction->name); ?></td>
                <?php if ($transaction->type == 'receive') { ?>
                <td><?php echo htmlspecialchars(-$transaction->amount); ?></td>
                <td></td>
                <?php } else { ?>
                <td></td>
                <td><?php echo htmlspecialchars($transaction->amount); ?></td>
                <?php } ?>
                <td>
                    <a href="<?php echo URL . 'admin/delete/' . $transaction->trans_id; ?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>