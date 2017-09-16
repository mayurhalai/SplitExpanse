<div>
    <table class="table-striped" style="width: 50%" align="center">
        <tr>
            <th>Name</th><th>Username</th><th>Change Password</th><th>Delete</th>
        </tr>
        <?php foreach ($users as $user) { ?>
            <?php if ($user->forgot) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($user->name); ?></td>
                    <td><?php echo htmlspecialchars($user->username); ?></td>
                    <td>
                        <form class="form-inline" action="<?php echo URL; ?>admin/changepassword" method="post">
                            <input type="hidden" name="username" value="<?php echo $user->username; ?>" />
                            <input type="text" class="form-control" name="password" required />
                            <button type="submit" class="btn btn-success">Set Password</button>
                        </form>
                    </td>
                    <td><a href="<?php echo URL . 'admin/deletefpr/' . $user->username ;?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Delete</a></td>
                <?php } ?>
            </tr>
        <?php } ?>
    </table>
</div>