<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "encomendas".
 *
 * @property int $id
 * @property int $fornecedor_id
 * @property int $user_id
 * @property string $data_encomenda
 * @property string|null $estado
 * @property float|null $total
 * @property string|null $observacoes
 * @property string|null $data_rececao
 *
 * @property EncomendaLinhas[] $encomendaLinhas
 * @property Fornecedores $fornecedor
 * @property User $user
 */
class Encomenda extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const ESTADO_PENDENTE = 'Pendente';
    const ESTADO_RECEBIDA = 'Recebida';
    const ESTADO_CANCELADA = 'Cancelada';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'encomendas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['observacoes', 'data_rececao'], 'default', 'value' => null],
            [['estado'], 'default', 'value' => 'Pendente'],
            [['total'], 'default', 'value' => 0.00],
            [['fornecedor_id', 'user_id', 'data_encomenda'], 'required'],
            [['fornecedor_id', 'user_id'], 'integer'],
            [['data_encomenda', 'data_rececao'], 'safe'],
            [['estado', 'observacoes'], 'string'],
            [['total'], 'number'],
            ['estado', 'in', 'range' => array_keys(self::optsEstado())],
            [['fornecedor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fornecedores::class, 'targetAttribute' => ['fornecedor_id' => 'id']],
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
            'fornecedor_id' => 'Fornecedor ID',
            'user_id' => 'User ID',
            'data_encomenda' => 'Data Encomenda',
            'estado' => 'Estado',
            'total' => 'Total',
            'observacoes' => 'Observacoes',
            'data_rececao' => 'Data Rececao',
        ];
    }

    /**
     * Gets query for [[EncomendaLinhas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEncomendaLinhas()
    {
        return $this->hasMany(EncomendaLinhas::class, ['encomenda_id' => 'id']);
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }


    /**
     * column estado ENUM value labels
     * @return string[]
     */
    public static function optsEstado()
    {
        return [
            self::ESTADO_PENDENTE => 'Pendente',
            self::ESTADO_RECEBIDA => 'Recebida',
            self::ESTADO_CANCELADA => 'Cancelada',
        ];
    }

    /**
     * @return string
     */
    public function displayEstado()
    {
        return self::optsEstado()[$this->estado];
    }

    /**
     * @return bool
     */
    public function isEstadoPendente()
    {
        return $this->estado === self::ESTADO_PENDENTE;
    }

    public function setEstadoToPendente()
    {
        $this->estado = self::ESTADO_PENDENTE;
    }

    /**
     * @return bool
     */
    public function isEstadoRecebida()
    {
        return $this->estado === self::ESTADO_RECEBIDA;
    }

    public function setEstadoToRecebida()
    {
        $this->estado = self::ESTADO_RECEBIDA;
    }

    /**
     * @return bool
     */
    public function isEstadoCancelada()
    {
        return $this->estado === self::ESTADO_CANCELADA;
    }

    public function setEstadoToCancelada()
    {
        $this->estado = self::ESTADO_CANCELADA;
    }
}
