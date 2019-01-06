<?php defined('_JEXEC') or die;
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.loadmodid
 *
 * @copyright   Copyright © 2019 Aleksey A. Morozov. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;

class PlgContentLoadmodid extends CMSPlugin
{
	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{
		if ($context == 'com_finder.indexer' || strpos($article->text, 'loadmodid') === false) {
			return true;
		}

		$regexmod = '/{loadmodid\s(.*?)}/i';
		preg_match_all($regexmod, $article->text, $matchesmod, PREG_SET_ORDER);

		if ($matchesmod) {
			foreach ($matchesmod as $matchmod) {
				$module = (int)trim($matchmod[1]);
				$output = $this->loadmod($module);
				$article->text = str_replace($matchmod[0], $output, $article->text);
			}
		}
	}

	protected function loadmod($module)
	{
		$document = Factory::getDocument();
		$renderer = $document->loadRenderer('module');
		$params = ['style' => 'none'];

		$module = Factory::getDbo()->setQuery('select * from #__modules where id=' . (int)$module)->loadObject();
		if ($module) {
			return $renderer->render($module, $params);
		}
	}
}
