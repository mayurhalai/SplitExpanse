<div class="container" style="position: relative; top:100px;  font-family: Arial; color: #2E8AE6">
    <form ng-app="myApp" ng-controller="validateForm" name="myForm" action="<?php echo URL; ?>signup/register" method="post">
    <table align="center">
        <tr>
            <td style="text-align: left">Name: </td>
            <td><input class="inputbox" type="text" name="firstname" placeholder="First" required /></td>
            <td><input class="inputbox" type="text" name="lastname" placeholder="Last" required /></td>
        </tr>
        <tr>
            <td style="text-align: left">Username: </td>
            <td><input class="inputbox" type="text" name="username" placeholder="Username" ng-model="user" ng-keyup="checkUser()" required /></td>
            <td><span style="color: red" id="username-error" ng-show="!UserOk"><span class="glyphicon glyphicon-remove"></span> Invalid Username</span>
                <span style="color: green" id="username-ok" ng-show="UserOk"><span class="glyphicon glyphicon-ok"></span> Username alright</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: left">Password: </td>
            <td><input class="inputbox" type="password" name="password" placeholder="Password" ng-model="pass" required /></td>
        </tr>
        <tr>
            <td style="text-align: left">Re-type Password: </td>
            <td><input class="inputbox" type="password" name="repassword" placeholder="Re-type Password" ng-model="repass" ng-keyup="checkPass()" /></td>
            <td><span style="color: red" id="password-error" ng-show="!PassOk"><span class="glyphicon glyphicon-remove"></span> Password does not match</span>
                <span style="color: green" id="password-ok" ng-show="PassOk"><span class="glyphicon glyphicon-ok"></span> Password match</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: left">Email: </td>
            <td><input class="inputbox" type="email" name="email" placeholder="xyz@abc.com" ng-model="email" required /></td>
            <td><span style="color: red" ng-show="myForm.email.$dirty && myForm.email.$error.email"><span class="glyphicon glyphicon-remove"> Invalid Email</span></td>
        </tr>
        <tr>
            <td style="text-align: right"><button type="reset" id="reset" class="btn btn-danger" ng-click="reset()">Reset Field</button></td>
            <td style="text-align: right"><button type="submit" id="register" class="btn btn-success" ng-disabled="!PassOk || !UserOk">Register</button></td>
        </tr>
    </table>
    </form>
</div>
<script type="text/javascript">
    var app = angular.module('myApp', []);
    app.controller('validateForm', function($scope, $http) {
        $scope.checkUser = function() {
            $http.get("<?php echo URL; ?>signup/checkuser/" + $scope.user).success(function(response) {
                var status = response;
                if ($scope.user != "" && status == "0")
                {
                    $scope.UserOk = true;
                }
                else
                {
                    $scope.UserOk = false;
                }
            });
        }
        
        $scope.checkPass = function() {
            if ($scope.pass == $scope.repass)
            {
                $scope.PassOk = true;
            }
            else
            {
                $scope.PassOk = false;
            }
        }
        
        $scope.reset = function() {
            $scope.UserOk = false;
            $scope.PassOk = false;
        }
    });
</script>