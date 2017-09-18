<div>
    <table class="table table-striped">
        <tr>
            <th style="width: 10%">Name</th><th style="width: 10%">Balance</th><th style="width: 40%">Receive</th><th style="width: 40%">Send</th>
        </tr>
        <?php foreach ($users as $user) { ?>
            <tr>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['balance']); ?></td>
                <td>
                    <form class="form-inline" action="<?php echo URL; ?>admin/receive" method="post">
                        <input type="hidden" name="username" value="<?php echo $user['username']; ?>" />
                        <input type="hidden" name="balance" value="<?php echo $user['balance']; ?>" />
                        <input type="text" class="form-control" name="amount" required />
                        <button class="btn btn-success" type="submit">Receive</button>
                    </form>
                </td>
                <td>
                    <form class="form-inline" action="<?php echo URL; ?>admin/send" method="post">
                        <input type="hidden" name="username" value="<?php echo $user['username']; ?>" />
                        <input type="hidden" name="balance" value="<?php echo $user['balance']; ?>" />
                        <input type="text" class="form-control" name="amount" required />
                        <button class="btn btn-danger" type="submit">Send</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>