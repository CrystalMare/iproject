function getCount(date,id){
    var dateNow = new Date();	//grab current date
    var amount = date.getTime() - dateNow.getTime();

    if(amount < 0) $('#' + id).html("Gesloten");
    else{
        amount = Math.floor(amount/1000);
        var days=Math.floor(amount/86400);
        amount=amount%86400;
        var hours=Math.floor(amount/3600);
        amount=amount%3600;
        var mins=Math.floor(amount/60);
        amount=amount%60;
        var secs=Math.floor(amount);
        var out = "";
        out += (days != 0) ? (days<=9?'':'')+days +((days==1)?" dag":" dagen")+" - " : "";
        out += (hours<=9?'0':'')+hours +((hours==1)?":":":");
        out += (mins<=9?'0':'')+mins +((mins==1)?":":":");
        out += (secs<=9?'0':'')+secs +((secs==1)?":":":");
        out = out.substr(0,out.length-1);
        $('#' + id).html(out);
        setTimeout(function(){getCount(date,id)}, 1000);
    }
}
