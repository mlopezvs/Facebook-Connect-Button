<?php

/*
 * FacebookConnect class
 * @author Nikonov Andrey <nikonov@zfort.net>
 * @link http://www.zfort.com/
 * @copyright Copyright &copy; 2000-2011 Zfort Group
 * @license http://www.zfort.com/terms-of-use
 * @version $Id$
 * @package packageName
 * @since 1.0
 */

class FacebookConnect extends CApplicationComponent
{
	/**
	* The Application ID.
	*
	* @var string
	*/
	public $appId;

	/**
	* The Application API Secret.
	*
	* @var string
	*/
	public $secret;

	public function init() {
		parent::init();

		if (!empty($this->appId) && !empty($this->secret)) {
			Yii::setPathOfAlias('facebookconnect', dirname(__FILE__));
			Yii::import('facebookconnect.*');
			Yii::import('facebookconnect.controllers.*');

			FConnect::init($this->appId, $this->secret);
			FConnect::getUser();

			if (Yii::app()->session->offsetExists('fb_popup')) {
				Yii::app()->session->offsetUnset('fb_popup');
				if (Yii::app()->session->offsetExists('fb_redirect_uri')) {
					$href = Yii::app()->session->get('fb_redirect_uri');
					Yii::app()->session->offsetUnset('fb_redirect_uri');
					echo '<script type="text/javascript">if (window.opener && !window.opener.closed) { window.opener.location.href="' . $href . '"; } window.close();</script>';
				} else {
					echo '<script type="text/javascript">if (window.opener && !window.opener.closed) { window.opener.location.reload(); } window.close();</script>';
				}
			}

			Yii::app()->configure(array(
				'controllerMap' => CMap::mergeArray(Yii::app()->controllerMap, array(
					'fbconnect' => array(
						'class' => 'FacebookConnectController',
					)
				))
			));
		} else {
			throw new Exception('You need to add appId and secret to config file!');
		}
	}

}
