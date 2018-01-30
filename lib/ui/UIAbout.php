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

include_once "IStaticModule.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-11-23
 *
 */
class UIAbout implements IStaticModule {

	private $settings;

	function __construct(Settings $settings) {
		$this->settings = $settings;
	}

	function printContent() { ?>
		<h1>Brightroom Gallery <?php echo $this->settings->version ?></h1>
		<p>
			Simple folder-based gallery system written in PHP. Brightroom is free and open source.
		</p>

		<p>
			For more information, check out the <a href="http://brightroom.eu">official website</a>.
			Source code is located in <a href="http://github.com/theranger/brightroom">GitHub</a>.
		</p>

		<h2>Credits</h2>
		<ul>
			<li><a href="https://github.com/theranger">The Ranger</a> - ranger at risk.ee (maintainer, repository holder)</li>
			<li><a href="https://github.com/tribut">Felix Eckhofer</a></li>
		</ul>

	<?php }
}
