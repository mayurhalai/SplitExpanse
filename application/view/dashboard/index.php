<div ng-app="dashApp" ng-controller="billCtrl">
    <div style="position: relative">
        <div class="btn-group btn-group-justified">
            <a href="" class="btn btn-warning">Your Bills</a>
            <a href="<?php echo URL; ?>dashboard/allbills" class="btn btn-warning">All Bills</a>
            <a href="<?php echo URL; ?>dashboard/allbalances" class="btn btn-warning">All Balances</a>
            <a href="<?php echo URL; ?>dashboard/edituser" class="btn btn-warning">Edit Your Details</a>
            <p class="btn btn-warning" ng-click="retrive()" title="Click to Refresh!">Your Balance: <span class="glyphicon glyphicon-euro"></span>{{balance}}</p>
        </div>
    </div>
    <a name="form"></a>
    <div style="position: absolute; right: 40px; width: 15%">
        <form action="<?php echo URL; ?>dashboard/add" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{id}}" />
            <div class="form-group">
                <label for="amount">Amount(<span class="glyphicon glyphicon-euro"></span>):</label>
                <input id="chk" type="text" class="inputbox" name="amount" ng-model="amount" required />
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="inputbox" name="description" ng-model="description"></textarea>
            </div>
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="text" class="form-control" name="date" ng-model="date" required />
            </div>
            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" name="image" />
            </div>
            <div class="form-group">
                <label for="users">Members (Who split Bill):</label><br />
                <?php
                foreach ($users as $user) {
                    echo '<input type="checkbox" name="members[]" value="' . $user->username . '" ' . ($user->common ? 'checked="checked"' : '') . ' />' . $user->name . '<br />';
                }
                ?>
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
            <button type="reset" class="btn btn-danger" ng-click="reset()">Reset</button>
        </form>    
    </div>
    <div style="width: 80%">
        <table class="table table-striped">
            <tr>
                <th>No.</th><th>Date</th><th>Description</th><th>Amount</th><th>Splitters</th><th>Bill Image</th><th>Edit</th><th>Delete</th>
            </tr>
            <tr ng-repeat="bill in bills">
                <td>{{$index + 1}}</td>
                <td>{{bill.date}}</td>
                <td>{{bill.description}}</td>
                <td><span class="glyphicon glyphicon-euro"></span> {{bill.amount}}</td>
                <td><button class="btn btn-info" ng-click="fetchSplit(bill.trans_id)"><span class="glyphicon glyphicon-user"></span> Bill Split</button></td>
                <td><a target="_blank" href="<?php echo URL; ?>img/{{bill.trans_id}}.jpg" class="btn btn-info"><span class="glyphicon glyphicon-film"></span> Bill Image</a></td>
                <td><a href="#form" class="btn btn-success" ng-click="setForm($index)"><span class="glyphicon glyphicon-pencil"></span> Edit</a></td>
                <td><a href="<?php echo URL; ?>dashboard/delete/{{bill.trans_id}}" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Delete</a></td>
            </tr>
        </table>
    </div>
</div>

<script>
var app = angular.module('dashApp', []);
var fbill, users, str = "";
app.controller('billCtrl', function ($scope, $http) {
    $http.get("<?php echo URL; ?>dashboard/fetchbills").success(function (response) {
        var fbill = $scope.bills = response.bills;
        $scope.setForm = function ($number) {
            $scope.id = fbill[$number].trans_id;
            $scope.amount = fbill[$number].amount;
            $scope.description = fbill[$number].description;
            $scope.date = fbill[$number].date;
        }
    });
    $scope.reset = function () {
        $scope.id = "";
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
    $scope.retrive = function() {
        $http.get("<?php echo URL; ?>dashboard/fetchbalance").success(function (response) {
            $scope.balance = response;
        });
    }
});

$('.form-control').datepicker({
    endDate: "date()",
    autoclose: true,
    todayHighlight: true
});

$('form').submit(function (event) {
    var check = false;
    var num = false;
    if ($.isNumeric($('#chk').val())) {
        num = true;
    }
    $('input[type="checkbox"]').each(function () {
        if ($(this).is(':checked')) {
            check = true;
        }
    });
    if (num == false) {
        alert('Amount should be a number');
    } else if (check == true) {
        return;
    } else {
        alert('Check at least one user!');
    }
    event.preventDefault();
});
</script>