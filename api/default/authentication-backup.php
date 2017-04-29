<?php 
//Admin Authentication
$app->get('/session', function() {
    $db = new DbHandler();
    $session = $db->getSession();
    $response["uid"] = $session['uid'];
    $response["email"] = $session['email'];
    $response["name"] = $session['name'];
    $response["utype"] = $session['utype'];
    echoResponse(200, $session);
});

$app->post('/login', function() use ($app) {
    require_once 'passwordHash.php';
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('email', 'password'),$r->user);
    $response = array();
    $db = new DbHandler();
    $password = $r->user->password;
    $email = $r->user->email;
    $user = $db->getOneRecord("SELECT * from user WHERE user_email='$email'");
    if ($user != NULL) {
        //if(passwordHash::check_password($user['user_password'],$password)){
        if($user['user_password'] == $password){
            
            $table_to_update = "user";
            $columns_to_update = ['user_last_login'=>date("Y-m-d h:i:s")];
            $where_clause = ['user_id'=>$user['user_id']];
            $lastlogin = $db->updateInTable($table_to_update, $columns_to_update, $where_clause);

            $response['status'] = "success";
            $response['message'] = 'Logged in successfully. Redirecting...';
            $response['name'] = $user['user_firstname'].' '.$user['user_surname'];
            $response['uid'] = $user['user_id'];
            $response['email'] = $user['user_email'];
            $response['createdAt'] = $user['user_create_date'];
            if (!isset($_SESSION)) {
                session_start();
            }
            $response['sid'] = session_id();
            $_SESSION['uid'] = $user['user_id'];
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $user['user_firstname'].' '.$user['user_surname'];
            $_SESSION['utype'] = $user['user_type'];
        } else {
            $response['status'] = "error";
            $response['message'] = 'Login failed. Incorrect credentials';
        }
    }else {
            $response['status'] = "error";
            $response['message'] = 'No such user is registered';
        }
    
    echoResponse(200, $response);
});

$app->get('/resetPassword', function() use ($app) {
    $response = array();

    $db = new DbHandler();
    $email = mysql_real_escape_string($app->request->get('email'));
    
    $user = $db->getOneRecord("SELECT * FROM user WHERE user_email='$email'");
    if($user) {
        //found user, generate new password
        $fn = new functions();
        $newPass = $fn->randomPassword();

        //update the new password in db
        $table_to_update = "user";
        $columns_to_update = ['user_password'=>$newPass];
        $where_clause = ['user_id'=>$user['user_id']];
        $affected_rows = $db->updateInTable($table_to_update, $columns_to_update, $where_clause);

        if($affected_rows > 0) {
            //send new password to user
            $swiftmailer = new mySwiftMailer();
            $subject = "Login Details RESET on EyeOnSite";
            $body = "<p>Dear ".$user['user_firstname'].",</p>
    <p>You requested a Password Reset on EyeOnsite. Your request has been completed.</p>
    <p>Your new Password is ".$newPass."</p>
    <p>Thank you for using EyeOnSite.</p>
    <p><br><strong>EyeOnSite App</strong></p>";
            $swiftmailer->sendmail('info@amfacilities.com', 'EyeOnSite', [$user['user_email']], $subject, $body);

            //return response
            $response['status'] = "success";
            $response["message"] = "Password Reset successfully! Please check your email to retrieve the new password.";
            echoResponse(200, $response);
        } else {
            $response['status'] = "error";
            $response["message"] = "ERROR: Something went wrong while trying to update the password. Please try again or contact Administrator!";
            echoResponse(201, $response);
        }
    } else {
        $response['status'] = "error";
        $response["message"] = "ERROR: The email you supplied is NOT associated with any user account!";
        echoResponse(201, $response);
    }
});

/*$app->post('/signUp', function() use ($app) {
    $response['alert'] = 'Reached PHP';
    echoResponse(200, $response);

    $response = array();
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('email', 'name', 'password'),$r->user);
    require_once 'passwordHash.php';
    $db = new DbHandler();
    $phone = $r->user->phone;
    $name = $r->user->name;
    $email = $r->user->email;
    $address = $r->user->address;
    $password = $r->user->password;
    $isUserExists = $db->getOneRecord("select 1 from users_auth where phone='$phone' or email='$email'");
    if(!$isUserExists){
        $r->user->password = passwordHash::hash($password);
        $tabble_name = "users_auth";
        $column_names = array('phone', 'name', 'email', 'password', 'city', 'address');
        $result = $db->insertIntoTable($r->user, $column_names, $tabble_name);
        if ($result != NULL) {
            $response["status"] = "success";
            $response["message"] = "User account created successfully";
            $response["uid"] = $result;
            if (!isset($_SESSION)) {
                session_start();
            }
            $_SESSION['uid'] = $response["uid"];
            $_SESSION['phone'] = $phone;
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            echoResponse(200, $response);
        } else {
            $response["status"] = "error";
            $response["message"] = "Failed to create user. Please try again";
            echoResponse(201, $response);
        }            
    }else{
        $response["status"] = "error";
        $response["message"] = "An user with the provided phone or email exists!";
        echoResponse(201, $response);
    }
});*/
$app->post('/changePassword', function() use ($app) {
    $response = array();
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('old', 'new'),$r->password);
    //require_once 'passwordHash.php';
    $db = new DbHandler();
    $old_pass = $r->password->old;
    $new_pass = $r->password->new;
    $session = $db->getSession();
    $uid = $session['uid'];
    //check if old password is correct
    /*$check_SQL = sprintf("SELECT * FROM user WHERE user_id=%s AND user_password=%s LIMIT 1", GetSQLValueString($uid, "int"),
        GetSQLValueString($old_pass, "text"));
    $check_RS = mysql_query($check_SQL, $dbconn);
    $response['mysql_error'] = mysql_error($dbconn);
    $isPasswordCorrect = mysql_num_rows($check_RS);*/

    $isPasswordCorrect = $db->getOneRecord("SELECT 1 FROM user WHERE user_id='$uid' AND user_password='$old_pass'");

    if($isPasswordCorrect){
        //$r->user->password = passwordHash::hash($password);
        //password is correct

        //update with new password
        $table_to_update = "user";
        $columns_to_update = ['user_password'=>$new_pass];
        $where_clause = ['user_id'=>$uid];

        $result = $db->updateInTable($table_to_update, $columns_to_update, $where_clause);

        if ($result > 0) {
            $response["status"] = "success";
            $response["message"] = "Password Updated Successfully!";
            echoResponse(200, $response);
        } else {
            $response["status"] = "error";
            $response["message"] = "Failed to update password!";
            echoResponse(201, $response);
        }            
    }else{
        $response["status"] = "error";
        $response["message"] = "The old password you supplied is incorrect!!!";
        echoResponse(201, $response);
    }
});
$app->get('/logout', function() {
    $db = new DbHandler();
    $session = $db->destroySession();
    $response["status"] = "info";
    $response["message"] = "Logged out successfully";
    echoResponse(200, $response);
});

