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

include_once "IController.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-14
 *
 */
abstract class Controller implements IController {

	protected $session;
	protected $settings;

	public function __construct(Session $session, Settings $settings) {
		$this->session = $session;
		$this->settings = $settings;
	}
}
