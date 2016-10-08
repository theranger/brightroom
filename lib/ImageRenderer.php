<?php

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-08
 *
 */
interface ImageRenderer {
	public function loadFile($path);
	public function outputImage($img);
	public function getHandle();
	public function setHandle($img);
}