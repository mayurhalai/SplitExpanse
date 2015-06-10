<div ng-app="dashApp" ng-controller="billCtrl">
    <div style="width: 80%">
        <table class="table table-striped">
            <tr>
                <th>Id.</th><th>Date</th><th>Description</th><th>Amount</th><th>Bill Owner</th><th>Bill Image</th><th>Delete</th>
            </tr>
            <tr ng-repeat="bill in bills">
                <td>{{bill.trans_id}}</td>
                <td>{{bill.date}}</td>
                <td>{{bill.description}}</td>
                <td><span class="glyphicon glyphicon-euro"></span> {{bill.amount}}</td>
                <td>{{bill.name}}</td>
                <td><a target="_blank" href="<?php echo URL; ?>img/{{bill.trans_id}}.jpg" class="btn btn-info"><span class="glyphicon glyphicon-film"></span> Bill Image</a></td>
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