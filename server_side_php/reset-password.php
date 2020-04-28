<?php

require("config.php");

if (isset($_POST['tag']) && $_POST['tag'] != '') {
    $tag = $_POST['tag'];
    $response = array("tag" => $tag, "error" => FALSE);

    $query = "SELECT * FROM users WHERE email = :email";
    
        $query_params = array(
            ':email' => $_POST['email']
        );
    
        try {
            $stmt   = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch (PDOException $ex) {

            $response["error"] = true;
            $response["message"] = $ex->getMessage();
            die(json_encode($response));
        
        }

        $success = false;

        $row = $stmt->fetch();

        $email = $_POST['email'];

    // Forgot Password
    if ($tag == 'forgot_pass') {

        $pass = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);            // Generate new password
        $newPassword = password_hash($pass, PASSWORD_DEFAULT);                                                          // Encrypted Password

        if ($row) {
            $stmt = $db->prepare("UPDATE users SET encrypted_password = :newPass WHERE email = :email");
            $stmt->bindparam(":newPass", $newPassword);
            $stmt->bindparam(":email",$email);
            $stmt->execute();
            $success = true;
        }
    
        if ($success == true) {
            $name = $row['name'];        
            $subject = "New Password Request";
            $message = "Hello $name.\n\nWe received a request to change your password on 
            Android Learning\n\nYour new password:\n\n $pass\n\nRegards,\nAndroid Learning.";
            $from = "support@androidlearning.in";
            $headers = "From:" . $from;
        
            // Uncomment below line if you are using online server.
            //mail($email,$subject,$message,$headers);
        
            $response["error"] = false;
            $response["message"] = "A new password has been sent to your e-mail address.";
            $response["mail"] = $message;
            die(json_encode($response));
        } else {
            $response["error"] = true;
            $response["message"] = "Invalid Credentials!";
            die(json_encode($response));        
        }
    }

    // Change Password
    else if ($tag == 'change_pass') {

        $oldPassword = $_POST['old_password'];
        $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

        if ($row) {

            if (password_verify($oldPassword, $row['encrypted_password'])) {
                $stmt = $db->prepare("UPDATE users SET encrypted_password = :password WHERE email = :email");
                $stmt->bindparam(":password", $newPassword);
                $stmt->bindparam(":email",$email);
                $stmt->execute();
                $success = true;
            }
        }

        if ($success == true) {
        
            $response["error"] = false;
            $response["message"] = "Your Password has been changed!.";
            die(json_encode($response));
        } else {
            $response["error"] = true;
            $response["message"] = "Invalid Credentials!";
            die(json_encode($response));        
        }

    }

} else {
    echo json_encode(array("message" => "Method not supported!"));
}

?>
