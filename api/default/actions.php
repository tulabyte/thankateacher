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

        $response["message"] = "Message found!";
        $response['messages'] = $message;
        $response['status'] = "success";
       
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "ERROR: Message not found!";
        echoResponse(201, $response);
    }

});

//get single message (with card)
$app->get('/getMessageWithCard', function() use ($app) {

    $response = array();
    $db = new DbHandler();
    $msg_id = $db->purify($app->request->get('id'));

    $message = $db->getOneRecord("SELECT * FROM message LEFT JOIN card_design ON msg_card_id = card_id WHERE msg_id = '$msg_id'");
    
    if($message) {

        $response["message"] = "Message found!";
        $response['msg'] = $message;
        $response['status'] = "success";
       
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "ERROR: Message not found!";
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

$app->get('/getActiveMessageList', function() use ($app) {

    $response = array();

    $db = new DbHandler();
    
    $approved_messages = $db->getRecordset("SELECT * FROM message LEFT JOIN admin ON msg_approver_id = admin_id WHERE msg_status = 'APPROVED' AND msg_is_disabled IS NULL ");

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

        $swiftmailer = new mySwiftMailer(); //prepare mailer

        // sending a card?
        if($message['msg_card_id']) {
            
            // $cardmaker = new cardMaker();

            // send a card
            if (!filter_var($message['msg_teacher_email'], FILTER_VALIDATE_EMAIL) === false) {
                // valid teacher email
                $card = $db->getOneRecord("SELECT * FROM card_design WHERE card_id = '".$message['msg_card_id']."' ");
                $subject = $message['msg_sender_name']." is saying THANK YOU with a beautiful card!";
                $card_link = "http://thankateacher.nigerianteachingawards.org/my-card.html#/view/".$msg_id;
                // $body = $cardmaker->makeCard($message, $card);
                $body = "<p>Hello ".$message['msg_teacher_name'].",</p>
        <p>".$message['msg_sender_name']." has created a beautiful THANK YOU card for YOU on the Thank A Teacher platform.</p>
        <p>
        To see your card, please click on the following link:<br>
        <a href='$card_link'>$card_link</a>
        </p>
        <p>NOTE: please DO NOT REPLY to this email.</p>
        <p><br><strong>Thank A Teacher</strong></p>";
                $swiftmailer->sendmail('info@nigerianteachingawards.org', 'Nigerian Teaching Awards', [$message['msg_teacher_email']], $subject, $body);
            } else {
                $email_invalid = "The email you supplied (".$message['msg_teacher_email'].") is INVALID, therefore we couldn't deliver your card.";
            }
        } else {
            // just send the message
            // notify teacher if valid email was provided
            if (!filter_var($message['msg_teacher_email'], FILTER_VALIDATE_EMAIL) === false) {
                
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
    $pin = isset($r->message->pin)? $db->purify($r->message->pin) : NULL;
    $time_submitted = date("Y-m-d h:i:s");
    $status = 'PENDING';

    $card = $db->getOneRecord("SELECT * FROM card_design WHERE card_id='$card_id' ");

    $table_name = "message";
    $column_names = ['msg_teacher_name','msg_teacher_email','msg_teacher_phone','msg_sender_name','msg_sender_email', 'msg_sender_phone', 'msg_school', 'msg_state', 'msg_city', 'msg_class', 'msg_message', 'msg_time_submitted', 'msg_status', 'msg_card_id'];
    $values = [$teacher_name,$teacher_email,$teacher_phone,$sender_name,$sender_email, $sender_phone, $school, $state, $city, $class, $message, $time_submitted, $status, $card_id];

    $result = $db->insertToTable($values, $column_names, $table_name);

    if ($result != NULL) {
        $response["status"] = "success";
        $response["message"] = "Message created successfully";
        $response["msg_id"] = $result;

        if($pin) {
            // record pin usage
            $table_to_update = "pincode";
            $columns_to_update = ['pin_is_used'=>'1', 'pin_msg_id'=>$result, 'pin_date_used'=>$time_submitted];
            $where_clause = ['pin_code'=>$pin];
            $result2 = $db->updateInTable($table_to_update, $columns_to_update, $where_clause);    
        }
        
        // Get admin email addresses for notification
        $admin_emails = $db->getRecordset("SELECT admin_email FROM admin WHERE admin_is_disabled IS NULL");
        // build $to array
        $to = [];
        foreach ($admin_emails as $email) {
            $to[] = $email['admin_email'];
        }

        $cardline = ($card_id) ? "<p>NOTE: This message will be sent in the card <strong>(".$card['card_title'].")</strong> using PIN <strong>($pin)</strong></p>" : NULL;

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

// get active card designs
$app->get('/getActiveCardDesigns', function() use ($app) {
    $response = array();

    $db = new DbHandler();
    // $msg_id = $db->purify($app->request->get('id'));

    $cards = $db->getRecordset("SELECT * FROM card_design WHERE card_is_disabled IS NULL");

    if($cards) {
        $response['status'] = "success";
        $response['cards'] = $cards;
        $response["message"] = count($cards) . " card designs found!";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "No card found in database!";
        echoResponse(201, $response);
    }

});

// check Pin
$app->get('/checkPin', function() use ($app) {
    $response = array();

    $db = new DbHandler();
    $pin = $db->purify($app->request->get('pin'));
    
    $pincode = $db->getOneRecord("SELECT * FROM pincode WHERE pin_code = '$pin' AND pin_msg_id IS NULL AND pin_is_used IS NULL AND pin_is_disabled IS NULL");

    if($pincode) {
        //found pin, return success result
        $response['pincode'] = $pincode;
        $response['status'] = "success";
        $response["message"] = "Pin is Valid, Active and Unused!";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "ERROR: Pin is INVALID, DISABLED or Already USED";
        echoResponse(201, $response);
    }
});

// create card
$app->post('/createNewCard', function() use ($app) {
    
    $response = array();

    $r = json_decode($app->request->getBody());
    verifyRequiredParams(['card_title', 'card_description', 'card_value', 'card_themeColor', 'card_image'],$r->card);
    // var_dump($r); die;
    //require_once 'passwordHash.php';
    $db = new DbHandler();
    $card_title = $db->purify($r->card->card_title);
    $card_description = $db->purify($r->card->card_description);
    $card_value = $db->purify($r->card->card_value);
    $card_themeColor = $db->purify($r->card->card_themeColor);
    $card_image = ($r->card->card_image);
    //check if card already exists with same title
    $iscardExists = $db->getOneRecord("SELECT 1 FROM card_design WHERE card_title='$card_title'");
    if(!$iscardExists){
        //the title has not yet been used
        //$r->card->password = passwordHash::hash($password);
        $table_name = "card_design";
        $column_names = ['card_title', 'card_description', 'card_value', 'card_themeColor', 'card_image'];
        $values = [$card_title, $card_description, $card_value, $card_themeColor,$card_image,];

        $result = $db->insertToTable($values, $column_names, $table_name);

        if ($result != NULL) {
            $response["status"] = "success";
            $response["message"] = "card created successfully";
            $response["card_id"] = $result;

            //log action
/*            $log_details = "Created New card: $card_title (ID: $result)";
            $db->logAction($log_details);            
*/
            echoResponse(200, $response);
        } else {
            $response["status"] = "error";
            $response["message"] = "Failed to create card. Please try again";
            echoResponse(201, $response);
        }            
    }else{
        $response["status"] = "error";
        $response["message"] = "card with the provided title already exists, please try another!";
        echoResponse(201, $response);
    }
});

//get all cards
$app->get('/getAllCards', function() use ($app) {
    $response = array();

    $db = new DbHandler();
    
$cards = $db->getRecordset("SELECT card_id, card_title, card_description, card_image, card_themeColor, card_value, card_is_disabled, count(msg_card_id) as card_count
            FROM card_design 
            left join message
            on card_id = msg_card_id
            group by card_id");

  if($cards) {
        //found course, return success result

        $response['cards'] = $cards;
        $response['status'] = "success";
        $response["message"] = "Latest Cards Loaded!";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "Error Loading Cards!";
        echoResponse(201, $response);
    }
});

//getCardDetails
$app->get('/getCardDetails', function() use ($app) {
    $response = array();

    $db = new DbHandler();
    $card_id = $db->purify($app->request->get('id'));
    
$card = $db->getOneRecord("SELECT card_title, card_description, card_image, card_themeColor, card_value, card_align, count(msg_card_id) as card_count
            FROM card_design 
            left join message
            on card_id = msg_card_id
            group by card_id
            HAVING card_id = '$card_id' ");

$messages = $db->getRecordset("SELECT msg_sender_name, msg_sender_phone, msg_message, msg_teacher_name, msg_teacher_phone, msg_time_submitted
            FROM message 
            WHERE msg_card_id = '$card_id' ");

  if($card) {
        //found card, return success result

        $response['card'] = $card;
        $response['messages'] = $messages;
        $response['status'] = "success";
        $response["message"] = "Card Details Loaded!";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "Error Loading Cards!";
        echoResponse(201, $response);
    }
});

//getPinDetails
$app->get('/getPinDetails', function() use ($app) {
    $response = array();

    $db = new DbHandler();
    $pin_id = $db->purify($app->request->get('id'));
    
$pin = $db->getOneRecord("SELECT pin_id, pin_code, pin_date_generated, pin_value, pin_is_used, pin_msg_id, pin_date_used, pin_is_disabled, msg_message, msg_teacher_name, msg_teacher_phone, msg_sender_name, msg_sender_phone
            FROM pincode 
            left join message
            on msg_card_id = msg_id 
            WHERE pin_id = '$pin_id' ");
  if($pin) {
        //found pin, return success result

        $response['pin'] = $pin;
        $response['status'] = "success";
        $response["message"] = "Pin Details Loaded!";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "Error Loading Pin Details!";
        echoResponse(201, $response);
    }
});

$app->get('/getAllPins', function() use ($app) {
    $response = array();

    $db = new DbHandler();

$pin = $db->getRecordset("SELECT pin_id, pin_code, pin_date_generated, pin_value, pin_is_used, pin_msg_id, pin_date_used, pin_is_disabled
            FROM pincode ");    
$unused_pin = $db->getRecordset("SELECT pin_id, pin_code, pin_date_generated, pin_value, pin_is_used, pin_msg_id, pin_date_used, pin_is_disabled
            FROM pincode 
            WHERE pin_is_used IS NULL ");
$used_pin = $db->getRecordset("SELECT pin_id, pin_code, pin_date_generated, pin_value, pin_is_used, pin_msg_id, pin_date_used, pin_is_disabled
            FROM pincode 
            WHERE pin_is_used = '1' ");

  if($unused_pin || $used_pin) {
        $response['unused_pin'] = $unused_pin;
        $response['used_pin'] = $used_pin;
        $response['pin'] = $pin;
        $response['status'] = "success";
        $response["message"] = "Latest Pins Loaded!";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "Error Loading Pins!";
        echoResponse(201, $response);
    }
});

$app->get('/getLatestGenPins', function() use ($app) {
    $response = array();

    $db = new DbHandler();
    $pin_total = $db->purify($app->request->get('id'));
    
$latestPins = $db->getRecordset("SELECT pin_id, pin_code, pin_date_generated, pin_value, pin_is_used, pin_msg_id, pin_date_used, pin_is_disabled
            FROM pincode 
            WHERE pin_is_used IS NULL
            ORDER BY pin_id DESC
            LIMIT $pin_total");
  if($latestPins) {
        //found pin, return success result

        $response['pins'] = $latestPins;
        $response['status'] = "success";
        $response["message"] = "Pin Details Loaded!";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "Error Loading Pin Details!";
        echoResponse(201, $response);
    }
});

// create card
$app->post('/generateNewPin', function() use ($app) {
    
    $response = array();

    $r = json_decode($app->request->getBody());
    verifyRequiredParams(['pin_total', 'pin_value'],$r->pin);
    //require_once 'passwordHash.php';
    $db = new DbHandler();
    $pin_value = $db->purify($r->pin->pin_value);
    $pin_total = $db->purify($r->pin->pin_total);
    $pin_date_generated = date("Y-m-d");
    $latest_pins = array();
    //check if pincode table exist
    $isPincodeTableExists = $db->getOneRecord("SELECT 1 FROM pincode");

    if ($isPincodeTableExists) {
                $pins = $db->getRecordset("SELECT pin_code FROM pincode");
                    for ($i=0; $i < $pin_total ; $i++) { 
                        $newPin = $db->randomPin();
                        //extra check to ensure we dont have the same pin genrated in different row in the database
                        $pin_code = $db->checkPin($newPin, $pins);
                        $latest_pins[$i] = $pin_code;
                        $table_name = "pincode";
                        $column_names = ['pin_code', 'pin_date_generated', 'pin_value'];
                        $values = [$pin_code, $pin_date_generated, $pin_value];
                        $result = $db->insertToTable($values, $column_names, $table_name);
                   }
                    if ($result != NULL) {
                        $response["latest_pins"] = $latest_pins;
                        $response["status"] = "success";
                        $response["message"] = "Pin generated successfully";
                        echoResponse(200, $response);                                            
                                    
                    } else {
                        $response["status"] = "error";
                        $response["message"] = "Failed to generate pin. Please try again";
                        echoResponse(201, $response);
                    }            
    }else{
        $response["status"] = "error";
        $response["message"] = "Error, please try again!";
        echoResponse(201, $response);
    }
});

$app->get('/getAllVideos', function() use ($app) {
    $response = array();

    $db = new DbHandler();

$videos = $db->getRecordset("SELECT vid_id, vid_date, vid_embed_url,vid_student, vid_teacher, vid_school, vid_city
            FROM video ");    

  if($videos) {
        $response['videos'] = $videos;
        $response['status'] = "success";
        $response["message"] = "Latest Videos Loaded!";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "Error Loading Videos!";
        echoResponse(201, $response);
    }
});


$app->post('/editVideo', function() use ($app) {
    
    $response = array();

    $r = json_decode($app->request->getBody());
    verifyRequiredParams(['vid_id', 'vid_embed_url','vid_student', 'vid_teacher', 'vid_school', 'vid_city'],$r->video);
    //require_once 'passwordHash.php';
    $db = new DbHandler();
    $vid_embed_url = $db->purify($r->video->vid_embed_url);
    $vid_student = $db->purify($r->video->vid_student);
    $vid_teacher = $db->purify($r->video->vid_teacher);
    $vid_school = $db->purify($r->video->vid_school);
    $vid_school = $db->purify($r->video->vid_school);
    $vid_city = $db->purify($r->video->vid_city);
    $vid_id = $db->purify($r->video->vid_id);
    //check if card already exists with same title

    $isVideoExists = $db->getOneRecord("SELECT 1 FROM video WHERE vid_id='$vid_id'");
    if($isVideoExists){
    $table_to_update = "video";
    $columns_to_update = ['vid_embed_url'=>$vid_embed_url, 'vid_student'=>$vid_student, ' vid_teacher'=>$vid_teacher, 'vid_school'=>$vid_school, 'vid_city'=>$vid_city];
    $where_clause = ['vid_id'=>$vid_id];
    $result = $db->updateInTable($table_to_update, $columns_to_update, $where_clause);
        
        if ($result) {
            $response["status"] = "success";
            $response["message"] = "video updated successfully";
            echoResponse(200, $response);
        } else {
            $response["status"] = "error";
            $response["message"] = "Failed to update card. Please try again";
            echoResponse(201, $response);
        }            
    }else{
        $response["status"] = "error";
        $response["message"] = "card does not exists!";
        echoResponse(201, $response);
    }
});

//creating new video
$app->post('/createNewVideo', function() use ($app) {
    
    $response = array();
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(['vid_embed_url','vid_student', 'vid_teacher', 'vid_school', 'vid_city'],$r->video);
    //require_once 'passwordHash.php';
    $db = new DbHandler();
    $vid_embed_url = $db->purify($r->video->vid_embed_url);
    $vid_student = $db->purify($r->video->vid_student);
    $vid_teacher = $db->purify($r->video->vid_teacher);
    $vid_school = $db->purify($r->video->vid_school);
    $vid_school = $db->purify($r->video->vid_school);
    $vid_city = $db->purify($r->video->vid_city);
    $vid_date = date("Y-m-d h:i:s");
    $video_count = $db->getOneRecord("SELECT count(*) as vid_count FROM video");
    //dummy test
    $isVideoTableExists = $db->getOneRecord("SELECT 1 FROM video");
    if ($isVideoTableExists || !$isVideoTableExists) {
    $video_count = $db->getOneRecord("SELECT count(*) as vid_count FROM video");
                       if ($video_count[vid_count] >= 3) {
                        $response["status"] = "error";
                        $response["message"] = "Video directory full. Try updating an existing video";
                        echoResponse(201, $response);       
                     }else{
                        $table_name = "video";
                        $column_names = ['vid_date','vid_embed_url','vid_student', 'vid_teacher', 'vid_school', 'vid_city'];
                        $values = [$vid_date, $vid_embed_url, $vid_student, $vid_teacher, $vid_school, $vid_city];
                        $result = $db->insertToTable($values, $column_names, $table_name);  
                            if ($result != NULL) {
                                $response["status"] = "success";
                                $response["message"] = "Video Created Successfully";
                                echoResponse(200, $response);                                            
                                            
                            }else{
                                $response["status"] = "error";
                                $response["message"] = "Failed to create video. Please try again";
                                echoResponse(201, $response);
                            }
                             
                     }
  
    }else{
        $response["status"] = "error";
        $response["message"] = "Error, please try again!";
        echoResponse(201, $response);
    }
});



$app->get('/getVideoDetails', function() use ($app) {
    $response = array();

    $db = new DbHandler();
    $vid_id = $db->purify($app->request->get('id'));
    
$video = $db->getOneRecord("SELECT *
            FROM video 
            WHERE vid_id = '$vid_id' ");
  if($video) {
        //found video, return success result

        $response['video'] = $video;
        $response['status'] = "success";
        $response["message"] = "Video Details Loaded!";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response["message"] = "Error Loading Video Details!";
        echoResponse(201, $response);
    }
});