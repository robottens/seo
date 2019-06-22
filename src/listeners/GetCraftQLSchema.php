<?php

namespace ether\seo\listeners;

use markhuot\CraftQL\Events\GetFieldSchema;
use markhuot\CraftQL\Types\VolumeInterface;

class GetCraftQLSchema
{

	function handle (GetFieldSchema $event)
	{
		$event->handled = true;

		$fieldObject =
			$event->schema->createObjectType('SeoData');
		$fieldObject->addStringField('title');
		$fieldObject->addStringField('description');
		$fieldObject->addStringField('keywords');
		$fieldObject->addField('social')->type($socialFieldObject);

		$event->schema->addField($event->sender)->type($fieldObject);
	}

}
