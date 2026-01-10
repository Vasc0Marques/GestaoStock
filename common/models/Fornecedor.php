<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "fornecedores".
 *
 * @property int $id
 * @property string $nome_fornecedor
 * @property string|null $nif
 * @property string|null $telefone
 * @property string|null $email
 * @property string|null $morada
 * @property int|null $ativo
 *
 * @property Encomendas[] $encomendas
 * @property MaterialFornecedor[] $materiaisFornecedores
 */
class Fornecedor extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fornecedores';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nif', 'telefone', 'email', 'morada'], 'default', 'value' => null],
            [['ativo'], 'default', 'value' => 1],
            [['nome_fornecedor'], 'required'],
            [['morada'], 'string'],
            [['ativo'], 'integer'],
            [['nome_fornecedor', 'email'], 'string', 'max' => 100],
            [['nif', 'telefone'], 'string', 'max' => 20],
            [['imagem'], 'string', 'max' => 255],
            [['imagemFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif'],
        ];
    }
    /**
     * @var \yii\web\UploadedFile
     */
    public $imagemFile;

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome_fornecedor' => 'Nome Fornecedor',
            'nif' => 'Nif',
            'telefone' => 'Telefone',
            'email' => 'Email',
            'morada' => 'Morada',
            'ativo' => 'Ativo',
        ];
    }

    /**
     * Gets query for [[Encomendas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEncomendas()
    {
        return $this->hasMany(Encomendas::class, ['fornecedor_id' => 'id']);
    }

    /**
     * Gets query for [[MateriaisFornecedores]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMateriaisFornecedores()
    {
        return $this->hasMany(MaterialFornecedor::class, ['fornecedor_id' => 'id']);
    }

    /**
     * Retorna o URL da imagem para uso no backend
     * @return string
     */
    public function getImageUrlBackend()
    {
        if ($this->imagem && file_exists(Yii::getAlias('@backend/web/' . $this->imagem))) {
            return Yii::getAlias('@web/' . $this->imagem);
        }
        return Yii::getAlias('@web/img/noImage.jpg');
    }

    /**
     * Retorna o URL da imagem para uso no frontend (aponta para o backend)
     * @return string
     */
    public function getImageUrlFrontend()
    {
        if ($this->imagem) {
            // Caminho absoluto para o backend a partir do frontend
            return '/gestaostock/backend/web/' . $this->imagem;
        }
        return '/gestaostock/backend/web/img/noImage.jpg';
    }

    /**
     * Verifica se tem imagem válida
     * @return bool
     */
    public function hasImage()
    {
        return $this->imagem && file_exists(Yii::getAlias('@backend/web/' . $this->imagem));
    }
}
