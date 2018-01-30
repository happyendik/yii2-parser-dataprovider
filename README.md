Установка
------------------
* Установка пакета с помощью Composer.
```
composer require happyendik/yii2-parser-dataprovider
```

Использование
------------------
* Создайте свой класс DataProvider, унаследуйте его от абстрактного класса `happyendik\ParserDataProvider`
 и реализуйте абстрактные методы данного класса:

```
    /**
     * @return integer
     */
    protected function getItemsOnPage()
    {
        //Определение количества моделей на странице
    }
```
```
    /**
     * @return integer
     */
    protected function getPagesAmount()
    {
        //определение количества страниц
    }
```
```
    /**
     * @return integer
     */
    protected function getItemsAmount()
    {
        //определение количества моделей (например, новостей или статей) 
    }
```
```
    /**
     * @param integer $i
     * @return happyendik\ItemInterface[]
     */
    protected function getItemsForPage($i)
    {
        //Получение моделей для страницы
        //Метод должен возвращать массив объектов, реализующих happyendik/ItemInterface
            $items[] = new Item([
                'attribute1' => $item->attribute1,
                'attribut2' => $item->attribute2,
                .....
                'attributeN' => $item->attributeN
            ]);
        }

        return $items;
    }
}
```

* Создайте класс для модели, реализующий `happyendik\ItemInterface`, и перечислите все аттрибуты модели:

```
class Item extends Object implements happyendik\ItemInterface
{
    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $link;

    /**
     * @return array
     */
    public function getAttributes()
    {
        return [
            'title' => $this->title,
            'link' => $this->link
        ];
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @inheritdoc
     */
    public function getLink()
    {
        return $this->link;
    }
}

```
* Подключайте свой `CustomDataProvider`, как дефолтные датапровайдеры в Yii2
```
    public function actionIndex()
    {
        $dataProvider = new CustomDataProvider([
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }
``` 
* И используйте стандартные приемы работы с датапровайдерами
```
echo \yii\widgets\ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => function ($model) {
        return '<a href="' . $model['link'] . '">' . $model['title'] . '</a>';
    }
]);

```
