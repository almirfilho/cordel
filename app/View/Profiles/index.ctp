<table class="table table-striped">
<thead>
<tr>
	<th class="pic"></th>
	<th><?= $this->Paginator->sort( "name", "Nome" ) ?></th>
</tr>
</thead>
<tbody>
<?php foreach( $profiles as $i => $profile ): ?>

<tr>
	<td><i class="icon-tag"></i></td>
	<td><?= $this->Html->link( $profile[ 'Profile' ][ 'name' ], "/profiles/view/{$profile['Profile']['id']}", array( 'escape' => false ) ) ?></td>
</tr>
	
<?php endforeach; ?>
</tbody>
</table>

<?= $this->element( "pagination" ) ?>