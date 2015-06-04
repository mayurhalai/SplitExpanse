<div style="position: relative; top:100px;  font-family: Arial; color: #2E8AE6">
    <table align="center">
        <tr>
            <td style="text-align: center; color: red" colspan="2"><p id="error">&nbsp;<?php echo $this->errormessage; ?></p></td>
        </tr>
        <form action="<?php echo URL; ?>admin" method="post">
        <tr>
            <td style="text-align: left">Admin Login: </td>
            <td><input autofocus class="inputbox" type="text" name="username" placeholder="Type Username" /></td>
        </tr>
        <tr>
            <td style="text-align: left">Admin Password: </td>
            <td><input class="inputbox" type="password" name="password" placeholder="Type Password" /></td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: right"><button type="submit" class="button">Login</button></td>
        </tr>
        </form>
    </table>
</div>