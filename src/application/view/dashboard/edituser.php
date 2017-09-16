<div ng-app="myApp" ng-controller="validateForm">
    <div style="position: relative">
        <div class="btn-group btn-group-justified">
            <a href="<?php echo URL; ?>dashboard" class="btn btn-warning">Your Bills</a>
            <a href="<?php echo URL; ?>dashboard/allbills" class="btn btn-warning">All Bills</a>
            <a href="<?php echo URL; ?>dashboard/allbalances" class="btn btn-warning">All Balances</a>
            <a href="" class="btn btn-warning">Edit Your Details</a>
            <p class="btn btn-warning" ng-click="retrive()" title="Click to Refresh!">Your Balance: <span class="glyphicon glyphicon-euro"></span>{{balance}}</p>
        </div>
    </div>
    <div class="container" style="position: relative; top:100px;  font-family: Arial; color: #2E8AE6">
        <form name="myForm" action="<?php echo URL; ?>dashboard/edituserdetail" method="post">
            <table align="center">
                <tr>
                    <td style="text-align: left">Name: </td>
                    <td><input class="inputbox" type="text" name="firstname" placeholder="First" value="<?php echo $name[0]; ?>" required /></td>
                    <td><input class="inputbox" type="text" name="lastname" placeholder="Last" value="<?php echo $name[1]; ?>" required /></td>
                </tr>
                <tr>
                    <td style="text-align: left">Old Password: </td>
                    <td><input class="inputbox" type="password" name="oldpass" placeholder="Old Password" ng-model="user" ng-keyup="checkUser()" required /></td>
                    <td><span style="color: red" ng-show="!UserOk"><span class="glyphicon glyphicon-remove"></span> Incorrec Password</span>
                        <span style="color: green" ng-show="UserOk"><span class="glyphicon glyphicon-ok"></span> Correct Password</span>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left">Password: </td>
                    <td><input class="inputbox" type="password" name="password" placeholder="Password" ng-model="pass" required /></td>
                </tr>
                <tr>
                    <td style="text-align: left">Re-type Password: </td>
                    <td><input class="inputbox" type="password" name="repassword" placeholder="Re-type Password" ng-model="repass" ng-keyup="checkPass()" /></td>
                    <td><span style="color: red" ng-show="!PassOk"><span class="glyphicon glyphicon-remove"></span> Password does not match</span>
                        <span style="color: green" ng-show="PassOk"><span class="glyphicon glyphicon-ok"></span> Password match</span>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right"><button type="reset" class="btn btn-danger" ng-click="reset()">Reset Field</button></td>
                    <td style="text-align: right"><button type="submit" class="btn btn-success" ng-disabled="!PassOk || !UserOk">Update</button></td>
                </tr>
            </table>/
        </form>
    </div>
</div>
<script type="text/javascript">
    var app = angular.module('myApp', []);
    app.controller('validateForm', function ($scope, $http) {
        $scope.checkUser = function () {
            $http.get("<?php echo URL; ?>dashboard/checkpass/" + $scope.user).success(function (response) {
                var status = response;
                if ($scope.user != "" && status != "0")
                {
                    $scope.UserOk = true;
                }
                else
                {
                    $scope.UserOk = false;
                }
            });
        }

        $scope.checkPass = function () {
            if ($scope.pass == $scope.repass)
            {
                $scope.PassOk = true;
            }
            else
            {
                $scope.PassOk = false;
            }
        }

        $scope.reset = function () {
            $scope.UserOk = false;
            $scope.PassOk = false;
        }

        $http.get("<?php echo URL; ?>dashboard/fetchbalance").success(function (response) {
            $scope.balance = response;
        });
        $scope.retrive = function () {
            $http.get("<?php echo URL; ?>dashboard/fetchbalance").success(function (response) {
                $scope.balance = response;
            });
        }
    });
</script>