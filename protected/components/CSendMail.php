<?php
/** ************************************************************
 *  File Name           : CSendMail.php
 *  Class Name		: CSendMail
 *  File Description    : Common class to send mail using PHPMailer.
 *  Author		: Benchmark, 
 *  Created Date	: 18th Feb 2014 11:27:00 AM IST
 *  Develop By		: Anand Rathi
 * ************************************************************* */
class CSendMail
{

	// collect all email ids
	public static $emails = array();
	// collect all placeholder & its value
	public static $placeholders = array();

	/**
	*   Usage       :   Get email template and prepare & send mail
	*   Parameters  :
	*       - $key          -   Unique Indentity value or message
        *   	- $send_direct  -   send mail direct flag* // True - Direct mail :: false - Vie Cron
	*   How to Use		:
                    $send_mail = new CSendMail();
                    $send_mail->addPlacehoder('PLACEHOLDER_VARIABLE', 'PLACEHOLDER_VALUE');
                    $send_mail->addFrom('FROM_EMAIL', 'FROM_NAME'); // Default sets in perams file
                    $send_mail->addTo('TO_EMAIL', 'TO_NAME');	
                    $send_mail->addCC('CC_EMAIL', 'CC_NAME');	
                    $send_mail->addBCC('BCC_EMAIL', 'BCC_NAME');	
                    $send_mail->mailTo( MESSAGE_KEY, TRUE/FALSE ); 
			
            NOTE : You can add multiple TO, Placeholder, CC, BCC
	*   Return      :   1 - Success	||  0 - Failuer
	**/
	public function mailTo( $key, $send_direct = false )
	{       
		// Get Message subject, body & notes by Spesific key
		$get_email_message = EmailMaster::model()->findByAttributes( array('key'=>$key, 'is_disabled'=>0 ) );
		$logo_path = CHelper::baseUrl(true);
		// Check is there any record found
		if( !empty( $get_email_message ) )
		{
                        $send_direct = $get_email_message->is_high == 1 ? true : false;
                        
			$email_subject 	= Yii::app()->params['smtpSettigns']['SubjectPrefix'] . $get_email_message->subject;
			$email_body 	= $get_email_message->email_body;
                       
			$email_notes 	= $get_email_message->email_notes ? "NOTE : ". $get_email_message->email_notes : '';
			
			// get all assigned placeholders
			$placeholders	= self::$placeholders;
			
			// Replace all filter from email body
			foreach( $placeholders as $placeholder => $placeholderValue )
			{
				$email_body = str_replace( '{'.$placeholder.'}', $placeholderValue, $email_body );
			}
			// Pre formated text using nl2br;
			$email_body	= nl2br( $email_body );
			
			// Render static view of Email template		
			$email_template	= CHelper::renderStaticContent( 'application.components.views.email_template', 'email_template' );
                        $email_template     = str_replace('{LOGO_PATH}',$logo_path,$email_template);
			// Replace Pacehoders From email template
			$email_template	= str_replace( '{EMAIL_BODY}', $email_body, $email_template );
			$email_template	= str_replace( '{EMAIL_NOTES}', $email_notes, $email_template );

			// Send mail by SMTP
			return self::sendMailByPHPMailer( $email_subject, $email_template, $send_direct );
		}
		else
		{
			return false;
		}

	}
	
	/**
	*   Usage       :   Send mail using PHP mailer
	*   Parameters  :
	*       - $email_subject    -   Email Subject
	*   	- $email_template   -   Email Template with message content
	*   	- $send_direct      -   send mail direct flag // True - Direct mail :: false - Vie Cron
	*   How to Use  :   CSendMail::sendMailByPHPMailer( $email_subject, $email_template, $send_direct );
	*   Return 	:   1 - Success	||  0 - Failuer
	**/
	public static function sendMailByPHPMailer( $email_subject, $email_template, $send_direct )
	{ 
                // Collecting all email ids having from, to, cc & bcc
		$emails	= self::$emails;
                // Check method if its via CRON or direct
                if( $send_direct === false )
                {  
                    $cron_job_email =   new CronJobEmail();
                    $cron_job_email->emails     =  serialize( $emails );
                    $cron_job_email->subject    =  $email_subject;
                    $cron_job_email->template   =  $email_template;
                    return $cron_job_email->save();
                    // set cron job
                }
		// PHP Mailer Settings
		Yii::import('application.extensions.phpmailer.JPhpMailer');
		$mail = new JPhpMailer;

		// SMTP setting comes from main configuration files
		$smtpSettigns	= Yii::app()->params['smtpSettigns'];
                
                // Cheking is having any extra cc emails
                if( is_array( $smtpSettigns['ExtraBCC'] ) ){
                    // All CC mailers
                    foreach( $smtpSettigns['ExtraBCC'] as $name => $email ){
                        $mail->AddCC( $email, $name  );
                    }
                
                }
                
		// Set SMTP Mail
		$mail->IsSMTP();
		$mail->Host         = $smtpSettigns['Host'];
		$mail->SMTPAuth     = $smtpSettigns['SMTPAuth'];
		$mail->Username     = $smtpSettigns['Username'];
		$mail->Password     = $smtpSettigns['Password'];
		$mail->Port         = $smtpSettigns['Port'];
		$mail->SMTPDebug    = $smtpSettigns['SMTPDebug'];
                $mail->SMTPSecure   = $smtpSettigns['SMTPSecure'];
		// Set email subject
		$mail->Subject      = $email_subject;
		$mail->MsgHTML( $email_template );
		
		// Assign from if not set
		if( !array_key_exists( 'from', $emails ) ){
			$emails['from']['email']    = $smtpSettigns['FromEmail'];
			$emails['from']['name']     = $smtpSettigns['FromName']; 
		}
		
		// check sender & and receiver are present or not
		if( is_array( $emails ) && array_key_exists( 'to', $emails ) && array_key_exists( 'from', $emails ) ){
			
			// Set From
			$mail->SetFrom( $emails['from']['email'], $emails['from']['name'] );
			
			// Set To
			$to_emails  = $emails['to'];
			foreach( $to_emails as $to_email )
				$mail->AddAddress( $to_email['email'], $to_email['name'] );

			// Set CC
			if( array_key_exists( 'cc', $emails ) )
			{
				$cc_emails  = $emails['cc'];
				foreach( $cc_emails as $cc_email )
					$mail->AddCC( $cc_email['email'], $cc_email['name']  );
			}
			
			// Set BCC
			if( array_key_exists( 'bcc', $emails ) )
			{
				$bcc_emails = $emails['bcc'];
				foreach( $bcc_emails as $bcc_email )
					$mail->AddBCC( $bcc_email['email'], $bcc_email['name']  );
			}
			// Send mails
			return $mail->Send();		
		}
		else
		{
			// Emails not found
			return false;
		}
	}

	/**
	*   Usage       :   Send mail CRON && PHPusing mailer
	*   Parameters  :
	*       - $email_subject    -   Email Subject
	*   	- $email_template   -   Email Template with message content
	*   How to Use  :   CSendMail::sendMailByPHPMailer( $emails, $email_subject, $email_template );
	*   Return 	:   1 - Success	||  0 - Failuer
	**/
	public static function sendMailByCron( $email_subject, $email_template )
	{
                return self::sendMailByPHPMailer( $email_subject, $email_template, true );
        }        
	/**
	*   Usage       :	Get all receiver email ids
	*   Parameters  :
	*       - $email    -	User Email ( Compulsary )
	*       - $name     -   User Name
	*   How to Use  :   CSendMail::addTo( $email, $name );
	**/
	public function addTo( $email, $name = '' )
	{
		self::addEmails( 'to', $email, $name );	
	}
	
	/**
	*   Usage       :   Get from email id
	*   Parameters  :
	*       - $email    -	User Email ( Compulsary )
		- $name     -	User Name
	*   How to Use  :   CSendMail::addFrom( $email, $name );
	**/
	public function addFrom( $email, $name = '' )
	{
		 self::$emails['from']['email']	= $email;
		 self::$emails['from']['name']	= $name;
	}
	
	/**
	*   Usage       :   Collect cc email ids
	*   Parameters  :
	*       - $email    -	User Email ( Compulsary )
	*	- $name     -	User Name
	*   How to Use  :   CSendMail::addCC( $email, $name );
	**/
	public function addCC( $email, $name = '' )
	{
		self::addEmails( 'cc', $email, $name );	
	}

	/**
	*   Usage       :   Collect bcc email ids
	*   Parameters  :
	*       - $email    -	User Email ( Compulsary )
	*       - $name     -	User Name
	*   How to Use  :   CSendMail::addBCC( $email, $name );
	**/
	public function addBCC( $email, $name = '' )
	{
		self::addEmails( 'bcc', $email, $name );	
	}

        /**
	*   Usage       :   Collect all email ids
	*   Parameters  :
        *	- $type     -	type of receiver ( Compulsary )
	*       - $email    -	User Email ( Compulsary )
	*       - $name     -	User Name
	*   How to Use  :   CSendMail::addEmails( $type, $email, $name );
	**/
	public static function addEmails( $type, $email, $name )
	{
		self::$emails[$type]    = !array_key_exists( $type, self::$emails ) ? array() : self::$emails[$type];
		// push email & name of user in emails
                array_push( self::$emails[$type], array( 'email'=>$email, 'name' => $name ) );	
	}
	
        /**
	*   Usage       :   Collect all placeholders
	*   Parameters  :
        *       - $placeholder	-   Placehoder Name ( Compulsary )
	*	- $value        -   Placehoder Value
	*   How to Use  :   CSendMail::addPlacehoder( $placeholder, $value );
	**/		
	public static function addPlacehoder( $placeholder, $value )
	{
		self::$placeholders[$placeholder]   = $value;
	}
	
        public function __destruct() {
            self::$emails       = array();
            self::$placeholders = array();
        }
}
?>