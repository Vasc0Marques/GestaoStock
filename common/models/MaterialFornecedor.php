<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "materiais_fornecedores".
 *
 * @property int $id
 * @property int $material_id
 * @property int $fornecedor_id
 * @property float $preco_base
 * @property int|null $prazo_entrega_dias
 *
 * @property Fornecedores $fornecedor
 * @property Materiais $material
 */
class MaterialFornecedor extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'materiais_fornecedores';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prazo_entrega_dias'], 'default', 'value' => 0],
            [['material_id', 'fornecedor_id', 'preco_base'], 'required'],
            [['material_id', 'fornecedor_id', 'prazo_entrega_dias'], 'integer'],
            [['preco_base'], 'number'],
            [['fornecedor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fornecedores::class, 'targetAttribute' => ['fornecedor_id' => 'id']],
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
            'fornecedor_id' => 'Fornecedor ID',
            'preco_base' => 'Preco Base',
            'prazo_entrega_dias' => 'Prazo Entrega Dias',
        ];
    }

    /**
     * Gets query for [[Fornecedor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFornecedor()
    {
        return $this->hasOne(Fornecedores::class, ['id' => 'fornecedor_id']);
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
