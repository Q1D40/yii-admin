<table class="am-table am-table-striped am-table-hover table-main">
	<tbody>
	<?php foreach ($list as $item){ ?>
		<tr>
			<td width="10%"><input type="checkbox" data-id="<?=$item['id']?>"
				data-title="<?=$item['title']?>"></td>
			<td width="50%"><?=$item['title']?></td>
		</tr>
	<?php }?>
	</tbody>
</table>
<?=$pageHtml?>