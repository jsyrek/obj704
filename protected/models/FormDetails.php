<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class FormDetails extends CFormModel
{
	public $name;

	public function attributeLabels()
	{
		return array(
			'name'=>'Form Name',
		);
	}
}