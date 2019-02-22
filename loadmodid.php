<?php defined('_JEXEC') or die;
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.loadmodid
 * @copyright   Copyright (C) 2019 Aleksey A. Morozov. All rights reserved.
 * @license     GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl-3.0.txt
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

        $matchesmod = [];
        preg_match_all('/{loadmodid\s(.*?)}/i', $article->text, $matchesmod, PREG_SET_ORDER);

        if ($matchesmod) {
            foreach ($matchesmod as $matchmod) {
                $module = explode(';', trim($matchmod[1]));
                $style = isset($module[1]) ? $module[1] : 'none';
                $module = (int)$module[0];
                $output = $this->loadmod($module, $style);
                $article->text = str_replace($matchmod[0], $output, $article->text);
            }
        }
    }

    protected function loadmod($module, $style = 'none')
    {
        $document = Factory::getDocument();
        $renderer = $document->loadRenderer('module');
        $params = ['style' => $style];

        $module = Factory::getDbo()
            ->setQuery('select * from #__modules where published=1 and id=' . (int)$module)
            ->loadObject();
        if ($module) {
            return $renderer->render($module, $params);
        } else {
            return '';
        }
    }
}
