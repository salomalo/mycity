<?php

namespace common\behaviors;
use dosamigos\transliterator\TransliteratorHelper;
//use dosamigos\helpers\TransliteratorHelper;
use yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;

class Slug extends Behavior
{
    public $in_attribute = 'title';
    public $out_attribute = 'alias';
    public $translit = true;
    public $mongo = false;
    public $unique = false;
    public $max_len = 100;

    public function events()
    {
        return [ActiveRecord::EVENT_BEFORE_VALIDATE => 'getSlug'];
    }

    private function substr_slug($str)
    {
        if (mb_strlen($str) > $this->max_len) {
            preg_match('/^.{0,'.$this->max_len.'} .*?/ui', $str, $match);
            return $match[0];
        } else {
            return $str;
        }
    }
    
    public function getSlug($event)
    {
        if (empty($this->owner->{$this->out_attribute})) {
            $str_in_atribute = $this->substr_slug($this->owner->{$this->in_attribute});
            $this->owner->{$this->out_attribute} = $this->generateSlug($str_in_atribute);
        } else {
            $this->owner->{$this->out_attribute} = $this->generateSlug($this->owner->{$this->out_attribute});
        }
    }

    private function generateSlug($slug)
    {
        $slug = $this->slugify($slug);
        if ($this->unique or $this->checkUniqueSlug($slug)) {
            return $slug;
        } else {
            for ($suffix = 2; !$this->checkUniqueSlug($new_slug = $slug . '-' . $suffix); $suffix++) {}
            return $new_slug;
        }
    }

    private function slugify($slug)
    {
        if ( $this->translit ) {
            return Inflector::slug(TransliteratorHelper::process($slug), '-', true);
        } else {
            return $this->slug($slug, '-', true);
        }
    }

    private function slug($string, $replacement = '-', $lowercase = true)
    {
        $string = preg_replace('/[^\p{L}\p{Nd}]+/u', $replacement, $string);
        $string = trim($string, $replacement);

        return $lowercase ? strtolower($string) : $string;
    }

    private function checkUniqueSlug($slug)
    {
        $pk = $this->owner->primaryKey();
        $pk = $pk[0];
        $query = $this->owner->find()->where([$this->out_attribute => $slug]);

        if (!$this->owner->isNewRecord) {
            $this->mongo ? $query->andWhere(['not',$pk,['$eq' => $this->owner->{$pk}]]) : $query->andWhere(['!=',$pk,$this->owner->{$pk}]);
        }

        return !$query->one();
    }
}
