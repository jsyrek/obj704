<div id="container">

    <div id="content">
        <div id="h1wrapper">
            <h2 id="fotografia-lotnicza" class="fontface"><?php $this->pageTitle=$data['title']; echo $data['title'];  ?></h2>
        </div>
        <?php echo $data['h1_box']; ?>
        <div class="column-a">
         <?php echo $data['content_box']; ?>
        </div>
        <div class="column-b">

            <div id="menucolumn">
                <?php $this->widget('ext.pageTreeMenu.PTMenuWidget', array( 'id' => 0 ) ) ?>
            </div>
        </div>
        <div class="clear"></div>

    </div>

</div>
<div class="clear"></div>