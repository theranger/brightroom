<?php

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-08
 *
 */
interface ImageRenderer {
	public function loadFile(string $path);

	public function outputImage(string $fileName);

	public function getHandle();

	public function setHandle($img);
}
