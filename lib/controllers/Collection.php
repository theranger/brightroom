<?php

include_once "Controller.php";
include_once "ui/UICollection.php";
include_once "ui/UI.php";
include_once "ui/UINavigation.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-14
 *
 */
class Collection extends Controller {

	private $folder;
	private $rootFolder;

	public function __construct(Session $session, Settings $settings, Folder $folder) {
		parent::__construct($session, $settings);
		$this->folder = $folder;
		$this->rootFolder = $folder;
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
				new UINavigation($this->listFolders($this->folder));
				return $response->render(ResponseCode::OK, "themes/" . $this->settings->theme . "/collection.php");
		}

		return $response->render(ResponseCode::BAD_REQUEST);
	}

	/**
	 * @param Folder $folder
	 * @return Folder[]
	 */
	private function listFolders(Folder $folder): array {
		try {
			$entries = $this->listFolders($folder->parentFolder());
			foreach ($entries as $key => $directoryEntry) {
				if ($directoryEntry->getPath() != $folder->getPath()) continue;
				$directoryEntry->getFolders();
				if ($folder->getURL() == $this->folder->getURL()) return $this->rootFolder->getChildren();
				return $directoryEntry->getChildren();
			}
			return array();	// This should never happen
		}
		catch (IOException $ex) {
			$this->rootFolder = $folder;
			return $folder->getFolders();
		}
	}
}
