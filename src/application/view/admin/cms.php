<div>
    <table class="table-striped" style="width: 60%" align="center">
        <tr>
            <th>Name</th><th>Username</th><th>Balance</th><th>Common</th><th>Change</th><th>Delete</th>
        </tr>
        <?php foreach ($users as $user) { ?>
        <tr>
            <td><?php echo htmlspecialchars($user['name']); ?></td>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
            <td><?php echo htmlspecialchars($user['balance']); ?></td>
            <?php if ($user['common']) { ?>
            <td>Yes</td>
            <td><a href="<?php echo URL . 'admin/updatecommon/' . $user['username'] . '/0'; ?>" class="btn btn-danger">Unset</a></td>
            <?php } else { ?>
            <td>No</td>
            <td><a href="<?php echo URL . 'admin/updatecommon/' . $user['username'] . '/1'; ?>" class="btn btn-success">Set</a></td>
            <?php } ?>
            <td><a href="<?php echo URL . 'admin/deleteuser/' . $user['username']; ?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Delete</a></td>
        </tr>
        <?php } ?>
    </table>
</div>