<div ng-app="dashApp" ng-controller="billCtrl">
    <a name="form"></a>
    <div style="position: absolute; right: 40px; width: 15%">
        <form action="<?php echo URL; ?>dashboard/add" method="post">
            <input type="hidden" name="id" value="{{id}}" />
            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="text" class="inputbox" name="amount" value="{{amount}}" required />
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="inputbox" name="description">{{description}}</textarea>
            </div>
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="text" class="form-control" name="date" value="{{date}}" required />
            </div>
            <div class="form-group">
                <label for="users">Members (Who split Bill):</label><br />
                <?php
                foreach ($users as $user) {
                    echo '<input type="checkbox" name="members[]" value="' . $user['username'] . '" ' . ($user['common'] ? 'checked="checked"' : '') . '" />' . $user['name'] . '<br />';
                }
                ?>
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>    
    </div>
    <div style="width: 80%">
        <table class="table table-striped">
            <tr>
                <th style="width: 5%">No.</th><th style="width: 15%">Date</th><th style="width: 50%">Description</th><th style="width: 10%">Amount</th><th style="width: 10%">Edit</th><th style="width: 10%">Delete</th>
            </tr>
            <tr ng-repeat="bill in bills">
                <td>{{$index + 1}}</td>
                <td>{{bill.date}}</td>
                <td>{{bill.description}}</td>
                <td>{{bill.amount}}</td>
                <td><a href="#form" class="btn btn-success" ng-click="setForm($index)"><span class="glyphicon glyphicon-pencil"></span>Edit</a></td>
                <td><a href="<?php echo URL; ?>dashboard/delete/{{bill.trans_id}}" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span>Delete</a></td>
            </tr>
        </table>
    </div>
</div>

<script>
    var app = angular.module('dashApp', []);
    var fbill;
    app.controller('billCtrl', function($scope, $http) {
        $http.get("<?php echo URL; ?>dashboard/fetchbills").success(function(response) {
            var fbill = $scope.bills = response.bills;
            $scope.setForm = function($number) {
                $scope.id = fbill[$number].trans_id;
                $scope.amount = fbill[$number].amount;
                $scope.description = fbill[$number].description;
                $scope.date = fbill[$number].date;
            }
        });
    });
    
    $('.form-control').datepicker({
        endDate: "date()",
        autoclose: true,
        todayHighlight: true
    });
    
    $('form').submit(function(event) {
        var check = false;
        $('input[type="checkbox"]').each(function() {
            if ($(this).is(':checked')) {
                check = true;
            }
        });
        if (check == true) 
            retutn;
        else
            alert('Check at least one user!');
            event.preventDefault();
    });
</script>