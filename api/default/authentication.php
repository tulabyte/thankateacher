<?php 
//Admin Authentication
$app->get('/session', function() {
    $db = new DbHandler();
    $session = $db->getSession();
    echoResponse(200, $session);
});

$app->post('/adminLogin', function() use ($app) {
    require_once 'passwordHash.php';
    $r = json_decode($app->request->getBody());
    //var_dump($r->admin); die;
    verifyRequiredParams(array('email', 'password'),$r->admin);
    $response = array();
    $db = new DbHandler();
    $password = $db->purify($r->admin->password);
    $email = $db->purify($r->admin->email);
    $admin = $db->getOneRecord("SELECT * from admin WHERE admin_email='$email'");
    if ($admin != NULL) {
        //if(passwordHash::check_password($admin['admin_password'],$password)){
        if($admin['admin_password'] == $password && empty($admin['admin_is_disabled'])){
            
            // check if admin is disabled

            /*if(!empty($admin['admin_is_disabled'])) {
                //die($admin['admin_is_disabled']);
                $response['status'] = "error";
                $response['message'] = 'Login failed! Your account is DISABLED';
                echoResponse(200, $response);
            }*/

            $table_to_update = "admin";
            $columns_to_update = ['admin_last_login'=>date("Y-m-d h:i:s")];
            $where_clause = ['admin_id'=>$admin['admin_id']];
            $lastlogin = $db->updateInTable($table_to_update, $columns_to_update, $where_clause);

            $response['status'] = "success";
            $response['message'] = 'Logged in successfully. Redirecting...';
            $response['tat_name'] = $admin['admin_name'];
            $response['tat_id'] = $admin['admin_id'];
            $response['tat_email'] = $admin['admin_email'];
            $response['tat_date_created'] = $admin['admin_date_created'];
            if (!isset($_SESSION)) {
                session_start();
            }
            $response['sid'] = session_id();
            $_SESSION['tat_id'] = $admin['admin_id'];
            $_SESSION['tat_email'] = $admin['admin_email'];
            $_SESSION['tat_name'] = $admin['admin_name'];
            $_SESSION['tat_type'] = $admin['admin_level'];
            $_SESSION['tat_is_admin'] = true;

            //log action
            $log_details = "Logged In: Successful";
            $db->logAction($log_details);

        } else {
            $response['status'] = "error";
            $response['message'] = 'Login failed! Access Denied.';
            // echoResponse(201, $response);
        }
    } else {
            $response['status'] = "error";
            $response['message'] = 'No such admin is registered!';
            // echoResponse(201, $response);
        }
    
    echoResponse(200, $response);
});

$app->get('/adminResetPassword', function() use ($app) {
    $response = array();

    $db = new DbHandler();
    $response['email_sent'] = $email = $db->purify($app->request->get('email'));

    
    $user = $db->getOneRecord("SELECT * FROM admin WHERE admin_email='$email' AND admin_is_disabled IS NULL");
    if($user) {
        //found active user, generate new password
        $response['pass_generated'] = $newPass = $db->randomPassword();

        //update the new password in db
        $table_to_update = "admin";
        $columns_to_update = ['admin_password'=>$newPass];
        $where_clause = ['admin_id'=>$user['admin_id']];
        $affected_rows = $db->updateInTable($table_to_update, $columns_to_update, $where_clause);

        if($affected_rows > 0) {
            //send new password to user
            $swiftmailer = new mySwiftMailer();
            $subject = "Login Details RESET on Thank A Teacher";
            $body = "<p>Dear ".$user['admin_name'].",</p>
    <p>You requested an Password Reset on Thank A Teacher. Your request has been completed.</p>
    <p>Your new Password is ".$newPass."</p>
    <p>Thank you for using Thank A Teacher.</p>
    <p><br><strong>Thank A Teacher App</strong></p>";
            $swiftmailer->sendmail('info@nigerianteachingawards.org', 'Thank A Teacher', [$user['admin_email']], $subject, $body);

            //return response
            $response['status'] = "success";
            $response["message"] = "Password Reset successfully! Please check your email to retrieve the new password.";
            echoResponse(200, $response);
        } else {
            $response['status'] = "error";
            $response["message"] = "ERROR: Something went wrong while trying to reset your password. Please try again or contact Administrator!";
            echoResponse(201, $response);
        }
    } else {
        $response['status'] = "error";
        $response["message"] = "ERROR: The email you supplied is NOT associated with any active user account!";
        echoResponse(201, $response);
    }
});

$app->post('/changeAdminPassword', function() use ($app) {
    $response = array();
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('old', 'new'),$r->password);
    //require_once 'passwordHash.php';
    $db = new DbHandler();
    $old_pass = $db->purify($r->password->old);
    $new_pass = $db->purify($r->password->new);
    $session = $db->getSession();
    $admin_id = $session['tat_id'];
    //check if old password is correct
    $isPasswordCorrect = $db->getOneRecord("SELECT 1 FROM admin WHERE admin_id='$admin_id' AND admin_password='$old_pass'");

    if($isPasswordCorrect){
        //$r->user->password = passwordHash::hash($password);
        //password is correct

        //update with new password
        $table_to_update = "admin";
        $columns_to_update = ['admin_password'=>$new_pass];
        $where_clause = ['admin_id'=>$admin_id];

        $result = $db->updateInTable($table_to_update, $columns_to_update, $where_clause);

        if ($result > 0) {
            //log action
            $log_details = "Changed Password";
            $db->logAction($log_details);

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

/*$app->post('/signUp', function() use ($app) {
    $response = array();
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('email', 'phone', 'password'),$r->user);
    require_once 'passwordHash.php';
    $db = new DbHandler();
    $phone = $db->purify($r->user->phone);
    $email = $db->purify($r->user->email);
    $password = $db->purify($r->user->password);
    $isUserExists = $db->getOneRecord("SELECT 1 FROM user WHERE user_phone='$phone' OR user_email='$email'");
    if(!$isUserExists){
        //$r->user->password = passwordHash::hash($password);
        $table_name = "user";
        $column_names = ['user_email','user_phone','user_password','user_date_created', 'user_reg_type'];
        $values = [$email, $phone, $password, date("Y-m-d"), 'DEFAULT'];
        $result = $db->insertToTable($values, $column_names, $table_name);

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
    } else {
        $response["status"] = "error";
        $response["message"] = "A user with the provided phone or email already exists!";
        echoResponse(201, $response);
    }
});*/

$app->post('/userLogin', function() use ($app) {
    require_once 'passwordHash.php';
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('email', 'password'),$r->user);
    $response = array();
    $db = new DbHandler();
    $password = $db->purify($r->user->password);
    $email = $db->purify($r->user->email);
    $user = $db->getOneRecord("SELECT * from user WHERE user_email='$email'");
    if ($user != NULL) {
        //if(passwordHash::check_password($user['user_password'],$password)){
        if($user['user_password'] == $password){
            
            /*//check if user is verified
            if($user['user_reg_status'] == 'PENDING') {
                $response['status'] = "error";
                $response['message'] = 'VERIFY';
                echoResponse(201, $response);
            }*/

            $table_to_update = "user";
            $last_login_date = date("Y-m-d h:i:s");
            $columns_to_update = ['user_last_login'=> $last_login_date];
            $where_clause = ['user_id'=>$user['user_id']];
            $lastlogin = $db->updateInTable($table_to_update, $columns_to_update, $where_clause);

            if (!isset($_SESSION)) {
                session_start();
            }
            $response['status'] = "success";
            $response['message'] = 'Logged in successfully. Redirecting...';
            $response['sid'] = session_id();
            $_SESSION['tat_name'] = $response['tat_name'] = $user['user_firstname'] ? $user['user_firstname'].' '. $user['user_surname'] : '' ;
            $_SESSION['tat_id'] = $response['tat_id'] = $user['user_id'];
            $_SESSION['tat_email'] = $response['tat_email'] = $user['user_email'];
            $_SESSION['tat_phone'] = $response['tat_phone'] = $user['user_phone'];
            $response['tat_date_created'] = $user['user_date_created'];
            $response['tat_type'] = $_SESSION['tat_type'] = $user['user_reg_type'];
            $response['tat_last_login'] = $last_login_date;
            $_SESSION['tat_is_admin'] = false;
        } else {
            $response['status'] = "error";
            $response['message'] = 'Login failed! Incorrect user credentials';
        }
    }else {
            $response['status'] = "error";
            $response['message'] = 'No such user is registered!';
        }
    
    echoResponse(200, $response);
});

$app->get('/userResetPassword', function() use ($app) {
    $response = array();

    $db = new DbHandler();
    $email = mysql_real_escape_string($app->request->get('email'));
    
    $user = $db->getOneRecord("SELECT * FROM user WHERE user_email='$email'");
    if($user) {
        //found user, generate new password
        $newPass = $db->randomPassword();

        //update the new password in db
        $table_to_update = "user";
        $columns_to_update = ['user_password'=>$newPass];
        $where_clause = ['user_id'=>$user['user_id']];
        $affected_rows = $db->updateInTable($table_to_update, $columns_to_update, $where_clause);

        if($affected_rows > 0) {
            //send new password to user
            $swiftmailer = new mySwiftMailer();
            $subject = "Login Details RESET on Thank A Teacher";
            $body = "<p>Dear ".$user['user_firstname'].",</p>
    <p>You requested a Password Reset on Thank A Teacher. Your request has been completed.</p>
    <p>Your new Password is <strong>".$newPass."</strong></p>
    <p>Thank you for using Thank A Teacher.</p>
    <p><br><strong>Thank A Teacher App</strong></p>";
            $swiftmailer->sendmail('info@fitc-ng.com', 'Thank A Teacher', [$user['user_email']], $subject, $body);

            //return response
            $response['status'] = "success";
            $response["message"] = "Password Reset successfully! Please check your email to retrieve the new password.";
            echoResponse(200, $response);
        } else {
            $response['status'] = "error";
            $response["message"] = "ERROR: Something went wrong while trying to reset your password. Please try again or contact Administrator!";
            echoResponse(201, $response);
        }
    } else {
        $response['status'] = "error";
        $response["message"] = "ERROR: The email you supplied is NOT associated with any user account!";
        echoResponse(201, $response);
    }
});

$app->post('/changeUserPassword', function() use ($app) {
    $response = array();
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('old', 'new'),$r->password);
    //require_once 'passwordHash.php';
    $db = new DbHandler();
    $old_pass = $db->purify($r->password->old);
    $new_pass = $db->purify($r->password->new);
    $session = $db->getSession();
    $user_id = $session['tat_id'];
    //check if old password is correct
    $isPasswordCorrect = $db->getOneRecord("SELECT 1 FROM user WHERE user_id='$user_id' AND user_password='$old_pass'");

    if($isPasswordCorrect){
        //$r->user->password = passwordHash::hash($password);
        //password is correct

        //update with new password
        $table_to_update = "user";
        $columns_to_update = ['user_password'=>$new_pass];
        $where_clause = ['user_id'=>$user_id];

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
    
    //log action
    $log_details = "Logged Out";
    $db->logAction($log_details);

    $session = $db->destroySession();
    $response["status"] = "success";
    $response["message"] = "Logged out successfully";
    echoResponse(200, $response);
});

$app->get('/userLogout', function() {
    $db = new DbHandler();
    
    $session = $db->destroySession();
    $response["status"] = "success";
    $response["message"] = "Logged out successfully";
    echoResponse(200, $response);
});

