<?php

namespace System\Classes\Guns;

class AssetsUrlGun
{
    public function main($url = '') {
		return url('assets/'.slashToUrlSeparator($url));
	}

}
