<?php 
$returnMsg = ''; 
 
if(isset($_POST['submit'])){ 
    
	// Form fields validation check
    if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['phone'])){ 
         
        // reCAPTCHA checkbox validation
        if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){ 
            // Google reCAPTCHA API secret key 
            $secret_key = '6LdBJCYsAAAAADqiSYvSfzowdGkAJ7Woxgxk0vtu'; 
             
            // reCAPTCHA response verification
            $verify_captcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$_POST['g-recaptcha-response']); 
            
            // Decode reCAPTCHA response 
            $verify_response = json_decode($verify_captcha); 
             
            // Check if reCAPTCHA response returns success 
            if($verify_response->success){ 
                
                $name = $_POST['name']; 
                $email = $_POST['email']; 
                $phone = $_POST['phone'];
				$message = $_POST['content'];
             
                #email Gmail
				require_once('class.phpmailer.php');
				require_once('mail_config.php');
				

				$mailBody = "User Name: " . $name . "\n";
				$mailBody .= "User Email: " . $email . "\n";
				$mailBody .= "Phone: " . $phone . "\n";
				$mailBody .= "Message: " . $message . "\n";
				
				$mail = new PHPMailer(true); 

				$mail->IsSMTP();

                try {
                    $mail->SMTPDebug  = 0; // Dezactivat pentru utilizator
                    $mail->SMTPAuth   = true; 
                    $mail->SMTPSecure = "ssl";                 
                    $mail->Host       = "mail.rnecula.daw.ssmr.ro";
                    $mail->Port       = 465;
                    $mail->Username   = $username; // Din mail_config.php
                    $mail->Password   = $password; // Din mail_config.php

                    // --- MODIFICĂRILE CHEIE AICI ---

                    // 1. EXPEDITORUL fix: Trebuie să fie adresa serverului tău pentru a fi acceptat
                    $mail->SetFrom('rneculas@rnecula.daw.ssmr.ro', 'Fitness Contact - ' . $name); 

                    // 2. DESTINATARUL fix: Unde vrei să primești tu mesajele
                    $mail->AddAddress('raresnecula05@gmail.com', 'Necula Rares'); 

                    // 3. REPLY-TO: Aceasta este adresa utilizatorului din formular. 
                    // Când apeși "Reply" în Gmail, vei răspunde automat acestei adrese.
                    $mail->AddReplyTo($email, $name); 

                    // ------------------------------

                    $mail->Subject = 'Formular contact site';
                    $mail->AltBody = 'To view this post you need a compatible HTML viewer!';
                    $mail->MsgHTML($mailBody);
                    
                    $mail->Send();
                    
                    $returnMsg = 'Your message has been submitted successfully.';
                }
                catch (phpmailerException $e)
				{
					echo $e->errorMessage(); //error from PHPMailer
				}
				 
            } 
        }
		else
		{
			$returnMsg = 'Please check the CAPTCHA box.'; 
        } 
    }
	else
	{ 
		$returnMsg = 'Please fill all the required fields.'; 
	} 
} 
header("Location: dashboard.php?mesaj=" . urlencode($returnMsg));
exit();
?>