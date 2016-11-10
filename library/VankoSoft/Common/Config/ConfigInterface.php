<?php

namespace VankoSoft\Common\Config;

interface ConfigInterface
{
	public function get( $option, $default );
}
