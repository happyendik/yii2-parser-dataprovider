<?php

namespace happyendik;

use yii\data\BaseDataProvider;

/**
 * Class ParserDataProvider
 * @package app\dataproviders
 */
abstract class ParserDataProvider extends BaseDataProvider
{
    /**
     * @return integer
     */
    abstract protected function getItemsOnPage();

    /**
     * @return integer
     */
    abstract protected function getPagesAmount();

    /**
     * @return integer
     */
    abstract protected function getItemsAmount();

    /**
     * @param integer $i
     * @return ItemInterface[]
     */
    abstract protected function getItemsForPage($i);


    /**
     * @param int $offset
     * @param int $limit
     * @return array|mixed
     */
    protected function getInformation($offset = 0, $limit = 0)
    {
        $models = [];
        $startPage = 0;
        $lastPage = $this->getPagesAmount();

        if ($offset) {
            $startPage = intdiv($offset, $this->getItemsOnPage());
        }

        for ($i = $startPage; $i < $lastPage; $i++) {

            $items = $this->getItemsForPage($i);

            foreach ($items as $key => $item) {
                $realKey = ($key + 1) + $this->getItemsOnPage()*$i;

                if ($limit && $realKey > $offset + $limit) {
                    break 2;
                }

                if ($realKey > $offset) {
                    $attributes = [];
                    foreach ($item->getAttributes() as $title => $attribute) {
                        $attributes[$title] = $attribute;
                    }

                    $models["$realKey"] = $attributes;
                }
            }
        }

        return $models;
    }


    /**
     * @return array
     */
    protected function prepareModels()
    {
        $pagination = $this->getPagination();

        if ($pagination === false) {
            $models = $this->getInformation();
        } else {
            $pagination->totalCount = $this->getTotalCount();
            $limit = $pagination->getLimit();
            $offset = $pagination->getOffset();
            $models = $this->getInformation($offset, $limit);
        }

        return $models;
    }

    /**
     * @inheritdoc
     */
    protected function prepareKeys($models)
    {
        return array_keys($models);
    }

    /**
     * @inheritdoc
     */
    protected function prepareTotalCount()
    {
        return $this->getItemsAmount();
    }
}
