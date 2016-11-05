<?php

include_once "Controller.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-11-05
 *
 */
class Text extends Controller {

	private $file;

	public function __construct(Session $session, Settings $settings, File $file) {
		parent::__construct($session, $settings);
		$this->file = $file;
	}

	public function get(Request $request): Response {
		$response = new Response($request);

		if (!$this->session->authorize($request->getURL())) {
			return $response->render(ResponseCode::UNAUTHORIZED);
		}

		$response->asType(ResponseCode::OK, $request->getAcceptedType());
		$this->file->read();
		return $response;
	}
}
