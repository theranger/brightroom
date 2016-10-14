<?php

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-14
 *
 */
abstract class Controller {

	protected $session;
	protected $settings;

	public function __construct(Session $session, Settings $settings) {
		$this->session = $session;
		$this->settings = $settings;
	}
}
