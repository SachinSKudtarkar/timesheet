<?php
/* @var $this SubProjectController */
/* @var $model SubProject */
/* @var $form CActiveForm */

?>
<style>
#reportcards { padding: 10px;background: #171717; }
#reportcards .span3{border:  2px solid #171717;padding: 10px;background: #232323;}
#reportcards h1{
    display: block;
    width: 100%;
    padding: 0;
    /* margin-bottom: 27px; */
    font-size: 19.5px;
    line-height: 36px;
    color: #D0D0D0;
    border: 0;
    border-bottom: 1px solid #171717;
    margin: 0px;
}
#reportcards span{
    font-size: 25px; 
    text-transform: uppercase;
    color: #37659D;   
}
#reportcards p{
    display: block;
    width: 100%;
    padding: 0px;
     margin: 18px auto; 
    font-size: 36px;
    font-weight: 600;
    line-height: 36px;
    color: #37659D;
    border: 0;
}
</style>
<div class="row span11" id="reportcards">
    <?php foreach($allcount[0] as $key => $count){ ?>
    <div class="span3">
        <div class="span8"><h1>Total<br><span> <?php echo $key; ?></span></h1></div>
        <div class="span4"><p><?php echo $count; ?></p></div>
    </div>
    <?php } ?> 
</div>