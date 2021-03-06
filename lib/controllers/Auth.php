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

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-12-08
 *
 */
class Auth extends Controller {

	private $fileSystem;
	private $responseCode = ResponseCode::OK;

	public function __construct(Session $session, Settings $settings, FileSystem $fileSystem) {
		parent::__construct($session, $settings);
		$this->fileSystem = $fileSystem;
	}

	public function setResponseCode(int $responseCode) {
		$this->responseCode = $responseCode;
	}

	function get(Request $request): Response {
		$response = new Response($request);

		if ($request->isLogin() && $this->session->authenticate($request->getUsername(), $request->getPassword())) {
			return $response->redirect($request->getURL());
		};

		switch ($request->getAcceptedType()) {
			case ContentType::HTML:
				(new UI($this->settings, $this->session))->setStaticModule(new UIAuth($this->session));
				new UINavigation($this->session, $this->fileSystem->getRoot()->getChildren(), $this->fileSystem->getFolder());
				return $response->render($this->responseCode, "themes/".$this->settings->theme."/index.php");
				break;
		}

		return $response;
	}
}
