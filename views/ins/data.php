<table class="am-table am-table-striped am-table-hover table-main">
<thead><tr><th class="table-check"><input type="checkbox" id="select-all"></th><th class="table-title">明星</th></tr></thead>
	<tbody>
	<?php foreach ($list as $item){ ?>
		<tr>
			<td width="10%"><input type="checkbox" data-user-id="<?=$item['user_id']?>"
				data-cnname="<?=$item['cnname']?>"></td>
			<td width="50%"><?=$item['cnname']?></td>
		</tr>
	<?php }?>
	</tbody>
</table>
<?=$pageHtml?>