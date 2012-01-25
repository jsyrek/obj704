<?php

/**
 * This is the model class for table "defaultPage".
 *
 * The followings are the available columns in table 'defaultPage':
 * @property integer $pagetree_id
 * @property string $title
 * @property string $h1_box
 * @property string $content_box
 * @property integer $show_banner
 */
class DefaultPage extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return DefaultPage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'defaultPage';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pagetree_id, title, h1_box, content_box, show_banner', 'required'),
			array('show_banner', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('pagetree_id, title, h1_box, content_box, show_banner', 'safe', 'on'=>'search'),
		);
	}
        
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'pagetree_id' => 'Pagetree',
			'title' => 'Title',
			'h1_box' => 'H1 Box',
			'content_box' => 'Content Box',
			'show_banner' => 'Show Banner',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('pagetree_id',$this->pagetree_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('h1_box',$this->h1_box,true);
		$criteria->compare('content_box',$this->content_box,true);
		$criteria->compare('show_banner',$this->show_banner);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}