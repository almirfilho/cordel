<div id="paginator-counter"><?= $this->Paginator->counter( array( 'format' => "<strong>%count%</strong> registro(s) encontrado(s)" ) ) ?></div>

<ul id="pagination" class="pager well">
	<li class="previous"><?= $this->Paginator->prev( "&larr; Anteriores", array( 'escape' => false ), null, array( 'escape' => false, 'class' => 'prev disabled' ) ) ?></li>
	<li class="counter"><?= $this->Paginator->counter( array( "format" => "p&aacute;gina %page% de %pages%" ) ) ?></li>
	<li class="next"><?= $this->Paginator->next( "Próximos &rarr;", array( 'escape' => false ), null, array( 'escape' => false, 'class' => 'next disabled' ) ) ?></li>
</ul>