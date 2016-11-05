<?php

include_once "Controller.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-11-05
 *
 */
class Text extends Controller {

	private $fileSystemHandler;

	public function __construct(Session $session, Settings $settings, FileSystemHandler $fileSystemHandler) {
		parent::__construct($session, $settings);
		$this->fileSystemHandler = $fileSystemHandler;
	}

	public function get(Request $request): Response {
		$response = new Response($request);

		if (!$this->session->authorize($request->getURL())) {
			return $response->render(ResponseType::UNAUTHORIZED);
		}

		$response->asType(ResponseType::OK, $request->getAcceptedType());
		$this->fileSystemHandler->getFile($request->getURL());
		return $response;
	}
}
