<div id="container">
        <div id="containertop">
            <div id="h1wrapper">
                    <h1 class="fontface"><?php $this->pageTitle=$data['title']; echo $data['title'];  ?></h1>
            </div>
            <?php echo $data['h1_box']; ?>
        </div>
    
        <div id="content">
            <div class="column-a">
                <?php echo $data['content_box']; ?>
            </div>
            <div class="column-b">
                <div id="menucolumn">
                    <?php $this->widget('ext.pageTreeMenu.PTMenuWidget', array( 'id' => $data['pagetree_id'] ) ) ?>
                </div>
            </div>
            <div class="clear"></div>        
        </div>
</div>


