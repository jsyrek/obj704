<?php

class ContentPageController extends Controller
{

        public function   init() {
            $this->registerAssets();
            parent::init();
        }
        
        private function registerAssets(){
            Yii::app()->clientScript->registerCoreScript('jquery');
            Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/page/mainjs.js');
        }
    
        public function filters()
        {
            return array(
            array(
                'COutputCache +index',
                'duration'=>0,
                'varyByParam'=>array('alias'),
            ),
            );
        }
        
        public static function registerJs($folder, $jsfile) {
            $sourceFolder = YiiBase::getPathOfAlias($folder);
            $publishedFolder = Yii::app()->assetManager->publish($sourceFolder);
            Yii::app()->clientScript->registerScriptFile($publishedFolder .'/'.  $jsfile);
            return $publishedFolder .'/'. $jsfile;
        }
        
        public function actionIndex($alias='')
        {
            $this->layout = '//layouts/front';
            if ( $alias == '' ){
                    $alias = '/';
            }
            $model = new PageTree;
            $sql = "SELECT * FROM PageTree WHERE url = '".$alias."' ";
            $result = Yii::app()->db->createCommand($sql)->queryRow();
            //print_r($result);exit();
            if (!$result) {
                    throw new CHttpException(404,'The specified post cannot be found.');
            }
            $type = $result['type'];
            $this->$type($result);
        }
        
        public function defaultPage($pagetree) {
            $model = new DefaultPage;
            $sql = "SELECT * FROM `defaultPage` WHERE pagetree_id = ".$pagetree['id'];
            $result = Yii::app()->db->createCommand($sql)->queryRow();
            $this->render('/page/defaultPage',array( 'data'=>$result ) );
        }
        
        public function frontPage($pagetree) {
            $model = new DefaultPage;
            $sql = "SELECT * FROM `defaultPage` WHERE pagetree_id = ".$pagetree['id'];
            $result = Yii::app()->db->createCommand($sql)->queryRow();  
            $this->render('/page/frontPage',array( 'data'=>$result ) );   
        }
        
        public function actionEdit_defaultPage()
        {
            if(!Yii::app()->user->isGuest)
            { 
                $cs=Yii::app()->clientScript;
                $cs->scriptMap=array(
                    'jquery.min.js'=>false,
                    'jquery.js'=>false,
                    'jquery.fancybox-1.3.4.js'=>false,
                    'jquery.jstree.js'=>false,
                    'jquery-ui-1.8.12.custom.min.js'=>false,
                    'json2.js'=>false,
                );
                Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/page/_editDefaultPage.js');
                
                $session=new CHttpSession;
                $session->open();
                $post = $session['post'];
                
                $formDetails =new FormDetails();
                if(isset($post['id']))
                {
                    $id = str_replace('node_', '', $post['id']);
                    $model=DefaultPage::model()->findbyPk($id);
                    if (!$model) {
                        $model=new DefaultPage;
                    }
                    $model_pagetree= PageTree::model()->findbyPk($id);
                    if (!$model_pagetree) {
                        $model_pagetree=new PageTree;
                    }
                    $url = $model_pagetree->url;
                    
                } else {
                    $model=new DefaultPage;
                    $model_pagetree=new PageTree;
                    $form_details = new FormDetails;
                    $id = $model->pagetree_id;
                }

                // uncomment the following code to enable ajax-based validation
                if(isset($_POST['ajax']) && $_POST['ajax']==='defaultPage-form')
                {
                   $model_pagetree->scenario = 'ajax';
                   echo CActiveForm::validate(array($model_pagetree,$model));
                    Yii::app()->end();
                }

                //save post data
                if(isset($post['DefaultPage'],$post['PageTree']))
                {  
					
					$url = $post['PageTree']['url'];
                    $model->attributes=$post['DefaultPage'];
                    $model_pagetree->attributes=$post['PageTree'];
                    
                    $valid=$model->validate();
                    $valid=$model_pagetree->validate() && $valid;
					
					
					$model->h1_box = str_replace("&oacute;", "ó", $model->h1_box  );

                    if($valid)
                    {

                        try {	
                            $model->save();
                            //return;
                        } catch(CDbException $e) {
                            if(trim($e->getCode()) == "23000"){
                                $model->isNewRecord = false;
                                $model->save();
                                //return;
                            }
                        }
                        
                        $type='defaultPage';
                        $controller='contentPage';
                        $sql = "UPDATE PageTree
                                SET url='".$url."', type='".$type."', controller='".$controller."'
                                WHERE id=".$model->pagetree_id;
                        $result = Yii::app()->db->createCommand($sql)->execute();
                    }
                }

                $this->renderPartial('/page/_editDefaultPage',array('form_details'=>$formDetails, 'model'=>$model,'model_pagetree'=>$model_pagetree,'id'=>$id,'url'=>$url),false,true);
            }
        }
        
        public function actionEdit_frontPage()
        {
            if(!Yii::app()->user->isGuest)
            {
                $cs=Yii::app()->clientScript;
                $cs->scriptMap=array(
                    'jquery.min.js'=>false,
                    'jquery.js'=>false,
                    'jquery.fancybox-1.3.4.js'=>false,
                    'jquery.jstree.js'=>false,
                    'jquery-ui-1.8.12.custom.min.js'=>false,
                    'json2.js'=>false,
                );
                Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/page/_editDefaultPage.js');
                
                $session=new CHttpSession;
                $session->open();
                $post = $session['post'];
                
                $formDetails =new FormDetails();
                if(isset($post['id']))
                {
                    $id = str_replace('node_', '', $post['id']);
                    $model=DefaultPage::model()->findbyPk($id);
                    if (!$model) {
                        $model=new DefaultPage;
                    }
                    
                    $model_pagetree= PageTree::model()->findbyPk($id);
                    if (!$model_pagetree) {
                        $model_pagetree=new PageTree;
                    }
                    $url = $model_pagetree->url;
                    
                } else {
                    $model=new DefaultPage;
                    $model_pagetree=new PageTree;
                    $id = $model->pagetree_id;
                }

                // uncomment the following code to enable ajax-based validation
                if(isset($_POST['ajax']) && $_POST['ajax']==='frontPage-form')
                {
                   $model_pagetree->scenario = 'ajax';
                   echo CActiveForm::validate(array($model_pagetree,$model));
                    Yii::app()->end();
                }

                if(isset($post['DefaultPage'],$post['PageTree']))
                {
                    
                    $url = $post['PageTree']['url'];
                    $model->attributes=$post['DefaultPage'];
                    $model_pagetree->attributes=$post['PageTree'];
                    
                    $valid=$model->validate();
                    $valid=$model_pagetree->validate() && $valid;

                    //print_r($model->attributes);exit();
                    if($valid)
                    {
                        
                        try {	
                            $model->save();
                            //return;
                        } catch(CDbException $e) {
                            if(trim($e->getCode()) == "23000"){
                                $model->isNewRecord = false;
                                $model->save();
                                //return;
                            }
                        }
                        
                        $type='frontPage';
                        $controller='contentPage';
                        $sql = "UPDATE PageTree
                                SET url='".$url."', type='".$type."', controller='".$controller."'
                                WHERE id=".$model->pagetree_id;
                        $result = Yii::app()->db->createCommand($sql)->execute();
                        

                    }
                }
                echo $this->renderPartial('/page/_editFrontPage',array('form_details'=>$formDetails, 'model'=>$model,'model_pagetree'=>$model_pagetree,'id'=>$id,'url'=>$url),true,true);
            }
        }

        
}