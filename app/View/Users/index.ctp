<table class="table table-striped">
<thead>
<tr>
	<th class="pic"></th>
	<th><?= $this->Paginator->sort( "name", "Nome" ) ?></th>
	<th><?= $this->Paginator->sort( "Profile.name", "Perfil" ) ?></th>
	<th><?= $this->Paginator->sort( "email", "Email" ) ?></th>
</tr>
</thead>
<tbody>
<?php foreach( $users as $i => $user ): ?>

<tr>
	<td><i class="icon-user"></i></td>
	<td><?= $this->Html->link( $user[ 'User' ][ 'name' ], "/users/view/{$user['User']['id']}", array( 'escape' => false ) ) ?></td>
	<td><?= $user[ 'Profile' ][ 'name' ] ?></td>
	<td><?= $user[ 'User' ][ 'email' ] ?></td>
</tr>
	
<?php endforeach; ?>
</tbody>
</table>

<?= $this->element( "pagination" ) ?>