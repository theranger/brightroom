<?php

include_once "Controller.php";
include_once "ui/UICollection.php";
include_once "ui/UI.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-14
 *
 */
class Collection extends Controller {

	private $folder;

	public function __construct(Session $session, Settings $settings, Folder $folder) {
		parent::__construct($session, $settings);
		$this->folder = $folder;
	}


	public function listing(Request $request): Response {
		$response = new Response($request);

		if (!$this->session->authorize($request->getURL())) {
			return $response->render(ResponseCode::UNAUTHORIZED);
		}

		$folders = $this->folder->getContents();

		switch ($request->getAcceptedType()) {
			case ContentType::JSON:
				return $response->asJson(ResponseCode::OK, $folders);

			case ContentType::PLAIN:
				return $response->asPlain(ResponseCode::OK, $folders);

			case ContentType::HTML:
				new UI($this->settings, $this->session);
				new UICollection($folders);
				return $response->render(ResponseCode::OK, "themes/" . $this->settings->theme . "/collection.php");
		}

		return $response->render(ResponseCode::BAD_REQUEST);
	}
}
