<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "materiais".
 *
 * @property int $id
 * @property string $nome_material
 * @property string $codigo
 * @property int $categoria_id
 * @property int $zona_id
 * @property string|null $unidade_medida
 * @property int|null $stock_minimo
 * @property string|null $criado_em
 *
 * @property Categorias $categoria
 * @property EncomendaLinhas[] $encomendaLinhas
 * @property MaterialFornecedor[] $materiaisFornecedores
 * @property Movimentacoes[] $movimentacoes
 * @property Stock[] $stocks
 * @property Zonas $zona
 */
class Material extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'materiais';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['unidade_medida'], 'default', 'value' => null],
            [['stock_minimo'], 'default', 'value' => 0],
            [['nome_material', 'codigo', 'categoria_id', 'zona_id'], 'required'],
            [['categoria_id', 'zona_id', 'stock_minimo'], 'integer'],
            [['criado_em'], 'safe'],
            [['nome_material'], 'string', 'max' => 100],
            [['codigo'], 'string', 'max' => 50],
            [['unidade_medida'], 'string', 'max' => 20],
            [['codigo'], 'unique'],
            ['categoria_id', 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Categoria::class, 'targetAttribute' => ['categoria_id' => 'id']],
            ['zona_id', 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Zona::class, 'targetAttribute' => ['zona_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome_material' => 'Nome Material',
            'codigo' => 'Codigo',
            'categoria_id' => 'Categoria ID',
            'zona_id' => 'Zona ID',
            'unidade_medida' => 'Unidade Medida',
            'stock_minimo' => 'Stock Minimo',
            'criado_em' => 'Criado Em',
        ];
    }

    /**
     * Gets query for [[Categoria]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoria()
    {
        return $this->hasOne(\common\models\Categoria::class, ['id' => 'categoria_id']);
    }

    /**
     * Gets query for [[EncomendaLinhas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEncomendaLinhas()
    {
        return $this->hasMany(EncomendaLinhas::class, ['material_id' => 'id']);
    }

    /**
     * Gets query for [[MateriaisFornecedores]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMateriaisFornecedores()
    {
        return $this->hasMany(MaterialFornecedor::class, ['material_id' => 'id']);
    }

    /**
     * Gets query for [[Movimentacoes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMovimentacoes()
    {
        return $this->hasMany(Movimento::class, ['material_id' => 'id']);
    }

    /**
     * Gets query for [[Stocks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStocks()
    {
        return $this->hasMany(Stock::class, ['material_id' => 'id']);
    }

    /**
     * Gets query for [[Zona]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getZona()
    {
        return $this->hasOne(\common\models\Zona::class, ['id' => 'zona_id']);
    }

}
