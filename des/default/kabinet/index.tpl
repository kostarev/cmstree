<div>
<?=$menu->get_tree_html('kabinet');?>   

<?if($this->conf['reg']['invite']):?>
<p><a href="/kabinet/invites">Пригласить в систему</a></p>
<?endif;?>
</div>