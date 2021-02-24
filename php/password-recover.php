
    <div class="container col-xs-12" id="passwordrecover-content" style="display:none">
      <div class="row">
      <div class="col-xs-12">

        <div class="main-login main-center" id="password-recover-box">
          <?php
          
          if(!isset($_GET["r"])){
          echo'<form class="form-horizontal" method="post" action="/php/password-recover-process.php?action=0" id="password-recover-form" autocomplete="off">
            <div id="user-info">
              <div class="form-group">
                <div>
                  <div class="input-group">
                    <span class="input-group-addon fa-dark"><i class="fa fa-users fa" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" name="username" id="username"  placeholder="Username"/>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div>
                  <div class="input-group">
                    <span class="input-group-addon fa-dark"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" name="email" id="email"  placeholder="Email" />
                  </div>
                </div><br>
               <input type="submit" class="btn btn-primary btn-lg btn-block login-button col-xs-8" value="'.$recoverPasswordButton1.'">
              </div>
                <div class="login-register">
                    <a href="javascript:changeSection(0)">Login</a>
                 </div>

            </div>
            </form>';
          }else{
        
            echo'<form action="/php/password-recover-process.php?action=1" method="POST" id="password-recover-form" class="form-horizontal" autocomplete="off"/>
              <div class="form-group">
                <div>
                  <div class="input-group">
                    <span class="input-group-addon fa-dark"><i class="fa fa-code fa" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" name="codice" id="codice" placeholder="'.$recoverPasswordPlaceholder1.'" autocomplete="off" />
                  </div>
                </div>
                <br>
                <div>
                  <div class="input-group">
                    <span class="input-group-addon fa-dark"><i class="fa fa-lock fa" aria-hidden="true"></i></span>
                    <input type="password" class="form-control" name="password" id="password"  placeholder="Password"  autocomplete="off" />
                  </div>
                </div>
                <br>
                <div>
                  <div class="input-group">
                    <span class="input-group-addon fa-dark"><i class="fa fa-lock fa" aria-hidden="true"></i></span>
                    <input type="password" class="form-control" name="confirm" id="confirm"  placeholder="'.$recoverPasswordPlaceholder2.'" autocomplete="off" />
                  </div>
                </div>
              </div>

            <div class="form-group ">
              <input type="submit" class="btn btn-primary btn-lg btn-block login-button col-xs-8" value="'.$recoverPasswordButton2.'">
            </div>
            <div class="login-register">
                    <a href="javascript:changeSection(0)">Login</a>
                 </div>
          </form>';
        }
    ?>
        </div>
      </div>
    </div>
</div>
