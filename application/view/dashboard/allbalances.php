<div ng-app="dashApp" ng-controller="billCtrl">
    <div style="position: relative">
        <div class="btn-group btn-group-justified">
            <a href="<?php echo URL; ?>dashboard" class="btn btn-warning">Your Bills</a>
            <a href="<?php echo URL; ?>dashboard/allbills" class="btn btn-warning">All Bills</a>
            <a href="" class="btn btn-warning">All Balances</a>
            <p class="btn btn-warning" ng-click="retrive()" title="Click to Refresh!">Your Balance: {{balance}}</p>
        </div>
    </div>
    <div style="position: relative">
        <div class="btn-group" ng-repeat="user in users">
            <p class="btn btn-info" ng-click="showBills(user.username)" title="Click to retrive Bills of this member!">{{user.name}} : {{user.balance}}</p>
        </div>
    </div>
    <div style="width: 80%" ng-show="allow">
        <table class="table table-striped">
            <tr>
                <th>No.</th><th>Date</th><th>Description</th><th>Amount</th><th>Splitters</th>
            </tr>
            <tr ng-repeat="bill in bills">
                <td>{{$index + 1}}</td>
                <td>{{bill.date}}</td>
                <td>{{bill.description}}</td>
                <td><span class="glyphicon glyphicon-euro"></span> {{bill.amount}}</td>
                <td><button class="btn btn-info" ng-click="fetchSplit(bill.trans_id)"><span class="glyphicon glyphicon-user"></span> Bill Split</button></td>
            </tr>
        </table>
    </div>
</div>
<script>
    var app = angular.module('dashApp', []);
    var users, str = "";
    app.controller('billCtrl', function ($scope, $http) {
        $http.get("<?php echo URL; ?>dashboard/fetchallbalance").success(function (response) {
            $scope.users = response.users;
        });
        $scope.showBills = function ($user) {
            $scope.allow = true;
            $http.get("<?php echo URL; ?>dashboard/fetchbillsbyuser/" + $user).success(function (response) {
                $scope.bills = response.bills;
            });
        }
        $scope.fetchSplit = function ($id) {
            $http.get("<?php echo URL; ?>dashboard/fetchsplit/" + $id).success(function (response) {
                var users = response.names;
                for (x in users) {
                    str += users[x].name + "\n";
                }
                alert(str);
                str = "";
            });
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