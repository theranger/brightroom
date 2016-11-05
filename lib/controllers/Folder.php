<?php

include_once "Controller.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-14
 *
 */
class Folder extends Controller {

	private $fileSystemHandler;

	public function __construct(Session $session, Settings $settings, FileSystemHandler $fileSystemHandler) {
		parent::__construct($session, $settings);
		$this->fileSystemHandler = $fileSystemHandler;
	}


	public function listing(Request $request): Response {
		$response = new Response($request);

		if (!$this->session->authorize($request->getURL())) {
			return $response->render(ResponseCode::UNAUTHORIZED);
		}

		$folders = $this->fileSystemHandler->getContents($request->getURL());

		switch($request->getAcceptedType()) {
			case ContentType::JSON:
				return $response->asJson(ResponseCode::OK, $folders);

			case ContentType::PLAIN:
				return $response->asPlain(ResponseCode::OK, $folders);

			case ContentType::HTML:
				new UI($this->settings, $this->session);
				new UIFolder($folders);
				return $response->render(ResponseCode::OK, "themes/".$this->settings->theme."/listing.php");
		}

		return $response->render(ResponseCode::BAD_REQUEST);
	}
}
