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

        $modules = [];
        $styles = [];
        if ($matchesmod) {
            foreach ($matchesmod as $matchmod) {
                $module = explode(';', trim($matchmod[1]));
                $style = isset($module[1]) ? trim($module[1]) : 'none';
                $styles[(int)trim($module[0])] = $style;
            }
        }

        if ($styles) {
            $db = Factory::getDbo();
            $query = $db->getQuery(true);
            $query
                ->select('*')
                ->from('#__modules')
                ->where('published=1')
                ->where('id in (' . implode(',', array_keys($styles)) . ')');
            $tmp = $db->setQuery($query)->loadObjectList();
            if ($tmp) {
                foreach ($tmp as $item) {
                    $modules[$item->id] = $item;
                    $modules[$item->id]->style = $styles[$item->id];
                }
                unset($tmp);
            }
        }

        if ($modules) {
            $renderer = Factory::getDocument()->loadRenderer('module');
            foreach ($matchesmod as $matchmod) {
                $id = explode(';', trim($matchmod[1]))[0];
                $output = '';
                if (isset($modules[$id])) {
                    $modparams = ['style' => $modules[$id]->style];
                    $output = $renderer->render($modules[$id], $modparams);
                }
                $article->text = str_replace($matchmod[0], $output, $article->text);
            }
        }
    }
}
