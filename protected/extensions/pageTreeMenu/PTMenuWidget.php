<?php
/**
* Generate HTML ul li menu when invoked
*/
class PTMenuWidget extends CWidget
{
    public $id;
    
    public function run(){
        echo $this->generateMenu();
    }
    
    protected function generateMenu(){
        $category=PageTree::model()->findByPk($this->id);
        $descendants=$category->children()->findAll();

        $li ='';
        if ( $descendants && $this->id!='' ){
                foreach ( $descendants as $row ){
                        $li .= '<li><a href="'.$row->url.'">'.$row->name.'</a></li>';
                }
        }

        $return = '
        <ul>
            <li><a href="'.Yii::app()->request->baseUrl.'/">Home Page</a></li>
           '.$li.'
        </ul>
        ';
        return $return;

    }
}
