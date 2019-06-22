<?php

namespace ether\seo\services;

use craft\base\Component;
use craft\base\Element;
use craft\base\Field;
use craft\db\Migration;
use craft\db\Query;
use craft\helpers\Json;
use ether\seo\fields\SeoField;
use ether\seo\models\data\SeoData;
use ether\seo\Seo;

class SeoService extends Component
{

	// Helpers
	// =========================================================================

	private function _getElementAndSeoFields ()
	{
		static $element = null;
		static $field = null;

		if ($element !== null)
			return [$field, $element];

		try {
			$resolve = \Craft::$app->request->resolve();
		} catch (\Exception $e) {
			$resolve = [null, []];
		}

		$resolve   = $resolve[1];
		$variables = array_key_exists('variables', $resolve)
			? $resolve['variables']
			: [];
		$handle = null;

		// Get all available "top-level" SEO fields
		foreach ($variables as $variable)
		{
			if (!is_subclass_of($variable, Element::class))
				continue;

			/** @var Element $variable */
			$element = $variable;

			/** @var Field $field */
			foreach ($variable->fieldLayout->getFields() as $field)
			{
				if (get_class($field) !== SeoField::class)
					continue;

				$handle = $field->handle;
				break;
			}

			break;
		}

		if ($handle)
			$field = $element->{$handle};
		else
			$field = new SeoData();

		return [$field, $element];
	}

}
