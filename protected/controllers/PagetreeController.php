<?php

class PageTreeController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
        public $layout='//layouts/column2';
            public function   init() {
            $this->registerAssets();
            parent::init();
        }

        /**
	 * Here you can load assets like javascript
	 */
        private function registerAssets(){
            Yii::app()->clientScript->registerCoreScript('jquery');
            $this->registerJs('webroot.js_plugins.jstree','/jquery.jstree.js');
            $this->registerCssAndJs('webroot.js_plugins.fancybox','/jquery.fancybox-1.3.4.js','/jquery.fancybox-1.3.4.css');
            $this->registerCssAndJs('webroot.js_plugins.jquery-ui-1816','/js/jquery-ui-1.8.16.custom.min.js','/css/smoothness/jquery-ui-1.8.16.custom.css');
            Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/json2/json2.js');
        }

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
        public function actionIndex()
	{
            if(!Yii::app()->user->isGuest)
            {
                $categories= PageTree::model()->findAll(array('order'=>'lft'));
                $identifiers=array();
                foreach($categories as $n=>$category)
                {
                    $identifiers[]="'".'node_'.$category->id."'";
                }
                $open_nodes=implode(',', $identifiers);

                $baseUrl=Yii::app()->baseUrl;

                $dataProvider=new CActiveDataProvider('PageTree');
                $this->render('index',array(
                    'dataProvider'=>$dataProvider,
                    'baseUrl'=> $baseUrl,
                    'open_nodes'=> $open_nodes,
                    //'model'=> $model,
                   // 'data'=> $data
                ));
            }else {
                $this->redirect(array('site/login'));
            }
        }
        
        private function renderData($rows) {
            $return = array();
            foreach ( $rows as $row ) {
                $return[] = $row['type'];
            }
            return $return;
            
        }
        
        public function actionContentPageURL()
        {
            $model=new PageTree;

            if(isset($_POST['PageTree']))
            {
                $model->attributes=$_POST['PageTree'];
                if($model->validate())
                {
                    // form inputs are valid, do something here
                    return;
                }
            }
            $this->renderPartial('ContentPageURL',array('model'=>$model),false,true);
        }
        
        /**
	 * According to type of page redirects to controller/action accountable for generating edit panel.
	 */
        public function actionTyperouter() {

            $session=new CHttpSession;
            $session->open();
            $session['post']=$_POST;
            
            $cs=Yii::app()->clientScript;
            $cs->scriptMap=array(
                'jquery.min.js'=>false,
                'jquery.js'=>false,
                'jquery.fancybox-1.3.4.js'=>false,
                'jquery.jstree.js'=>false,
                'jquery-ui-1.8.12.custom.min.js'=>false,
                'json2.js'=>false,
            );
            $cs->registerScriptFile(
                Yii::getPathOfAlias('application.components.js.page').'\_editDefaultPage.js',
                CClientScript::POS_END
            );
            
            if(isset($_POST['id'])){
                $id = str_replace('node_', '', $_POST['id']);
                $model = PageTree::model()->findbyPk($id);
                if ($model) {
                    $type = $model->type;
                    $controller = $model->controller;
                }    
            }

            if ( isset($_POST['FormDetails']) ) {
                $type = $_POST['FormDetails']['action'];
                $controller = $_POST['FormDetails']['controller'];
            }
            if ( !isset($controller) || $controller == '' ) {
                $controller = 'contentPage';
                $type = 'defaultPage';
            }
            
            $this->redirect(array($controller."/edit_".$type));
            
        }
        

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=PageTree::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

      public function actionFetchTree(){
          PageTree::printULTree();
      }


      public function actionRename(){

           $new_name=$_POST['new_name'];
           $id=$_POST['id'];
           $renamed_cat=$this->loadModel($id);
           $renamed_cat->name= $new_name;
          if ($renamed_cat->saveNode()){
              echo json_encode (array('success'=>true));
              exit;
      }else{
                  echo json_encode (array('success'=>false));
                    exit;
                }
      }

       public function actionRemove(){
                    $id=$_POST['id'];
                 $deleted_cat=$this->loadModel($id);


                if ( $deleted_cat->deleteNode() ){
               echo json_encode (array('success'=>true));
               exit;
                }else{
                  echo json_encode (array('success'=>false));
                    exit;
                }
      }

      public function actionReturnForm(){


               //don't reload these scripts or they will mess up the page
                //yiiactiveform.js still needs to be loaded that's why we don't use
                // Yii::app()->clientScript->scriptMap['*.js'] = false;
                $cs=Yii::app()->clientScript;
                $cs->scriptMap=array(
                                                 'jquery.min.js'=>false,
                                                 'jquery.js'=>false,
                                                 'jquery.fancybox-1.3.4.js'=>false,
                                                 'jquery.jstree.js'=>false,
                                                 'jquery-ui-1.8.12.custom.min.js'=>false,
                                                 'json2.js'=>false,

        );


   //Figure out if we are updating a Model or creating a new one.
  if(isset($_POST['update_id']))$model= $this->loadModel($_POST['update_id']);else $model=new PageTree;


        $this->renderPartial('_form', array('model'=>$model,'parent_id'=>!empty($_POST['parent_id'])?$_POST['parent_id']:''),false, true);

      }

 public function actionReturnView(){

               //don't reload these scripts or they will mess up the page
                //yiiactiveform.js still needs to be loaded that's why we don't use
                // Yii::app()->clientScript->scriptMap['*.js'] = false;
                $cs=Yii::app()->clientScript;
                $cs->scriptMap=array(
                                                 'jquery.min.js'=>false,
                                                 'jquery.js'=>false,
                                                 'jquery.fancybox-1.3.4.js'=>false,
                                                 'jquery.jstree.js'=>false,
                                                 'jquery-ui-1.8.12.custom.min.js'=>false,
                                                 'json2.js'=>false,

        );

        $model=$this->loadModel($_POST['id']);

        $this->renderPartial('view', array(
                                                          'model'=>$model,
                                                           ),
                                                          false, true);

      }

      public function actionCreateRoot()
	{

               if(isset($_POST['PageTree']))
		{

                       $new_root=new PageTree;
                       $new_root->attributes=$_POST['PageTree'];
		       if($new_root->saveNode(false)){
                                echo json_encode(array('success'=>true,
                                                              'id'=>$new_root->primaryKey)
                                                              );
                                exit;
                        } else
                        {
                            echo json_encode(array('success'=>false,
                                                                  'message'=>'Error.Root PageTree was not created.'
                                                                  )
                                                        );
                            exit;
                        }
		}

	}


  public function actionCreate(){

               if(isset($_POST))
		{
                       $model=new PageTree;
                      //set the submitted values
                       $model->attributes=$_POST;
                       $parent=$this->loadModel($_POST['parent_id']);
                       //return the JSON result to provide feedback.
			if($model->appendTo($parent)){
                                echo json_encode(array('success'=>true,
                                                              'id'=>$model->primaryKey)
                                                              );
                                exit;
                        } else
                        {
                            echo json_encode(array('success'=>false,
                                                                  'message'=>'Error.PageTree was not created.'
                                                                  )
                                                        );
                            exit;
                        }
		}

}


public function actionUpdate(){

		if(isset($_POST['PageTree']))
		{

                        $model=$this->loadModel($_POST['update_id']);
			$model->attributes=$_POST['PageTree'];

			if( $model->saveNode(false)){
                                      echo json_encode(array('success'=>true));
		             }else echo json_encode(array('success'=>false));
                }

}


public function actionMoveCopy(){

    $moved_node_id=$_POST['moved_node'];
    $new_parent_id=$_POST['new_parent'];
    $new_parent_root_id=$_POST['new_parent_root'];
    $previous_node_id=$_POST['previous_node'];
    $next_node_id=$_POST['next_node'];
    $copy=$_POST['copy'];

    //the following is additional info about the operation provided by
    // the jstree.It's there if you need it.See documentation for jstree.

   //  $old_parent_id=$_POST['old_parent'];
   //$pos=$_POST['pos'];
   //  $copied_node_id=$_POST['copied_node'];
   //  $replaced_node_id=$_POST['replaced_node'];

   //the  moved,copied  node
    $moved_node=$this->loadModel($moved_node_id);

  //if we are not moving as a new root...
  if ($new_parent_root_id!='root') {
  //the new parent node
   $new_parent=$this->loadModel($new_parent_id);
  //the previous sibling node (after the move).
     if($previous_node_id!='false')
       $previous_node=$this->loadModel($previous_node_id);


//if we move
if ($copy == 'false'){


    //if the moved node is only child of new parent node
    if ($previous_node_id=='false'&&  $next_node_id=='false')
    {

         if ($moved_node->moveAsFirst($new_parent)){
             echo json_encode(array('success'=>true));
             exit;
         }
    }

    //if we moved it in the first position
    else if($previous_node_id=='false' &&  $next_node_id !='false')
    {

             if($moved_node->moveAsFirst($new_parent)){
                     echo json_encode(array('success'=>true));
                     exit;
             }
    }
 //if we moved it in the last position
     else if($previous_node_id !='false' &&  $next_node_id == 'false')
     {

             if($moved_node->moveAsLast($new_parent)){
                   echo json_encode(array('success'=>true));
                     exit;
             }
     }
      //if the moved node is somewhere in the middle
     else if($previous_node_id !='false' &&  $next_node_id != 'false')
     {

             if($moved_node->moveAfter($previous_node)){
                  echo json_encode(array('success'=>true));
                     exit;
             }

     }

    }//end of it's a move
    //else if it is a copy
    else{
        //create the copied PageTree model
        $copied_node=new PageTree;
        //copy the attributes (only safe attributes will be copied).
        $copied_node->attributes=$moved_node->attributes;
        //remove the primary key
        $copied_node->id=null;


		if($copied_node->appendTo($new_parent)){
			echo json_encode(array('success'=>true,'id'=>$copied_node->primaryKey));
			exit;
		}
		}


	}//if the new parent is not root end
	//else,move it as a new Root
	else{
		//if moved/copied node is not Root
		if(!$moved_node->isRoot())  {
			if($moved_node->moveAsRoot()){
			echo json_encode(array('success'=>true ));
			}else{
			echo json_encode(array('success'=>false ));
			}
		}
		//else if moved/copied node is Root
		else {
			echo json_encode(array('success'=>false,'message'=>'Node is already a Root.Roots are ordered by id.' ));
		}
	}

}//action moveCopy

//UTILITY FUNCTIONS
  public static  function registerCssAndJs($folder, $jsfile, $cssfile) {
        $sourceFolder = YiiBase::getPathOfAlias($folder);
        $publishedFolder = Yii::app()->assetManager->publish($sourceFolder);
        Yii::app()->clientScript->registerScriptFile($publishedFolder . $jsfile, CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerCssFile($publishedFolder . $cssfile);
    }

 public static function registerCss($folder, $cssfile) {
        $sourceFolder = YiiBase::getPathOfAlias($folder);
        $publishedFolder = Yii::app()->assetManager->publish($sourceFolder);
        Yii::app()->clientScript->registerCssFile($publishedFolder .'/'. $cssfile);
        return $publishedFolder .'/'. $cssfile;
    }

     public static function registerJs($folder, $jsfile) {
        $sourceFolder = YiiBase::getPathOfAlias($folder);
        $publishedFolder = Yii::app()->assetManager->publish($sourceFolder);
        Yii::app()->clientScript->registerScriptFile($publishedFolder .'/'.  $jsfile);
        return $publishedFolder .'/'. $jsfile;
    }
	

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='pagetree-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
