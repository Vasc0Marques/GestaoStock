<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "stock".
 *
 * @property int $id
 * @property int $material_id
 * @property int|null $quantidade_atual
 * @property string|null $ultima_atualizacao
 *
 * @property Materiais $material
 */
class Stock extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stock';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quantidade_atual'], 'default', 'value' => 0],
            [['material_id'], 'required'],
            [['material_id', 'quantidade_atual'], 'integer'],
            [['ultima_atualizacao'], 'safe'],
            [['material_id'], 'exist', 'skipOnError' => true, 'targetClass' => Materiais::class, 'targetAttribute' => ['material_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'material_id' => 'Material ID',
            'quantidade_atual' => 'Quantidade Atual',
            'ultima_atualizacao' => 'Ultima Atualizacao',
        ];
    }

    /**
     * Gets query for [[Material]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMaterial()
    {
        return $this->hasOne(Materiais::class, ['id' => 'material_id']);
    }

}
