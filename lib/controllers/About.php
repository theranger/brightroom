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

include_once "ui/UI.php";
include_once "ui/UIAbout.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-11-23
 *
 */
class About extends Controller {

	private $fileSystem;

	public function __construct(Session $session, Settings $settings, FileSystem $fileSystem) {
		parent::__construct($session, $settings);
		$this->fileSystem = $fileSystem;
	}

	function get(Request $request): Response {
		$response = new Response($request);
		(new UI($this->settings, $this->session))->setStaticModule(new UIAbout($this->settings));
		new UINavigation($this->fileSystem->getRoot()->getChildren(), $this->fileSystem->getFolder());
		return $response->render(ResponseCode::OK, "themes/".$this->settings->theme."/index.php");
	}
}
