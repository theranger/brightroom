<?php

include_once "Controller.php";
include_once "ui/UICollection.php";
include_once "ui/UI.php";
include_once "ui/UINavigation.php";
include_once "io/FileSystem.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-14
 *
 */
class Collection extends Controller {

	private $fileSystem;

	public function __construct(Session $session, Settings $settings, FileSystem $fileSystem) {
		parent::__construct($session, $settings);
		$this->fileSystem = $fileSystem;
	}

	public function listing(Request $request): Response {
		$response = new Response($request);

		if (!$this->session->authorize($request->getURL())) {
			return $response->render(ResponseCode::UNAUTHORIZED);
		}

		$folders = $this->fileSystem->getFolder()->getContents();

		switch ($request->getAcceptedType()) {
			case ContentType::JSON:
				return $response->asJson(ResponseCode::OK, $folders);

			case ContentType::PLAIN:
				return $response->asPlain(ResponseCode::OK, $folders);

			case ContentType::HTML:
				new UI($this->settings, $this->session);
				new UICollection($folders);
				new UINavigation($this->fileSystem->getRoot()->getChildren());
				return $response->render(ResponseCode::OK, "themes/" . $this->settings->theme . "/collection.php");
		}

		return $response->render(ResponseCode::BAD_REQUEST);
	}
}
