<?php

use App\Mail\NotifyMail;
use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as MailException;

function helper_send_email($email_to, $subject, $message, $email_myself = '', $add_cc  = [])
{
    if (settings()->mail_protocol == 'smtp') {
        try {
            // if (true) {
            // PHPMailer object
            $mail = new PHPMailer;
            // SMTP configuration
            $mail->isSMTP();
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->Encoding = 'base64';
            $mail->CharSet = 'UTF-8';
            $mail->Host     = settings()->mail_host;
            $mail->SMTPAuth = true;
            $mail->Username = settings()->mail_username;
            $mail->Password = base64_decode(settings()->mail_password);
            //$mail->SMTPSecure = settings()->mail_encryption;;
            $mail->Port     = settings()->mail_port;

            $mail->setFrom(settings()->mail_username, settings()->site_name);
            $mail->addReplyTo(settings()->mail_username, settings()->site_name);

            // Add a recipient
            $mail->addAddress($email_to);

            // Add cc or bcc
            if (!empty($email_myself)) {
                $mail->addCC($email_myself);
            }

            if (!empty($add_cc)) {
                foreach ($add_cc as $e) {
                    $mail->addCC($e);
                }
            }

            // $mail->addBCC('bcc@example.com');

            // Email subject
            $mail->Subject = $subject;

            // Set email format to HTML
            $mail->isHTML(true);

            // Email body content
            $mailContent = $message;
            $mail->Body = $mailContent;

            // Send email

            if (!$mail->send()) {
                // echo '<pre>';
                // print_r('Mailer Error: ' . $mail->ErrorInfo);
                // echo '</pre>';
                return false;
            } else {
                // echo 'Message has been sent';
                return true;
            }
        } catch (MailException $e) {
            // ini_set('memory_limit', '44M');

            // echo '<pre>';
            // print_r($e);
            // echo '</pre>';
            return false;
        }
    } else {
        try {
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-type: text/html; charset=UTF-8';

            // Additional headers
            $headers[] = 'To: <' . $email_to . '>';
            $headers[] = 'From: ' . settings()->site_name . ' <' . settings()->mail_username . '>';
            if (!empty($email_myself)) {
                $headers[] = 'Cc: ' . user()->email;
            }
            if (mail($email_to, $subject, view('email_template.index', ['my_content' => $message])->render(), implode("\r\n", $headers))) {
                return true;
            }
            return false;


            // // new NotifyMail($subject, $message)
            // Mail::send('email_template.index', ['my_content' => $message], function ($m) use ($email_to, $subject, $message, $email_myself) {
            //     $m->from(settings()->admin_email, settings()->site_name);
            //     $m->to($email_to);
            //     $m->subject($subject);
            //     if (!empty($email_myself)) {
            //         $m->cc(user()->email);
            //     }
            // });
            // if (count(Mail::failures()) > 0) {
            //     // return Mail::failures();
            //     // dd(Mail::failures());
            //     // exit;
            //     return false;
            // }
            // return true;
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return false;
        }

        // // $this->load->library('email');
        // // $this->load->library('encryption');
        // // $this->email->set_mailtype('html');

        // // $this->email->from(settings()->admin_email, settings()->site_name);
        // // $this->email->to($email_to);
        // // if (!empty($email_myself)) {
        // //     $this->email->cc($email_myself);
        // // }
        // // $this->email->subject($subject);
        // // $this->email->message($message);
        // // $this->email->send();

        // // if ($this->email->send()) {
        // //     //Success email Sent
        // //     return true;
        // // } else {
        // //     //Email Failed To Send
        // //     return $this->email->print_debugger();
        // // }

        // $phpmailer_lib = new PHPMailer_Lib;
        // $mail = $phpmailer_lib->load();

        // //$mail->SMTPDebug = 2;
        // $mail->Encoding = 'base64';
        // $mail->CharSet = 'UTF-8';
        // $mail->Host     = settings()->mail_host;
        // $mail->SMTPAuth = true;
        // $mail->Username = settings()->mail_username;
        // $mail->Password = base64_decode(settings()->mail_password);
        // //$mail->SMTPSecure = settings()->mail_encryption;;
        // $mail->Port     = settings()->mail_port;

        // $mail->setFrom(settings()->admin_email, settings()->site_name);
        // $mail->addReplyTo(settings()->admin_email, settings()->site_name);

        // // Add a recipient
        // $mail->addAddress($email_to);

        // // Add cc or bcc
        // if (!empty($email_myself)) {
        //     $mail->addCC($email_myself);
        // }
        // // $mail->addBCC('bcc@example.com');

        // // Email subject
        // $mail->Subject = $subject;

        // // Set email format to HTML
        // $mail->isHTML(true);

        // // Email body content
        // $mailContent = $message;
        // $mail->Body = $mailContent;

        // // Send email

        // if (!$mail->send()) {
        //     // print_r('Mailer Error: ' . $mail->ErrorInfo);
        //     return false;
        // } else {
        //     //echo 'Message has been sent';
        //     return true;
        // }
    }
}

// function send_email($email_to, $subject = '', $message = '', $bcc = '')
// {

//     if (settings()->mail_protocol == 'smtp') {
//         // Load PHPMailer library
//         $this->load->library('PHPMailer_Lib');
//         // PHPMailer object
//         $mail = $this->phpmailer_lib->load();
//         // SMTP configuration
//         $mail->isSMTP();
//         //$mail->SMTPDebug = 2;
//         $mail->Encoding = 'base64';
//         $mail->CharSet = 'UTF-8';
//         $mail->Host     = settings()->mail_host;
//         $mail->SMTPAuth = true;
//         $mail->Username = settings()->mail_username;
//         $mail->Password = base64_decode(settings()->mail_password);
//         //$mail->SMTPSecure = settings()->mail_encryption;;
//         $mail->Port     = settings()->mail_port;

//         $mail->setFrom(settings()->admin_email, settings()->site_name);

//         // Add a recipient
//         $mail->addAddress($email_to);
//         // Add cc or bcc
//         if (!empty($bcc)) {
//             if (is_array($bcc)) {
//                 foreach ($bcc as $email) {
//                     $mail->addBCC($email);
//                 }
//             } else
//                 $mail->addBCC($bcc);
//         }

//         // Email subject
//         $mail->Subject = $subject;

//         // Set email format to HTML
//         $mail->isHTML(true);

//         // Email body content
//         $mailContent = $message;
//         $mail->Body = $mailContent;

//         if (!$mail->send()) {
//             return 'Mailer Error: ' . $mail->ErrorInfo;
//             //return false;
//         } else {
//             //echo 'Message has been sent';
//             return true;
//         }
//     } else {
//         $this->load->library('email');
//         $this->load->library('encryption');
//         $this->email->set_mailtype('html');

//         $this->email->from(settings()->admin_email, settings()->site_name);
//         $this->email->to($email_to);
//         // Add cc or bcc
//         if (!empty($bcc)) {
//             if (is_array($bcc)) {
//                 foreach ($bcc as $email) {
//                     $this->email->bcc($email);
//                 }
//             } else
//                 $this->email->bcc($bcc);
//         }
//         $this->email->subject($subject);
//         $this->email->message($message);
//         $this->email->send();
//         if ($this->email->send()) {
//             //Success email Sent
//             return true;
//         } else {
//             //Email Failed To Send
//             return $this->email->print_debugger();
//         }
//     }
// }
