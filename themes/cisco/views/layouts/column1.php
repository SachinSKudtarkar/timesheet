<?php $this->beginContent('//layouts/main'); ?>
<div class="row-fluid">
        <div class="span12">
               <?php if(isset($this->breadcrumbs)):?>
                      <?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
                              'links'=>$this->breadcrumbs,
                              'homeLink'=>false,
                              'tagName'=>'ul',
                              'separator'=>'&raquo;',
                              'activeLinkTemplate'=>'<li><a href="{url}">{label}</a> <span class="divider">/</span></li>',
                              'inactiveLinkTemplate'=>'<li><span>{label}</span></li>',
                              'htmlOptions'=>array ('class'=>'breadcrumb')
                      )); ?>     
                <?php endif?>
                <?php 
                /**
                 * Date: 28-2-2014
                 * Changed By Shailesh Giri
                 * Flash Message display code start
                 */
                ?>
                <div class="main">
                        <?php echo $content; ?>
                </div>	
	</div><!-- content -->
</div>
<?php $this->endContent(); ?>