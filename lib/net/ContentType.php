<?php

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-14
 *
 */
abstract class ContentType {

	const JSON			= "application/json";
	const HTML			= "text/html";
	const PLAIN			= "text/plain";
	const JPEG			= "image/jpeg";
	const PNG			= "image/png";
	const CSS			= "text/css";
	const OCTETSTREAM	= "application/octet-stream";
	const ANY			= "*/*";

	private static $supportedTypes = array(
		ContentType::JSON,
		ContentType::PNG,
		ContentType::JPEG,
		ContentType::HTML,
		ContentType::CSS,
		ContentType::PLAIN,
		ContentType::ANY
	);

	public static function parseAcceptHeader(string $acceptHeader): string {
		$acceptHeader = explode(",", $acceptHeader);
		foreach ($acceptHeader as $value) {
			$key = array_search($value, ContentType::$supportedTypes);
			if ($key !== false) return ContentType::$supportedTypes[$key];
		}
		return ContentType::PLAIN;
	}

	public static function parseExtension(string $fileName): string {
		$dot = strrpos($fileName, ".");
		if ($dot === false) return ContentType::ANY;

		$extension = strtolower(substr($fileName, $dot));
		switch ($extension) {
			case ".jpg":
			case ".jpeg":
				return ContentType::JPEG;

			case ".png":
				return ContentType::PNG;

			case ".css":
				return ContentType::CSS;
		}

		return ContentType::ANY;
	}
}
