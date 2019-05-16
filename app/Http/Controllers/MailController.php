<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MailController extends Controller {

	/**
	 * Generate Basic Email Template
	 *
	*/
	public function basic_email($name, $recipient_mail, $recipient_name, $subject, $sender_mail, $sender_name)
	{
	  $data = array('name'=>$name);

	  Mail::send(['text'=>'mail'], $data, function($message) {
		 $message->to($recipient_mail, $recipient_name)->subject
			($subject);
		 $message->from($sender_mail,$sender_name);
	  });
	}
	/**
	 * END Generate Basic Email Template
	 *
	*/

	/**
	 * Generate HTML Email Template
	 *
	*/
	public function html_email($name, $recipient_mail, $recipient_name, $subject, $sender_mail, $sender_name)
	{
	  $data = array('name'=>$name);

	  Mail::send('mail', $data, function($message) {
		 $message->to($recipient_mail, $recipient_name)->subject
			($subject);
		 $message->from($sender_mail,$sender_name);
	  });
	}
	/**
	 * END Generate HTML Email Template
	 *
	*/
}
