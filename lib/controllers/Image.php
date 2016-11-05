<?php

include_once "Controller.php";
include_once "io/File.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-11-02
 *
 */
class Image extends Controller {

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

		switch($request->getAcceptedType()) {
			case ContentType::PNG:
			case ContentType::JPEG:
				return $this->handleImage($request, $response);

			case ContentType::HTML:
				$folders = $this->file->getFolder()->getContents();
				new UI($this->settings, $this->session);
				new UIFolder($folders);
				return $response->render(ResponseCode::OK, "themes/".$this->settings->theme."/image.php");
		}

		error_log($request->getURL() . ": Invalid request");
		return $response->render(ResponseCode::BAD_REQUEST);
	}

	private function handleImage(Request $request, Response $response): Response {
		switch ($request->getRequestType()) {
			case RequestType::IMAGE_FILE:
				$response->asType(ResponseCode::OK, $request->getAcceptedType());
				$this->file->read();
				return $response;

			case RequestType::THUMBNAIL_FILE:
				$response->asType(ResponseCode::OK, $request->getAcceptedType());
				break;

			default:
				$response->asType(ResponseCode::INTERNAL_SERVER_ERROR, $request->getAcceptedType());
				return $response;
		}

		// Thumbnail was requested
		$imageHandler = new ImageHandler($request->getAcceptedType(), $this->settings, $this->file);
		$imageHandler->resizeImage($this->settings->thumbnailSize, 0);
		return $response;
	}
}
