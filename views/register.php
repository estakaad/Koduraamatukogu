<?php require_once('views/header.html'); ?>

<div class="control-group">
	<div class="modal fade" id="registerUser">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title">Registreeru kasutajaks</h3>
				</div>
				<div class="modal-body">
					<form role="form" form action="?page=register" method="POST">
						<div class="form-group">
							<label for="registerName">Nimi</label>
							<input type="text" id="registerName" class="form-control">
						</div>
						<div class="form-group">
							<label for="registerEmail">E-posti aadress</label>
							<input type="email" id="registerEmail" class="form-control">
						</div>
						<div class="form-group">
							<label for="registerPassword">Salasõna</label>
							<input type="password" id="registerPassword" class="form-control">
						</div>
						<div class="form-group">
							<label for="registerPassword">Salasõna uuesti</label>
							<input type="password" id="registerPassword" class="form-control">
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary btn-block">Registreeru kasutajaks</button>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require_once('views/footer.html'); ?>