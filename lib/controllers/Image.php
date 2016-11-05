<?php

include_once "Controller.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-11-02
 *
 */
class Image extends Controller {

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

		switch($request->getAcceptedType()) {
			case ContentType::JPEG:
				$response->asJpeg(ResponseType::OK);
				$this->fileSystemHandler->getFile($request->getURL());
				return $response;

			case ContentType::HTML:
				$folders = $this->fileSystemHandler->getContents(dirname($request->getURL()));
				new UI($this->settings, $this->session);
				new UIFolder($folders);
				return $response->render(ResponseType::OK, "themes/".$this->settings->theme."/image.php");
		}

		error_log($request->getURL() . ": Invalid request");
		return $response->render(ResponseType::BAD_REQUEST);
	}
}
