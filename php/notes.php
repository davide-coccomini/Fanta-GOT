
<div class="container">

<div class="row">
	<div class="col-md-5">
		<form method="POST" action="notes-process.php">
		<div class="notebox">
			<textarea placeholder="Appunti" name="appunti"></textarea>
		</div>
		<div class="rulescouplesbox col-md-12">
			<select class="col-md-3" name="pg">
				<?php
					$query="SELECT * FROM personaggi";
					$result=$mysqli->query($query);
	    			while($row=$result->fetch_assoc())
	    				echo "<option value='".$row['id']."'>".$row['nome']."</option>";
				?>
			</select>
			<select class="col-md-6" name="regola">
				<?php
					$query="SELECT * FROM vociregolamento";
					$result=$mysqli->query($query);
	    			while($row=$result->fetch_assoc())
	    				echo "<option value='".$row['id']."'>".$row['nome']."</option>";
				?>
			</select>
			<input type="submit" class="btn btn-success col-md-3" value="Aggiungi">
			</form>
			<a href=""><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
		</div>
	</div>
	<div class="col-md-7 noterulebox">
		<?php
		   	
			$query="SELECT * FROM vociregolamento ORDER BY id ASC";
			$result=$mysqli->query($query);
			while($row=$result->fetch_assoc()){
			 echo"<div class='row rule-box col-md-12'><div id='rule'>
			 <div class='scores-voice'>".$row['nome']."</div>
			 <div class='scores-details col-md-10'>".$row['dettagli']."</div>

			 </div></div>";
			}

		?>
	</div>
</div>

</div>