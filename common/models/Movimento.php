<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "movimentacoes".
 *
 * @property int $id
 * @property int $material_id
 * @property int $user_id
 * @property string $tipo
 * @property int $quantidade
 * @property string|null $data_movimentacao
 * @property string|null $origem
 *
 * @property Materiais $material
 * @property User $user
 */
class Movimento extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const TIPO_ENTRADA = 'entrada';
    const TIPO_SAIDA = 'saida';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'movimentacoes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['origem'], 'default', 'value' => null],
            [['material_id', 'user_id', 'tipo', 'quantidade'], 'required'],
            [['material_id', 'user_id', 'quantidade'], 'integer'],
            [['tipo'], 'string'],
            [['data_movimentacao'], 'safe'],
            [['origem'], 'string', 'max' => 100],
            ['tipo', 'in', 'range' => array_keys(self::optsTipo())],
            [['material_id'], 'exist', 'skipOnError' => true, 'targetClass' => Materiais::class, 'targetAttribute' => ['material_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'user_id' => 'User ID',
            'tipo' => 'Tipo',
            'quantidade' => 'Quantidade',
            'data_movimentacao' => 'Data Movimentacao',
            'origem' => 'Origem',
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

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }


    /**
     * column tipo ENUM value labels
     * @return string[]
     */
    public static function optsTipo()
    {
        return [
            self::TIPO_ENTRADA => 'entrada',
            self::TIPO_SAIDA => 'saida',
        ];
    }

    /**
     * @return string
     */
    public function displayTipo()
    {
        return self::optsTipo()[$this->tipo];
    }

    /**
     * @return bool
     */
    public function isTipoEntrada()
    {
        return $this->tipo === self::TIPO_ENTRADA;
    }

    public function setTipoToEntrada()
    {
        $this->tipo = self::TIPO_ENTRADA;
    }

    /**
     * @return bool
     */
    public function isTipoSaida()
    {
        return $this->tipo === self::TIPO_SAIDA;
    }

    public function setTipoToSaida()
    {
        $this->tipo = self::TIPO_SAIDA;
    }
}
