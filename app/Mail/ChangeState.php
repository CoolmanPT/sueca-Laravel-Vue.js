<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Changestate extends Mailable
{
	use Queueable, SerializesModels;

	private $msg;

	private $sentBy;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct($msg, $from)
	{
		$this->msg = $msg;
		$this->sentBy = $from;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		$name = 'Recurso Sueca';

		$subject = 'Account state changed';

		return $this->view('email.changestate')
			->with([
				'msg' => $this->msg,
			])->from($this->sentBy, $name)->subject($subject);

	}
}
