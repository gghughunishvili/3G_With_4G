<?php
use pro\gateways\UserGateway;

class RemindersController extends BaseController {

	protected $layout = 'layouts.home';
	private $gateway; 

	public function __construct(UserGateway $gateway) {
		$this->gateway = $gateway;
	}

	/**
	 * Display the password reminder view.
	 *
	 * @return Response
	 */
	public function getRemind()
	{
		$this->layout->content = View::make('ITDC_Project.account.password.remind');
	}

	/**
	 * Handle a POST request to remind a user of their password.
	 *
	 * @return Response
	 */
	public function postRemind()
	{
		switch ($response = Password::remind(Input::only('email'), function($message){$message->subject('ITDC Project Password Reminder');}))
		{
			case Password::INVALID_USER:
				return Redirect::back()->with('error', Lang::get($response))
					->withInput()
					->with('message_type','danger');

			case Password::REMINDER_SENT:
				return Redirect::back()->with('status', Lang::get($response))
					->withInput()
					->with('message_type','success');
		}
	}

	/**
	 * Display the password reset view for the given token.
	 *
	 * @param  string  $token
	 * @return Response
	 */
	public function getReset($token = null)
	{
		if (is_null($token)) App::abort(404);

		$this->layout->content = View::make('ITDC_Project.account.password.reset')->with('token', $token);
	}

	/**
	 * Handle a POST request to reset a user's password.
	 *
	 * @return Response
	 */
	public function postReset()
	{
		$credentials = Input::only(
			'email', 'password', 'password_confirmation', 'token'
		);

		$response = Password::reset($credentials, function($user, $password)
		{
			$user->password = Hash::make($password);

			$user->save();
		});

		switch ($response)
		{
			case Password::INVALID_PASSWORD:
			case Password::INVALID_TOKEN:
			case Password::INVALID_USER:
				return Redirect::back()->with('error', Lang::get($response))
					->with('message_type','danger');

			case Password::PASSWORD_RESET:
				return Redirect::route('home')
					->with('message_type','success')
					->with('message', 'You have successfuly recovered yout password!');
		}
	}

}
