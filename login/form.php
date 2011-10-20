<form method="POST">
        Username:<input type="text" id="username" name="username" onkeyup="vaildateField(this,0);" onblur="vaildateField(this,1);"/>
        <span style="display: none;" id="valusername"></span>
        <br/>
        Password:<input type="password" id="password" name="password" onkeyup="vaildateField(this,0);" onblur="vaildateField(this,1);"/>
        <span style="display: none;" id="valpassword"></span>
        <br/>
        <input type="submit" value="Go!" name="submit"/>
    </form>