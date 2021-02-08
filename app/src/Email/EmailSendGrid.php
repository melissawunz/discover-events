<?php

namespace MelissaWu\DiscoverEvents;

use Exception;
use SendGrid;
use SilverStripe\Core\Environment;
use SendGrid\Mail\Mail;

/**
 * construct the email and 
 * hand over the email to Sendgrid for sending to the receiver
 */
class EmailSendGrid
{

	protected $email;

	protected $sendgrid;

	protected $from;

	protected $to;

	protected $subject;

	protected $fromName;

	protected $toName;

	protected $body;

	public function __construct($from, $to, $subject, $body, $fromName = null, $toName = null)
	{
		$this->email = new Mail();
		$this->sendgrid = new SendGrid(Environment::getEnv('SENDGRID_API_KEY'));
		$this->from = $from;
		$this->to = $to;
		$this->subject = $subject;
		$this->body = $body;
		if (isset($fromName)) {
			$this->fromName = $fromName;
		}
		if (isset($toName)) {
			$this->toName = $toName;
		}
	}

	/**
	 * add an attachement file to the email
	 * 
	 * @param string $file
	 * @param string $fileName
	 * 
	 * @return EmailSendGrid $this
	 * 
	 */
	public function setAttachment($file, $fileName)
	{
		$file_encoded = base64_encode(file_get_contents($file));
		$this->email->addAttachment(
			$file_encoded,
			"application/text",
			$fileName,
			"attachment"
		);
		return $this;
	}

	/**
	 * hand over the email to SendGrid
	 * 
	 */
	public function send()
	{
		$this->email->setFrom($this->from, $this->fromName);
		$this->email->addTo($this->to, $this->toName);
		$this->email->setSubject($this->subject);
		$this->email->addContent("text/html", $this->body);
		try {
			$response = $this->sendgrid->send($this->email);
			print $response->statusCode() . "\n";
			print_r($response->headers());
			print $response->body() . "\n";
		} catch (Exception $e) {
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}
}
