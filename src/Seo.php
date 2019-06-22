<?php

namespace ether\seo;

use craft\base\Plugin;
use craft\events\ExceptionEvent;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\events\RegisterUserPermissionsEvent;
use craft\helpers\UrlHelper;
use craft\services\Fields;
use craft\services\UserPermissions;
use craft\web\Application;
use craft\web\ErrorHandler;
use craft\web\twig\variables\CraftVariable;
use craft\web\UrlManager;
use craft\web\View;
use ether\seo\fields\SeoField;
use ether\seo\listeners\GetCraftQLSchema;
use ether\seo\models\Settings;
use ether\seo\services\SeoService;
use ether\seo\web\twig\Variable;
use yii\base\Event;

/**
 * Class Seo
 *
 * @package ether\seo
 *
 * @property SeoService         $seo
 * @property UpgradeService     $upgrade
 */
class Seo extends Plugin
{

	// Variables
	// =========================================================================

	/** @var Seo */
	public static $i;

	public $hasCpSection        = false;
	public $hasCpSettings       = false;

	public $changelogUrl =
		'https://raw.githubusercontent.com/ethercreative/seo/v3/CHANGELOG.md';
	public $downloadUrl  =
		'https://github.com/ethercreative/seo/archive/v3.zip';
	public $documentationUrl =
		'https://github.com/ethercreative/seo/blob/v3/README.md';

	public $schemaVersion = '3.1.0';

	// Craft
	// =========================================================================

	public function init ()
	{
		parent::init();
		self::$i = self::getInstance();

		$craft = \Craft::$app;

		// Components
		// ---------------------------------------------------------------------

		$this->setComponents([
			'seo' => SeoService::class
		]);

		// Events
		// ---------------------------------------------------------------------

		// Field type
		Event::on(
			Fields::class,
			Fields::EVENT_REGISTER_FIELD_TYPES,
			[$this, 'onRegisterFieldTypes']
		);

		// Variable
		Event::on(
			CraftVariable::class,
			CraftVariable::EVENT_INIT,
			[$this, 'onRegisterVariable']
		);

		// CraftQL Support
		/** @noinspection PhpUndefinedNamespaceInspection */
		/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
		if (class_exists(\markhuot\CraftQL\CraftQL::class)) {
			Event::on(
				SeoField::class,
				'craftQlGetFieldSchema',
				[new GetCraftQLSchema, 'handle']
			);
		}
	}

	// Craft: Settings
	// -------------------------------------------------------------------------

	protected function createSettingsModel (): Settings
	{
		return new Settings();
	}

	public function getSettingsResponse ()
	{
		// Redirect to our settings page
		\Craft::$app->controller->redirect(
			UrlHelper::cpUrl('seo/settings')
		);
	}

	// Events
	// =========================================================================

	public function onRegisterFieldTypes (RegisterComponentTypesEvent $event)
	{
		$event->types[] = SeoField::class;
	}

	/**
	 * @param Event $event
	 *
	 * @throws \yii\base\InvalidConfigException
	 */
	public function onRegisterVariable (Event $event)
	{
		/** @var CraftVariable $variable */
		$variable = $event->sender;
		$variable->set('seo', Variable::class);
	}

	// Misc
	// =========================================================================

	public static function getFieldTypeSettingsVariables ()
	{
		$volumes = \Craft::$app->volumes->getPublicVolumes();

		return compact(
			'volumes'
		);
	}

}
