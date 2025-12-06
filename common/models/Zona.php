<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "zonas".
 *
 * @property int $id
 * @property string $nome_zona
 * @property string|null $descricao
 *
 * @property Material[] $materiais
 */
class Zona extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'zonas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descricao'], 'default', 'value' => null],
            [['nome_zona'], 'required'],
            [['descricao'], 'string'],
            [['nome_zona'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome_zona' => 'Nome Zona',
            'descricao' => 'Descricao',
        ];
    }

    /**
     * Gets query for [[Materiais]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMateriais()
    {
        return $this->hasMany(Material::class, ['zona_id' => 'id']);
    }

}
