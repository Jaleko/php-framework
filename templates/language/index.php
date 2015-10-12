<a href="<?php UrlHelper::get(array('action' => 'update')); ?>">Aggiungi</a>
<table>
	<tr>
		<th>&nbsp;</th>
		<th>ISO Code</th>
		<th>Locale Code</th>
		<th>Nome</th>
		<th>Attiva</th>
	</tr>
	<?php
		$collection = $this->getVar('collection');
		foreach ($collection as $item)
		{
			?>
	<tr>
		<td>
			<a href="<?php UrlHelper::get(array('action' => 'update', 'id' => $item->id)); ?>">Modifica</a>
			<span class="require-delete-confirm" data-href="<?php UrlHelper::get(array('action' => 'delete', 'id' => $item->id)); ?>">Cancella</span>
		</td>
		<td><?php echo $item->iso_code; ?></td>
		<td><?php echo $item->locale_code; ?></td>
		<td><?php echo $item->label; ?></td>
		<td><?php echo $item->is_active; ?></td>
	</tr>
			<?php
		}
	?>
</table>