<?php 
$this->widget('ext.pixelmatrix.EUniform', array(
    'selector' => 'select:not(.no-uniform), input:not(:button), textarea',
    'theme' => 'default',
    'options' => array('useID' => false)
));
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'defaultPage-form',
	'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
)); ?>

<div class="form">
	<p class="note">Fields2 with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary(array($model_pagetree, $model, $form_details)); ?>


	<div class="row">
		<?php echo $form->hiddenField($model,'pagetree_id',array('value'=>$id)); ?>
		<?php echo $form->error($model,'pagetree_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title'); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model_pagetree,'url'); ?>
		<?php echo $form->textField($model_pagetree,'url',array('value'=>$url)); ?>
		<?php echo $form->error($model_pagetree,'url'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'h1_box'); ?>
		<?php echo $form->textField($model,'h1_box'); ?>
		<?php echo $form->error($model,'h1_box'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content_box'); ?>
                <?php echo $form->textArea($model,'content_box'); ?>
		<?php echo $form->error($model,'content_box'); ?>
	</div>

	<div class="row">
            <?php echo $form->hiddenField($model,'show_banner',array('value'=>0)); ?>
            <?php echo $form->hiddenField($form_details,'action',array('value'=>'defaultPage')); ?>
            <?php echo $form->hiddenField($form_details,'controller',array('value'=>'contentPage')); ?>
	</div>


	<div class="row buttons">
		<?php 
                echo CHtml::ajaxSubmitButton('frontPage-form',Yii::app()->request->baseUrl.'/pagetree/typerouter',array(
                'update'=>'#jstree_editmodule',
                'success'=>'js:function(){
                    $(\'#jstree_editmodule\').html();
                }',
                ));
                ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->