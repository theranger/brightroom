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

include_once "Request.php";
include_once "Response.php";
include_once "controllers/Collection.php";
include_once "controllers/Image.php";
include_once "controllers/Text.php";
include_once "controllers/About.php";
include_once "controllers/Auth.php";
include_once "controllers/Download.php";
include_once "io/SecuredFileSystem.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-12
 *
 */
class Router {

	private $settings;

	public function __construct(Settings $settings) {
		$this->settings = $settings;
	}

	public function route(Request $request): Response {
		if ($this->settings->forceHTTPS == true && !$request->isSecure()) {
			error_log($request->getURL().": Secure connection forced, redirecting to HTTPS");
			header("Location: https://".$request->getURL());
			die();
		}

		$fileSystem = new SecuredFileSystem($this->settings->dataDirectory, $request->getURL(), $this->settings);

		// Handle request types that do not require session
		switch ($request->getRequestType()) {
			case RequestType::THEME_FILE:
				$fileSystem = new SecuredFileSystem(getcwd(), $request->getURL(), $this->settings);	// Override file system object
				if ($fileSystem->getFile() == null) return $this->renderResponse($request, ResponseCode::BAD_REQUEST);

				$session = new Session($fileSystem, $this->settings);	// Override session with new filesystem
				$fileController = new Text($session, $this->settings, $fileSystem->getFile());
				return $fileController->get($request);

			case RequestType::UNKNOWN:
				$request->elaborateType($fileSystem);
				return $this->route($request);

			case RequestType::INVALID:
				error_log($request->getURL().": Requested file not found");
				return (new Response($request))->render(ResponseCode::NOT_FOUND);
		}

		$session = new Session($fileSystem, $this->settings);
		if ($request->isLogout()) $session->clear();

		if (!$session->authorize($fileSystem->getSecuredFolder())) {
			$authController = new Auth($session, $this->settings, $fileSystem);
			$authController->setResponseCode(ResponseCode::UNAUTHORIZED);
			return $authController->get($request);

		}

		switch ($request->getRequestType()) {
			case RequestType::IMAGE_FILE:
			case RequestType::THUMBNAIL_FILE:
				if ($fileSystem->getFile() == null) $this->renderResponse($request, ResponseCode::BAD_REQUEST);

				$imageController = new Image($session, $this->settings, $fileSystem);
				return $imageController->get($request);

			case RequestType::IMAGE_FOLDER:
				$collectionController = new Collection($session, $this->settings, $fileSystem);
				return $collectionController->get($request);

			case RequestType::ABOUT_PAGE:
				$aboutController = new About($session, $this->settings, $fileSystem);
				return $aboutController->get($request);

			case RequestType::LOGIN_PAGE:
				$authController = new Auth($session, $this->settings, $fileSystem);
				return $authController->get($request);

			case RequestType::DOWNLOAD:
				$downloadController = new Download($session, $this->settings, $fileSystem);
				return $downloadController->get($request);
		}

		error_log($request->getURL().": Access denied");
		return $this->renderResponse($request, ResponseCode::FORBIDDEN);
	}

	private function renderResponse(Request $request, int $responseCode): Response {
		return (new Response($request))->render($responseCode);
	}
}
