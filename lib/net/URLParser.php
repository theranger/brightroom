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

class URLParser {

	private static $urlPatterns = array('/index\.php\/?/', '/\w+\/\.\.\//');

	private $settings;
	private $url;

	public function __construct(string $url, Settings &$settings) {
		$this->settings = $settings;

		// Cleanup the bad things
		$url = preg_replace(self::$urlPatterns, '', urldecode($url));

		// Strip prefix
		if (strncmp($url, $this->settings->documentRoot, strlen($this->settings->documentRoot)) == 0) {
			$url = substr($url, strlen($this->settings->documentRoot));
		}

		// Do not trim the beginning of URL since it is absolute path
		$this->url = rtrim($url, "/");
	}

	public function getURL(): string {
		return $this->url;
	}

	public function isRoot(): bool {
		return trim($this->url, "/") == '';
	}

	public function getResourceName(): string {
		return basename($this->url);
	}
}
