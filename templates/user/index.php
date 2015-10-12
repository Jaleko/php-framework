<a href="<?php UrlHelper::get(array('action' => 'update')); ?>">Aggiungi</a>
<table>
	<tr>
		<th>&nbsp;</th>
		<th>Username</th>
		<th>Email</th>
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
		<td><?php echo $item->username; ?></td>
		<td><?php echo $item->email; ?></td>
	</tr>
			<?php
		}
	?>
</table>