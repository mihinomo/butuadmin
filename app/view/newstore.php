
<?php 




?>
<br><br>
<div class="section section-contacts">
    <br>
    <div class="card">
        <div class="row">
		  
          <div class="col-md-8 ml-auto mr-auto">
            <div style='border:1px solid white; border-radius:50px;'>
              <h2 class="text-center title m-2">Start by setting up your bussiness</h2>
            </div><br><br>
            <form class="contact-form mr-2 ml-2" method='post' autocomplete="off">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">Bussiness Name</label>
                    <input type="text" name='st_nm' class="form-control" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">Bussiness Phone</label>
                    <input type="text" class="form-control" name='st_ph' maxlength="10" required>
                  </div>
                </div>
				        <div class="col-md-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">Bussiness Address</label>
                    <input type="text" name='st_addr' class="form-control" required>
                  </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <br>
                        <select class="form-control m-2" name='st_type' required>
                            <option disabled selected>Bussiness Type</option>
                            <option value="re">Restaurant</option>
                            <option value="fin">Finance</option>
                            
                        </select>
                    </div>
                </div>
              </div>
              <div class="col-md-5 m-1">
                <div class="form-group">
                    <br><br>
                    <select class="form-control" name='st_region' required>
                        <option disabled selected>Bussiness Region</option>
                        <option value="ziro">Ziro, 791120</option>
                        <option value="itanagar">Naharlagun, 791110</option>
                    </select>
                </div>
              </div>
              <div class="form-group">
                <label for="exampleMessage" class="bmd-label-floating">Bussiness Description</label>
                <textarea type="text" name="st_desc" class="form-control" rows="4" id="exampleMessage" required></textarea>
              </div>
              <div class="row">
                <div class="col-md-4 ml-auto mr-auto text-center">
                  <input type="submit" class="btn btn-primary btn-raised" name='create_store' value="Create Bussiness">
                    
                </div>
              </div>
              <br><br>
            </form>
          </div>
        </div>
      </div>
	</div>