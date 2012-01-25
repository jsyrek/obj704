<?php

class ContentPageDetails extends CFormModel
{
    public $type;
    public $url;
 
    public function rules()
    {
        return array(
            array('type', 'length', 'max'=>3, 'min'=>1 ),
            array('url', 'length', 'max'=>255, 'min'=>1 ),
        );
    }
 
}