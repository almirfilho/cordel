<table class="table table-striped">
<thead>
<tr>
	<th class="pic"></th>
	<th><?= $this->Paginator->sort( "Nome", "name" ) ?></th>
	<th><?= $this->Paginator->sort( "Perfil", "Profile.name" ) ?></th>
	<th><?= $this->Paginator->sort( "Email", "email" ) ?></th>
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