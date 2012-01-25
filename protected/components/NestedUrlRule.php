<?php

/**
 * Extends CBaseUrlRule to check Page tree nested model set URL's
 */
class NestedUrlRule extends CBaseUrlRule
{
    public $connectionID = 'db';
 
    public function createUrl($manager,$route,$params,$ampersand) {

      echo $route;exit();
                if ($route==='defaultPage') {
                        $_GET['alias']=130;
                        return $this->route;
                } else
                        return false;   
        
    }
 
    public function parseUrl($manager,$request,$pathInfo,$rawPathInfo)
    {
 //return '/defaultPage/117';
        
        $model = new PageTree;
        $sql = "SELECT * FROM PageTree WHERE url = '".$rawPathInfo."' ";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        
        //return '___ '.$result['type'].'/'.$result['id'];
        
        if (!$result) {
            return false;
        } else {
            //return $result['type'].'/'.$result['id'];
           
        }   

    }
}
