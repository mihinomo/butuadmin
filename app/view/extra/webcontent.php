
<div class="card">
    <br>
    <div class="card-body">
        <form method='get'>
            <h4>About Content</h4>
            <textarea class='form-control' id='about' name='about'>
                <?php echo $r['about']; ?>
            </textarea>
            <input type='submit' class='btn btn-info m-1' value="Update About">
        </form>
    </div>
</div>




<div class="card">
    <br>
    <div class="card-body">
        <form method='get'>
            <h4>Faq Content</h4>
            <textarea class='form-control' id='faq' name='faq'>
                <?php echo $r['faq']; ?>
            </textarea>
            <input type='submit' class='btn btn-info m-1' value="Update FAQ">
        </form>
    </div>
</div>

<div class="card">
    <br>
    <div class="card-body">
        <form method='post'>
            <h4>Scam Info</h4>
            <textarea class='form-control' id='sclis' name='sclis'>
                <?php echo htmlspecialchars_decode($r['waft']); ?>
            </textarea>
            <input type='submit' class='btn btn-info m-1' value="Update Scam Info">
        </form>
    </div>
</div>


<div class="card">
    <br>
    <div class="card-body">
        <form method='get'>
            <h4>Contact us</h4>
            <textarea class='form-control' id='contact' name='contact'>
                <?php echo $r['contact']; ?>
            </textarea>
            <input type='submit' class='btn btn-info m-1' value="Update Contact">
        </form>
    </div>
</div>

<div class="card">
    <br>
    <div class="card-body">
        <form method='get'>
            <h4>Privacy Policy Content</h4>
            <textarea class='form-control' id='contact' name='privacy'>
                <?php echo $r['privacy']; ?>
            </textarea>
            <input type='submit' class='btn btn-info m-1' value="Update Privacy">
        </form>
    </div>
</div>

<div class="card">
    <br>
    <div class="card-body">
        <form method='get'>
            <h4>Terms Content</h4>
            <textarea class='form-control' id='contact' name='terms'>
                <?php echo $r['terms']; ?>
            </textarea>
            <input type='submit' class='btn btn-info m-1' value="Update Terms">
        </form>
    </div>
</div>

<div class="card">
    <br>
    <div class="card-body">
        <form method='get'>
            <h4>SEO (comma separated keywords)</h4>
            <textarea class='form-control' id='contact' name='seo'>
                <?php echo $r['seo']; ?>
            </textarea>
            <input type='submit' class='btn btn-info m-1' value="Update SEO">
        </form>
    </div>
</div>

<div class="card">
    <br>
    <div class="card-body">
        <form method='get'>
            <h4>Site Description (for google results and scraping)</h4>
            <textarea class='form-control' id='contact' name='seodesc'>
                <?php echo $r['seodesc']; ?>
            </textarea>
            <input type='submit' class='btn btn-info m-1' value="Update Seo Description">
        </form>
    </div>
</div>