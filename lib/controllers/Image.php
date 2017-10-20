<?php
/**
 * Copyright 2016 The Ranger <ranger@risk.ee>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types = 1);

include_once "Controller.php";
include_once "io/FileSystem.php";
include_once "ui/UI.php";
include_once "ui/UICollection.php";
include_once "ui/UIImage.php";
include_once "img/ImageHandler.php";
include_once "img/ExifParser.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-11-02
 *
 */
class Image extends Controller {

	private $fileSystem;

	public function __construct(Session $session, Settings $settings, SecuredFileSystem $fileSystem) {
		parent::__construct($session, $settings);
		$this->fileSystem = $fileSystem;
	}

	public function get(Request $request): Response {
		$response = new Response($request);

		switch ($request->getAcceptedType()) {
			case ContentType::PNG:
			case ContentType::JPEG:
				return $this->handleImage($request, $response);

			case ContentType::HTML:
				$folders = $this->fileSystem->getFolder()->getContents();
				new UI($this->settings, $this->session);
				new UICollection($this->settings, $folders, $this->fileSystem->getFolder());
				new UINavigation($this->session, $this->fileSystem->getRoot()->getChildren(), $this->fileSystem->getFile());
				new UIImage($this->fileSystem->getFile(), new ExifParser($this->fileSystem->getFile()));
				return $response->render(ResponseCode::OK, "themes/".$this->settings->theme."/image.php");
		}

		error_log($request->getURL().": Invalid request");
		return $response->render(ResponseCode::BAD_REQUEST);
	}

	private function handleImage(Request $request, Response $response): Response {
		switch ($request->getRequestType()) {
			case RequestType::IMAGE_FILE:
				$response->asType(ResponseCode::OK, $request->getAcceptedType());
				$resizeTo = $this->settings->imageSize;
				break;

			case RequestType::THUMBNAIL_FILE:
				$response->asType(ResponseCode::OK, $request->getAcceptedType());
				$resizeTo = $this->settings->thumbnailSize;
				break;

			default:
				$response->asType(ResponseCode::INTERNAL_SERVER_ERROR, $request->getAcceptedType());
				return $response;
		}

		// Thumbnail was requested
		if ($resizeTo == 0) {
			$this->fileSystem->getFile()->read();
			return $response;
		}
		$imageHandler = new ImageHandler($request->getAcceptedType(), $this->settings, $this->fileSystem->getFile());
		$imageHandler->resizeImage($resizeTo, 0);
		return $response;
	}
}
