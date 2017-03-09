
</div>

</div><div id="footer">
    &copy; <?php echo date('Y'); ?> <a href="<?=COPYRIGHTURL;?>/"><?=COPYRIGHTURL;?></a></div>
<?if(SiteRead::me()->is_access('panel')):?>
Time: <?=$gentime;?> с, SQL: <?=$sql_count;?> (<?=$sql_time;?> с.)
<?endif;?>
</div>
</body>
</html>