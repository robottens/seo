/**
 * SEO for Craft CMS
 *
 * @author    Tam McDonald
 * @copyright Ether Creative 2017
 * @link      https://ethercreative.co.uk
 * @package   SEO
 * @since     2.0.0
 */

import "babel-polyfill";
import FocusKeywords from "./field/FocusKeywords";
import Snippet from "./field/Snippet";

class SeoField {

	// Variables
	// =========================================================================

	// Set in Snippet
	snippetFields = {
		title: null,
		slug:  null,
		desc:  null,
	};

	// Overwritten, but useful for auto-complete
	options = {
		hasPreview: false,
		previewAction: null,
		isNew: false,
	};

	// SeoField
	// =========================================================================

	/**
	 * Initialize the SEO field
	 *
	 * @param {string} namespace - Field namespace
	 * @param {object} options - The options for the SEO field
	 * @constructor
	 */
	constructor (namespace, options) {
		this.options = options;

		new Snippet(namespace, this);

		if (!this.options.hasPreview) return;
		// TODO: Disable all preview related functionality

		new FocusKeywords(namespace, this);
	}

}

window.SeoField = SeoField;
