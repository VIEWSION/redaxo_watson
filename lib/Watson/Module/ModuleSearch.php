<?php

/**
 * This file is part of the Watson package.
 *
 * @author (c) Thomas Blum <thomas@addoff.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Watson\Module;

use \Watson\Foundation\Documentation;
use \Watson\Foundation\Search;
use \Watson\Foundation\SearchCommand;
use \Watson\Foundation\SearchResult;
use \Watson\Foundation\SearchResultEntry;
use \Watson\Foundation\Watson;

class ModuleSearch extends Search
{
    /**
     * Provide the commands of the search.
     *
     * @return array
     */
    public function commands()
    {
        return array('m');
    }

    /**
     *
     * @return Documentation
     */
    public function documentation()
    {
        $documentation = new Documentation();
        $documentation->setDescription(Watson::translate('watson_module_documentation_description'));
        $documentation->setUsage('m keyword');
        $documentation->setExample('$headline');
        $documentation->setExample('m $headline');
        $documentation->setExample('m module name');

        return $documentation;
    }

    /**
     *
     * @return an array of registered page params
     */
    public function registerPageParams()
    {
        
    }

    /**
     * Execute the search for the given SearchCommand
     *
     * @param  SearchCommand $search
     * @return SearchResult
     */
    public function fire(SearchCommand $search)
    {
        
        $search_result = new SearchResult();


        
        $fields = array(
            'name',
            'eingabe',
            'ausgabe',
        );

        $sql_query  = ' SELECT      id,
                                    name
                        FROM        ' . Watson::getTable('module') . '
                        WHERE       ' . $search->getSqlWhere($fields) . '
                        ORDER BY    name';

        $results = $this->getDatabaseResults($sql_query);

        if (count($results)) {

            foreach ($results as $result) {

                $url = Watson::getUrl(array('page' => 'module', 'modul_id' => $result['id'], 'function' => 'edit'));

                $entry = new SearchResultEntry();
                $entry->setValue($result['name']);
                $entry->setDescription(Watson::translate('watson_open_module'));
                $entry->setIcon('icon_module.png');
                $entry->setUrl($url);
                $entry->setQuickLookUrl($url);

                $search_result->addEntry($entry);

            }
        }


        return $search_result;
    }

}
