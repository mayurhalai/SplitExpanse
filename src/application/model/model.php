<?php

class Model
{
    function __construct($db) {
        try {
            $this->db = $db;
        } catch (Exception $ex) {
            exit('Database connection could not established.');
        }
    }
    
    public function validateAdmin($user, $pass) {
        $sql = "SELECT * FROM admin WHERE username=:user AND password=:pass";
        $query = $this->db->prepare($sql);
        $parameters = array(':user' => $user, ':pass' => $pass);
        $query->execute($parameters);
        $query->fetch();
        $count = $query->rowcount();
        if($count > 0) {
            return TRUE;
        }
        return FALSE;
    }
    
    public function setForgotUser($username) {
        $sql = "UPDATE users SET forgot=1 WHERE username=:username";
        $query = $this->db->prepare($sql);
        $parameters = array(':username' => $username);
        $query->execute($parameters);
    }
    
    public function validateUser($user, $pass) {
        $sql = "SELECT * FROM users WHERE username=:user AND password=:pass AND approved=1";
        $query = $this->db->prepare($sql);
        $parameters = array(':user' => $user, ':pass' => $pass);
        $query->execute($parameters);
        $query->fetch();
        $count = $query->rowcount();
        if($count > 0) {
            return TRUE;
        }
        return FALSE;
    }

    public function retriveUserByCookie($c_value) {
        $sql = "SELECT username FROM users WHERE session=:c_value";
        $query = $this->db->prepare($sql);
        $parameters = array(':c_value' => $c_value);
        $query->execute($parameters);
        return $query->fetch();
    }
    
    public function checkUser($user) {
        $sql = "SELECT username FROM users WHERE username=:user";
        $query = $this->db->prepare($sql);
        $parameters = array(':user' => $user);
        $query->execute($parameters);
        $query->fetch();
        $count = $query->rowcount();
        return $count;
    }
    
    public function checkPass($pass) {
        $sql = "SELECT password FROM users WHERE password=:pass";
        $query = $this->db->prepare($sql);
        $parameters = array(':pass' => $pass);
        $query->execute($parameters);
        $query->fetch();
        $count = $query->rowcount();
        return $count;
    }
    
    public function fetchName($user) {
        $sql = "SELECT name FROM users WHERE username=:user";
        $query = $this->db->prepare($sql);
        $parameters = array(':user' => $user);
        $query->execute($parameters);
        return $query->fetch();
    }

    public function setUser($user) {
        $sessionValue = md5($user);
        $sql = "UPDATE users SET session=:sessionValue WHERE username=:user";
        $query = $this->db->prepare($sql);
        $parameters = array(':sessionValue' => $sessionValue, ':user' => $user);
        $query->execute($parameters);
    }

    public function unsetUser($user) {
        $sql = "UPDATE users SET session='' WHERE username=:user";
        $query = $this->db->prepare($sql);
        $parameters = array(':user' => $user);
        $query->execute($parameters);
    }
    
    public function registerUser($name, $username, $password, $email) {
        $sql = "INSERT INTO users (name, username, password, email, balance, common, forgot, approved) VALUES (:name, :username, :password, :email, 0, 0, 0, 0)";
        $query = $this->db->prepare($sql);
        $parameters = array(':name' => $name, ':username' => $username, ':password' => md5($password), ':email' => $email);
        $query->execute($parameters);
    }
    
    public function editUser($name, $username, $password) {
        $sql = "UPDATE users SET name=:name, password=:password WHERE username=:username";
        $query = $this->db->prepare($sql);
        $parameters = array(':name' => $name, ':username' => $username, ':password' => md5($password));
        $query->execute($parameters);
    }

    public function fetchBillsByUsers($user) {
        $sql = "SELECT trans_id, date, description, amount FROM transaction WHERE username=:user ORDER BY trans_id DESC";
        $query = $this->db->prepare($sql);
        $parameters = array(':user' => $user);
        $query->execute($parameters);
        return $query->fetchall();
    }

    public function editBill($id, $date, $description, $amount, $members) {
        //fetch old amount from transaction
        $sql = "SELECT amount, username FROM transaction WHERE trans_id=:id";
        $query = $this->db->prepare($sql);
        $parameters = array(':id'=> $id);
        $query->execute($parameters);
        $temp_amount = $query->fetch();
        
        //update user bill balance
        $sql = "SELECT balance FROM users WHERE username=:username";
        $query = $this->db->prepare($sql);
        $parameters = array(':username'=> $temp_amount->username);
        $query->execute($parameters);
        $old_balance = $query->fetch();
        
        $new_balance = $old_balance->balance - $temp_amount->amount + $amount;
        
        $sql = "UPDATE users SET balance=:new_balance WHERE username=:username";
        $query = $this->db->prepare($sql);
        $parameters = array(':new_balance' => $new_balance, ':username' => $temp_amount->username);
        $query->execute($parameters);
        
        //fetch users from split
        $sql = "SELECT username FROM split WHERE trans_id=:id";
        $query = $this->db->prepare($sql);
        $parameters = array(':id'=> $id);
        $query->execute($parameters);
        $temp_users = $query->fetchall();
        $num = $query->rowcount();
        
        //update transaction
        $sql = "UPDATE transaction SET date=:date, description=:description, amount=:amount WHERE trans_id=:id";
        $query = $this->db->prepare($sql);
        $parameters = array(':date' => $date, ':description' => $description, ':amount' => $amount, ':id' => $id);
        $query->execute($parameters);
        
        //delete users in split
        $sql = "DELETE FROM split WHERE trans_id=:id";
        $query = $this->db->prepare($sql);
        $parameters = array(':id'=> $id);
        $query->execute($parameters);
        
        //plus old amount from users as per old users
        $old_split = $temp_amount->amount/$num;
        foreach ($temp_users as $temp_user) {
            $sql = "SELECT balance FROM users WHERE username=:username";
            $query = $this->db->prepare($sql);
            $parameters = array(':username' => $temp_user->username);
            $query->execute($parameters);
            $temp_balance = $query->fetch();
            
            $balance = $temp_balance->balance + $old_split;
            $sql = "UPDATE users SET balance=:balance WHERE username=:username";
            $query = $this->db->prepare($sql);
            $parameters = array(':balance' => $balance, ':username' => $temp_user->username);
            $query->execute($parameters);
        }
        
        //minus new amount in users as per new users and insert users in split
        $num = count($members);
        $split = $amount/$num;
        foreach ($members as $member) {
            $sql = "SELECT balance FROM users WHERE username=:username";
            $query = $this->db->prepare($sql);
            $parameters = array(':username' => $member);
            $query->execute($parameters);
            $temp_balance = $query->fetch();
            
            $balance = $temp_balance->balance - $split;
            $sql = "UPDATE users SET balance=:balance WHERE username=:username";
            $query = $this->db->prepare($sql);
            $parameters = array(':balance' => $balance, ':username' => $member);
            $query->execute($parameters);
            
            $sql = "INSERT INTO split (trans_id, username) VALUES (:id, :username)";
            $query = $this->db->prepare($sql);
            $parameters = array(':id' => $id, ':username' => $member);
            $query->execute($parameters);
        }
    }
    
    public function editBillWithImage($id, $date, $description, $amount, $members, $image) {
        $targetdir = 'img/';
        $targetfile = $targetdir . $id;
        $imagetype = pathinfo($image['name'], PATHINFO_EXTENSION);
        $check = getimagesize($image['tmp_name']);
        $uploadOk = 1;
        if ($check == FALSE) {
            $uploadOk = 0;
        }
        if ($imagetype != 'jpg' && $imagetype != 'png' && $imagetype != 'jpeg' && $imagetype != 'gif') {
            $uploadOk = 0;
        }
        if ($uploadOk == 1) {
            move_uploaded_file($image['tmp_name'], $targetfile . '.jpg');
        }
        
        //fetch old amount from transaction
        $sql = "SELECT amount, username FROM transaction WHERE trans_id=:id";
        $query = $this->db->prepare($sql);
        $parameters = array(':id'=> $id);
        $query->execute($parameters);
        $temp_amount = $query->fetch();
        
        //update user bill balance
        $sql = "SELECT balance FROM users WHERE username=:username";
        $query = $this->db->prepare($sql);
        $parameters = array(':username'=> $temp_amount->username);
        $query->execute($parameters);
        $old_balance = $query->fetch();
        
        $new_balance = $old_balance->balance - $temp_amount->amount + $amount;
        
        $sql = "UPDATE users SET balance=:new_balance WHERE username=:username";
        $query = $this->db->prepare($sql);
        $parameters = array(':new_balance' => $new_balance, ':username' => $temp_amount->username);
        $query->execute($parameters);
        
        //fetch users from split
        $sql = "SELECT username FROM split WHERE trans_id=:id";
        $query = $this->db->prepare($sql);
        $parameters = array(':id'=> $id);
        $query->execute($parameters);
        $temp_users = $query->fetchall();
        $num = $query->rowcount();
        
        //update transaction
        $sql = "UPDATE transaction SET date=:date, description=:description, amount=:amount WHERE trans_id=:id";
        $query = $this->db->prepare($sql);
        $parameters = array(':date' => $date, ':description' => $description, ':amount' => $amount, ':id' => $id);
        $query->execute($parameters);
        
        //delete users in split
        $sql = "DELETE FROM split WHERE trans_id=:id";
        $query = $this->db->prepare($sql);
        $parameters = array(':id'=> $id);
        $query->execute($parameters);
        
        //plus old amount from users as per old users
        $old_split = $temp_amount->amount/$num;
        foreach ($temp_users as $temp_user) {
            $sql = "SELECT balance FROM users WHERE username=:username";
            $query = $this->db->prepare($sql);
            $parameters = array(':username' => $temp_user->username);
            $query->execute($parameters);
            $temp_balance = $query->fetch();
            
            $balance = $temp_balance->balance + $old_split;
            $sql = "UPDATE users SET balance=:balance WHERE username=:username";
            $query = $this->db->prepare($sql);
            $parameters = array(':balance' => $balance, ':username' => $temp_user->username);
            $query->execute($parameters);
        }
        
        //minus new amount in users as per new users and insert users in split
        $num = count($members);
        $split = $amount/$num;
        foreach ($members as $member) {
            $sql = "SELECT balance FROM users WHERE username=:username";
            $query = $this->db->prepare($sql);
            $parameters = array(':username' => $member);
            $query->execute($parameters);
            $temp_balance = $query->fetch();
            
            $balance = $temp_balance->balance - $split;
            $sql = "UPDATE users SET balance=:balance WHERE username=:username";
            $query = $this->db->prepare($sql);
            $parameters = array(':balance' => $balance, ':username' => $member);
            $query->execute($parameters);
            
            $sql = "INSERT INTO split (trans_id, username) VALUES (:id, :username)";
            $query = $this->db->prepare($sql);
            $parameters = array(':id' => $id, ':username' => $member);
            $query->execute($parameters);
        }
    }
    
    public function deleteBill($id) {
        $filename = 'img/' . $id  . '.jpg';
        unlink($filename);
        
        //fetch old amount from transaction
        $sql = "SELECT amount FROM transaction WHERE trans_id=:id";
        $query = $this->db->prepare($sql);
        $parameters = array(':id'=> $id);
        $query->execute($parameters);
        $temp_amount = $query->fetch();
        $row_count = $query->rowcount();
        if ($row_count>0) {
        
            //fetch users from split
            $sql = "SELECT username FROM split WHERE trans_id=:id";
            $query = $this->db->prepare($sql);
            $parameters = array(':id'=> $id);
            $query->execute($parameters);
            $temp_users = $query->fetchall();
            $num = $query->rowcount();

            //reduce bill amount from user balance 
            $sql = "SELECT balance, username, (SELECT amount FROM transaction WHERE trans_id=:id) as amount FROM users WHERE username=(SELECT username FROM transaction WHERE trans_id=:id)";
            $query = $this->db->prepare($sql);
            $parameters = array(':id' => $id);
            $query->execute($parameters);
            $temp = $query->fetch();

            $balance = $temp->balance - $temp->amount;
            $sql = "UPDATE users SET balance=:balance WHERE username=:username";
            $query = $this->db->prepare($sql);
            $parameters = array(':balance' => $balance, ':username' => $temp->username);
            $query->execute($parameters);

            //delete transaction
            $sql = "DELETE FROM transaction WHERE trans_id=:id";
            $query = $this->db->prepare($sql);
            $parameters = array(':id' => $id);
            $query->execute($parameters);

            //delete users in split
            $sql = "DELETE FROM split WHERE trans_id=:id";
            $query = $this->db->prepare($sql);
            $parameters = array(':id'=> $id);
            $query->execute($parameters);

            //plus old amount from users as per old users
            $split = $temp_amount->amount/$num;
            foreach ($temp_users as $temp_user) {
                $sql = "SELECT balance FROM users WHERE username=:username";
                $query = $this->db->prepare($sql);
                $parameters = array(':username' => $temp_user->username);
                $query->execute($parameters);
                $temp_balance = $query->fetch();

                $balance = $temp_balance->balance + $split;
                $sql = "UPDATE users SET balance=:balance WHERE username=:username";
                $query = $this->db->prepare($sql);
                $parameters = array(':balance' => $balance, ':username' => $temp_user->username);
                $query->execute($parameters);
            }
        }
    }
    
    public function addBill($date, $description, $amount, $user, $members) {
        //add record
        $sql = "INSERT INTO transaction (amount, description, date, username) VALUES (:amount, :description, :date, :user)";
        $query = $this->db->prepare($sql);
        $parameters = array(':amount' => $amount, ':description' => $description, ':date' => $date, ':user' => $user);
        $query->execute($parameters);
        //devide bill into members
        $num = count($members);
        $split = $amount/$num;
        foreach ($members as $member) {
            $sql = "SELECT balance FROM users WHERE username=:username";
            $query = $this->db->prepare($sql);
            $parameters = array(':username' => $member);
            $query->execute($parameters);
            $temp_balance = $query->fetch();
            
            $balance = $temp_balance->balance - $split;
            $sql = "UPDATE users SET balance=:balance WHERE username=:username";
            $query = $this->db->prepare($sql);
            $parameters = array(':balance' => $balance, ':username' => $member);
            $query->execute($parameters);
            
            $sql = "INSERT INTO split (trans_id, username) VALUES ((SELECT trans_id FROM transaction ORDER BY trans_id DESC LIMIT 1), :username)";
            $query = $this->db->prepare($sql);
            $parameters = array(':username' => $member);
            $query->execute($parameters);
        }
        //add bill amount to bill owner
        $sql = "SELECT balance FROM users WHERE username=:username";
        $query = $this->db->prepare($sql);
        $parameters = array(':username' => $user);
        $query->execute($parameters);
        $temp_balance = $query->fetch();
        
        $balance = $temp_balance->balance + $amount;
        $sql = "UPDATE users SET balance=:balance WHERE username=:username";
        $query = $this->db->prepare($sql);
        $parameters = array(':balance' => $balance, ':username' => $user);
        $query->execute($parameters);
    }
    
    public function addBillWithImage($date, $description, $amount, $user, $members, $image) {
        $sql = "INSERT INTO transaction (amount, description, date, username) VALUES (:amount, :description, :date, :user)";
        $query = $this->db->prepare($sql);
        $parameters = array(':amount' => $amount, ':description' => $description, ':date' => $date, ':user' => $user);
        $query->execute($parameters);
        
        $sql = "SELECT trans_id FROM transaction ORDER BY trans_id DESC LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute();
        $f_name = $query->fetch();
        
        $targetdir = 'img/';
        $targetfile = $targetdir . $f_name->trans_id;
        $imagetype = pathinfo($image['name'], PATHINFO_EXTENSION);
        $check = getimagesize($image['tmp_name']);
        $uploadOk = 1;
        if ($check == FALSE) {
            $uploadOk = 0;
        }
        if ($imagetype != 'jpg' && $imagetype != 'png' && $imagetype != 'jpeg' && $imagetype != 'gif') {
            $uploadOk = 0;
        }
        if ($uploadOk == 1) {
            move_uploaded_file($image['tmp_name'], $targetfile . '.jpg');
        }
        
        $num = count($members);
        $split = $amount/$num;
        foreach ($members as $member) {
            $sql = "SELECT balance FROM users WHERE username=:username";
            $query = $this->db->prepare($sql);
            $parameters = array(':username' => $member);
            $query->execute($parameters);
            $temp_balance = $query->fetch();
            
            $balance = $temp_balance->balance - $split;
            $sql = "UPDATE users SET balance=:balance WHERE username=:username";
            $query = $this->db->prepare($sql);
            $parameters = array(':balance' => $balance, ':username' => $member);
            $query->execute($parameters);
            
            $sql = "INSERT INTO split (trans_id, username) VALUES ((SELECT trans_id FROM transaction ORDER BY trans_id DESC LIMIT 1), :username)";
            $query = $this->db->prepare($sql);
            $parameters = array(':username' => $member);
            $query->execute($parameters);
        }
        
        $sql = "SELECT balance FROM users WHERE username=:username";
        $query = $this->db->prepare($sql);
        $parameters = array(':username' => $user);
        $query->execute($parameters);
        $temp_balance = $query->fetch();
        
        $balance = $temp_balance->balance + $amount;
        $sql = "UPDATE users SET balance=:balance WHERE username=:username";
        $query = $this->db->prepare($sql);
        $parameters = array(':balance' => $balance, ':username' => $user);
        $query->execute($parameters);
    }
    
    public function fetchUser() {
        $sql = "SELECT username, name, ROUND(balance,2) as balance, common, forgot FROM users WHERE approved=1";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchall();
    }
    
    public function retriveUser($username) {
        $sql = "SELECT name, password, email FROM users WHERE username=:username";
        $query = $this->db->prepare($sql);
        $parameters = array(':username' => $username);
        $query->execute($parameters);
        return $query->fetch();
    }

    public function fetchSplit($id) {
        $sql = "SELECT username, (SELECT name FROM users WHERE users.username=split.username) as name FROM split WHERE trans_id=:id";
        $query = $this->db->prepare($sql);
        $parameters = array(':id' => $id);
        $query->execute($parameters);
        return $query->fetchall();
        
    }
    
    public function fetchAllBills() {
        $sql = "SELECT trans_id, date, description, amount, (SELECT name FROM users WHERE users.username=transaction.username) as name FROM transaction ORDER BY transaction.trans_id DESC";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchall();
    }
    
    public function fetchBalance ($user) {
        $sql = "SELECT ROUND(balance,2) as balance FROM users WHERE username=:user";
        $query = $this->db->prepare($sql);
        $parameters = array(':user' => $user);
        $query->execute($parameters);
        return $query->fetch();
    }
    
    //for admin
    public function updateBalance($user, $balance, $type, $amount) {
        $sql = "UPDATE users SET balance=:balance WHERE username=:username";
        $query = $this->db->prepare($sql);
        $parameters = array(':balance' => $balance, ':username' => $user);
        $query->execute($parameters);
        
        $sql = "INSERT INTO admin_transaction (amount, type, username) VALUES (:amount, :type, :username)";
        $query = $this->db->prepare($sql);
        $parameters = array(':amount' => $amount, ':type' => $type, ':username' => $user);
        $query->execute($parameters);
    }
    
    public function fetchAdminTransaction() {
        $sql = "SELECT trans_id, amount, type, username, (SELECT name FROM users WHERE users.username=admin_transaction.username) as name FROM admin_transaction";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }
    
    public function deleteTransaction($id) {
        $sql = "SELECT amount, username, (SELECT balance FROM users WHERE users.username=admin_transaction.username) as balance FROM admin_transaction WHERE trans_id=:id";
        $query = $this->db->prepare($sql);
        $parameters = array(':id' => $id);
        $query->execute($parameters);
        $temp = $query->fetch();
        
        $balance = $temp->balance + $temp->amount;
        
        $sql = "UPDATE users SET balance=:balance WHERE username=:username";
        $query = $this->db->prepare($sql);
        $parameters = array(':balance' => $balance, ':username' => $temp->username);
        $query->execute($parameters);
        
        $sql = "DELETE FROM admin_transaction WHERE trans_id=:id";
        $query = $this->db->prepare($sql);
        $parameters = array(':id' => $id);
        $query->execute($parameters);
    }
    
    public function safeDeleteTransaction($id) {
        $sql = "DELETE FROM transaction WHERE trans_id<=:id";
        $query = $this->db->prepare($sql);
        $parameters = array(':id' => $id);
        $query->execute($parameters);
    }
    
    public function updateCommon($username, $common) {
        $sql = "UPDATE users SET common=:common WHERE username=:username";
        $query = $this->db->prepare($sql);
        $parameters = array(':common' => $common, ':username' => $username);
        $query->execute($parameters);
    }
    
    public function updatePassword($username, $password) {
        $sql = "UPDATE users SET password=:password, forgot=0 WHERE username=:username";
        $query = $this->db->prepare($sql);
        $parameters = array(':password' => $password, ':username' => $username);
        $query->execute($parameters);
    }
    
    public function unsetFpr($username) {
        $sql = "UPDATE users SET forgot=0 WHERE username=:username";
        $query = $this->db->prepare($sql);
        $parameters = array(':username' => $username);
        $query->execute($parameters);
    }
    
    public function fetchUnapproved() {
        $sql = "SELECT username, name, email FROM users WHERE approved=0";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchall();
    }
    
    public function approveUser($username) {
        $sql = "UPDATE users SET approved=1 WHERE username=:username";
        $query = $this->db->prepare($sql);
        $parameters = array(':username' => $username);
        $query->execute($parameters);
    }
    
    public function deleteUser($username) {
        $sql = "DELETE FROM users WHERE username=:username";
        $query = $this->db->prepare($sql);
        $parameters = array(':username' => $username);
        $query->execute($parameters);
    }
    
    //For Mobile
    public function setMobileUser($token, $username) {
        $sql = "UPDATE users SET token=:token WHERE username=:username";
        $query = $this->db->prepare($sql);
        $parameters = array(':token' => $token, ':username' => $username);
        $query->execute($parameters);
    }
    
    public function unsetMobileUser($username) {
        $sql = "UPDATE users SET token='none' WHERE username=:username";
        $query = $this->db->prepare($sql);
        $parameters = array(':username' => $username);
        $query->execute($parameters);
    }
    
    public function retriveMobileUser($token) {
        $sql = "SELECT username FROM users WHERE token=:token";
        $query = $this->db->prepare($sql);
        $parameters = array(':token' => $token);
        $query->execute($parameters);
        return $query->fetch();
    }
}
