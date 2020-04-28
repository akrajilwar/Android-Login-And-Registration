<?php

    require("config.php");

    if (!empty($_POST)) {

        $response = array(
            "error" => FALSE
        );

        $query = " SELECT 1 FROM users WHERE email = :email";

        //now lets update what :user should be
        $query_params = array(
            ':email' => $_POST['email']
        );

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }

        catch (PDOException $ex) {

            $response["error"] = TRUE;
            $response["message"] = $ex->getMessage();
            die(json_encode($response));
        }

        $row = $stmt->fetch();

        if ($row) {

            $response["error"] = TRUE;
            $response["message"] = "I'm sorry, this email is already in use";
            die(json_encode($response));

        } else {

            $query = "INSERT INTO users ( `unique_id`, `name`, `email`, `encrypted_password`, `otp`, `created_at` ) 
            VALUES ( :uuid, :name, :email, :encrypted_password, :otp, NOW() )";

			$otp = rand(100000, 999999);
			$verified = 0;

            $query_params = array(
                ':uuid' => uniqid('', true),
                ':name' => $_POST['name'],
                ':email' => $_POST['email'],
                ':encrypted_password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                ':otp' => $otp
            );

            try {
                $stmt = $db->prepare($query);
                $result = $stmt->execute($query_params);
            }

            catch (PDOException $ex) {
                $response["error"] = TRUE;
                $response["message"] = $ex->getMessage();
                die(json_encode($response));
            }

            $name = $_POST['name'];
            $email = $_POST['email'];
            $subject = "Android Learning Email Verification";
            $message = "Hello $name,\n\nVerify that you own $email.\n\nYou may be asked to enter this confirmation code:\n\n$otp\n\nRegards,\nAndroid Learning.";
            $from = "support@androidlearning.com";
            $headers = "From:" . $from;

            // Uncomment this line if you are using online server.
            //mail($email,$subject,$message,$headers);

            $response["error"] = FALSE;
            $response["message"] = "Register successful!";
            echo json_encode($response);
        }

    } else {
        echo json_encode(array("message" => "Method not supported!"));
    }
