<div style="text-align: center; font-family: Arial; color: #2E8AE6">
    <div style="position: relative; top: 50px">
        <table align="center">
            <tr>
                <td style="text-align: center; color: red" colspan="2"><p id="error">&nbsp;<?php echo $this->errormessage; ?></p></td>
            </tr>
            <form action="<?php echo URL; ?>" method="post">
                <tr>
                    <td style="text-align: left">Login: </td>
                    <td><input autofocus class="inputbox" type="text" name="username" placeholder="Type Username" /></td>
                </tr>
                <tr>
                    <td style="text-align: left">Password: </td>
                    <td><input class="inputbox" type="password" name="password" placeholder="Type Password" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: right"><button type="submit" class="button">Login</button></td>
                </tr>
            </form>
        </table>
    </div>
    <div style="position: relative; top: 50px">
        Not member? Please <button class="button" onclick="window.location.replace('<?php echo URL; ?>signup')">Sign Up</button><br />
        <div id="pass">If you Forgot Password, <button id="btn" class="button">Click Here</button></div>
        <div id="forgot">
            <form class="form-inline" action="<?php echo URL; ?>home/forgotpassword" method="post">
            <input type="text" class="form-control" name="username" placeholder="Username" required />
            <button type="submit" class="button">Submit</button>
        </form>
        </div>
    </div>
    <div style="position: relative; top: 60px">
        Also an Android App Available.<br />To download an app <a href="<?php echo URL; ?>app/SplitExpanse.apk" download>Click here</a>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#forgot').hide();
        $('#btn').click(function () {
            $('#pass').hide();
            $('#forgot').show();
        });
    });
</script>