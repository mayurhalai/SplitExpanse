<div>
    <table class="table table-striped" style="width: 80%" align="center">
        <tr>
            <th style="width: 15%">Name</th><th style="width: 15%">Username</th><th style="width: 30%">Email</th><th style="width: 15%">Approve</th><th style="width: 15%">Delete</th>
        </tr>
        <?php foreach ($users as $user) { ?>
            <tr>
                <td><?php echo htmlspecialchars($user->name); ?></td>
                <td><?php echo htmlspecialchars($user->username); ?></td>
                <td><?php echo htmlspecialchars($user->email); ?></td>
                <td><a href="<?php echo URL . 'admin/approveuser/' . $user->username; ?>" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span> Approve</a></td>
                <td><a href="<?php echo URL . 'admin/deleteuser/' . $user->username; ?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Delete</a></td>
            </tr>
        <?php } ?>
    </table>
</div>