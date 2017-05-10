<?php 

/*create new stuff*/

//create admin
$app->post('/createAdmin', function() use ($app) {
    
    $response = array();

    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('admin_name','admin_email','admin_level','admin_password'),$r->admin);
    //require_once 'passwordHash.php';
    $db = new DbHandler();
    $admin_name = $db->purify($r->admin->admin_name);
    $admin_email = $db->purify($r->admin->admin_email);
    $admin_password = $db->purify($r->admin->admin_password);
    $admin_level = $db->purify($r->admin->admin_level);  
    $admin_date_created = date("Y-m-d");

    $isAdminExists = $db->getOneRecord("SELECT 1 FROM admin WHERE admin_email='$admin_email'");
    if(!$isAdminExists){
        //$r->admin->password = passwordHash::hash($password);
        $table_name = "admin";
        $column_names = ['admin_name','admin_email','admin_level','admin_password','admin_date_created'];
        $values = [$admin_name,$admin_email,$admin_level,$admin_password,$admin_date_created];

        $result = $db->insertToTable($values, $column_names, $table_name);

        if ($result != NULL) {
            $response["status"] = "success";
            $response["message"] = "Admin created successfully";
            $response["admin_id"] = $result;

            //send email notification to new admin
            $swiftmailer = new mySwiftMailer();
            $subject = "Your new Admin Account on FITC Training";
            $body = "<p>Dear $admin_name,</p>
    <p>An admin account has been created for you on FITC Training. You can login using the following details:</p>
    <p>
    URL: http://fta.fitc-ng.com<br>
    Email: $admin_email<br>
    Password: $admin_password
    </p>
    <p>You are advised to change your password to something more personal once you login.</p>
    <p>Thank you for using FITC Training.</p>
    <p>NOTE: please DO NOT REPLY to this email.</p>
    <p><br><strong>FITC Training App</strong></p>";
            $swiftmailer->sendmail('info@fitc-ng.com', 'FITC Training', [$admin_email], $subject, $body);

            //log action
            $log_details = "Created Admin: $admin_name (ID: $result)";
            $db->logAction($log_details);

            echoResponse(200, $response);
        } else {
            $response["status"] = "error";
            $response["message"] = "Failed to create admin. Please try again";
            echoResponse(201, $response);
        }            
    }else{
        $response["status"] = "error";
        //$response['message'] = $r->admin;
        $response["message"] = "Admin with the provided email already exists, please try another!";
        echoResponse(201, $response);
    }
});

//get single message
$app->get('/getMessageDetails', function() use ($app) {

    $response = array();
    $db = new DbHandler();
    $msg_id = $db->purify($app->request->get('id'));

    $message = $db->getOneRecord("SELECT * FROM message WHERE msg_id = '$msg_id'");
    
    if($message) {

        $response["message"] = "Messages loaded successfully!";
        $response['messages'] = $message;
        $response['status'] = "success";
       
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "error!!!";
        echoResponse(201, $response);
    }

});


// edit Message
$app->post('/updateMessage', function() use ($app) {
    
    $response = array();

    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('msg_id', 'msg_teacher_name','msg_teacher_phone','msg_teacher_email','msg_class', 'msg_message','msg_sender_name','msg_sender_phone','msg_sender_email'),$r->message);
    //require_once 'passwordHash.php';
    $db = new DbHandler();
    $message_id = $db->purify($r->message->msg_id);
    $message_name = $db->purify($r->message->msg_teacher_name);
    $message_email = $db->purify($r->message->msg_teacher_email);
    $message_phone = $db->purify($r->message->msg_teacher_phone);
    $message_msg_class = $db->purify($r->message->msg_class);
    $message_msg_message = $db->purify($r->message->msg_message);
    $message_sender_name = $db->purify($r->message->msg_sender_name);
    $message_sender_phone = $db->purify($r->message->msg_sender_phone);
    $message_sender_email = $db->purify($r->message->msg_sender_email); 

    $isMessageExists = $db->getOneRecord("SELECT 1 FROM message WHERE msg_id='$message_id'");
    if($isMessageExists){
        $table_to_update = "message";
        $columns_to_update = ['msg_teacher_name'=>$message_name,'msg_teacher_email'=>$message_email,'msg_teacher_phone'=>$message_phone,'msg_class'=>$message_msg_class, 'msg_message'=>$message_msg_message, 'msg_sender_name'=>$message_sender_name, 'msg_sender_phone'=>$message_sender_phone, 'msg_sender_email'=>$message_sender_email];
        $where_clause = ['msg_id'=>$message_id];

        $result = $db->updateInTable($table_to_update, $columns_to_update, $where_clause);

        if ($result > 0) {
/*            //log action
            $log_details = "Edited Message: $admin_name (ID: $admin_id)";
            $db->logAction($log_details);*/

            $response["status"] = "success";
            $response["message"] = "Message updated successfully";
            echoResponse(200, $response);
        } else {
            $response["status"] = "error";
            $response["message"] = "Failed to update message. Please try again";
            echoResponse(201, $response);
        }            
    }else{
        $response["status"] = "error";
        $response["message"] = "message does not exist!";
        echoResponse(201, $response);
    }
});


// edit admin
$app->post('/editAdmin', function() use ($app) {
    
    $response = array();

    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('admin_id', 'admin_name','admin_email','admin_level','admin_password'),$r->admin);
    //require_once 'passwordHash.php';
    $db = new DbHandler();
    $admin_id = $db->purify($r->admin->admin_id);
    $admin_name = $db->purify($r->admin->admin_name);
    $admin_email = $db->purify($r->admin->admin_email);
    $admin_password = $db->purify($r->admin->admin_password);
    $admin_level = $db->purify($r->admin->admin_level); 

    $isAdminExists = $db->getOneRecord("SELECT 1 FROM admin WHERE admin_id='$admin_id'");
    if($isAdminExists){
        //$r->admin->password = passwordHash::hash($password);
        $table_to_update = "admin";
        $columns_to_update = ['admin_name'=>$admin_name,'admin_email'=>$admin_email,'admin_level'=>$admin_level,'admin_password'=>$admin_password];
        $where_clause = ['admin_id'=>$admin_id];

        $result = $db->updateInTable($table_to_update, $columns_to_update, $where_clause);

        if ($result > 0) {
            //log action
            $log_details = "Edited Admin: $admin_name (ID: $admin_id)";
            $db->logAction($log_details);

            $response["status"] = "success";
            $response["message"] = "Admin updated successfully";
            echoResponse(200, $response);
        } else {
            $response["status"] = "error";
            $response["message"] = "Failed to update admin. Please try again";
            echoResponse(201, $response);
        }            
    }else{
        $response["status"] = "error";
        $response["message"] = "Admin does not exist!";
        echoResponse(201, $response);
    }
});

// update user profile
$app->post('/updateUserProfile', function() use ($app) {
    
    $response = array();

    $r = json_decode($app->request->getBody());
    verifyRequiredParams(['user_id','user_firstname','user_surname'],$r->user);
    //require_once 'passwordHash.php';
    $db = new DbHandler();
    $user_id = $db->purify($r->user->user_id);
    $user_firstname = $db->purify($r->user->user_firstname);
    $user_surname = $db->purify($r->user->user_surname);
    
    $isUserExists = $db->getOneRecord("SELECT 1 FROM user WHERE user_id='$user_id'");
    if($isUserExists){
        //$r->user->password = passwordHash::hash($password);
        $table_to_update = "user";
        $columns_to_update = ['user_firstname'=>$user_firstname,'user_surname'=>$user_surname];
        $where_clause = ['user_id'=>$user_id];

        $result = $db->updateInTable($table_to_update, $columns_to_update, $where_clause);

        if ($result > 0) {
            $response["status"] = "success";
            $response["message"] = "User updated successfully";
            echoResponse(200, $response);
        } else {
            $response["status"] = "error";
            $response["message"] = "Failed to update user. Please try again";
            echoResponse(201, $response);
        }            
    }else{
        $response["status"] = "error";
        //$response['message'] = $r->user;
        $response["message"] = "ERROR: User does not exist!";
        echoResponse(201, $response);
    }
});

//get admin
$app->get('/getAdmin', function() use ($app) {
    $response = array();

    $db = new DbHandler();
    $admin_id = $db->purify($app->request->get('id'));
    
    $admin = $db->getOneRecord("SELECT * FROM admin WHERE admin_id='$admin_id'");
    if($admin) {

        //found admin, return success result
        $response['admin'] = $admin;
        $response['status'] = "success";
        $response["message"] = "Admin Details Loaded!";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "Error loading admin!";
        echoResponse(201, $response);
    }

    // $response["admin_id"] = $admin_id;
    // echoResponse(200, $response);
});

/*Get list of items*/

// get admin list
$app->get('/getAdminList', function() use ($app) {
    $response = array();

    $db = new DbHandler();
    $session = $db->getSession();
    $admin_id = $session['tat_id'];
    
    $admins = $db->getRecordset("SELECT * FROM admin WHERE admin_id <> '$admin_id' ");
    if($admins) {
        //admins found

        $response['admins'] = $admins;
        $response['status'] = "success";
        $response["message"] = "Admins Found!";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "No admin found!";
        echoResponse(201, $response);
    }
});

// get admin logs
$app->get('/getAdminLogs', function() use ($app) {
    $response = array();

    $db = new DbHandler();
    
    $logs = $db->getRecordset("SELECT * FROM admin_log ORDER BY log_time DESC ");
    if($logs) {
        //logs found

        $response['logs'] = $logs;
        $response['status'] = "success";
        $response["message"] = "Admin Logs Found!";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "No admin logs found!";
        echoResponse(201, $response);
    }
});

/*Delete Stuff*/

// delete message
$app->get('/deleteMessage', function() use ($app) {
    $response = array();

    $db = new DbHandler();
    $msg_id = $db->purify($app->request->get('id'));
    
    //get message details
    $message = $db->getOneRecord("SELECT * FROM message WHERE msg_id = '$msg_id'");
    $table_name = 'message';
    $col_name = 'msg_id';
    $result = $db->deleteFromTable($table_name, $col_name, $msg_id);

    if($result > 0) {
        //msg deleted

        //log action
        $log_details = "Deleted Message From: ".$message['msg_sender_name']." ($msg_id)";
        $db->logAction($log_details);

        $response['status'] = "success";
        $response["message"] = "Message Deleted successfully!";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "Error deleting message!";
        echoResponse(201, $response);
    }

    // $response["msg_id"] = $msg_id;
    // echoResponse(200, $response);
});

// delete admin
$app->get('/deleteAdmin', function() use ($app) {
    $response = array();

    $db = new DbHandler();
    $admin_id = $db->purify($app->request->get('id'));

    //get admin details
    $admin = $db->getOneRecord("SELECT * FROM admin WHERE admin_id='$admin_id'");

    $table_name = 'admin';
    $col_name = 'admin_id';
    $result = $db->deleteFromTable($table_name, $col_name, $admin_id);

    if($result > 0) {
        //admin deleted

        //log action
        $log_details = "Deleted Admin: ".$admin['admin_name']." ($admin_id)";
        $db->logAction($log_details);

        $response['status'] = "success";
        $response["message"] = "Admin Deleted successfully!";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "Error deleting admin!";
        echoResponse(201, $response);
    }

    // $response["admin_id"] = $admin_id;
    // echoResponse(200, $response);
});

// disable admin
$app->get('/disableAdmin', function() use ($app) {
    $response = array();

    $db = new DbHandler();
    $admin_id = $db->purify($app->request->get('id'));

    //get admin details
    $admin = $db->getOneRecord("SELECT * FROM admin WHERE admin_id='$admin_id'");

    $table_to_update = "admin";
    $columns_to_update = ['admin_is_disabled'=>1];
    $where_clause = ['admin_id'=>$admin_id];

    $result = $db->updateInTable($table_to_update, $columns_to_update, $where_clause);

    if($result > 0) {
        //admin deleted

        //log action
        $log_details = "Disabled Admin: ".$admin['admin_name']." ($admin_id)";
        $db->logAction($log_details);

        $response['status'] = "success";
        $response["message"] = "Admin DISABLED successfully!";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "Error disabling admin!";
        echoResponse(201, $response);
    }

});

// enable admin
$app->get('/enableAdmin', function() use ($app) {
    $response = array();

    $db = new DbHandler();
    $admin_id = $db->purify($app->request->get('id'));

    //get admin details
    $admin = $db->getOneRecord("SELECT * FROM admin WHERE admin_id='$admin_id'");

    $table_to_update = "admin";
    $column_to_update = 'admin_is_disabled';
    $where_clause = ['admin_id'=>$admin_id];

    $result = $db->updateToNull($table_to_update, $column_to_update, $where_clause);

    if($result > 0) {
        //admin enabled

        //log action
        $log_details = "Enabled Admin: ".$admin['admin_name']." ($admin_id)";
        $db->logAction($log_details);

        $response['status'] = "success";
        $response["message"] = "Admin ENABLED successfully!";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "Error enabling admin!";
        echoResponse(201, $response);
    }

});

//delete file
$app->get('/deleteFile', function() use ($app) {
    $response = array();

    $db = new DbHandler();
    $filename = $db->purify($app->request->get('filename'));
    
    unlink('../../img/course-images/'.$filename);

    if(!file_exists('../../img/course-images/'.$filename)) {
        //user deleted
        $response['status'] = "success";
        $response["message"] = "File Deleted successfully!";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "Error deleting file!";
        echoResponse(201, $response);
    }

    // $response["user_id"] = $user_id;
    // echoResponse(200, $response);
});


$app->get('/test', function() use ($app) {
    $response = array();

    $db = new DbHandler();

    //test if this api is working. Return some params
    $response['param_submitted'] = $app->request->get('id');
    $response['owner'] = "Yemi Adetula";
    $response['time'] = date('Y-m-d h:i:s');
    $response['status'] = "success";
    $response["message"] = "API Working perfectly!";
    echoResponse(200, $response);
});

//get payment
$app->get('/getDashTrends', function() use ($app) {
    $response = array();

    $db = new DbHandler();
    $start_date = $db->purify($app->request->get('start_date'));
    $end_date = $db->purify($app->request->get('end_date'));

    // user trends
    $user_trends = $sub_trends = [];
    $currdate = $start_date;
    do {
        
        $user_counter = $db->getOneRecord("SELECT COUNT(*) AS user_count FROM user WHERE user_date_created = '$currdate'");
        $user_trends[] = $user_counter['user_count'];

        $sub_counter = $db->getOneRecord("SELECT COUNT(*) AS sub_count FROM subscription WHERE sub_date_started = '$currdate'");
        $sub_trends[] = $sub_counter['sub_count'];

        // next date
        $currdate = date("Y-m-d", strtotime($currdate) + 86400);

    } while(strtotime($currdate) <= strtotime($end_date));
    
    
    if($sub_trends && $user_trends) {
        //found payment, return success result
        $response['user_trends'] = $user_trends;
        $response['sub_trends'] = $sub_trends;
        $response['status'] = "success";
        $response["message"] = "Dashboard Trends Loaded successfully!";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "Error loading dashboard trends!";
        echoResponse(201, $response);
    }
});

$app->get('/getPendingMessageList', function() use ($app) {

    $response = array();
    $db = new DbHandler();

    $query = "SELECT * FROM message WHERE msg_status = 'PENDING' ORDER BY msg_time_submitted DESC ";
    $pending_messages = $db->getRecordset($query);

    if($pending_messages) {

        $response["message"] = "Messages loaded successfully!";
        $response['pending_messages'] = $pending_messages;
        $response['status'] = "success";
       
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "No message (awaiting approval) found !";
        echoResponse(201, $response);
    }

});

$app->get('/getApprovedMessageList', function() use ($app) {

    $response = array();

    $db = new DbHandler();
    
    $approved_messages = $db->getRecordset("SELECT * FROM message LEFT JOIN admin ON msg_approver_id = admin_id WHERE msg_status = 'APPROVED' ");

    if($approved_messages) {

        $response["message"] = "Messages loaded successfully!";
        $response['approved_messages'] = $approved_messages;
        $response['status'] = "success";
       
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "No approved message found !";
        echoResponse(201, $response);
    }

});

//pause subscription
$app->get('/approveMessage', function() use ($app) {
    $response = array();
    $db = new DbHandler();

    $msg_id = $db->purify($app->request->get('id'));
    $session = $db->getSession();

    // get the message details
    $message = $db->getOneRecord("SELECT * FROM message WHERE msg_id = '$msg_id'");

    // update subscription
    $table_to_update = "message";
    $columns_to_update = ['msg_status'=>'APPROVED', 'msg_time_approved'=> date("Y-m-d h:i:s"), 'msg_approver_id'=>$session['tat_id'] ];
    $where_clause = ['msg_id'=>$msg_id];
    $result = $db->updateInTable($table_to_update, $columns_to_update, $where_clause);
    
    if($result) {
        //log action
        $log_details = "Approved Message: (ID: $msg_id)";
        $db->logAction($log_details);

        // notify teacher if valid email was provided
        if (!filter_var($message['msg_teacher_email'], FILTER_VALIDATE_EMAIL) === false) {
            $swiftmailer = new mySwiftMailer();
            $subject = $message['msg_sender_name']." is saying THANK YOU!";
            $msg_link = "http://thankateacher.nigerianteachingawards.org/index.html#/view-message/".$msg_id;
            $body = "<p>Hello ".$message['msg_teacher_name'].",</p>
    <p>".$message['msg_sender_name']." has submitted a new THANK YOU message for YOU on the Thank A Teacher platform.</p>
    <p>
    To see your message, please click on the following link:<br>
    <a href='$msg_link'>$msg_link</a>
    </p>
    <p>NOTE: please DO NOT REPLY to this email.</p>
    <p><br><strong>Thank A Teacher</strong></p>";
            $swiftmailer->sendmail('info@nigerianteachingawards.org', 'Nigerian Teaching Awards', [$message['msg_teacher_email']], $subject, $body);            
        }

        // notify sender if valid email was provided
        if (!filter_var($message['msg_sender_email'], FILTER_VALIDATE_EMAIL) === false) {
            $swiftmailer = new mySwiftMailer();
            $subject = "Your THANK YOU MESSAGE to ".$message['msg_teacher_name']." has been APPROVED";
            $body = "<p>Hello ".$message['msg_sender_name'].",</p>
    <p>The THANK YOU message you submitted for ".$message['msg_teacher_name']." on the Thank A Teacher platform has been APPROVED.</p>
    <p>Thank you for using the Thank A Teacher platform.</p>
    <p>NOTE: please DO NOT REPLY to this email.</p>
    <p><br><strong>Thank A Teacher</strong></p>";
            $swiftmailer->sendmail('info@nigerianteachingawards.org', 'Nigerian Teaching Awards', [$message['msg_sender_email']], $subject, $body);            
        }
        
        //sub paused, return success
        $response['status'] = "success";
        $response["message"] = "Message approved successfully!";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "Error while attempting to approve message!";
        echoResponse(201, $response);
    }
});

// disable message
$app->get('/toggleItem', function() use ($app) {
    $response = array();

    $db = new DbHandler();
    $item_type = $db->purify($app->request->get('type'));
    $item_id = $db->purify($app->request->get('id'));
    $item_val = $db->purify($app->request->get('val'));
    

    switch ($item_type) {
        case 'admin':
            $prefix = 'admin';
            break;

        case 'message':
            $prefix = 'msg';
            break;
    }

    $table_to_update = $item_type;

    switch ($item_val) {
        case 'off':
            $columns_to_update = [$prefix.'_is_disabled'=>1];
            $where_clause = [$prefix.'_id'=>$item_id];
            $result = $db->updateInTable($table_to_update, $columns_to_update, $where_clause);
            $actioned = 'Disabled';   
        break;

        case 'on':
            $column_to_update = $prefix.'_is_disabled';
            $where_clause = [$prefix.'_id'=>$item_id];
            $result = $db->updateToNull($table_to_update, $column_to_update, $where_clause); 
            $actioned = 'Enabled';
        break;

    }

    if($result > 0) {

        //log action
        $log_details = "$actioned $item_type: ID ($item_id)";
        $db->logAction($log_details);

        $response['status'] = "success";
        $response["message"] = "$item_type $actioned successfully!";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "Error! $item_type NOT $actioned";
        echoResponse(201, $response);
    }

});



// getNewUsers

$app->get('/getNewUsers', function() use ($app) {
   
$response = array();
$db = new DbHandler();
$new_users = $db->getRecordset("SELECT  * FROM user ORDER BY user_date_created DESC LIMIT 5");
    if($new_users) {
        //found new users, return success result

        $response['new_users'] = $new_users;
        $response['status'] = "success";
        $response["message"] = "Newest users Loaded!";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "Error loading new users!";
        echoResponse(201, $response);
    }
});



//getDashStats

$app->get('/getDashStats', function() use ($app) {
    $response = array();

    $db = new DbHandler();
    
    $user_count = $db->getOneRecord("SELECT COUNT(user_id) as user_count
                                             FROM user ");

    $sub_count = $db->getOneRecord("SELECT COUNT(sub_user_id) as sub_count FROM subscription WHERE sub_status = 'ACTIVE'");

    $course_count = $db->getOneRecord("SELECT COUNT(course_id) as course_count
                                             FROM course ");

    $total_revenue = $db->getOneRecord("SELECT SUM(pay_amount) as total_revenue
                                             FROM payment WHERE pay_status = 'SUCCESSFUL'");

    $stats['user_count'] = $user_count['user_count'];
    $stats['sub_count'] = $sub_count['sub_count'];
    $stats['course_count'] = $course_count['course_count'];
    $stats['total_revenue'] = $total_revenue['total_revenue'] ? $total_revenue['total_revenue'] : 0;

    if($stats) {
        //found course, return success result

       $response['stats'] = $stats;
        $response['status'] = "success";
        $response["message"] = "Dashboard Stats Loaded!";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "Error loading stats!";
        echoResponse(201, $response);
    }
});


//getLatestSubs

$app->get('/getLatestSubs', function() use ($app) {
    $response = array();

    $db = new DbHandler();
    
$subs = $db->getRecordset("SELECT user_id, user_firstname, user_surname, course_title, course_id, sub_id, sub_date_started
             FROM subscription 
             LEFT JOIN user ON sub_user_id = user_id 
             LEFT JOIN course ON sub_course_id = course_id
             WHERE sub_status = 'ACTIVE' 
             ORDER BY sub_date_started DESC
             LIMIT 5 ");

  if($subs) {
        //found course, return success result

        $response['latest_subs'] = $subs;
        $response['status'] = "success";
        $response["message"] = "Latest subscriptions Loaded!";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "Error loading latest subscriptions!";
        echoResponse(201, $response);
    }
});


//getTopUsers

$app->get('/getTopUsers', function() use ($app) {
    $response = array();

    $db = new DbHandler();
    
$subs = $db->getRecordset("SELECT user_id, user_firstname, user_surname, COUNT(sub_user_id) AS sub_count 
            FROM subscription 
            LEFT JOIN user ON sub_user_id = user_id 
            WHERE sub_status = 'ACTIVE' OR sub_status = 'EXPIRED' 
            GROUP BY sub_user_id
            ORDER BY sub_count  
            DESC LIMIT 5 ");

  if($subs) {
        //found course, return success result

        $response['top_users'] = $subs;
        $response['status'] = "success";
        $response["message"] = "Top 5 Users Loaded!";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "Error Loading top users!";
        echoResponse(201, $response);
    }
});



//getTopCourses

$app->get('/getTopCourses', function() use ($app) {
    $response = array();

    $db = new DbHandler();
    
$subs = $db->getRecordset("SELECT course_id, course_title, COUNT(sub_user_id) AS sub_count 
            FROM subscription 
            LEFT JOIN course ON sub_course_id = course_id 
            WHERE sub_status = 'ACTIVE' OR sub_status = 'EXPIRED' 
            GROUP BY sub_course_id
            ORDER BY sub_count  
            DESC LIMIT 5 ");

  if($subs) {
        //found course, return success result

        $response['top_courses'] = $subs;
        $response['status'] = "success";
        $response["message"] = "Top 5 Courses Loaded!";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "Error Loading courses!";
        echoResponse(201, $response);
    }
});


//getNewPayments

$app->get('/getNewPayments', function() use ($app) {
    $response = array();

    $db = new DbHandler();
    
$subs = $db->getRecordset("SELECT user_firstname, user_surname, pay_id, pay_time_completed, pay_amount
            FROM payment
            LEFT JOIN user ON pay_user_id = user_id 
            WHERE pay_status = 'SUCCESSFUL' 
            ORDER BY pay_time_completed 
            DESC LIMIT 5 ");

  if($subs) {
        //found course, return success result

        $response['new_payments'] = $subs;
        $response['status'] = "success";
        $response["message"] = "Latest Payments Loaded!";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "Error Loading Payments!";
        echoResponse(201, $response);
    }
});

// create message
$app->post('/createMessage', function() use ($app) {
    
    $response = array();

    $r = json_decode($app->request->getBody());
    verifyRequiredParams(['teacher_name','sender_name','school','city', 'state', 'message'],$r->message);
    
    $db = new DbHandler();
    $teacher_name = $db->purify($r->message->teacher_name);
    $teacher_email = $r->message->teacher_email? $db->purify($r->message->teacher_email) : NULL;
    $teacher_phone = $r->message->teacher_phone? $db->purify($r->message->teacher_phone) : NULL;
    $sender_name = $db->purify($r->message->sender_name);
    $sender_email = $r->message->sender_email? $db->purify($r->message->sender_email) : NULL;
    $sender_phone = $r->message->sender_phone? $db->purify($r->message->sender_phone) : NULL;
    $school = $db->purify($r->message->school);
    $state = $db->purify($r->message->state);
    $city = $db->purify($r->message->city);
    $class = $r->message->class? $db->purify($r->message->class) : NULL;
    $message = $db->purify($r->message->message);
    $card_id = isset($r->message->msg_card_id)? $db->purify($r->message->msg_card_id) : NULL;
    $time_submitted = date("Y-m-d h:i:s");
    $status = 'PENDING';

    //$r->admin->password = passwordHash::hash($password);
    $table_name = "message";
    $column_names = ['msg_teacher_name','msg_teacher_email','msg_teacher_phone','msg_sender_name','msg_sender_email', 'msg_sender_phone', 'msg_school', 'msg_state', 'msg_city', 'msg_class', 'msg_message', 'msg_time_submitted', 'msg_status', 'msg_card_id'];
    $values = [$teacher_name,$teacher_email,$teacher_phone,$sender_name,$sender_email, $sender_phone, $school, $state, $city, $class, $message, $time_submitted, $status, $card_id];

    $result = $db->insertToTable($values, $column_names, $table_name);

    if ($result != NULL) {
        $response["status"] = "success";
        $response["message"] = "Message created successfully";
        $response["msg_id"] = $result;

        // Get admin email addresses for notification
        $admin_emails = $db->getRecordset("SELECT admin_email FROM admin WHERE admin_is_disabled IS NULL");
        // build $to array
        $to = [];
        foreach ($admin_emails as $email) {
            $to[] = $email['admin_email'];
        }

        $cardline = ($card_id) ? "<p>NOTE: This message will be sent in a card</p>" : NULL;

        //send email notification to admin
        $swiftmailer = new mySwiftMailer();
        $subject = "New Thank You message submitted!";
        $body = "<p>Hello,</p>
<p>A new message has been created on the Thank A Teacher application with the following details.</p>
<p>
Teacher's Name: $teacher_name <br>
Sender's Name: $sender_name <br>
School: $school <br>
City/State: $city/$state <br>
Message:
$message
</p>

$cardline

<p>This message is awaiting approval. Please login to the Admin Backend to approve this message.</p>
<p>NOTE: please DO NOT REPLY to this email.</p>
<p><br><strong>Thank A Teacher</strong></p>";
        $swiftmailer->sendmail('info@nigerianteachingawards.org', 'Nigerian Teaching Awards', $to, $subject, $body);

        // return response
        echoResponse(200, $response);
    } else {
        $response["status"] = "error";
        $response["message"] = "Failed to create message. Please try again";
        echoResponse(201, $response);
    }
});

$app->get('/getFeaturedMessages', function() use ($app) {

    $response = array();

    $db = new DbHandler();
    
    $featured_messages = $db->getRecordset("SELECT * FROM message WHERE msg_is_featured IS NOT NULL ORDER BY msg_time_submitted DESC");

    if($featured_messages) {

        $response["message"] = "Featured messages loaded successfully!";
        $response['featured_messages'] = $featured_messages;
        $response['status'] = "success";
       
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "No featured message found !";
        echoResponse(201, $response);
    }

});

// mark message as featured
$app->get('/markFeaturedMessage', function() use ($app) {
    $response = array();

    $db = new DbHandler();
    $msg_id = $db->purify($app->request->get('id'));

    // set the msg_is_featured column to 1 in db
    $table_to_update = "message";
    $columns_to_update = ['msg_is_featured'=>'1'];
    $where_clause = ['msg_id'=>$msg_id];
    $result = $db->updateInTable($table_to_update, $columns_to_update, $where_clause);

    if($result > 0) {
        //message removed

        //log action
        $log_details = "Marked Featured Message: ($msg_id)";
        $db->logAction($log_details);

        $response['status'] = "success";
        $response["message"] = "Message Featured successfully!";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "Error featuring message!";
        echoResponse(201, $response);
    }

});

// remove message from featured
$app->get('/removeFeaturedMessage', function() use ($app) {
    $response = array();

    $db = new DbHandler();
    $msg_id = $db->purify($app->request->get('id'));

    // set the msg_is_featured column to null in db
    $table_to_update = "message";
    $column_to_update = 'msg_is_featured';
    $where_clause = ['msg_id'=>$msg_id];
    $result = $db->updateToNull($table_to_update, $column_to_update, $where_clause);

    if($result > 0) {
        //message removed

        //log action
        $log_details = "Removed Featured Message: ($msg_id)";
        $db->logAction($log_details);

        $response['status'] = "success";
        $response["message"] = "Message Removed successfully!";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "Error removing message!";
        echoResponse(201, $response);
    }

});