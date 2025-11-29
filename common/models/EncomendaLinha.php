<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "encomenda_linhas".
 *
 * @property int $id
 * @property int $encomenda_id
 * @property int $material_id
 * @property string $nome_material
 * @property int $quantidade
 * @property float $preco_unitario
 * @property float|null $subtotal
 *
 * @property Encomendas $encomenda
 * @property Materiais $material
 */
class EncomendaLinha extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'encomenda_linhas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subtotal'], 'default', 'value' => null],
            [['encomenda_id', 'material_id', 'nome_material', 'quantidade', 'preco_unitario'], 'required'],
            [['encomenda_id', 'material_id', 'quantidade'], 'integer'],
            [['preco_unitario', 'subtotal'], 'number'],
            [['nome_material'], 'string', 'max' => 100],
            [['encomenda_id'], 'exist', 'skipOnError' => true, 'targetClass' => Encomendas::class, 'targetAttribute' => ['encomenda_id' => 'id']],
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
            'encomenda_id' => 'Encomenda ID',
            'material_id' => 'Material ID',
            'nome_material' => 'Nome Material',
            'quantidade' => 'Quantidade',
            'preco_unitario' => 'Preco Unitario',
            'subtotal' => 'Subtotal',
        ];
    }

    /**
     * Gets query for [[Encomenda]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEncomenda()
    {
        return $this->hasOne(Encomendas::class, ['id' => 'encomenda_id']);
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
