<?php

use common\models\KinoGenre;

class m151009_120438_kino_genre extends yii\db\Migration
{
    public function up()
    {
        $this->createTable('{{%kino_genre}}', [
            'id' => $this->primaryKey(),
            'title' => $this->text(),
            'url' => $this->string(),
            
        ]);
        
        $this->batchInsert('kino_genre', ['title'], [
            ['Аниме'],
            ['Биографический'],
            ['Боевик'],
            ['Вестерн'],
            ['Военный'],
            ['Детектив'],
            ['Детский'],
            ['Документальный'],
            ['Драма'],
            ['Исторический'],
            ['Кинокомикс'],
            ['Комедия'],
            ['Концерт'],
            ['Короткометражный'],
            ['Криминал'],
            ['Мелодрама'],
            ['Мистика'],
            ['Музыка'],
            ['Мультфильм'],
            ['Мюзикл'],
            ['Научный'],
            ['Приключения'],
            ['Реалити-шоу'],
            ['Семейный'],
            ['Спорт'],
            ['Ток-шоу'],
            ['Триллер'],
            ['Ужасы'],
            ['Фантастика'],
            ['Фильм-нуар'],
            ['Фэнтези'],
            ['Эротика'],
        ]);
        
        foreach (KinoGenre::find()->all() as $item){
            $item->save();
        }
    }

    public function down()
    {
        echo "m151009_120438_kino_genre cannot be reverted.\n";

        return false;
    }
}
