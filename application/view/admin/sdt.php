<div ng-app="dashApp" ng-controller="billCtrl">
    <div style="width: 80%">
        <table class="table table-striped">
            <tr>
                <th style="width: 5%">Id.</th><th style="width: 15%">Date</th><th style="width: 35%">Description</th><th style="width: 10%">Amount</th><th style="width: 20%">Bill Owner</th><th style="width: 15%">Delete</th>
            </tr>
            <tr ng-repeat="bill in bills">
                <td>{{bill.trans_id}}</td>
                <td>{{bill.date}}</td>
                <td>{{bill.description}}</td>
                <td><span class="glyphicon glyphicon-euro"></span> {{bill.amount}}</td>
                <td>{{bill.name}}</td>
                <td><a href="<?php echo URL; ?>admin/safedelete/{{bill.trans_id}}" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Delete</a></td>
            </tr>
        </table>
    </div>
</div>

<script>
var app = angular.module('dashApp', []);
var fbill, users, str = "";
app.controller('billCtrl', function ($scope, $http) {
    $http.get("<?php echo URL; ?>admin/fetchallbills").success(function (response) {
        var fbill = $scope.bills = response.bills;
    });
});
</script>